<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tientrinh', function (Blueprint $table) {
            $table->string('MaBanDo', 10);
            $table->string('UserID', 10);
            $table->string('MaXH', 10);
            $table->string('ViTri', 20)->nullable();
            $table->string('KetQuaMan', 20)->nullable();

            $table->primary(['MaBanDo', 'UserID', 'MaXH']);
            $table->foreign('MaBanDo')->references('MaBanDo')->on('bandophieuluu');
            $table->foreign('UserID')->references('UserID')->on('user');
            $table->foreign('MaXH')->references('MaXH')->on('xephang');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tientrinh');
    }
};
