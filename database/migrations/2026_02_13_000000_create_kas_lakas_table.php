<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kas_lakas', function (Blueprint $table) {
            $table->id();

            // Optional: terkait transaksi tertentu
            $table->unsignedBigInteger('transaksi_id')->nullable();

            $table->date('tanggal');
            $table->enum('jenis', ['pemasukan', 'pengeluaran']);
            $table->string('kategori', 100);
            $table->text('keterangan')->nullable();
            $table->decimal('nominal', 15, 2)->default(0);

            // ✅ Multi foto (array path), contoh: ["kas-laka/xxx.jpg","kas-laka/yyy.png"]
            $table->json('foto')->nullable();

            $table->timestamps();

            $table->foreign('transaksi_id')
                ->references('id')->on('transaksis')
                ->nullOnDelete();

            $table->index('transaksi_id');
            $table->index('tanggal');
            $table->index('jenis');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kas_lakas');
    }
};
