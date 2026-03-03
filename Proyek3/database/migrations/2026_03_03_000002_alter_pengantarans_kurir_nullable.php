<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pengantarans', function (Blueprint $table) {
            // kurir_id awalnya wajib -> jadi nullable dulu
            $table->foreignId('kurir_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('pengantarans', function (Blueprint $table) {
            $table->foreignId('kurir_id')->nullable(false)->change();
        });
    }
};