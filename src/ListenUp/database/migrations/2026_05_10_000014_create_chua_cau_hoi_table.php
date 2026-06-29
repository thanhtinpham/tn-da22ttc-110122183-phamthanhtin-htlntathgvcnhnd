<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chua cau hoi', function (Blueprint $table) {
            $table->string('MaBai', 10);
            $table->string('MaCauHoi', 10);

            $table->primary(['MaBai', 'MaCauHoi']);
            $table->foreign('MaBai')->references('MaBai')->on('baitest');
            $table->foreign('MaCauHoi')->references('MaCauHoi')->on('cauhoi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chua cau hoi');
    }
};
