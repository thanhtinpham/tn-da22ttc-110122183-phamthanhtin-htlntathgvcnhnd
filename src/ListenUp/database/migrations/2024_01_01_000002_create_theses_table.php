<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('audio_file');
            $table->text('transcript');
            $table->enum('level', ['beginner', 'intermediate', 'advanced']);
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->integer('duration'); // seconds
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};