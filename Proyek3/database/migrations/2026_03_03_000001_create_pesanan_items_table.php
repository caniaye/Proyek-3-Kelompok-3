<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pesanan_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanans')->cascadeOnDelete();
            $table->enum('jenis_tabung', ['3kg', '12kg']);
            $table->unsignedInteger('qty')->default(0);
            $table->timestamps();

            $table->unique(['pesanan_id', 'jenis_tabung']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan_items');
    }
};