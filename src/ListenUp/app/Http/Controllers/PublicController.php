<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Capdonghe;
use App\Models\Chude;
use App\Models\Baitest;

class PublicController extends Controller
{
    public function index()
    {
        $levels = Capdonghe::all();
        $topUsers = \App\Models\User::orderBy('TongDiem', 'desc')->take(10)->get();
        
        $recommendedTests = collect();
        if (auth()->check()) {
            $user = auth()->user();
            
            $userGoal = $user->learning_goal;
            $userLevel = $user->current_level;
            
            $matchingLevelIds = [];
            if ($userLevel) {
                $levelQuery = \App\Models\Capdonghe::query();
                if ($userLevel === 'beginner') {
                    $levelQuery->where(function($q) {
                        $q->where('TenCDN', 'like', '%cơ bản%')
                          ->orWhere('TenCDN', 'like', '%mới bắt đầu%')
                          ->orWhere('TenCDN', 'like', '%dễ%')
                          ->orWhere('TenCDN', 'like', '%beginner%')
                          ->orWhere('TenCDN', 'like', '%easy%')
                          ->orWhere('TenCDN', 'like', '%A1%')
                          ->orWhere('TenCDN', 'like', '%A2%');
                    });
                } elseif ($userLevel === 'intermediate') {
                    $levelQuery->where(function($q) {
                        $q->where('TenCDN', 'like', '%trung cấp%')
                          ->orWhere('TenCDN', 'like', '%vừa%')
                          ->orWhere('TenCDN', 'like', '%intermediate%')
                          ->orWhere('TenCDN', 'like', '%medium%')
                          ->orWhere('TenCDN', 'like', '%B1%')
                          ->orWhere('TenCDN', 'like', '%B2%');
                    });
                } elseif ($userLevel === 'advanced') {
                    $levelQuery->where(function($q) {
                        $q->where('TenCDN', 'like', '%nâng cao%')
                          ->orWhere('TenCDN', 'like', '%khó%')
                          ->orWhere('TenCDN', 'like', '%advanced%')
                          ->orWhere('TenCDN', 'like', '%hard%')
                          ->orWhere('TenCDN', 'like', '%C1%')
                          ->orWhere('TenCDN', 'like', '%C2%');
                    });
                }
                $matchingLevelIds = $levelQuery->pluck('MaCDN')->toArray();
            }
            
            $matchingTopicIds = [];
            if ($userGoal) {
                $topicQuery = \App\Models\Chude::query();
                if ($userGoal === 'communication') {
                    $topicQuery->where(function($q) {
                        $q->where('TenCD', 'like', '%giao tiếp%')
                          ->orWhere('TenCD', 'like', '%hội thoại%')
                          ->orWhere('TenCD', 'like', '%daily%')
                          ->orWhere('TenCD', 'like', '%communication%')
                          ->orWhere('TenCD', 'like', '%đàm thoại%');
                    });
                } elseif ($userGoal === 'exams') {
                    $topicQuery->where(function($q) {
                        $q->where('TenCD', 'like', '%thi%')
                          ->orWhere('TenCD', 'like', '%ielts%')
                          ->orWhere('TenCD', 'like', '%toeic%')
                          ->orWhere('TenCD', 'like', '%toefl%')
                          ->orWhere('TenCD', 'like', '%test%')
                          ->orWhere('TenCD', 'like', '%exam%');
                    });
                } elseif ($userGoal === 'business') {
                    $topicQuery->where(function($q) {
                        $q->where('TenCD', 'like', '%công việc%')
                          ->orWhere('TenCD', 'like', '%business%')
                          ->orWhere('TenCD', 'like', '%work%')
                          ->orWhere('TenCD', 'like', '%office%')
                          ->orWhere('TenCD', 'like', '%đi làm%')
                          ->orWhere('TenCD', 'like', '%career%');
                    });
                }
                $matchingTopicIds = $topicQuery->pluck('MaCD')->toArray();
            }
            
            // Prioritized Tier: Survey recommendation
            if ($userGoal && $userLevel) {
                $surveyQuery = Baitest::with(['chude', 'capdonghe'])
                    ->where('TrangThaiBai', 'Mo')
                    ->whereNotExists(function ($query) use ($user) {
                        $query->select(\DB::raw(1))
                            ->from('CHITIETLAMBAI')
                            ->whereRaw('CHITIETLAMBAI.MaBai = baitest.MaBai')
                            ->where('CHITIETLAMBAI.UserID', $user->UserID)
                            ->whereRaw('CHITIETLAMBAI.SoCauDung = CHITIETLAMBAI.TongSoCau');
                    });
                
                if (!empty($matchingLevelIds)) {
                    $surveyQuery->whereIn('MaCDN', $matchingLevelIds);
                }
                if (!empty($matchingTopicIds)) {
                    $surveyQuery->whereIn('MaCD', $matchingTopicIds);
                }
                
                $surveyRecommendations = $surveyQuery->take(3)->get();
                
                if ($surveyRecommendations->count() < 3 && !empty($matchingLevelIds)) {
                    $extraLevelRecs = Baitest::with(['chude', 'capdonghe'])
                        ->where('TrangThaiBai', 'Mo')
                        ->whereIn('MaCDN', $matchingLevelIds)
                        ->whereNotIn('MaBai', $surveyRecommendations->pluck('MaBai')->toArray())
                        ->whereNotExists(function ($query) use ($user) {
                            $query->select(\DB::raw(1))
                                ->from('CHITIETLAMBAI')
                                ->whereRaw('CHITIETLAMBAI.MaBai = baitest.MaBai')
                                ->where('CHITIETLAMBAI.UserID', $user->UserID)
                                ->whereRaw('CHITIETLAMBAI.SoCauDung = CHITIETLAMBAI.TongSoCau');
                        })
                        ->take(3 - $surveyRecommendations->count())
                        ->get();
                        
                    $surveyRecommendations = $surveyRecommendations->merge($extraLevelRecs);
                }
                
                foreach ($surveyRecommendations as $rec) {
                    $rec->recommendation_reason = 'Phù hợp với lộ trình khảo sát';
                    $rec->recommendation_type = 'survey_path';
                    $recommendedTests->push($rec);
                }
            }
            
            // 1. Phân tích các bài test đã làm để tìm các chủ đề yếu (chính xác trung bình < 75%)
            $topicStats = \App\Models\Chitietlambai::where('UserID', $user->UserID)
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
            $completedMapIds = \App\Models\Tientrinh::where('UserID', $user->UserID)
                ->where('KetQuaMan', 100)
                ->pluck('MaBanDo')
                ->toArray();

            $currentMap = \App\Models\Bandophieuluu::orderBy('MaBanDo', 'asc')
                ->get()
                ->first(function ($map) use ($user, $completedMapIds) {
                    if (in_array($map->MaBanDo, $completedMapIds)) {
                        return false;
                    }
                    $reqScore = (int) ($map->YeuCauBanDo ?? 0);
                    return $user->DiemMan >= $reqScore;
                });

            // 3. Tìm bài học gợi ý (Tối đa 3 bài)
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
        }
        
        return view('welcome', compact('levels', 'topUsers', 'recommendedTests'));
    }

    public function levels()
    {
        $levels = Capdonghe::all();
        return view('public.levels.index', compact('levels'));
    }

    public function levelDetail($id)
    {
        $level = Capdonghe::with('baitests.chude')->where('MaCDN', $id)->firstOrFail();
        
        // Lấy danh sách các chủ đề có trong cấp độ này
        $topicIds = $level->baitests->whereNotNull('MaCD')->where('MaCD', '!=', '')->pluck('MaCD')->unique();
        $topics = Chude::whereIn('MaCD', $topicIds)->get();

        // Lấy các bài test chỉ có cấp độ mà không có chủ đề (MaCD là null hoặc rỗng)
        $uncategorizedLessons = Baitest::where('MaCDN', $id)
            ->where(function($query) {
                $query->whereNull('MaCD')
                      ->orWhere('MaCD', '');
            })
            ->where('TrangThaiBai', 'Mo')
            ->get();

        return view('public.levels.show', compact('level', 'topics', 'uncategorizedLessons'));
    }

    public function topicDetail($level_id, $topic_id)
    {
        $level = Capdonghe::where('MaCDN', $level_id)->firstOrFail();
        $topic = Chude::where('MaCD', $topic_id)->firstOrFail();
        
        $lessons = Baitest::where('MaCDN', $level_id)
            ->where('MaCD', $topic_id)
            ->with(['phan.cauhoi'])
            ->get();

        return view('public.topics.show', compact('level', 'topic', 'lessons'));
    }

    public function topics()
    {
        $topics = \App\Models\Chude::all();
        return view('public.topics.index', compact('topics'));
    }

    public function topicDetailById($id)
    {
        $topic = \App\Models\Chude::findOrFail($id);
        $lessons = \App\Models\Baitest::where('MaCD', $id)->with(['capdonghe', 'phan.cauhoi'])->get();
        return view('public.topics.detail', compact('topic', 'lessons'));
    }

    public function games()
    {
        $games = \App\Models\Bandophieuluu::orderBy('MaBanDo')->get();
        $user = auth()->user();
        $gameRankings = \App\Models\User::where('DiemMan', '>', 0)
            ->orderBy('DiemMan', 'desc')
            ->take(10)
            ->get();
        return view('public.games.index', compact('games', 'user', 'gameRankings'));
    }

    public function rankings()
    {
        // Get game rankings using DiemMan
        $gameRankings = \App\Models\User::where('DiemMan', '>', 0)
            ->orderBy('DiemMan', 'desc')
            ->take(10)
            ->get();
            
        // Get student rankings using TongDiem
        $studentRankings = \App\Models\User::where('TongDiem', '>', 0)
            ->orderBy('TongDiem', 'desc')
            ->take(10)
            ->get();
            
        return view('public.rankings.index', compact('gameRankings', 'studentRankings'));
    }
}
