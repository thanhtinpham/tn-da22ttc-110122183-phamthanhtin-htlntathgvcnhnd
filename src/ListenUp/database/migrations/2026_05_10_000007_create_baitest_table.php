<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('baitest', function (Blueprint $table) {
            $table->string('MaBai', 10)->primary();
            $table->string('MaBanDo', 10);
            $table->string('MaCD', 10);
            $table->string('MaCDN', 10);
            $table->string('TenBai', 20)->nullable();
            $table->integer('SoCauHoi')->nullable();
            $table->string('TrangThaiBai', 20)->nullable();
            $table->string('MoTa', 100)->nullable();

            $table->foreign('MaBanDo')->references('MaBanDo')->on('bandophieuluu');
            $table->foreign('MaCD')->references('MaCD')->on('chude');
            $table->foreign('MaCDN')->references('MaCDN')->on('capdonghe');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('baitest');
    }
};
