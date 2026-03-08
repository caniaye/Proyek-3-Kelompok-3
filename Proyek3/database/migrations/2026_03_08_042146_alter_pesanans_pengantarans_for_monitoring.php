<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1) kurir_id jadi nullable (kurir dipilih nanti)
        Schema::table('pengantarans', function (Blueprint $table) {
            $table->unsignedBigInteger('kurir_id')->nullable()->change();
        });

        // 2) tambah status dibatalkan di pesanans
        // Karena MySQL enum, paling aman pakai statement raw
        DB::statement("ALTER TABLE pesanans MODIFY status 
            ENUM('belum_dikirim','proses','berhasil','dibatalkan')
            NOT NULL DEFAULT 'belum_dikirim'");

        // 3) (disarankan) tambah dibatalkan di pengantarans.status biar jelas di monitoring
        DB::statement("ALTER TABLE pengantarans MODIFY status 
            ENUM('belum_dikirim','dalam_perjalanan','berhasil','dibatalkan')
            NOT NULL DEFAULT 'belum_dikirim'");
    }

    public function down(): void
    {
        // balikkan sesuai sebelum diubah
        Schema::table('pengantarans', function (Blueprint $table) {
            $table->unsignedBigInteger('kurir_id')->nullable(false)->change();
        });

        DB::statement("ALTER TABLE pesanans MODIFY status 
            ENUM('belum_dikirim','proses','berhasil')
            NOT NULL DEFAULT 'belum_dikirim'");

        DB::statement("ALTER TABLE pengantarans MODIFY status 
            ENUM('belum_dikirim','dalam_perjalanan','berhasil')
            NOT NULL DEFAULT 'belum_dikirim'");
    }
};