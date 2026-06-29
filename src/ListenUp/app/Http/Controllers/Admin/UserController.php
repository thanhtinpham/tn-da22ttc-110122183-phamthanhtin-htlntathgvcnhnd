<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $data = User::paginate(10);
        return view('admin.user.index', compact('data'));
    }

    public function show($id)
    {
        $item = User::with(['results.baitest.chude', 'results.baitest.capdonghe'])->findOrFail($id);
        return view('admin.user.show', compact('item'));
    }

    public function create()
    {
        return view('admin.user.create');
    }

    public function store(Request $request)
    {
        User::create($request->all());
        return redirect()->route('admin.user.index')->with('success', 'Thêm thành công');
    }

    public function edit($id)
    {
        $item = User::findOrFail($id);
        return view('admin.user.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = User::findOrFail($id);
        $item->update($request->all());
        return redirect()->route('admin.user.index')->with('success', 'Cập nhật thành công');
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            if ($user->Status == 'Chặn') {
                $user->Status = 'Hoạt động';
                $user->save();
                return redirect()->route('admin.user.index')->with('success', 'Đã bỏ chặn người dùng.');
            } else {
                $user->Status = 'Chặn';
                $user->save();
                return redirect()->route('admin.user.index')->with('success', 'Đã chặn người dùng.');
            }
        } catch (\Exception $e) {
            return redirect()->route('admin.user.index')->with('error', 'Có lỗi xảy ra.');
        }
    }
}

