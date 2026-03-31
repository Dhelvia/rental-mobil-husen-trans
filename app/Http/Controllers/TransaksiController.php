<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Penyewa;
use App\Models\Laporan;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksis = Transaksi::with('mobil')->latest()->get();
        return view('transaksi.index', compact('transaksis'));
    }

    public function ubahStatus(\Illuminate\Http\Request $request, \App\Models\Transaksi $transaksi)
{
    $request->validate([
        'status' => 'required|in:booking,diambil,selesai',
    ]);

    $statusBaru = $request->status;

    $transaksi->status = $statusBaru;
    $transaksi->save();

    // kalau selesai: masukkan ke penyewa + laporan, mobil kembali tersedia
    if ($statusBaru === 'selesai') {

        Penyewa::create([
            'nama' => $transaksi->nama_customer,
            'no_ktp' => $transaksi->no_ktp,
            'merk_motor' => $transaksi->merk_motor,
            'plat_nomor' => $transaksi->plat_motor_jaminan,
            'no_hp' => $transaksi->no_hp_customer,
            'alamat' => $transaksi->alamat,
            'keterangan' => 'lancar',
        ]);

        Laporan::create([
            'nama_customer' => $transaksi->nama_customer,
            'tanggal_ambil' => $transaksi->tanggal_ambil ?? now()->toDateString(),
            'durasi_sewa' => $transaksi->durasi_sewa ?? $transaksi->lama_sewa,
            'total_pemasukan' => $transaksi->biaya_sewa,
        ]);

        // mobil kembali tersedia
        $transaksi->mobil->update(['tersedia' => true]);
    }

    return back()->with('sukses', 'Status transaksi diperbarui');
}


    public function hapus(Transaksi $transaksi)
    {
        // jika transaksi dihapus, mobil dibuat tersedia lagi
        $transaksi->mobil->update(['tersedia' => true]);
        $transaksi->delete();
        return back()->with('sukses', 'Transaksi berhasil dihapus');
    }

    // update dari popup (jaminan, durasi, jam, tanggal ambil, harga)
    public function updateModal(Request $request, Transaksi $transaksi)
    {
        $request->validate([
            'no_ktp' => 'nullable',
            'alamat' => 'nullable',
            'plat_motor_jaminan' => 'nullable',
            'merk_motor' => 'nullable',
            'tujuan' => 'nullable',
            'durasi_sewa' => 'nullable',
            'jam_ambil' => 'nullable',
            'tanggal_ambil' => 'nullable|date',
            'biaya_sewa' => 'required|numeric',
        ]);

        $transaksi->update([
            'no_ktp' => $request->no_ktp,
            'alamat' => $request->alamat,
            'plat_motor_jaminan' => $request->plat_motor_jaminan,
            'merk_motor' => $request->merk_motor,
            'tujuan' => $request->tujuan,
            'durasi_sewa' => $request->durasi_sewa,
            'jam_ambil' => $request->jam_ambil,
            'tanggal_ambil' => $request->tanggal_ambil,
            'biaya_sewa' => $request->biaya_sewa,
        ]);

        return back()->with('sukses', 'Data transaksi berhasil diperbarui');
    }
}
