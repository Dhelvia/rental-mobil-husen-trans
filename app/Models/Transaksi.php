<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksis';

    protected $fillable = [
        'mobil_id',
        'nama_customer','no_hp_customer',
        'no_ktp','alamat','plat_motor_jaminan','merk_motor',
        'lama_sewa','tanggal_booking','tanggal_ambil','jam_ambil',
        'durasi_sewa','biaya_sewa',
        'keterangan',
        'tujuan',      
        'status'
    ];

    public function mobil()
    {
        return $this->belongsTo(Mobil::class);
    }
}
