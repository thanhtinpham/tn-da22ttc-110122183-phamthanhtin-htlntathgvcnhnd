<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Capdonghe;

class CapdongheController extends Controller
{
    public function index()
    {
        $data = Capdonghe::orderBy('MaCDN', 'desc')->paginate(10);
        return view('admin.capdonghe.index', compact('data'));
    }

    public function create()
    {
        return view('admin.capdonghe.create');
    }

    public function store(Request $request)
    {
        $data = $request->all();
        if (empty($data['MaCDN'])) {
            $data['MaCDN'] = 'LV' . substr((string) time(), -8);
        }
        Capdonghe::create($data);
        return redirect()->route('admin.capdonghe.index')->with('success', 'Thêm thành công');
    }

    public function edit($id)
    {
        $item = Capdonghe::findOrFail($id);
        return view('admin.capdonghe.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = Capdonghe::findOrFail($id);
        $item->update($request->all());
        return redirect()->route('admin.capdonghe.index')->with('success', 'Cập nhật thành công');
    }

    public function destroy($id)
    {
        try {
            Capdonghe::destroy($id);
            return redirect()->route('admin.capdonghe.index')->with('success', 'Xóa thành công');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('admin.capdonghe.index')->with('error', 'Không thể xóa vì có bài test thuộc cấp độ này.');
        }
    }
}

