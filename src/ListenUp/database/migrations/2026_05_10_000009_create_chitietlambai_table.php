<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chitietlambai', function (Blueprint $table) {
            $table->string('MaCTLB', 10)->primary();
            $table->string('UserID', 10);
            $table->integer('SoLanLam')->nullable();

            $table->foreign('UserID')->references('UserID')->on('user');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chitietlambai');
    }
};
