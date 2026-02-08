<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('mobils', function (Blueprint $table) {
            $table->id();
            $table->string('nama_mobil');     // BRIO, INNOVA, AVANZA
            $table->string('plat');
            $table->string('warna')->nullable();
            $table->string('transmisi')->default('Manual'); // Manual/Automatic
            $table->string('gambar')->nullable(); // path gambar
            $table->unsignedBigInteger('harga_6_jam')->default(0);
            $table->unsignedBigInteger('harga_12_jam')->default(0);
            $table->unsignedBigInteger('harga_24_jam')->default(0);
            $table->unsignedBigInteger('harga_per_hari')->default(0);
            $table->boolean('tersedia')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mobils');
    }
};
