<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mobil extends Model
{
    protected $table = 'mobils';

    protected $fillable = [
        'nama_mobil','plat','warna','transmisi','gambar',
        'harga_6_jam','harga_12_jam','harga_24_jam','harga_per_hari',
        'tersedia'
    ];

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }
}
