<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cauhoi', function (Blueprint $table) {
            $table->string('MaCauHoi', 10)->primary();
            $table->string('MaLoai', 10);
            $table->string('NDCauHoi', 2000)->nullable();
            $table->string('TrangThaiCauHoi', 20)->nullable();

            $table->foreign('MaLoai')->references('MaLoai')->on('loaicauhoi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cauhoi');
    }
};
