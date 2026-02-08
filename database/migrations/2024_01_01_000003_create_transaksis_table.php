<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mobil_id')->constrained('mobils')->cascadeOnDelete();

            // Customer
            $table->string('nama_customer');
            $table->string('no_hp_customer');

            // Jaminan
            $table->string('no_ktp')->nullable();
            $table->text('alamat')->nullable();
            $table->string('plat_motor_jaminan')->nullable();
            $table->string('merk_motor')->nullable();

            // Sewa
            $table->string('lama_sewa')->nullable(); // bebas (mis: 1 hari / 12 jam)
            $table->date('tanggal_booking')->nullable();
            $table->date('tanggal_ambil')->nullable();
            $table->time('jam_ambil')->nullable();
            $table->string('durasi_sewa')->nullable(); // bebas
            $table->unsignedBigInteger('biaya_sewa')->default(0);

            // Keterangan
            $table->enum('keterangan', ['antar rental', 'pribadi'])->default('pribadi');

            // Status
            $table->enum('status', ['booking', 'diambil', 'selesai'])->default('booking');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
