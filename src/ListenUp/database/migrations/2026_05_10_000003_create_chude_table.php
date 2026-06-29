<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chude', function (Blueprint $table) {
            $table->string('MaCD', 10)->primary();
            $table->string('TenCD', 20)->nullable();
            $table->string('MoTa', 100)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chude');
    }
};
