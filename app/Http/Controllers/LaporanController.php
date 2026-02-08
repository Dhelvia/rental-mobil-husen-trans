<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Laporan;
use App\Models\Pengeluaran;

class LaporanController extends Controller
{
    public function index()
    {
        // WAJIB ADA ini, biar $laporans kepake di blade kamu
        $laporans = Laporan::with('pengeluarans')->latest()->get();

        // opsional (kalau kamu mau akses list pengeluaran langsung)
        $pengeluarans = Pengeluaran::with('laporan')->latest()->get();

        $totalPemasukan = (int) Laporan::sum('total_pemasukan');
        $totalPengeluaran = (int) Pengeluaran::sum('total_pengeluaran');
        $penghasilanAkhir = $totalPemasukan - $totalPengeluaran;

        return view('laporan.index', compact(
            'laporans', 'pengeluarans', 'totalPemasukan', 'totalPengeluaran', 'penghasilanAkhir'
        ));
    }

    public function tambahPengeluaran(Request $request, Laporan $laporan)
    {
        $request->validate([
            'jenis_pengeluaran' => 'required',
            'total_pengeluaran' => 'required|numeric',
        ], [
            'jenis_pengeluaran.required' => 'Jenis pengeluaran wajib diisi',
        ]);

        Pengeluaran::create([
            'laporan_id' => $laporan->id,
            'jenis_pengeluaran' => $request->jenis_pengeluaran,
            'total_pengeluaran' => $request->total_pengeluaran,
        ]);

        return back()->with('sukses', 'Pengeluaran berhasil ditambahkan');
    }

    // =========
    // Catatan: route kamu sudah ada laporan.update & laporan.hapus,
    // tapi kamu belum kirim function update() & hapus() untuk Laporan.
    // Aku gak nambah supaya gak ganggu yang lain.
    // =========

    // EDIT PENGELUARAN
    public function updatePengeluaran(Request $request, Pengeluaran $pengeluaran)
    {
        $request->validate([
            'jenis_pengeluaran' => 'required',
            'tanggal' => 'required|date',
            'total_pengeluaran' => 'required|numeric',
        ]);

        // Karena tabel pengeluaran kamu kemungkinan TIDAK punya kolom "tanggal",
        // kita simpan tanggal manual ke created_at biar tampilnya berubah di tabel.
        $pengeluaran->jenis_pengeluaran = $request->jenis_pengeluaran;
        $pengeluaran->total_pengeluaran = $request->total_pengeluaran;
        $pengeluaran->created_at = $request->tanggal . ' 00:00:00';
        $pengeluaran->save();

        return back()->with('sukses', 'Pengeluaran berhasil diperbarui');
    }

    public function hapusPengeluaran(Pengeluaran $pengeluaran)
    {
        $pengeluaran->delete();
        return back()->with('sukses', 'Pengeluaran berhasil dihapus');
    }
}
