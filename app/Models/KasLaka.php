<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KasLaka extends Model
{
    protected $table = 'kas_lakas';

    protected $fillable = [
        'transaksi_id',
        'tanggal',
        'jenis',
        'kategori',
        'keterangan',
        'nominal',
        'foto',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'nominal' => 'decimal:2',
        'foto' => 'array', 
    ];

    public function transaksi()
    {
        // pastikan model transaksi kamu bernama Transaksi
        return $this->belongsTo(Transaksi::class, 'transaksi_id');
    }
}
