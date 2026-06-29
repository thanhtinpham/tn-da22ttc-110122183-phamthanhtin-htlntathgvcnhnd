<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('capdonghe', function (Blueprint $table) {
            $table->string('MaCDN', 10)->primary();
            $table->string('TenCDN', 20)->nullable();
            $table->string('MoTaCDN', 100)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('capdonghe');
    }
};
