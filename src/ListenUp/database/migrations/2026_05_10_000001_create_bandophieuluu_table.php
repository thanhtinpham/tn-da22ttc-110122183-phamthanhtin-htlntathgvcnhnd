<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bandophieuluu', function (Blueprint $table) {
            $table->string('MaBanDo', 10)->primary();
            $table->string('TenBanDo', 20)->nullable();
            $table->string('YeuCauBanDo', 20)->nullable();
            $table->string('TrangThaiBanDo', 20)->nullable();
            $table->string('HinhAnh', 200)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bandophieuluu');
    }
};
