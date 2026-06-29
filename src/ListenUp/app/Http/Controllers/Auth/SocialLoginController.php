<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('Email', $googleUser->getEmail())->first();

            if ($user) {
                // Đã có tài khoản bằng Email này
                if (!$user->google_id) {
                    $user->google_id = $googleUser->getId();
                    $user->email_verified_at = now();
                    $user->save();
                }
                Auth::login($user);
            } else {
                // Tạo tài khoản mới
                $user = User::create([
                    'UserID' => 'US' . strtoupper(Str::random(8)),
                    'UserName' => $googleUser->getName(),
                    'Email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'Password' => Hash::make(Str::random(24)),
                    'Role' => 'user',
                    'Status' => 'active',
                    'CreatedAt' => now(),
                    'email_verified_at' => now(), // Đăng nhập Google đã xác thực email
                ]);

                Auth::login($user);
            }

            return redirect()->route('user.dashboard');
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['email' => 'Lỗi đăng nhập bằng Google. Vui lòng thử lại.']);
        }
    }
}
