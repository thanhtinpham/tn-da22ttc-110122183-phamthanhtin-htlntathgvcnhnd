<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bandophieuluu;

class BandophieuluuController extends Controller
{
    public function index()
    {
        $data = Bandophieuluu::orderBy('MaBanDo', 'desc')->paginate(10);
        return view('admin.bandophieuluu.index', compact('data'));
    }

    public function create()
    {
        return view('admin.bandophieuluu.create');
    }

    public function store(Request $request)
    {
        $data = $request->all();
        if (empty($data['MaBanDo'])) {
            $data['MaBanDo'] = 'MP' . substr((string) time(), -8);
        }
        Bandophieuluu::create($data);
        return redirect()->route('admin.bandophieuluu.index')->with('success', 'Thêm thành công');
    }

    public function edit($id)
    {
        $item = Bandophieuluu::findOrFail($id);
        return view('admin.bandophieuluu.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = Bandophieuluu::findOrFail($id);
        $item->update($request->all());
        return redirect()->route('admin.bandophieuluu.index')->with('success', 'Cập nhật thành công');
    }

    public function destroy($id)
    {
        try {
            Bandophieuluu::destroy($id);
            return redirect()->route('admin.bandophieuluu.index')->with('success', 'Xóa thành công');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('admin.bandophieuluu.index')->with('error', 'Không thể xóa vì có dữ liệu liên quan (Bài test, Tiến trình...)');
        }
    }
    public function content($id)
    {
        $map = Bandophieuluu::with('baitests')->findOrFail($id);
        $allTests = \App\Models\Baitest::all();
        return view('admin.bandophieuluu.content', compact('map', 'allTests'));
    }

    public function assignTest(Request $request, $id)
    {
        $request->validate([
            'MaBai' => 'required|exists:baitest,MaBai'
        ]);

        $test = \App\Models\Baitest::findOrFail($request->MaBai);
        $test->MaBanDo = $id;
        $test->save();

        return back()->with('success', 'Đã thêm bài test vào bản đồ này!');
    }

    public function unassignTest($id, $test_id)
    {
        $test = \App\Models\Baitest::findOrFail($test_id);
        if ($test->MaBanDo == $id) {
            $test->MaBanDo = null;
            $test->save();
        }

        return back()->with('success', 'Đã gỡ bài test khỏi bản đồ này!');
    }
}

