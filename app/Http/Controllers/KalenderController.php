<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;

class KalenderController extends Controller
{
    public function index()
    {
        $transaksis = Transaksi::with('mobil')
    ->whereIn('status', ['booking', 'diambil'])
    ->orderBy('tanggal_ambil')
    ->orderBy('jam_ambil')
    ->get();

        $events = [];

        foreach ($transaksis as $t) {

            if ($t->tanggal_ambil) {

                $events[] = [

                    'title' => $t->nama_customer,

                    'start' => $t->tanggal_ambil,

                    'backgroundColor' => $t->status == 'booking'
                        ? '#2f6dff'
                        : '#ff9800',

                    'borderColor' => $t->status == 'booking'
                        ? '#2f6dff'
                        : '#ff9800',

                    'extendedProps' => [

                        'nama' => $t->nama_customer,

                        'tanggal' => $t->tanggal_ambil,

                        'jam' => $t->jam_ambil ?? '-',

                        'durasi' => $t->durasi_sewa ?? '-',

                        'plat' => $t->mobil->plat ?? '-',

                        'mobil' => $t->mobil->nama_mobil ?? '-',
                    ]
                ];
            }
        }

        return view('kalender.index', compact('events'));
    }
}