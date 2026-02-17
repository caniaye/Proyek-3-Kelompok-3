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
        Schema::create('pengantarans', function (Blueprint $table) {
            $table->id();
            $table->string('resi', 20)->unique(); // GCV001

            $table->foreignId('pesanan_id')->constrained('pesanans')->cascadeOnDelete();
            $table->foreignId('kurir_id')->constrained('kurirs')->cascadeOnDelete();

            $table->enum('status', ['belum_dikirim', 'dalam_perjalanan', 'berhasil'])->default('belum_dikirim');
            $table->timestamp('waktu_verifikasi')->nullable();

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengantarans');
    }
};
