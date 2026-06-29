<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Daily Conversation', 'description' => 'Hội thoại hàng ngày', 'icon' => 'fas fa-comments'],
            ['name' => 'Business English', 'description' => 'Tiếng Anh thương mại', 'icon' => 'fas fa-briefcase'],
            ['name' => 'Travel English', 'description' => 'Tiếng Anh du lịch', 'icon' => 'fas fa-plane'],
            ['name' => 'Academic English', 'description' => 'Tiếng Anh học thuật', 'icon' => 'fas fa-graduation-cap'],
            ['name' => 'News & Media', 'description' => 'Tin tức và truyền thông', 'icon' => 'fas fa-newspaper'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}