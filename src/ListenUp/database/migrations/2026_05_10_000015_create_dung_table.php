<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dung', function (Blueprint $table) {
            $table->string('MaBai', 10);
            $table->string('MaTep', 10);

            $table->primary(['MaBai', 'MaTep']);
            $table->foreign('MaBai')->references('MaBai')->on('baitest');
            $table->foreign('MaTep')->references('MaTep')->on('tepamthanh');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dung');
    }
};
