<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiAssistantController extends Controller
{
    private $pythonServiceUrl = 'http://127.0.0.1:8001';

    /**
     * CHỨC NĂNG 1: Chatbot Hỏi đáp
     */
    public function chat(Request $request)
    {
        $request->validate([
            'user_message' => 'required|string|max:1000',
            'context' => 'nullable|string|max:2000'
        ]);

        try {
            // Lấy danh sách các chủ đề, cấp độ, bài test trong hệ thống
            $topics = \App\Models\Chude::all(['MaCD', 'TenCD'])->toArray();
            $levels = \App\Models\Capdonghe::all(['MaCDN', 'TenCDN'])->toArray();
            $tests = \App\Models\Baitest::with(['chude', 'capdonghe'])
                ->where('TrangThaiBai', 'Mo')
                ->get(['MaBai', 'TenBai', 'MaCD', 'MaCDN'])
                ->map(function ($test) {
                    return [
                        'MaBai' => $test->MaBai,
                        'TenBai' => $test->TenBai,
                        'ChuDe' => $test->chude ? $test->chude->TenCD : null,
                        'CapDo' => $test->capdonghe ? $test->capdonghe->TenCDN : null,
                    ];
                })
                ->toArray();

            $db_context = "--- HỆ THỐNG DANH SÁCH LISTENUP ---\n";
            $db_context .= "1. CHỦ ĐỀ (Topics):\n";
            foreach ($topics as $cd) {
                $db_context .= "- Mã chủ đề: {$cd['MaCD']}, Tên chủ đề: {$cd['TenCD']}\n";
            }
            $db_context .= "\n2. CẤP ĐỘ (Levels):\n";
            foreach ($levels as $cdn) {
                $db_context .= "- Mã cấp độ: {$cdn['MaCDN']}, Tên cấp độ: {$cdn['TenCDN']}\n";
            }
            $db_context .= "\n3. BÀI TEST ĐANG MỞ (Tests):\n";
            foreach ($tests as $bt) {
                $db_context .= "- Mã bài test: {$bt['MaBai']}, Tên bài: {$bt['TenBai']}, Thuộc chủ đề: " . ($bt['ChuDe'] ?? 'Không') . ", Cấp độ: " . ($bt['CapDo'] ?? 'Không') . "\n";
            }
            $db_context .= "------------------------------------\n";

            $context = $request->input('context') 
                ? $request->input('context') . "\n" . $db_context 
                : $db_context;

            $response = Http::post("{$this->pythonServiceUrl}/chatbot/chat", [
                'user_message' => $request->input('user_message'),
                'context' => $context
            ]);

            if ($response->successful()) {
                return response()->json($response->json());
            }

            Log::error("AiAssistantController: API python trả về lỗi " . $response->status());
            return response()->json(['error' => 'Dịch vụ AI phản hồi không thành công.'], 500);
        } catch (\Exception $e) {
            Log::error("AiAssistantController: " . $e->getMessage());
            return response()->json(['error' => 'Không thể kết nối với máy chủ AI phụ trợ. Vui lòng kiểm tra xem server Python đã chạy chưa.'], 500);
        }
    }

    /**
     * CHỨC NĂNG 2: Tóm tắt bài nghe
     */
    public function summarize(Request $request)
    {
        $request->validate([
            'transcript' => 'required|string'
        ]);

        try {
            $response = Http::post("{$this->pythonServiceUrl}/chatbot/summarize", [
                'transcript' => $request->input('transcript')
            ]);

            if ($response->successful()) {
                return response()->json($response->json());
            }

            Log::error("AiAssistantController: API python tóm tắt trả về lỗi " . $response->status());
            return response()->json(['error' => 'Dịch vụ AI tóm tắt phản hồi không thành công.'], 500);
        } catch (\Exception $e) {
            Log::error("AiAssistantController: " . $e->getMessage());
            return response()->json(['error' => 'Không thể kết nối máy chủ AI để tóm tắt.'], 500);
        }
    }

    /**
     * CHỨC NĂNG 3: Cá nhân hóa lộ trình học
     */
    public function personalize()
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Bạn cần đăng nhập để thực hiện chức năng này.'], 401);
        }

        try {
            // 1. Lấy lịch sử làm bài (Chitietlambai)
            $attempts = $user->results()->with('baitest')->orderBy('CreatedAt', 'desc')->take(5)->get();
            
            $scores = [];
            $history = [];
            $weakTopics = [];
            $totalStudyTime = 0;
            $totalReplays = 0;

            foreach ($attempts as $attempt) {
                // Tính điểm trên thang 10
                $score = $attempt->TongSoCau > 0 ? round(($attempt->SoCauDung / $attempt->TongSoCau) * 10, 1) : 0;
                $scores[] = floatval($score);
                
                $testName = $attempt->baitest ? $attempt->baitest->TenBai : 'Bài nghe';
                $history[] = "Làm bài test '{$testName}' đạt {$attempt->SoCauDung}/{$attempt->TongSoCau} câu đúng vào ngày " . date('d/m/Y', strtotime($attempt->CreatedAt));
                
                // Nếu điểm dưới 5.0, xem như chủ đề yếu
                if ($score < 5.0 && $attempt->baitest) {
                    $weakTopics[] = "Chủ đề học từ bài '{$testName}'";
                }
                
                $totalStudyTime += intval($attempt->ThoiGianLam);
                $totalReplays += intval($attempt->SoLanLam);
            }

            // 2. Lấy danh sách các bản đồ (chủ đề) đã học xong
            $completedMaps = $user->progress()->with('bandophieuluu')
                ->where('KetQuaMan', '100')
                ->get()
                ->pluck('bandophieuluu.TenBanDo')
                ->unique()
                ->toArray();

            // 3. Chuẩn bị payload để gửi sang Python
            $payload = [
                'scores' => empty($scores) ? [0.0] : $scores,
                'history' => empty($history) ? ["Bắt đầu tham gia khóa học luyện nghe ListenUp"] : $history,
                'completed_topics' => empty($completedMaps) ? ["Chưa hoàn thành bản đồ nào"] : array_values($completedMaps),
                'weak_topics' => empty($weakTopics) ? ["Luyện phát âm", "Nghe từ khóa chính"] : array_values(array_unique($weakTopics)),
                'replays' => $totalReplays ?: 1,
                'completion_rate' => count($attempts) > 0 ? round(count($attempts) / 5, 2) : 0.2, // Tỷ lệ ước lượng hoàn thành
                'study_time' => floatval(round($totalStudyTime / 60, 1)) ?: 10.0 // Chuyển sang phút
            ];

            // 4. Gọi Python FastAPI
            $response = Http::post("{$this->pythonServiceUrl}/chatbot/personalize", $payload);

            if ($response->successful()) {
                return response()->json($response->json());
            }

            Log::error("AiAssistantController: API python cá nhân hóa trả về lỗi " . $response->status());
            return response()->json(['error' => 'Dịch vụ AI phân tích lộ trình phản hồi không thành công.'], 500);
        } catch (\Exception $e) {
            Log::error("AiAssistantController: " . $e->getMessage());
            return response()->json(['error' => 'Không thể kết nối máy chủ AI để lập lộ trình.'], 500);
        }
    }
}
