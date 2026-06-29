<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('choi', function (Blueprint $table) {
            $table->string('MaBanDo', 10);
            $table->string('MaCTLB', 10);

            $table->primary(['MaBanDo', 'MaCTLB']);
            $table->foreign('MaBanDo')->references('MaBanDo')->on('bandophieuluu');
            $table->foreign('MaCTLB')->references('MaCTLB')->on('chitietlambai');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('choi');
    }
};
