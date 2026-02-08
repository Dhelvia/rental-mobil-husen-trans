<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('penyewas', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('no_ktp')->nullable();
            $table->string('merk_motor')->nullable();
            $table->string('plat_nomor')->nullable(); // plat motor jaminan
            $table->string('no_hp');
            $table->text('alamat')->nullable();
            $table->enum('keterangan', ['ruwet', 'lancar'])->default('lancar');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penyewas');
    }
};
