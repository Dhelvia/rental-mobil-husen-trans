<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Laporan;
use App\Models\Pengeluaran;
use App\Models\Transaksi;

class LaporanController extends Controller
{
    public function index()
    {
        // 🔥 AUTO SYNC DATA LAMA
        $transaksiSelesai = Transaksi::where('status', 'selesai')->get();

        foreach ($transaksiSelesai as $t) {
            $sudahAda = Laporan::where('transaksi_id', $t->id)->exists();

            if (!$sudahAda) {
                Laporan::create([
                    'transaksi_id' => $t->id,
                    'nama_customer' => $t->nama_customer,
                    'tanggal_ambil' => $t->tanggal_ambil ?? now()->toDateString(),
                    'durasi_sewa' => $t->durasi_sewa ?? $t->lama_sewa,
                    'total_pemasukan' => $t->biaya_sewa,
                ]);
            }
        }

        $laporans = Laporan::with('pengeluarans')->latest()->get();
        $pengeluarans = Pengeluaran::with('laporan')->latest()->get();

        $totalPemasukan = (int) Laporan::sum('total_pemasukan');
        $totalPengeluaran = (int) Pengeluaran::sum('total_pengeluaran');
        $penghasilanAkhir = $totalPemasukan - $totalPengeluaran;

        return view('laporan.index', compact(
            'laporans', 'pengeluarans', 'totalPemasukan', 'totalPengeluaran', 'penghasilanAkhir'
        ));
    }

    public function update(Request $request, Laporan $laporan)
    {
        $request->validate([
            'nama_customer' => 'required|string|max:255',
            'tanggal_ambil' => 'nullable|date',
            'durasi_sewa' => 'nullable|string|max:100',
            'total_pemasukan' => 'required|numeric|min:0',
        ]);

        $laporan->update($request->all());

        return back()->with('sukses', 'Pemasukan berhasil diperbarui');
    }

    public function hapus(Laporan $laporan)
    {
        Pengeluaran::where('laporan_id', $laporan->id)->delete();
        $laporan->delete();

        return back()->with('sukses', 'Laporan berhasil dihapus');
    }

    public function tambahPengeluaran(Request $request, Laporan $laporan)
    {
        $request->validate([
            'jenis_pengeluaran' => 'required',
            'total_pengeluaran' => 'required|numeric',
        ]);

        Pengeluaran::create([
            'laporan_id' => $laporan->id,
            'jenis_pengeluaran' => $request->jenis_pengeluaran,
            'total_pengeluaran' => $request->total_pengeluaran,
        ]);

        return back()->with('sukses', 'Pengeluaran berhasil ditambahkan');
    }

    public function updatePengeluaran(Request $request, Pengeluaran $pengeluaran)
    {
        $request->validate([
            'jenis_pengeluaran' => 'required',
            'tanggal' => 'required|date',
            'total_pengeluaran' => 'required|numeric',
        ]);

        $pengeluaran->update([
            'jenis_pengeluaran' => $request->jenis_pengeluaran,
            'total_pengeluaran' => $request->total_pengeluaran,
            'created_at' => $request->tanggal . ' 00:00:00',
        ]);

        return back()->with('sukses', 'Pengeluaran berhasil diperbarui');
    }

    public function hapusPengeluaran(Pengeluaran $pengeluaran)
    {
        $pengeluaran->delete();
        return back()->with('sukses', 'Pengeluaran berhasil dihapus');
    }
}