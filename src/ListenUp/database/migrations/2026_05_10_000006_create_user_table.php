<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user', function (Blueprint $table) {
            $table->string('UserID', 10)->primary();
            $table->string('UserName', 30)->nullable();
            $table->string('Email', 70)->nullable();
            $table->string('Password', 255)->nullable();
            $table->string('Role', 10)->nullable();
            $table->string('Status', 20)->nullable();
            $table->datetime('CreatedAt')->nullable();
            $table->datetime('LastLoginAt')->nullable();
            $table->string('AnhDaiDien', 100)->nullable();
            $table->integer('SDT')->nullable();
            $table->string('GioiTinh', 20)->nullable();
            $table->datetime('NgaySinh')->nullable();
            $table->bigInteger('TongDiem')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
