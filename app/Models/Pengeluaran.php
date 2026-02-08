<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    protected $table = 'pengeluarans';

    protected $fillable = [
  'laporan_id','jenis_pengeluaran','tanggal','total_pengeluaran'
];


    public function laporan()
    {
        return $this->belongsTo(Laporan::class);
    }
}


