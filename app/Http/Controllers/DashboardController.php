<?php

namespace App\Http\Controllers;

use App\Models\Mobil;
use App\Models\Transaksi;
use App\Models\Laporan;
use App\Models\Pengeluaran;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalMobilTersedia = Mobil::where('tersedia', true)->count();
        $totalRiwayatPenyewa = \App\Models\Penyewa::count();
        $transaksiHariIni = Transaksi::whereDate('created_at', now()->toDateString())->count();

        $totalPemasukan = (int) Laporan::sum('total_pemasukan');
        $totalPengeluaran = (int) Pengeluaran::sum('total_pengeluaran');
        $saldo = $totalPemasukan - $totalPengeluaran;

        // Grafik 7 hari terakhir
        $labels = [];
        $dataPemasukan = [];
        $dataPengeluaran = [];

        for ($i = 6; $i >= 0; $i--) {
            $tanggal = Carbon::now()->subDays($i)->toDateString();
            $labels[] = Carbon::parse($tanggal)->format('d M');

            $pemasukanHarian = (int) Laporan::whereDate('created_at', $tanggal)->sum('total_pemasukan');
            $pengeluaranHarian = (int) Pengeluaran::whereDate('created_at', $tanggal)->sum('total_pengeluaran');

            $dataPemasukan[] = $pemasukanHarian;
            $dataPengeluaran[] = $pengeluaranHarian;
        }

        return view('dashboard', compact(
            'totalMobilTersedia',
            'totalRiwayatPenyewa',
            'transaksiHariIni',
            'saldo',
            'labels',
            'dataPemasukan',
            'dataPengeluaran'
        ));
    }
}
