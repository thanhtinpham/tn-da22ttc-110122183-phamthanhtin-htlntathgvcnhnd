<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Phan;
use App\Models\Cauhoi;
use App\Models\Phuongancauhoi;
use App\Models\Tepamthanh;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class TestContentController extends Controller
{
    public function storePhan(Request $request, $baitestId)
    {
        $request->validate([
            'TenPhan' => 'required|string|max:100',
            'ThuTuPhan' => 'required|integer',
        ]);

        $maPhan = 'P' . rand(100, 999) . rand(10, 99);

        Phan::create([
            'MaPhan' => $maPhan,
            'MaBai' => $baitestId,
            'TenPhan' => $request->TenPhan,
            'ThuTuPhan' => $request->ThuTuPhan,
            'SoCauHoi' => 0,
            'MoTaPhan' => $request->MoTaPhan ?? '',
            'TrangThaiPhan' => 'Active',
        ]);

        return back()->with('success', 'Thêm phần thành công!');
    }

    public function storeAudio(Request $request, $phanId)
    {
        $request->validate([
            'audio_source' => 'required|in:upload,generate',
            'audio_file' => 'required_if:audio_source,upload|nullable|file|mimes:mp3,wav,mpeg,ogg,mpga|max:10240',
            'audio_text' => 'required_if:audio_source,generate|nullable|string|max:1000',
            'TGTep' => 'required|string',
            'GioiHanPhat' => 'required|string',
        ]);

        $phan = Phan::findOrFail($phanId);
        $fileName = null;

        if ($request->input('audio_source') === 'generate') {
            $text = $request->input('audio_text');
            $accent = $request->input('audio_accent', 'en-US');
            $speed = $request->input('audio_speed', '1.0');

            $escapedText = escapeshellarg($text);
            $escapedAccent = escapeshellarg($accent);
            $escapedSpeed = escapeshellarg($speed);
            
            $scriptPath = base_path('CA/app_cli.py');
            $output = shell_exec("python " . escapeshellarg($scriptPath) . " convert {$escapedText} --accent {$escapedAccent} --speed {$escapedSpeed}");

            if (empty($output)) {
                return back()->withErrors(['audio_text' => 'Không thể kết nối với dịch vụ chuyển đổi AI. Hãy kiểm tra cài đặt Python.'])->withInput();
            }
            
            $result = json_decode($output, true);
            if (json_last_error() !== JSON_ERROR_NONE || isset($result['error'])) {
                $errorMsg = isset($result['error']) ? $result['error'] : 'Phản hồi từ dịch vụ AI không hợp lệ.';
                return back()->withErrors(['audio_text' => $errorMsg])->withInput();
            }

            $tempFileName = basename($result['audio_url']);
            $publicPath = public_path('audio/' . $tempFileName);
            $fileName = time() . '_' . $tempFileName;
            $targetPath = storage_path('app/public/' . $fileName);

            if (file_exists($publicPath)) {
                rename($publicPath, $targetPath);
            } else {
                return back()->withErrors(['audio_text' => 'Tệp âm thanh được tạo ra không tìm thấy.'])->withInput();
            }
        } else {
            $file = $request->file('audio_file');
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $sluggedName = Str::slug(substr($originalName, 0, 50));
            $fileName = time() . '_' . $sluggedName . '.' . $extension;
            $file->move(storage_path('app/public'), $fileName);
        }

        do {
            $maTep = 'TP' . rand(100, 999);
        } while (Tepamthanh::where('MaTep', $maTep)->exists());

        $tep = Tepamthanh::create([
            'MaTep' => $maTep,
            'DuongDan' => $fileName,
            'TenTep' => substr('Audio ' . $phan->TenPhan, 0, 10),
            'TGTep' => $request->TGTep,
            'GioiHanPhat' => $request->GioiHanPhat,
        ]);

        $phan->MaTep = $maTep;
        $phan->save();

        return back()->with('success', 'Cung cấp âm thanh thành công!');
    }

    public function storeCauhoi(Request $request, $phanId)
    {
        $phan = Phan::findOrFail($phanId);
        $baitest = $phan->baitest;
        $mapNumber = 0;
        if ($baitest && $baitest->MaBanDo) {
            $mapNumber = (int) str_replace('BD', '', $baitest->MaBanDo);
        }

        $maCauHoi = 'CH' . rand(1000, 9999);

        if ($mapNumber === 1) {
            $request->validate([
                'NDCauHoi' => 'required|string',
                'DapAnOChu' => 'required|string|max:100',
                'ViTriGiao' => 'required|integer',
            ]);

            $cauhoi = Cauhoi::create([
                'MaCauHoi' => $maCauHoi,
                'MaLoai' => 'L01',
                'NDCauHoi' => $request->NDCauHoi,
                'ViTriGiao' => $request->ViTriGiao,
                'TrangThaiCauHoi' => 'Active',
            ]);

            $phan->cauhoi()->attach($maCauHoi);
            $phan->SoCauHoi = $phan->cauhoi()->count();
            $phan->save();

            Phuongancauhoi::create([
                'MaPA' => 'PA' . rand(1000, 9999),
                'MaCauHoi' => $maCauHoi,
                'NDPA' => 'A. ' . strtoupper(trim($request->DapAnOChu)),
                'HinhAnh' => null,
                'DapAn' => 'Dung',
                'Diem' => 10,
            ]);

            return back()->with('success', 'Thêm câu hỏi ô chữ thành công!');
        }

        if ($mapNumber === 2 || $mapNumber === 4 || $mapNumber === 6) {
            $request->validate([
                'NDCauHoi' => 'required|string',
                'OptionA' => 'required|string',
            ]);

            $cauhoi = Cauhoi::create([
                'MaCauHoi' => $maCauHoi,
                'MaLoai' => 'L01',
                'NDCauHoi' => $request->NDCauHoi,
                'TrangThaiCauHoi' => 'Active',
            ]);

            $phan->cauhoi()->attach($maCauHoi);
            $phan->SoCauHoi = $phan->cauhoi()->count();
            $phan->save();

            Phuongancauhoi::create([
                'MaPA' => 'PA' . rand(1000, 9999),
                'MaCauHoi' => $maCauHoi,
                'NDPA' => 'A. ' . trim($request->OptionA),
                'HinhAnh' => null,
                'DapAn' => 'Dung',
                'Diem' => 10,
            ]);

            return back()->with('success', 'Thêm câu hỏi thành công!');
        }

        if ($mapNumber === 3) {
            $request->validate([
                'NDCauHoi' => 'required|string',
                'words' => 'required|array|min:1',
                'words.*' => 'required|string',
            ]);

            $joinedWords = implode(' ', $request->words);

            $cauhoi = Cauhoi::create([
                'MaCauHoi' => $maCauHoi,
                'MaLoai' => 'L01',
                'NDCauHoi' => $request->NDCauHoi,
                'TrangThaiCauHoi' => 'Active',
            ]);

            $phan->cauhoi()->attach($maCauHoi);
            $phan->SoCauHoi = $phan->cauhoi()->count();
            $phan->save();

            Phuongancauhoi::create([
                'MaPA' => 'PA' . rand(1000, 9999),
                'MaCauHoi' => $maCauHoi,
                'NDPA' => 'A. ' . trim($joinedWords),
                'HinhAnh' => null,
                'DapAn' => 'Dung',
                'Diem' => 10,
            ]);

            return back()->with('success', 'Thêm câu hỏi hoàn thành câu thành công!');
        }

        if ($mapNumber === 7) {
            $request->validate([
                'NDCauHoi' => 'required|string',
                'words' => 'required|array|min:1',
                'words.*' => 'required|string',
            ]);

            $joinedWords = implode('|', $request->words);

            $cauhoi = Cauhoi::create([
                'MaCauHoi' => $maCauHoi,
                'MaLoai' => 'L01',
                'NDCauHoi' => $request->NDCauHoi,
                'TrangThaiCauHoi' => 'Active',
            ]);

            $phan->cauhoi()->attach($maCauHoi);
            $phan->SoCauHoi = $phan->cauhoi()->count();
            $phan->save();

            Phuongancauhoi::create([
                'MaPA' => 'PA' . rand(1000, 9999),
                'MaCauHoi' => $maCauHoi,
                'NDPA' => 'A. ' . trim($joinedWords),
                'HinhAnh' => null,
                'DapAn' => 'Dung',
                'Diem' => 10,
            ]);

            return back()->with('success', 'Thêm câu hỏi hoàn thành câu thành công!');
        }

        if ($mapNumber === 5) {
            $request->validate([
                'NDCauHoi' => 'required|string',
                'OptionA' => 'required|string',
                'OptionB' => 'required|string',
                'OptionC' => 'nullable|string',
                'OptionD' => 'nullable|string',
            ]);

            $cauhoi = Cauhoi::create([
                'MaCauHoi' => $maCauHoi,
                'MaLoai' => 'L01',
                'NDCauHoi' => $request->NDCauHoi,
                'TrangThaiCauHoi' => 'Active',
            ]);

            $phan->cauhoi()->attach($maCauHoi);
            $phan->SoCauHoi = $phan->cauhoi()->count();
            $phan->save();

            Phuongancauhoi::create([
                'MaPA' => 'PA' . rand(1000, 9999),
                'MaCauHoi' => $maCauHoi,
                'NDPA' => 'A. ' . trim($request->OptionA),
                'HinhAnh' => null,
                'DapAn' => 'Dung',
                'Diem' => 10,
            ]);

            Phuongancauhoi::create([
                'MaPA' => 'PA' . rand(1000, 9999),
                'MaCauHoi' => $maCauHoi,
                'NDPA' => 'B. ' . trim($request->OptionB),
                'HinhAnh' => null,
                'DapAn' => 'Sai',
                'Diem' => 0,
            ]);

            if ($request->filled('OptionC')) {
                Phuongancauhoi::create([
                    'MaPA' => 'PA' . rand(1000, 9999),
                    'MaCauHoi' => $maCauHoi,
                    'NDPA' => 'C. ' . trim($request->OptionC),
                    'HinhAnh' => null,
                    'DapAn' => 'Sai',
                    'Diem' => 0,
                ]);
            }

            if ($request->filled('OptionD')) {
                Phuongancauhoi::create([
                    'MaPA' => 'PA' . rand(1000, 9999),
                    'MaCauHoi' => $maCauHoi,
                    'NDPA' => 'D. ' . trim($request->OptionD),
                    'HinhAnh' => null,
                    'DapAn' => 'Sai',
                    'Diem' => 0,
                ]);
            }

            return back()->with('success', 'Thêm câu hỏi thành công!');
        }

        $request->validate([
            'NDCauHoi' => 'required|string',
            'OptionA' => 'nullable|string',
            'OptionB' => 'nullable|string',
            'OptionC' => 'nullable|string',
            'OptionD' => 'nullable|string',
            'ImageA' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'ImageB' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'ImageC' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'ImageD' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $cauhoi = Cauhoi::create([
            'MaCauHoi' => $maCauHoi,
            'MaLoai' => 'L01',
            'NDCauHoi' => $request->NDCauHoi,
            'TrangThaiCauHoi' => 'Active',
        ]);

        $phan->cauhoi()->attach($maCauHoi);
        $phan->SoCauHoi = $phan->cauhoi()->count();
        $phan->save();

        // Handle Image Uploads
        $imgA = $imgB = $imgC = $imgD = null;
        if ($request->hasFile('ImageA')) {
            $name = $request->file('ImageA')->hashName();
            $request->file('ImageA')->move(public_path('storage/options'), $name);
            $imgA = 'options/' . $name;
        }
        if ($request->hasFile('ImageB')) {
            $name = $request->file('ImageB')->hashName();
            $request->file('ImageB')->move(public_path('storage/options'), $name);
            $imgB = 'options/' . $name;
        }
        if ($request->hasFile('ImageC')) {
            $name = $request->file('ImageC')->hashName();
            $request->file('ImageC')->move(public_path('storage/options'), $name);
            $imgC = 'options/' . $name;
        }
        if ($request->hasFile('ImageD')) {
            $name = $request->file('ImageD')->hashName();
            $request->file('ImageD')->move(public_path('storage/options'), $name);
            $imgD = 'options/' . $name;
        }

        // Create options
        $options = [];
        $correct = $request->CorrectAnswer;
        if ($request->filled('OptionA') || $imgA) $options[] = ['MaPA' => 'PA' . rand(1000, 9999), 'NDPA' => 'A. ' . ($request->OptionA ?? ''), 'HinhAnh' => $imgA, 'isCorrect' => ($correct == 'A'), 'Score' => ($correct == 'A' ? $request->ScoreA : 0)];
        if ($request->filled('OptionB') || $imgB) $options[] = ['MaPA' => 'PA' . rand(1000, 9999), 'NDPA' => 'B. ' . ($request->OptionB ?? ''), 'HinhAnh' => $imgB, 'isCorrect' => ($correct == 'B'), 'Score' => ($correct == 'B' ? $request->ScoreB : 0)];
        if ($request->filled('OptionC') || $imgC) $options[] = ['MaPA' => 'PA' . rand(1000, 9999), 'NDPA' => 'C. ' . ($request->OptionC ?? ''), 'HinhAnh' => $imgC, 'isCorrect' => ($correct == 'C'), 'Score' => ($correct == 'C' ? $request->ScoreC : 0)];
        if ($request->filled('OptionD') || $imgD) $options[] = ['MaPA' => 'PA' . rand(1000, 9999), 'NDPA' => 'D. ' . ($request->OptionD ?? ''), 'HinhAnh' => $imgD, 'isCorrect' => ($correct == 'D'), 'Score' => ($correct == 'D' ? $request->ScoreD : 0)];

        foreach ($options as $opt) {
            Phuongancauhoi::create([
                'MaPA' => $opt['MaPA'],
                'MaCauHoi' => $maCauHoi,
                'NDPA' => $opt['NDPA'],
                'HinhAnh' => $opt['HinhAnh'],
                'DapAn' => $opt['isCorrect'] ? 'Dung' : 'Sai',
                'Diem' => $opt['Score'] ?? 0,
            ]);
        }

        return back()->with('success', 'Thêm câu hỏi thành công!');
    }

    public function updateCauhoi(Request $request, $id)
    {
        $cauhoi = Cauhoi::findOrFail($id);
        $phan = $cauhoi->phans()->first();
        $mapNumber = 0;
        if ($phan && $phan->baitest && $phan->baitest->MaBanDo) {
            $mapNumber = (int) str_replace('BD', '', $phan->baitest->MaBanDo);
        }

        if ($mapNumber === 1) {
            $request->validate([
                'NDCauHoi' => 'required|string',
                'DapAnOChu' => 'required|string|max:100',
                'ViTriGiao' => 'required|integer',
            ]);

            $cauhoi->NDCauHoi = $request->NDCauHoi;
            $cauhoi->ViTriGiao = $request->ViTriGiao;
            $cauhoi->save();

            Phuongancauhoi::where('MaCauHoi', $id)->delete();

            Phuongancauhoi::create([
                'MaPA' => 'PA' . rand(1000, 9999),
                'MaCauHoi' => $id,
                'NDPA' => 'A. ' . strtoupper(trim($request->DapAnOChu)),
                'HinhAnh' => null,
                'DapAn' => 'Dung',
                'Diem' => 10,
            ]);

            return back()->with('success', 'Cập nhật câu hỏi ô chữ thành công!');
        }

        if ($mapNumber === 2 || $mapNumber === 4 || $mapNumber === 6) {
            $request->validate([
                'NDCauHoi' => 'required|string',
                'OptionA' => 'required|string',
            ]);

            $cauhoi->NDCauHoi = $request->NDCauHoi;
            $cauhoi->save();

            Phuongancauhoi::where('MaCauHoi', $id)->delete();

            Phuongancauhoi::create([
                'MaPA' => 'PA' . rand(1000, 9999),
                'MaCauHoi' => $id,
                'NDPA' => 'A. ' . trim($request->OptionA),
                'HinhAnh' => null,
                'DapAn' => 'Dung',
                'Diem' => 10,
            ]);

            return back()->with('success', 'Cập nhật câu hỏi thành công!');
        }

        if ($mapNumber === 3) {
            $request->validate([
                'NDCauHoi' => 'required|string',
                'words' => 'required|array|min:1',
                'words.*' => 'required|string',
            ]);

            $joinedWords = implode(' ', $request->words);

            $cauhoi->NDCauHoi = $request->NDCauHoi;
            $cauhoi->save();

            Phuongancauhoi::where('MaCauHoi', $id)->delete();

            Phuongancauhoi::create([
                'MaPA' => 'PA' . rand(1000, 9999),
                'MaCauHoi' => $id,
                'NDPA' => 'A. ' . trim($joinedWords),
                'HinhAnh' => null,
                'DapAn' => 'Dung',
                'Diem' => 10,
            ]);

            return back()->with('success', 'Cập nhật câu hỏi thành công!');
        }

        if ($mapNumber === 7) {
            $request->validate([
                'NDCauHoi' => 'required|string',
                'words' => 'required|array|min:1',
                'words.*' => 'required|string',
            ]);

            $joinedWords = implode('|', $request->words);

            $cauhoi->NDCauHoi = $request->NDCauHoi;
            $cauhoi->save();

            Phuongancauhoi::where('MaCauHoi', $id)->delete();

            Phuongancauhoi::create([
                'MaPA' => 'PA' . rand(1000, 9999),
                'MaCauHoi' => $id,
                'NDPA' => 'A. ' . trim($joinedWords),
                'HinhAnh' => null,
                'DapAn' => 'Dung',
                'Diem' => 10,
            ]);

            return back()->with('success', 'Cập nhật câu hỏi thành công!');
        }

        if ($mapNumber === 5) {
            $request->validate([
                'NDCauHoi' => 'required|string',
                'OptionA' => 'required|string',
                'OptionB' => 'required|string',
                'OptionC' => 'nullable|string',
                'OptionD' => 'nullable|string',
            ]);

            $cauhoi->NDCauHoi = $request->NDCauHoi;
            $cauhoi->save();

            Phuongancauhoi::where('MaCauHoi', $id)->delete();

            Phuongancauhoi::create([
                'MaPA' => 'PA' . rand(1000, 9999),
                'MaCauHoi' => $id,
                'NDPA' => 'A. ' . trim($request->OptionA),
                'HinhAnh' => null,
                'DapAn' => 'Dung',
                'Diem' => 10,
            ]);

            Phuongancauhoi::create([
                'MaPA' => 'PA' . rand(1000, 9999),
                'MaCauHoi' => $id,
                'NDPA' => 'B. ' . trim($request->OptionB),
                'HinhAnh' => null,
                'DapAn' => 'Sai',
                'Diem' => 0,
            ]);

            if ($request->filled('OptionC')) {
                Phuongancauhoi::create([
                    'MaPA' => 'PA' . rand(1000, 9999),
                    'MaCauHoi' => $id,
                    'NDPA' => 'C. ' . trim($request->OptionC),
                    'HinhAnh' => null,
                    'DapAn' => 'Sai',
                    'Diem' => 0,
                ]);
            }

            if ($request->filled('OptionD')) {
                Phuongancauhoi::create([
                    'MaPA' => 'PA' . rand(1000, 9999),
                    'MaCauHoi' => $id,
                    'NDPA' => 'D. ' . trim($request->OptionD),
                    'HinhAnh' => null,
                    'DapAn' => 'Sai',
                    'Diem' => 0,
                ]);
            }

            return back()->with('success', 'Cập nhật câu hỏi thành công!');
        }

        $request->validate([
            'NDCauHoi' => 'required|string',
            'OptionA' => 'nullable|string',
            'OptionB' => 'nullable|string',
            'OptionC' => 'nullable|string',
            'OptionD' => 'nullable|string',
            'ImageA' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'ImageB' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'ImageC' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'ImageD' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $cauhoi->NDCauHoi = $request->NDCauHoi;
        $cauhoi->save();

        // Update options
        $options = $cauhoi->phuongancauhoi()->orderBy('MaPA')->get();
        
        $inputLabels = ['A', 'B', 'C', 'D'];
        $correct = $request->CorrectAnswer;
        $inputs = [
            'A' => ['text' => $request->OptionA, 'file' => $request->file('ImageA'), 'hasFile' => $request->hasFile('ImageA'), 'isCorrect' => ($correct == 'A'), 'score' => ($correct == 'A' ? $request->ScoreA : 0)],
            'B' => ['text' => $request->OptionB, 'file' => $request->file('ImageB'), 'hasFile' => $request->hasFile('ImageB'), 'isCorrect' => ($correct == 'B'), 'score' => ($correct == 'B' ? $request->ScoreB : 0)],
            'C' => ['text' => $request->OptionC, 'file' => $request->file('ImageC'), 'hasFile' => $request->hasFile('ImageC'), 'isCorrect' => ($correct == 'C'), 'score' => ($correct == 'C' ? $request->ScoreC : 0)],
            'D' => ['text' => $request->OptionD, 'file' => $request->file('ImageD'), 'hasFile' => $request->hasFile('ImageD'), 'isCorrect' => ($correct == 'D'), 'score' => ($correct == 'D' ? $request->ScoreD : 0)]
        ];

        $existing = [];
        foreach ($options as $opt) {
            $prefix = strtoupper(substr($opt->NDPA, 0, 1));
            if (in_array($prefix, $inputLabels)) {
                $existing[$prefix] = $opt;
            }
        }

        foreach ($inputLabels as $label) {
            $in = $inputs[$label];
            $hasContent = !empty($in['text']) || $in['hasFile'];

            if ($hasContent) {
                $data = [
                    'NDPA' => $label . '. ' . preg_replace('/^[A-D]\.\s*/', '', $in['text'] ?? ''),
                    'DapAn' => $in['isCorrect'] ? 'Dung' : 'Sai',
                    'Diem' => $in['score'] ?? 0
                ];
                if ($in['hasFile']) {
                    $name = $in['file']->hashName();
                    $in['file']->move(public_path('storage/options'), $name);
                    $data['HinhAnh'] = 'options/' . $name;
                }

                if (isset($existing[$label])) {
                    $existing[$label]->update($data);
                } else {
                    Phuongancauhoi::create(array_merge($data, [
                        'MaPA' => 'PA' . rand(1000, 9999),
                        'MaCauHoi' => $id
                    ]));
                }
            } else {
                if (isset($existing[$label])) {
                    $existing[$label]->delete();
                }
            }
        }

        return back()->with('success', 'Cập nhật câu hỏi thành công!');
    }

    public function destroyCauhoi($id)
    {
        $cauhoi = Cauhoi::findOrFail($id);
        
        // Update SoCauHoi for related Phans before deleting
        foreach ($cauhoi->phans as $phan) {
            $phan->cauhoi()->detach($id);
            $phan->SoCauHoi = $phan->cauhoi()->count();
            $phan->save();
        }

        // Delete from 'ket qua' table to avoid foreign key constraints
        DB::table('ket qua')->where('MaCauHoi', $id)->delete();

        // Delete options
        Phuongancauhoi::where('MaCauHoi', $id)->delete();
        
        // Delete question
        $cauhoi->delete();

        return back()->with('success', 'Xóa câu hỏi thành công!');
    }

    public function updateAudio(Request $request, $id)
    {
        $request->validate([
            'audio_source' => 'required|in:upload,generate',
            'audio_file' => 'nullable|file|mimes:mp3,wav,mpeg,ogg,mpga|max:10240',
            'audio_text' => 'required_if:audio_source,generate|nullable|string|max:1000',
            'TGTep' => 'required|string',
            'GioiHanPhat' => 'required|string',
        ]);

        $tep = Tepamthanh::findOrFail($id);
        $fileName = null;

        if ($request->input('audio_source') === 'generate') {
            $text = $request->input('audio_text');
            $accent = $request->input('audio_accent', 'en-US');
            $speed = $request->input('audio_speed', '1.0');

            $escapedText = escapeshellarg($text);
            $escapedAccent = escapeshellarg($accent);
            $escapedSpeed = escapeshellarg($speed);
            
            $scriptPath = base_path('CA/app_cli.py');
            $output = shell_exec("python " . escapeshellarg($scriptPath) . " convert {$escapedText} --accent {$escapedAccent} --speed {$escapedSpeed}");

            if (empty($output)) {
                return back()->withErrors(['audio_text' => 'Không thể kết nối với dịch vụ chuyển đổi AI. Hãy kiểm tra cài đặt Python.'])->withInput();
            }
            
            $result = json_decode($output, true);
            if (json_last_error() !== JSON_ERROR_NONE || isset($result['error'])) {
                $errorMsg = isset($result['error']) ? $result['error'] : 'Phản hồi từ dịch vụ AI không hợp lệ.';
                return back()->withErrors(['audio_text' => $errorMsg])->withInput();
            }

            $tempFileName = basename($result['audio_url']);
            $publicPath = public_path('audio/' . $tempFileName);
            $fileName = time() . '_' . $tempFileName;
            $targetPath = storage_path('app/public/' . $fileName);

            if (file_exists($publicPath)) {
                // Remove old file if it exists
                $oldPath = storage_path('app/public/' . $tep->DuongDan);
                if (file_exists($oldPath) && is_file($oldPath)) {
                    @unlink($oldPath);
                }
                rename($publicPath, $targetPath);
                $tep->DuongDan = $fileName;
            } else {
                return back()->withErrors(['audio_text' => 'Tệp âm thanh được tạo ra không tìm thấy.'])->withInput();
            }
        } elseif ($request->hasFile('audio_file')) {
            // Remove old file if it exists
            $oldPath = storage_path('app/public/' . $tep->DuongDan);
            if (file_exists($oldPath) && is_file($oldPath)) {
                @unlink($oldPath);
            }

            $file = $request->file('audio_file');
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $sluggedName = Str::slug(substr($originalName, 0, 50));
            $fileName = time() . '_' . $sluggedName . '.' . $extension;
            $file->move(storage_path('app/public'), $fileName);
            $tep->DuongDan = $fileName;
        }

        $tep->TGTep = $request->TGTep;
        $tep->GioiHanPhat = $request->GioiHanPhat;
        $tep->save();

        return back()->with('success', 'Cập nhật âm thanh thành công!');
    }

    public function destroyAudio($id)
    {
        $tep = Tepamthanh::findOrFail($id);

        // Set MaTep to null in all Phans using it
        Phan::where('MaTep', $id)->update(['MaTep' => null]);

        // Delete physical file
        $path = storage_path('app/public/' . $tep->DuongDan);
        if (file_exists($path) && is_file($path)) {
            @unlink($path);
        }

        $tep->delete();

        return back()->with('success', 'Xóa âm thanh thành công!');
    }

    public function destroyPhan($id)
    {
        $phan = Phan::findOrFail($id);
        $baitest = $phan->baitest;

        // 1. Get all related questions and delete them (with options and results)
        $questions = $phan->cauhoi;
        foreach ($questions as $question) {
            // Detach from all phans
            foreach ($question->phans as $p) {
                $p->cauhoi()->detach($question->MaCauHoi);
                $p->SoCauHoi = $p->cauhoi()->count();
                $p->save();
            }

            // Delete from 'ket qua' table
            DB::table('ket qua')->where('MaCauHoi', $question->MaCauHoi)->delete();

            // Delete options
            Phuongancauhoi::where('MaCauHoi', $question->MaCauHoi)->delete();

            // Delete question
            $question->delete();
        }

        // 2. Delete related audio file and record
        if ($phan->MaTep) {
            $tep = Tepamthanh::find($phan->MaTep);
            if ($tep) {
                // Set MaTep to null in all Phans using it to avoid integrity issues
                Phan::where('MaTep', $tep->MaTep)->update(['MaTep' => null]);

                // Delete physical file
                $path = storage_path('app/public/' . $tep->DuongDan);
                if (file_exists($path) && is_file($path)) {
                    @unlink($path);
                }

                $tep->delete();
            }
        }

        // 3. Delete the Phan itself
        $phan->delete();

        // 4. Update TongSoPhan for the Baitest
        if ($baitest) {
            $baitest->TongSoPhan = $baitest->phan()->count();
            $baitest->save();
        }

        return back()->with('success', 'Xóa phần, câu hỏi và tệp âm thanh liên quan thành công!');
    }
}
