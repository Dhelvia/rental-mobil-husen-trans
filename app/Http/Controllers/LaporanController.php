<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Laporan;
use App\Models\Pengeluaran;

class LaporanController extends Controller
{
    public function index()
    {
        // biar $laporans kepake di blade kamu
        $laporans = Laporan::with('pengeluarans')->latest()->get();

        //akses list pengeluaran langsun
        $pengeluarans = Pengeluaran::with('laporan')->latest()->get();

        $totalPemasukan = (int) Laporan::sum('total_pemasukan');
        $totalPengeluaran = (int) Pengeluaran::sum('total_pengeluaran');
        $penghasilanAkhir = $totalPemasukan - $totalPengeluaran;

        return view('laporan.index', compact(
            'laporans', 'pengeluarans', 'totalPemasukan', 'totalPengeluaran', 'penghasilanAkhir'
        ));
    }

    // =========================
    // UPDATE PEMASUKAN (LAPORAN)
    // route: laporan.update
    // =========================
    public function update(Request $request, Laporan $laporan)
    {
        $request->validate([
            'nama_customer'    => 'required|string|max:255',
            'tanggal_ambil'    => 'nullable|date',
            'durasi_sewa'      => 'nullable|string|max:100',
            'total_pemasukan'  => 'required|numeric|min:0',
        ], [
            'nama_customer.required' => 'Nama customer wajib diisi',
            'total_pemasukan.required' => 'Total pemasukan wajib diisi',
        ]);

        $laporan->nama_customer = $request->nama_customer;
        $laporan->tanggal_ambil = $request->tanggal_ambil;
        $laporan->durasi_sewa = $request->durasi_sewa;
        $laporan->total_pemasukan = $request->total_pemasukan;
        $laporan->save();

        return back()->with('sukses', 'Pemasukan berhasil diperbarui');
    }
    // HAPUS PEMASUKAN (LAPORAN)
    // route: laporan.hapus
    public function hapus(Laporan $laporan)
    {
        // hapus dulu semua pengeluaran milik laporan ini (biar aman kalau ada FK)
        Pengeluaran::where('laporan_id', $laporan->id)->delete();

        $laporan->delete();

        return back()->with('sukses', 'Laporan berhasil dihapus');
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

    // EDIT PENGELUARAN
    public function updatePengeluaran(Request $request, Pengeluaran $pengeluaran)
    {
        $request->validate([
            'jenis_pengeluaran' => 'required',
            'tanggal' => 'required|date',
            'total_pengeluaran' => 'required|numeric',
        ]);

        // simpan tanggal manual ke created_at biar tampilnya berubah di tabel.
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
