<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mobil;
use App\Models\Transaksi;
use App\Models\Penyewa;

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

            // ✅ FIX VALIDASI TANGGAL
            'tanggal_booking' => 'required|date|after_or_equal:today',
            'tanggal_ambil' => 'nullable|date|after_or_equal:tanggal_booking',

            'biaya_sewa' => 'required|numeric',
            'keterangan' => 'required|in:antar rental,pribadi',
            'tujuan' => 'nullable|string|max:255',
        ], [
            'nama_customer.required' => 'Nama customer wajib diisi',
            'no_hp_customer.required' => 'No HP customer wajib diisi',
            'tanggal_booking.required' => 'Tanggal booking wajib diisi',
            'tanggal_booking.after_or_equal' => 'Tidak bisa pilih tanggal kemarin!',
            'tanggal_ambil.after_or_equal' => 'Tanggal ambil tidak boleh sebelum tanggal booking!',
        ]);

        $transaksi = Transaksi::create([
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
            'tujuan' => $request->tujuan,
            'status' => 'booking',
        ]);

        Penyewa::firstOrCreate(
            ['no_hp' => $request->no_hp_customer],
            [
                'nama' => $request->nama_customer,
                'no_ktp' => $request->no_ktp,
                'merk_motor' => $request->merk_motor,
                'plat_nomor' => $request->plat_motor_jaminan,
                'alamat' => $request->alamat,
                'keterangan' => 'lancar',
            ]
        );

        return redirect()->route('transaksi.index')->with('sukses', 'Booking berhasil disimpan dan data penyewa langsung masuk');
    }
}