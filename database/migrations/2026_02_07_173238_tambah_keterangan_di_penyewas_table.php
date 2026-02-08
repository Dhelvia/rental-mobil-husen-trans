<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('penyewas', function (Blueprint $table) {
            if (!Schema::hasColumn('penyewas', 'keterangan')) {
                $table->enum('keterangan', ['ruwet', 'lancar', 'suka bon'])->default('lancar')->after('alamat');
            }
        });
    }

    public function down(): void
    {
        Schema::table('penyewas', function (Blueprint $table) {
            if (Schema::hasColumn('penyewas', 'keterangan')) {
                $table->dropColumn('keterangan');
            }
        });
    }
};
