<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('phuongancauhoi', function (Blueprint $table) {
            $table->string('MaPA', 10)->primary();
            $table->string('MaCauHoi', 10);
            $table->string('NDPA', 2000)->nullable();
            $table->string('DapAn', 100)->nullable();
            $table->integer('Diem')->nullable();

            $table->foreign('MaCauHoi')->references('MaCauHoi')->on('cauhoi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('phuongancauhoi');
    }
};
