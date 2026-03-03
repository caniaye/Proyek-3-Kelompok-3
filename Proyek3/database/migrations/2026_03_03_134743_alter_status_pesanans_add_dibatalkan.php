<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // MySQL enum harus diubah via raw SQL
        DB::statement("ALTER TABLE pesanans MODIFY status ENUM('belum_dikirim','proses','berhasil','dibatalkan') NOT NULL DEFAULT 'belum_dikirim'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE pesanans MODIFY status ENUM('belum_dikirim','proses','berhasil') NOT NULL DEFAULT 'belum_dikirim'");
    }
};