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
        Schema::create('pesanans', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique(); // P001
            $table->foreignId('pelanggan_id')->constrained('pelanggans')->cascadeOnDelete();
            $table->unsignedInteger('jumlah_tabung')->default(1);
            $table->date('tanggal_pesan')->default(now());
            $table->enum('status', ['belum_dikirim', 'proses', 'berhasil'])->default('belum_dikirim');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};
