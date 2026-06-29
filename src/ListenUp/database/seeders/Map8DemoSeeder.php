<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Baitest;
use App\Models\Phan;
use App\Models\Cauhoi;
use App\Models\Phuongancauhoi;
use Illuminate\Support\Facades\DB;

class Map8DemoSeeder extends Seeder
{
    public function run()
    {
        // 1. Find or create BT08 baitest
        $baitest = Baitest::where('MaBai', 'BT08')->first();
        if (!$baitest) {
            $baitest = Baitest::create([
                'MaBai' => 'BT08',
                'MaBanDo' => 'BD08',
                'MaCD' => 'CD08',
                'MaCDN' => 'CDN08',
                'TenBai' => 'Music Fun',
                'TrangThaiBai' => 'Mo',
                'MoTa' => 'Trò chơi mở mảnh ghép đầy thú vị!',
            ]);
        }

        // Update BT08 puzzle setup
        $baitest->AnhTroChoi = 'map8_puzzle.png';
        $baitest->SoManhGhep = 4;
        $baitest->TrangThaiBai = 'Mo';
        $baitest->save();

        // 2. Clear old parts and question associations to prevent duplicates
        $oldPhanIds = Phan::where('MaBai', 'BT08')->pluck('MaPhan');
        
        // Delete pivot records
        DB::table('phan_cauhoi')->whereIn('MaPhan', $oldPhanIds)->delete();
        
        // Delete questions and options
        $oldQuestionIds = ['CH081', 'CH082', 'CH083', 'CH084'];
        Phuongancauhoi::whereIn('MaCauHoi', $oldQuestionIds)->delete();
        Cauhoi::whereIn('MaCauHoi', $oldQuestionIds)->delete();
        Phan::whereIn('MaPhan', $oldPhanIds)->delete();

        // 3. Create 4 Parts
        $partsData = [
            [
                'MaPhan' => 'PH081',
                'TenPhan' => 'Phần 1: Khởi động',
                'MaTep' => 'TP01',
                'ThuTuPhan' => 1,
            ],
            [
                'MaPhan' => 'PH082',
                'TenPhan' => 'Phần 2: Vượt chướng ngại',
                'MaTep' => 'TP02',
                'ThuTuPhan' => 2,
            ],
            [
                'MaPhan' => 'PH083',
                'TenPhan' => 'Phần 3: Tăng tốc',
                'MaTep' => 'TP03',
                'ThuTuPhan' => 3,
            ],
            [
                'MaPhan' => 'PH084',
                'TenPhan' => 'Phần 4: Về đích',
                'MaTep' => 'TP01', // reuse TP01
                'ThuTuPhan' => 4,
            ]
        ];

        foreach ($partsData as $pData) {
            Phan::create([
                'MaPhan' => $pData['MaPhan'],
                'MaBai' => 'BT08',
                'MaTep' => $pData['MaTep'],
                'TenPhan' => $pData['TenPhan'],
                'ThuTuPhan' => $pData['ThuTuPhan'],
                'SoCauHoi' => 1,
                'ThoiLuong' => '1m',
                'GioiHanPhat' => '3 lần',
                'MoTaPhan' => 'Phần chơi game mở mảnh ghép',
                'TrangThaiPhan' => 'KichHoat'
            ]);
        }

        // 4. Create 4 Questions
        $questionsData = [
            [
                'MaCauHoi' => 'CH081',
                'NDCauHoi' => 'What color is the sky on a clear day?',
                'MaPhan' => 'PH081',
                'options' => [
                    ['MaPA' => 'PA0811', 'NDPA' => 'A. Blue', 'DapAn' => 'Dung'],
                    ['MaPA' => 'PA0812', 'NDPA' => 'B. Red', 'DapAn' => 'Sai'],
                    ['MaPA' => 'PA0813', 'NDPA' => 'C. Green', 'DapAn' => 'Sai'],
                    ['MaPA' => 'PA0814', 'NDPA' => 'D. Yellow', 'DapAn' => 'Sai'],
                ]
            ],
            [
                'MaCauHoi' => 'CH082',
                'NDCauHoi' => 'How many legs does a typical dog have?',
                'MaPhan' => 'PH082',
                'options' => [
                    ['MaPA' => 'PA0821', 'NDPA' => 'A. Two', 'DapAn' => 'Sai'],
                    ['MaPA' => 'PA0822', 'NDPA' => 'B. Four', 'DapAn' => 'Dung'],
                    ['MaPA' => 'PA0823', 'NDPA' => 'C. Six', 'DapAn' => 'Sai'],
                    ['MaPA' => 'PA0824', 'NDPA' => 'D. Eight', 'DapAn' => 'Sai'],
                ]
            ],
            [
                'MaCauHoi' => 'CH083',
                'NDCauHoi' => 'Which of the following is a fruit?',
                'MaPhan' => 'PH083',
                'options' => [
                    ['MaPA' => 'PA0831', 'NDPA' => 'A. Apple', 'DapAn' => 'Dung'],
                    ['MaPA' => 'PA0832', 'NDPA' => 'B. Carrot', 'DapAn' => 'Sai'],
                    ['MaPA' => 'PA0833', 'NDPA' => 'C. Potato', 'DapAn' => 'Sai'],
                    ['MaPA' => 'PA0834', 'NDPA' => 'D. Broccoli', 'DapAn' => 'Sai'],
                ]
            ],
            [
                'MaCauHoi' => 'CH084',
                'NDCauHoi' => 'Which planet is known as the Red Planet?',
                'MaPhan' => 'PH084',
                'options' => [
                    ['MaPA' => 'PA0841', 'NDPA' => 'A. Earth', 'DapAn' => 'Sai'],
                    ['MaPA' => 'PA0842', 'NDPA' => 'B. Mars', 'DapAn' => 'Dung'],
                    ['MaPA' => 'PA0843', 'NDPA' => 'C. Venus', 'DapAn' => 'Sai'],
                    ['MaPA' => 'PA0844', 'NDPA' => 'D. Jupiter', 'DapAn' => 'Sai'],
                ]
            ],
        ];

        foreach ($questionsData as $qData) {
            // Create question
            Cauhoi::create([
                'MaCauHoi' => $qData['MaCauHoi'],
                'MaLoai' => 'L01', // Standard multiple choice (L01)
                'NDCauHoi' => $qData['NDCauHoi'],
                'TrangThaiCauHoi' => 'KichHoat'
            ]);

            // Associate with part via pivot table
            DB::table('phan_cauhoi')->insert([
                'MaPhan' => $qData['MaPhan'],
                'MaCauHoi' => $qData['MaCauHoi']
            ]);

            // Create options
            foreach ($qData['options'] as $opt) {
                Phuongancauhoi::create([
                    'MaPA' => $opt['MaPA'],
                    'MaCauHoi' => $qData['MaCauHoi'],
                    'NDPA' => $opt['NDPA'],
                    'DapAn' => $opt['DapAn'],
                    'Diem' => $opt['DapAn'] === 'Dung' ? 10 : 0
                ]);
            }
        }
    }
}
