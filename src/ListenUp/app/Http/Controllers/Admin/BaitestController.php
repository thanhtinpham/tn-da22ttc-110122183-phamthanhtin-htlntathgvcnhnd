<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Baitest;

class BaitestController extends Controller
{
    public function index()
    {
        $data = Baitest::with(['bandophieuluu', 'chude', 'capdonghe'])->orderBy('MaBai', 'desc')->paginate(10);
        return view('admin.baitest.index', compact('data'));
    }

    public function create()
    {
        $chudes = \App\Models\Chude::all();
        $bandos = \App\Models\Bandophieuluu::all();
        $capdonghes = \App\Models\Capdonghe::all();
        return view('admin.baitest.create', compact('chudes', 'bandos', 'capdonghes'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $data['MaBanDo'] = !empty($data['MaBanDo']) ? $data['MaBanDo'] : null;
        $data['MaCD'] = !empty($data['MaCD']) ? $data['MaCD'] : null;
        $data['MaCDN'] = !empty($data['MaCDN']) ? $data['MaCDN'] : null;

        $validator = \Validator::make($data, [
            'TenBai' => 'required|string|max:20',
            'TrangThaiBai' => 'required|string|in:Mo,Dong',
            'MaBanDo' => 'nullable|exists:bandophieuluu,MaBanDo',
            'MaCD' => 'nullable|exists:chude,MaCD',
            'MaCDN' => 'nullable|exists:capdonghe,MaCDN',
            'MoTa' => 'nullable|string|max:100',
            'TuKhoaHangDoc' => 'nullable|string|max:50',
            'AnhTroChoi' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'SoManhGhep' => 'nullable|integer|min:4',
        ]);

        $hasErrors = $validator->fails();

        if (!empty($data['SoManhGhep'])) {
            $root = sqrt((int)$data['SoManhGhep']);
            if ($root != floor($root)) {
                $validator->errors()->add('SoManhGhep', 'Số mảnh ghép phải là số chính phương (4, 9, 16, 25, ...) để tạo thành hình vuông.');
                $hasErrors = true;
            }
        }

        if (empty($data['MaBanDo']) && empty($data['MaCD']) && empty($data['MaCDN'])) {
            $validator->errors()->add('relation', 'Bài test phải thuộc ít nhất một trong ba loại: Bản đồ, Chủ đề, hoặc Cấp độ nghe.');
            $hasErrors = true;
        }

        if ($hasErrors) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($request->hasFile('AnhTroChoi')) {
            $file = $request->file('AnhTroChoi');
            $fileName = 'puzzle_' . time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images'), $fileName);
            $data['AnhTroChoi'] = $fileName;
        }

        if (empty($data['MaBai'])) {
            $data['MaBai'] = 'BT' . substr((string) time(), -8);
        }
        Baitest::create($data);
        return redirect()->route('admin.baitest.index')->with('success', 'Thêm thành công');
    }

    public function edit($id)
    {
        $item = Baitest::findOrFail($id);
        $chudes = \App\Models\Chude::all();
        $bandos = \App\Models\Bandophieuluu::all();
        $capdonghes = \App\Models\Capdonghe::all();
        return view('admin.baitest.edit', compact('item', 'chudes', 'bandos', 'capdonghes'));
    }

    public function update(Request $request, $id)
    {
        $item = Baitest::findOrFail($id);
        $data = $request->all();
        $data['MaBanDo'] = !empty($data['MaBanDo']) ? $data['MaBanDo'] : null;
        $data['MaCD'] = !empty($data['MaCD']) ? $data['MaCD'] : null;
        $data['MaCDN'] = !empty($data['MaCDN']) ? $data['MaCDN'] : null;

        $validator = \Validator::make($data, [
            'TenBai' => 'required|string|max:20',
            'TrangThaiBai' => 'required|string|in:Mo,Dong',
            'MaBanDo' => 'nullable|exists:bandophieuluu,MaBanDo',
            'MaCD' => 'nullable|exists:chude,MaCD',
            'MaCDN' => 'nullable|exists:capdonghe,MaCDN',
            'MoTa' => 'nullable|string|max:100',
            'TuKhoaHangDoc' => 'nullable|string|max:50',
            'AnhTroChoi' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'SoManhGhep' => 'nullable|integer|min:4',
        ]);

        $hasErrors = $validator->fails();

        if (!empty($data['SoManhGhep'])) {
            $root = sqrt((int)$data['SoManhGhep']);
            if ($root != floor($root)) {
                $validator->errors()->add('SoManhGhep', 'Số mảnh ghép phải là số chính phương (4, 9, 16, 25, ...) để tạo thành hình vuông.');
                $hasErrors = true;
            }
        }

        if (empty($data['MaBanDo']) && empty($data['MaCD']) && empty($data['MaCDN'])) {
            $validator->errors()->add('relation', 'Bài test phải thuộc ít nhất một trong ba loại: Bản đồ, Chủ đề, hoặc Cấp độ nghe.');
            $hasErrors = true;
        }

        if ($hasErrors) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($request->hasFile('AnhTroChoi')) {
            // Delete old file if it exists
            if (!empty($item->AnhTroChoi)) {
                $oldPath = public_path('images/' . $item->AnhTroChoi);
                if (file_exists($oldPath) && is_file($oldPath)) {
                    @unlink($oldPath);
                }
            }

            $file = $request->file('AnhTroChoi');
            $fileName = 'puzzle_' . time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images'), $fileName);
            $data['AnhTroChoi'] = $fileName;
        }

        $item->update($data);
        return redirect()->route('admin.baitest.index')->with('success', 'Cập nhật thành công');
    }

    public function destroy($id)
    {
        try {
            Baitest::destroy($id);
            return redirect()->route('admin.baitest.index')->with('success', 'Xóa thành công');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('admin.baitest.index')->with('error', 'Không thể xóa vì có dữ liệu liên quan (Phần, Câu hỏi...)');
        }
    }

    public function content($id)
    {
        $baitest = Baitest::with(['phan.cauhoi.phuongancauhoi', 'phan.tepamthanh'])->findOrFail($id);
        return view('admin.baitest.content', compact('baitest'));
    }

    public function updateKeyword(Request $request, $id)
    {
        $baitest = Baitest::findOrFail($id);
        $request->validate([
            'TuKhoaHangDoc' => 'nullable|string|max:50',
        ]);
        $baitest->update([
            'TuKhoaHangDoc' => strtoupper(trim($request->TuKhoaHangDoc))
        ]);
        return back()->with('success', 'Cập nhật từ khóa hàng dọc thành công!');
    }
}

