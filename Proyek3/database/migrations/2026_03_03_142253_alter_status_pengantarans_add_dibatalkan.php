<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("ALTER TABLE pengantarans MODIFY status ENUM('belum_dikirim','dalam_perjalanan','berhasil','dibatalkan') NOT NULL DEFAULT 'belum_dikirim'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE pengantarans MODIFY status ENUM('belum_dikirim','dalam_perjalanan','berhasil') NOT NULL DEFAULT 'belum_dikirim'");
    }
};