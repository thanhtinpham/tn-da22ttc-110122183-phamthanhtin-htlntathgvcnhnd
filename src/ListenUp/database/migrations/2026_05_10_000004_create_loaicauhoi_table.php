<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loaicauhoi', function (Blueprint $table) {
            $table->string('MaLoai', 10)->primary();
            $table->string('TenLoai', 20)->nullable();
            $table->string('MoTaLoai', 50)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loaicauhoi');
    }
};
