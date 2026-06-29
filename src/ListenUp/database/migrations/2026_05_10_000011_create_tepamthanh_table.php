<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tepamthanh', function (Blueprint $table) {
            $table->string('MaTep', 10)->primary();
            $table->string('DuongDan', 100)->nullable();
            $table->string('TGTep', 50)->nullable();
            $table->string('GioiHanPhat', 20)->nullable();
            $table->char('TenTep', 10)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tepamthanh');
    }
};
