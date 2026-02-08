<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mobil;
use App\Models\Transaksi;

class BookingController extends Controller
{
    public function create(Mobil $mobil)
    {
        return view('booking.create', compact('mobil'));
    }

    public function store(Request $request, Mobil $mobil)
    {
        $request->validate([
            'nama_customer' => 'required',
            'no_hp_customer' => 'required',
            'tanggal_booking' => 'required|date',
            'biaya_sewa' => 'required|numeric',
            'keterangan' => 'required|in:antar rental,pribadi',
        ], [
            'nama_customer.required' => 'Nama customer wajib diisi',
            'no_hp_customer.required' => 'No HP customer wajib diisi',
            'tanggal_booking.required' => 'Tanggal booking wajib diisi',
        ]);

        Transaksi::create([
            'mobil_id' => $mobil->id,

            'nama_customer' => $request->nama_customer,
            'no_hp_customer' => $request->no_hp_customer,

            'no_ktp' => $request->no_ktp,
            'alamat' => $request->alamat,
            'plat_motor_jaminan' => $request->plat_motor_jaminan,
            'merk_motor' => $request->merk_motor,

            'jam_ambil' => $request->jam_ambil,
            'tanggal_booking' => $request->tanggal_booking,
            'tanggal_ambil' => $request->tanggal_ambil,
            'durasi_sewa' => $request->durasi_sewa,

            'biaya_sewa' => $request->biaya_sewa,
            'keterangan' => $request->keterangan,
            'status' => 'booking',
        ]);

        // saat booking, mobil dianggap tidak tersedia
        $mobil->update(['tersedia' => false]);

        return redirect()->route('transaksi.index')->with('sukses', 'Booking berhasil disimpan dan masuk transaksi');
    }
}

