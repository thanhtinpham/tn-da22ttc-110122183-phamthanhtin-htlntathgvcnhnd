<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user', function (Blueprint $table) {
            $table->string('preferred_accent', 10)->default('en-US')->nullable();
            $table->float('preferred_speed')->default(1.0)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('user', function (Blueprint $table) {
            $table->dropColumn(['preferred_accent', 'preferred_speed']);
        });
    }
};
