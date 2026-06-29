<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Tạo admin mặc định
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@englishlistening.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'level' => 'advanced',
            'status' => 'active',
        ]);

        // Tạo user mẫu
        User::create([
            'name' => 'John Doe',
            'email' => 'user@englishlistening.com',
            'password' => Hash::make('user123'),
            'role' => 'user',
            'level' => 'beginner',
            'phone' => '0123456789',
            'status' => 'active',
        ]);
    }
}