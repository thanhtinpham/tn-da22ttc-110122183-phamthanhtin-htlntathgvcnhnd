<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:user,Email',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'email.unique' => 'Email này đã được sử dụng. Vui lòng đăng nhập hoặc dùng email khác.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.'
        ]);

        $user = User::create([
            'UserID' => 'US' . strtoupper(Str::random(8)),
            'UserName' => $request->username,
            'Email' => $request->email,
            'Password' => Hash::make($request->password),
            'Role' => 'user',
            'Status' => 'active',
            'CreatedAt' => now()
        ]);

        Auth::login($user);

        return redirect()->route('user.dashboard');
    }
}
