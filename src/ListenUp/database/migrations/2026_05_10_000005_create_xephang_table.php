<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('xephang', function (Blueprint $table) {
            $table->string('MaXH', 10)->primary();
            $table->string('TenXH', 20)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('xephang');
    }
};
