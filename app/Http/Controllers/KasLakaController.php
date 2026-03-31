<?php

namespace App\Http\Controllers;

use App\Models\KasLaka;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KasLakaController extends Controller
{
    public function index(Request $request)
    {
        $query = KasLaka::query()->orderByDesc('tanggal')->orderByDesc('id');

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }
        if ($request->filled('kategori')) {
            $query->where('kategori', 'like', '%' . $request->kategori . '%');
        }
        if ($request->filled('transaksi_id')) {
            $query->where('transaksi_id', $request->transaksi_id);
        }

        $items = $query->paginate(10)->withQueryString();

        $totalPemasukan = KasLaka::where('jenis', 'pemasukan')->sum('nominal');
        $totalPengeluaran = KasLaka::where('jenis', 'pengeluaran')->sum('nominal');
        $saldo = $totalPemasukan - $totalPengeluaran;

        $transaksis = Transaksi::select('id', 'nama_customer', 'tanggal_ambil')
            ->orderByDesc('id')
            ->limit(200)
            ->get();

        return view('kas_laka.index', compact(
            'items',
            'saldo',
            'totalPemasukan',
            'totalPengeluaran',
            'transaksis'
        ));
    }

    private function storePhotos(Request $request): array
    {
        $paths = [];

        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                if (!$file) continue;
                // simpan ke storage/app/public/kas-laka
                $paths[] = $file->store('kas-laka', 'public');
            }
        }

        return $paths;
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jenis' => 'required|in:pemasukan,pengeluaran',
            'kategori' => 'required|string|max:100',
            'keterangan' => 'nullable|string',
            'nominal' => 'required|numeric|min:0',
            'transaksi_id' => 'nullable|integer|exists:transaksis,id',

            // ✅ multi foto: boleh kosong, max 6 file, masing-masing max 3MB
            'foto' => 'nullable|array|max:6',
            'foto.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);

        $transaksiId = $request->input('transaksi_id');
        if ($transaksiId === '' || $transaksiId === '0') $transaksiId = null;

        $fotoPaths = $this->storePhotos($request);

        KasLaka::create([
            'tanggal' => $request->tanggal,
            'jenis' => $request->jenis,
            'kategori' => $request->kategori,
            'keterangan' => $request->keterangan,
            'nominal' => $request->nominal,
            'transaksi_id' => $transaksiId,
            'foto' => count($fotoPaths) ? $fotoPaths : null,
        ]);

        return back()->with('sukses', 'Data Kas Laka berhasil ditambahkan.');
    }

    public function update(Request $request, KasLaka $kasLaka)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jenis' => 'required|in:pemasukan,pengeluaran',
            'kategori' => 'required|string|max:100',
            'keterangan' => 'nullable|string',
            'nominal' => 'required|numeric|min:0',
            'transaksi_id' => 'nullable|integer|exists:transaksis,id',

            // ✅ upload foto baru opsional saat edit
            'foto' => 'nullable|array|max:6',
            'foto.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',

            // ✅ jika centang hapus foto lama
            'hapus_foto_lama' => 'nullable|in:1',
        ]);

        $transaksiId = $request->input('transaksi_id');
        if ($transaksiId === '' || $transaksiId === '0') $transaksiId = null;

        $fotoLama = is_array($kasLaka->foto) ? $kasLaka->foto : [];

        // kalau user minta hapus foto lama
        if ($request->input('hapus_foto_lama') == '1') {
            foreach ($fotoLama as $p) {
                if ($p && Storage::disk('public')->exists($p)) {
                    Storage::disk('public')->delete($p);
                }
            }
            $fotoLama = [];
        }

        // upload foto baru (jika ada)
        $fotoBaru = $this->storePhotos($request);

        // strategi: foto lama tetap, foto baru ditambahkan (append)
        $gabung = array_values(array_filter(array_merge($fotoLama, $fotoBaru)));

        $kasLaka->update([
            'tanggal' => $request->tanggal,
            'jenis' => $request->jenis,
            'kategori' => $request->kategori,
            'keterangan' => $request->keterangan,
            'nominal' => $request->nominal,
            'transaksi_id' => $transaksiId,
            'foto' => count($gabung) ? $gabung : null,
        ]);

        return back()->with('sukses', 'Data Kas Laka berhasil diperbarui.');
    }

    public function destroy(KasLaka $kasLaka)
    {
        // hapus foto fisik
        $foto = is_array($kasLaka->foto) ? $kasLaka->foto : [];
        foreach ($foto as $p) {
            if ($p && Storage::disk('public')->exists($p)) {
                Storage::disk('public')->delete($p);
            }
        }

        $kasLaka->delete();
        return back()->with('sukses', 'Data Kas Laka berhasil dihapus.');
    }
}
