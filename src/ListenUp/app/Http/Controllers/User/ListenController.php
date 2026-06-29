<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ListenController extends Controller
{
    /**
     * Display the AI Voice & IPA generation interface.
     */
    public function index()
    {
        return view('user.ca.index');
    }

    /**
     * Process the text input to generate IPA transcription and TTS audio file.
     */
    public function generate(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:1000',
            'gender' => 'nullable|string|in:male,female',
        ]);

        $text = $request->input('text');
        $gender = $request->input('gender', 'female');
        
        // Retrieve authenticated user's preferences if logged in
        $user = auth()->user();
        $accent = $user ? ($user->preferred_accent ?? 'en-US') : 'en-US';
        $speed = $user ? ($user->preferred_speed ?? 1.0) : 1.0;
        
        // Safely escape the arguments for shell execution
        $escapedText = escapeshellarg($text);
        $escapedAccent = escapeshellarg($accent);
        $escapedSpeed = escapeshellarg($speed);
        $escapedGender = escapeshellarg($gender);
        
        // Resolve path to python helper script
        $scriptPath = base_path('CA/app_cli.py');
        
        // Run the python script with personalized parameters and capture stdout
        $output = shell_exec("python " . escapeshellarg($scriptPath) . " convert {$escapedText} --accent {$escapedAccent} --speed {$escapedSpeed} --gender {$escapedGender}");
        
        if (empty($output)) {
            Log::error("ListenController: shell_exec returned empty output.");
            return response()->json(['error' => 'Không thể kết nối với dịch vụ chuyển đổi AI. Hãy kiểm tra cài đặt Python.'], 500);
        }
        
        $result = json_decode($output, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error("ListenController: Invalid JSON response from CLI: " . $output);
            return response()->json(['error' => 'Phản hồi từ dịch vụ AI không hợp lệ.'], 500);
        }
        
        if (isset($result['error'])) {
            Log::error("ListenController: CLI error: " . $result['error']);
            return response()->json(['error' => $result['error']], 500);
        }
        
        return response()->json($result);
    }
}
