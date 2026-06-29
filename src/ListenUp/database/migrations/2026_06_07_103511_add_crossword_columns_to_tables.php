<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('baitest', function (Blueprint $table) {
            $table->string('TuKhoaHangDoc', 50)->nullable()->after('MoTa');
        });

        Schema::table('cauhoi', function (Blueprint $table) {
            $table->integer('ViTriGiao')->default(0)->after('NDCauHoi');
        });
    }

    public function down(): void
    {
        Schema::table('baitest', function (Blueprint $table) {
            $table->dropColumn('TuKhoaHangDoc');
        });

        Schema::table('cauhoi', function (Blueprint $table) {
            $table->dropColumn('ViTriGiao');
        });
    }
};
