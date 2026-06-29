<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Chude;

class ChudeController extends Controller
{
    public function index()
    {
        $data = Chude::orderBy('MaCD', 'desc')->paginate(10);
        return view('admin.chude.index', compact('data'));
    }

    public function create()
    {
        return view('admin.chude.create');
    }

    public function store(Request $request)
    {
        $data = $request->all();
        if (empty($data['MaCD'])) {
            $data['MaCD'] = 'CD' . substr((string) time(), -8);
        }
        Chude::create($data);
        return redirect()->route('admin.chude.index')->with('success', 'Thêm thành công');
    }

    public function edit($id)
    {
        $item = Chude::findOrFail($id);
        return view('admin.chude.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = Chude::findOrFail($id);
        $item->update($request->all());
        return redirect()->route('admin.chude.index')->with('success', 'Cập nhật thành công');
    }

    public function destroy($id)
    {
        try {
            Chude::destroy($id);
            return redirect()->route('admin.chude.index')->with('success', 'Xóa thành công');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('admin.chude.index')->with('error', 'Không thể xóa vì có bài test thuộc chủ đề này.');
        }
    }
}

