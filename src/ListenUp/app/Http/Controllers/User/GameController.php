<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bandophieuluu;
use App\Models\Baitest;
use App\Models\Tientrinh;
use App\Models\Chitietlambai;

class GameController extends Controller
{
    public function play(Request $request, $id)
    {
        $user = auth()->user();
        $map = Bandophieuluu::findOrFail($id);

        // Check if map is accessible using DiemMan
        $firstMap = Bandophieuluu::orderBy('MaBanDo')->first();
        if ($map->YeuCauBanDo !== null && $map->YeuCauBanDo !== '' && $map->MaBanDo !== $firstMap->MaBanDo) {
            $requiredScore = (int) $map->YeuCauBanDo;
            if ($user->DiemMan < $requiredScore) {
                return redirect()->route('public.games')->with('error', 'Bạn cần đạt đủ '.$requiredScore.' Điểm Phiêu Lưu để mở khóa bản đồ này.');
            }
        }

        // Get all tests for this map that actually have sections
        $tests = Baitest::where('MaBanDo', $id)
            ->whereHas('phan')
            ->with(['phan.cauhoi.phuongancauhoi'])
            ->get();

        if ($tests->isEmpty()) {
            return redirect()->route('public.games')->with('error', 'Bản đồ này chưa có bài kiểm tra.');
        }

        $testId = $request->query('test_id');
        $test = $tests->firstWhere('MaBai', $testId) ?? $tests->first();

        $allGames = Bandophieuluu::orderBy('MaBanDo')->get();

        return view('user.games.play', compact('map', 'test', 'tests', 'allGames'));
    }

    public function submit(Request $request, $id)
    {
        $user = auth()->user();
        $map = Bandophieuluu::findOrFail($id);
        
        $testId = $request->input('test_id');
        if ($testId) {
            $test = Baitest::where('MaBanDo', $id)->where('MaBai', $testId)->with(['phan.cauhoi.phuongancauhoi'])->firstOrFail();
        } else {
            $test = Baitest::where('MaBanDo', $id)->with(['phan.cauhoi.phuongancauhoi'])->firstOrFail();
        }

        $answers = $request->input('answers', []);
        $correctCount = 0;
        $totalQuestions = 0;

        foreach ($test->phan as $phan) {
            foreach ($phan->cauhoi as $cauhoi) {
                $totalQuestions++;
                $userAnswer = $answers[$cauhoi->MaCauHoi] ?? null;
                
                if ($userAnswer) {
                    if (!empty($test->TuKhoaHangDoc)) {
                        $correctOption = $cauhoi->phuongancauhoi->firstWhere('DapAn', 'Dung');
                        if ($correctOption) {
                            $cleanCorrect = preg_replace('/^[A-D]\.\s*/', '', $correctOption->NDPA);
                            $cleanCorrect = strtoupper(trim($cleanCorrect));
                            $cleanUser = strtoupper(trim($userAnswer));
                            if ($cleanCorrect === $cleanUser) {
                                $correctCount++;
                            }
                        }
                    } else {
                        $selectedOption = $cauhoi->phuongancauhoi->firstWhere('MaPA', $userAnswer);
                        if ($selectedOption && ($selectedOption->DapAn === 'Dung' || $selectedOption->DapAn == 1)) {
                            $correctCount++;
                        }
                    }
                }
            }
        }

        if ($totalQuestions > 0 && $correctCount < $totalQuestions) {
            return redirect()->route('user.games.play', $id)->with('error', 'Bạn đã trả lời đúng ' . $correctCount . '/' . $totalQuestions . ' câu hỏi. Hãy thử lại để trả lời đúng tất cả các câu và nhận 100 Điểm Phiêu Lưu nhé!');
        }

        // Determine frame filename based on map number (BD01 -> 1, BD10 -> 10)
        $mapNumber = (int) str_replace('BD', '', $id);
        $frameFile = 'vien' . $mapNumber . '.jpg';
        if ($mapNumber === 5) {
            $frameFile = 'vein5.jpg'; // Special case for map 5
        }

        // Equip/save unlocked frame to user
        $user->Vien = $frameFile;
        $user->save();

        // Save progress to mark this map as completed
        $progress = Tientrinh::firstOrNew([
            'MaBanDo' => $id,
            'UserID' => $user->UserID
        ]);

        $pointsEarned = 0;

        // If not completed before, give 100 points
        if (!$progress->exists || $progress->KetQuaMan <= 0) {
            $pointsEarned = 100;
            $user->DiemMan += $pointsEarned;
            $user->save();

            // Mark as completed
            $progress->KetQuaMan = 100;
            $progress->ViTri = 'HoanThanh';
            $progress->MaXH = 'XH01';
            $progress->save();
        }

        return redirect()->route('user.games.play', $id)
            ->with('unlocked_frame', $frameFile)
            ->with('unlocked_map_name', $map->TenBanDo)
            ->with('unlocked_map_number', $mapNumber)
            ->with('success', 'Xuất sắc! Bạn đã trả lời đúng tất cả câu hỏi và đạt được phần quà viền avatar mới!');
    }
}
