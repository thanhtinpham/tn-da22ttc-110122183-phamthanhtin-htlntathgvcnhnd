<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || (!Auth::user()->isUser() && !Auth::user()->isAdmin())) {
            return redirect('/')->with('error', 'Bạn không có quyền truy cập trang này.');
        }

        return $next($request);
    }
}