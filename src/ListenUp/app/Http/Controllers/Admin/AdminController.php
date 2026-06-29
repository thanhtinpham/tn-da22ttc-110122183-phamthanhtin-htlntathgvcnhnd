<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Baitest;
use App\Models\Chude;
use App\Models\Chitietlambai;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_users' => User::where('Role', 'user')->count(),
            'total_lessons' => Baitest::count(),
            'total_results' => Chitietlambai::count(),
            'active_lessons' => Baitest::where('TrangThaiBai', 'Mo')->count(),
        ];

        // 1. Tần suất hoạt động học tập (7 ngày qua)
        $sevenDaysAgo = now()->subDays(7);
        $recentAttempts = Chitietlambai::where('CreatedAt', '>=', $sevenDaysAgo)->get();
        
        $activityData = [];
        for ($i = 6; $i >= 0; $i--) {
            $dateStr = now()->subDays($i)->format('d/m');
            $activityData[$dateStr] = 0;
        }
        
        foreach ($recentAttempts as $attempt) {
            if ($attempt->CreatedAt) {
                $dateStr = date('d/m', strtotime($attempt->CreatedAt));
                if (isset($activityData[$dateStr])) {
                    $activityData[$dateStr]++;
                }
            }
        }

        // 2. Phân bố bài học theo cấp độ
        $lessonsByLevel = Baitest::with('capdonghe')
            ->get()
            ->groupBy(function($item) {
                return $item->capdonghe ? $item->capdonghe->TenCDN : 'Chưa phân loại';
            })
            ->map(function($group) {
                return $group->count();
            });

        // 3. Số lượng bài học theo chủ đề
        $lessonsByTopic = Baitest::with('chude')
            ->get()
            ->groupBy(function($item) {
                return $item->chude ? $item->chude->TenCD : 'Chưa phân loại';
            })
            ->map(function($group) {
                return $group->count();
            });

        return view('admin.dashboard', compact('stats', 'activityData', 'lessonsByLevel', 'lessonsByTopic'));
    }

    public function users()
    {
        $users = User::where('Role', 'user')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function lessons()
    {
        $lessons = Baitest::with(['chude'])->paginate(10);
        return view('admin.lessons.index', compact('lessons'));
    }

    public function categories()
    {
        $categories = Chude::paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    public function editProfile()
    {
        $user = auth()->user();
        
        $frames = [];
        for ($i = 1; $i <= 10; $i++) {
            $filename = ($i === 5) ? 'vein5.jpg' : 'vien' . $i . '.jpg';
            $frames[] = [
                'filename' => $filename,
                'name' => 'Viền Map ' . $i,
                'unlocked' => true // Admins always have all frames unlocked
            ];
        }
        
        return view('admin.profile.edit', compact('user', 'frames'));
    }

    public function updateProfile(\Illuminate\Http\Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'UserName' => 'required|string|max:255',
            'SDT' => 'nullable|string|max:20',
            'GioiTinh' => 'nullable|string',
            'NgaySinh' => 'nullable|date',
            'AnhDaiDien' => 'nullable|image|max:2048',
            'Vien' => 'nullable|string',
        ]);

        $user->UserName = $request->UserName;
        $user->SDT = $request->SDT;
        $user->GioiTinh = $request->GioiTinh;
        $user->NgaySinh = $request->NgaySinh;

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

        return redirect()->route('admin.profile.edit')->with('success', 'Cập nhật thông tin thành công!');
    }
}