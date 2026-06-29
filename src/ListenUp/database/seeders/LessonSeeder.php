<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lesson;
use App\Models\Category;

class LessonSeeder extends Seeder
{
    public function run(): void
    {
        $dailyConversation = Category::where('name', 'Daily Conversation')->first();
        $businessEnglish = Category::where('name', 'Business English')->first();

        $lessons = [
            [
                'title' => 'Greeting and Introduction',
                'description' => 'Learn basic greetings and how to introduce yourself',
                'audio_file' => 'lessons/greeting_intro.mp3',
                'transcript' => 'Hello, my name is Sarah. Nice to meet you. How are you today?',
                'level' => 'beginner',
                'category_id' => $dailyConversation->id,
                'duration' => 120,
            ],
            [
                'title' => 'Business Meeting',
                'description' => 'Common phrases used in business meetings',
                'audio_file' => 'lessons/business_meeting.mp3',
                'transcript' => 'Good morning everyone. Let\'s start today\'s meeting. First item on the agenda...',
                'level' => 'intermediate',
                'category_id' => $businessEnglish->id,
                'duration' => 180,
            ],
        ];

        foreach ($lessons as $lesson) {
            Lesson::create($lesson);
        }
    }
}