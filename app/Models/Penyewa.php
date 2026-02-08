<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penyewa extends Model
{
    protected $table = 'penyewas';

    protected $fillable = [
        'nama','no_ktp','merk_motor','plat_nomor','no_hp','alamat','keterangan'
    ];
}
