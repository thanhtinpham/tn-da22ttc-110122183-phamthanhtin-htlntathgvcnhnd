<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ket qua', function (Blueprint $table) {
            $table->string('MaCTLB', 10);
            $table->string('MaCauHoi', 10);
            $table->string('KetQuaChon', 200)->nullable();

            $table->primary(['MaCTLB', 'MaCauHoi']);
            $table->foreign('MaCTLB')->references('MaCTLB')->on('chitietlambai');
            $table->foreign('MaCauHoi')->references('MaCauHoi')->on('cauhoi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ket qua');
    }
};
