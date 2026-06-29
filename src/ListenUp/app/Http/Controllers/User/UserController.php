<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Baitest;
use App\Models\Chude;
use App\Models\Chitietlambai;
use App\Models\Tientrinh;
use App\Models\Bandophieuluu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        
        // 1. Phân tích các bài test đã làm để tìm các chủ đề yếu (chính xác trung bình < 75%)
        $topicStats = Chitietlambai::where('UserID', $user->UserID)
            ->join('baitest', 'CHITIETLAMBAI.MaBai', '=', 'baitest.MaBai')
            ->selectRaw('baitest.MaCD, SUM(CHITIETLAMBAI.SoCauDung) as total_correct, SUM(CHITIETLAMBAI.TongSoCau) as total_questions')
            ->groupBy('baitest.MaCD')
            ->get();

        $weakTopicIds = [];
        foreach ($topicStats as $stat) {
            $accuracy = $stat->total_questions > 0 ? ($stat->total_correct / $stat->total_questions) * 100 : 100;
            if ($accuracy < 75) {
                $weakTopicIds[] = $stat->MaCD;
            }
        }

        // 2. Xác định bản đồ phiêu lưu hiện tại của người dùng (bản đồ chưa hoàn thành nhưng đủ điều kiện điểm phiêu lưu)
        $completedMapIds = Tientrinh::where('UserID', $user->UserID)
            ->where('KetQuaMan', 100)
            ->pluck('MaBanDo')
            ->toArray();

        $currentMap = Bandophieuluu::orderBy('MaBanDo', 'asc')
            ->get()
            ->first(function ($map) use ($user, $completedMapIds) {
                if (in_array($map->MaBanDo, $completedMapIds)) {
                    return false;
                }
                $reqScore = (int) ($map->YeuCauBanDo ?? 0);
                return $user->DiemMan >= $reqScore;
            });

        // 3. Tìm bài học gợi ý (Tối đa 3 bài)
        $recommendedTests = collect();

        // Ưu tiên 1: Bài thuộc chủ đề yếu mà học viên chưa làm hoặc chưa đạt điểm tuyệt đối
        if (!empty($weakTopicIds)) {
            $weakRecommendations = Baitest::with(['chude', 'capdonghe'])
                ->whereIn('MaCD', $weakTopicIds)
                ->where('TrangThaiBai', 'Mo')
                ->whereNotExists(function ($query) use ($user) {
                    $query->select(\DB::raw(1))
                        ->from('CHITIETLAMBAI')
                        ->whereRaw('CHITIETLAMBAI.MaBai = baitest.MaBai')
                        ->where('CHITIETLAMBAI.UserID', $user->UserID)
                        ->whereRaw('CHITIETLAMBAI.SoCauDung = CHITIETLAMBAI.TongSoCau');
                })
                ->take(3)
                ->get();
            
            foreach ($weakRecommendations as $rec) {
                $rec->recommendation_reason = 'Cải thiện chủ đề: ' . ($rec->chude ? $rec->chude->TenCD : 'Tổng hợp');
                $rec->recommendation_type = 'weak_topic';
                $recommendedTests->push($rec);
            }
        }

        // Ưu tiên 2: Bài thuộc bản đồ phiêu lưu hiện tại mà học viên chưa hoàn thành
        if ($recommendedTests->count() < 3 && $currentMap) {
            $mapRecommendations = Baitest::with(['chude', 'capdonghe'])
                ->where('MaBanDo', $currentMap->MaBanDo)
                ->where('TrangThaiBai', 'Mo')
                ->whereNotExists(function ($query) use ($user) {
                    $query->select(\DB::raw(1))
                        ->from('CHITIETLAMBAI')
                        ->whereRaw('CHITIETLAMBAI.MaBai = baitest.MaBai')
                        ->where('CHITIETLAMBAI.UserID', $user->UserID)
                        ->whereRaw('CHITIETLAMBAI.SoCauDung = CHITIETLAMBAI.TongSoCau');
                })
                ->whereNotIn('MaBai', $recommendedTests->pluck('MaBai')->toArray())
                ->take(3 - $recommendedTests->count())
                ->get();

            foreach ($mapRecommendations as $rec) {
                $rec->recommendation_reason = 'Thử thách ' . $currentMap->TenBanDo;
                $rec->recommendation_type = 'adventure_map';
                $recommendedTests->push($rec);
            }
        }

        // Ưu tiên 3: Các bài học cơ bản thuộc cấp độ dễ mà học viên chưa từng thử sức
        if ($recommendedTests->count() < 3) {
            $generalRecommendations = Baitest::with(['chude', 'capdonghe'])
                ->where('TrangThaiBai', 'Mo')
                ->whereNotExists(function ($query) use ($user) {
                    $query->select(\DB::raw(1))
                        ->from('CHITIETLAMBAI')
                        ->whereRaw('CHITIETLAMBAI.MaBai = baitest.MaBai')
                        ->where('CHITIETLAMBAI.UserID', $user->UserID);
                })
                ->whereNotIn('MaBai', $recommendedTests->pluck('MaBai')->toArray())
                ->orderBy('MaCDN', 'asc')
                ->take(3 - $recommendedTests->count())
                ->get();

            foreach ($generalRecommendations as $rec) {
                $rec->recommendation_reason = 'Gợi ý bài học mới phù hợp';
                $rec->recommendation_type = 'new_discovery';
                $recommendedTests->push($rec);
            }
        }

        // Dự phòng (fallback): Lấy ngẫu nhiên các bài đang mở nếu danh sách trống
        if ($recommendedTests->isEmpty()) {
            $fallbackRecommendations = Baitest::with(['chude', 'capdonghe'])
                ->where('TrangThaiBai', 'Mo')
                ->inRandomOrder()
                ->take(3)
                ->get();
            foreach ($fallbackRecommendations as $rec) {
                $rec->recommendation_reason = 'Ôn tập ngẫu nhiên';
                $rec->recommendation_type = 'fallback';
                $recommendedTests->push($rec);
            }
        }

        $myResultsCount = Chitietlambai::where('UserID', $user->UserID)->count();
        $recentResults = Chitietlambai::where('UserID', $user->UserID)
            ->with(['baitest'])
            ->orderBy('CreatedAt', 'desc')
            ->take(5)
            ->get();
        
        return view('user.dashboard', compact('user', 'myResultsCount', 'recentResults', 'recommendedTests'));
    }

    public function editProfile()
    {
        $user = Auth::user();
        
        $completedMaps = \App\Models\Tientrinh::where('UserID', $user->UserID)
            ->where('KetQuaMan', '>', 0)
            ->pluck('MaBanDo')
            ->toArray();
            
        $frames = [];
        for ($i = 1; $i <= 10; $i++) {
            $mapId = 'BD' . str_pad($i, 2, '0', STR_PAD_LEFT);
            $filename = ($i === 5) ? 'vein5.jpg' : 'vien' . $i . '.jpg';
            $frames[] = [
                'filename' => $filename,
                'name' => 'Viền Map ' . $i,
                'unlocked' => in_array($mapId, $completedMaps)
            ];
        }
        
        return view('user.profile.edit', compact('user', 'frames'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'UserName' => 'required|string|max:255',
            'SDT' => 'nullable|string|max:20',
            'GioiTinh' => 'nullable|string',
            'NgaySinh' => 'nullable|date',
            'AnhDaiDien' => 'nullable|image|max:2048',
            'Vien' => 'nullable|string',
            'preferred_accent' => 'nullable|string|in:en-US,en-GB,en-AU',
            'preferred_speed' => 'nullable|numeric|in:0.75,1.0,1.25,1.5',
        ]);

        $user->UserName = $request->UserName;
        $user->SDT = $request->SDT;
        $user->GioiTinh = $request->GioiTinh;
        $user->NgaySinh = $request->NgaySinh;
        $user->preferred_accent = $request->input('preferred_accent', 'en-US');
        $user->preferred_speed = (float) $request->input('preferred_speed', 1.0);

        if ($request->hasFile('AnhDaiDien')) {
            $file = $request->file('AnhDaiDien');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/avatars'), $filename);
            $user->AnhDaiDien = 'avatars/' . $filename;
        }

        if ($request->filled('Vien')) {
            $chosenFrame = $request->Vien;
            $validFrames = [];
            for ($i = 1; $i <= 10; $i++) {
                $validFrames[] = ($i === 5) ? 'vein5.jpg' : 'vien' . $i . '.jpg';
            }
            
            if (in_array($chosenFrame, $validFrames)) {
                if (!$user->isAdmin()) {
                    $mapNumber = (int) filter_var($chosenFrame, FILTER_SANITIZE_NUMBER_INT);
                    if (str_contains($chosenFrame, 'vein5')) {
                        $mapNumber = 5;
                    }
                    $mapId = 'BD' . str_pad($mapNumber, 2, '0', STR_PAD_LEFT);
                    
                    $unlocked = \App\Models\Tientrinh::where('UserID', $user->UserID)
                        ->where('MaBanDo', $mapId)
                        ->where('KetQuaMan', '>', 0)
                        ->exists();
                        
                    if (!$unlocked) {
                        return redirect()->back()->withErrors(['Vien' => 'Bạn chưa mở khóa viền này!']);
                    }
                }
                $user->Vien = $chosenFrame;
            } else {
                $user->Vien = null;
            }
        } else {
            $user->Vien = null;
        }

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'min:6|confirmed'
            ]);
            $user->Password = bcrypt($request->password);
        }

        $user->save();

        return redirect()->route('user.profile.edit')->with('success', 'Cập nhật thông tin thành công!');
    }

    public function lessons()
    {
        $lessons = Baitest::with(['chude'])->where('TrangThaiBai', 'Mo')->paginate(12);
        return view('user.lessons.index', compact('lessons'));
    }

    public function showLesson($id)
    {
        $lesson = Baitest::with(['phan.cauhoi', 'chude'])->where('MaBai', $id)->firstOrFail();
        return view('user.lessons.show', compact('lesson'));
    }

    public function results()
    {
        $user = Auth::user();
        $results = Chitietlambai::with('baitest')->where('UserID', $user->UserID)
            ->orderBy('CreatedAt', 'desc')
            ->paginate(10);
        
        return view('user.results.index', compact('results'));
    }

    public function showTest($id)
    {
        $lesson = Baitest::with(['phan.cauhoi.phuongancauhoi', 'phan.tepamthanh', 'chude'])
            ->where('MaBai', $id)
            ->firstOrFail();
            
        $topics = Chude::with(['baitests' => function($q) {
            $q->where('TrangThaiBai', 'Mo');
        }])->get();
            
        return view('user.test.show', compact('lesson', 'topics'));
    }

    public function submitTest(Request $request, $id)
    {
        $lesson = Baitest::with(['phan.cauhoi.phuongancauhoi'])->findOrFail($id);
        
        $startTime = $request->input('start_time');
        $duration = time() - $startTime; // in seconds
        
        $correctCount = 0;
        $totalQuestions = 0;
        
        // Cần lưu vào bảng ket qua nếu cần chi tiết
        $ketQuaData = [];

        foreach ($lesson->phan as $phan) {
            foreach ($phan->cauhoi as $cauhoi) {
                $totalQuestions++;
                $questionKey = 'question_' . trim($cauhoi->MaCauHoi);
                $userAnswer = trim($request->input($questionKey));
                
                // Determine if correct based on DapAn column
                // Wait, if DapAn is 1 or something
                $isCorrect = false;
                if ($userAnswer) {
                    $selectedOption = $cauhoi->phuongancauhoi->first(function($opt) use ($userAnswer) {
                        return trim($opt->MaPA) == $userAnswer;
                    });
                    if ($selectedOption && (mb_strtolower(trim($selectedOption->DapAn), 'UTF-8') === 'dung' || mb_strtolower(trim($selectedOption->DapAn), 'UTF-8') === 'đúng' || $selectedOption->DapAn == '1')) {
                        $isCorrect = true;
                        $correctCount++;
                    }
                }
                
                $ketQuaData[] = [
                    'MaCauHoi' => $cauhoi->MaCauHoi,
                    'KetQuaChon' => $userAnswer
                ];
            }
        }
        
        $user = Auth::user();
        
        // Generate a random ID for MaCTLB (length 10)
        $maCTLB = 'CT' . strtoupper(\Illuminate\Support\Str::random(8));

        $chitiet = Chitietlambai::create([
            'MaCTLB' => $maCTLB,
            'UserID' => $user->UserID,
            'SoLanLam' => 1,
            'MaBai' => $id,
            'ThoiGianLam' => $duration > 0 ? $duration : 0,
            'SoCauDung' => $correctCount,
            'TongSoCau' => $totalQuestions,
            // CreatedAt is auto set by DB
        ]);
        
        // Lưu bảng `ket qua` (many-to-many with pivot)
        foreach($ketQuaData as $kq) {
            $chitiet->cauhoi()->attach($kq['MaCauHoi'], ['KetQuaChon' => $kq['KetQuaChon']]);
        }

        return redirect()->route('user.results.show', $maCTLB)->with('success', 'Nộp bài thành công! Bạn đúng ' . $correctCount . '/' . $totalQuestions . ' câu.');
    }

    public function showResult($id)
    {
        $user = Auth::user();
        $result = Chitietlambai::with(['baitest.phan.cauhoi.phuongancauhoi', 'cauhoi'])->where('UserID', $user->UserID)->findOrFail($id);
        
        // Mảng chứa các câu hỏi user đã chọn để so sánh
        $userAnswers = [];
        foreach($result->cauhoi as $c) {
            $userAnswers[$c->MaCauHoi] = $c->pivot->KetQuaChon;
        }

        return view('user.results.show', compact('result', 'userAnswers'));
    }

    public function updateSurvey(Request $request)
    {
        $user = Auth::user();
        
        // If it's a reset request
        if ($request->input('daily_target_time') == 0 || $request->input('daily_target_time') === '0') {
            $user->learning_goal = null;
            $user->current_level = null;
            $user->daily_target_time = null;
            $user->save();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Đã đặt lại khảo sát thành công!'
                ]);
            }
            return redirect()->back()->with('success', 'Đã đặt lại khảo sát thành công!');
        }
        
        $request->validate([
            'learning_goal' => 'required|string|max:50',
            'current_level' => 'required|string|max:20',
            'daily_target_time' => 'required|integer|min:5|max:120',
        ]);
        
        $user->learning_goal = $request->learning_goal;
        $user->current_level = $request->current_level;
        $user->daily_target_time = (int) $request->daily_target_time;
        $user->save();
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Lộ trình cá nhân đã được thiết lập thành công!'
            ]);
        }
        
        return redirect()->back()->with('success', 'Thiết lập lộ trình học tập thành công!');
    }
}