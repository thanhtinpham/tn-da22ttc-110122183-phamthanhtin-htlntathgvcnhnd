<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user', function (Blueprint $table) {
            $table->string('learning_goal', 50)->nullable();
            $table->string('current_level', 20)->nullable();
            $table->integer('daily_target_time')->nullable(); // in minutes
        });
    }

    public function down(): void
    {
        Schema::table('user', function (Blueprint $table) {
            $table->dropColumn(['learning_goal', 'current_level', 'daily_target_time']);
        });
    }
};
