<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('chitietlambai', function (Blueprint $table) {
            $table->string('MaBai', 10)->nullable();
            $table->integer('ThoiGianLam')->nullable()->comment('Thời gian làm bài tính bằng giây');
            $table->integer('SoCauDung')->nullable();
            $table->integer('TongSoCau')->nullable();
            $table->timestamp('CreatedAt')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::table('chitietlambai', function (Blueprint $table) {
            $table->dropColumn(['MaBai', 'ThoiGianLam', 'SoCauDung', 'TongSoCau', 'CreatedAt']);
        });
    }
};
