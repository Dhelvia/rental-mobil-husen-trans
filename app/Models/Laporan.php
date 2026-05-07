<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    protected $table = 'laporans';

    protected $fillable = [
        'transaksi_id', // 
        'nama_customer',
        'tanggal_ambil',
        'durasi_sewa',
        'total_pemasukan'
    ];

    public function pengeluarans()
    {
        return $this->hasMany(Pengeluaran::class);
    }

    public function getTotalPengeluaranAttribute()
    {
        return (int) $this->pengeluarans()->sum('total_pengeluaran');
    }

    public function getPenghasilanAkhirAttribute()
    {
        return (int) $this->total_pemasukan - (int) $this->total_pengeluaran;
    }
}