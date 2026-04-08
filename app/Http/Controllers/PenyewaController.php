<?php

namespace App\Http\Controllers;

use App\Models\Penyewa;
use Illuminate\Http\Request;

class PenyewaController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->keyword;

        $penyewas = Penyewa::when($keyword, function ($query, $keyword) {
            return $query->where('nama', 'like', "%$keyword%");
        })->latest()->get();

        return view('penyewa.index', compact('penyewas', 'keyword'));
    }

    private function normalizeHp(?string $hp): ?string
    {
        if ($hp === null) return null;

        $hp = trim($hp);
        if ($hp === '') return null;

        $hp = preg_replace('/\D+/', '', $hp);

        if ($hp === '') return null;

        if (substr($hp, 0, 2) === '62') {
            $hp = '0' . substr($hp, 2);
        }

        return $hp;
    }

    public function update(Request $request, Penyewa $penyewa)
    {
        $normalizedHp = $this->normalizeHp($request->input('no_hp'));

        $request->merge([
            'no_hp' => $normalizedHp
        ]);

        $request->validate([
            'keterangan' => 'required|in:ruwet,lancar,suka bon',
            'nama' => 'nullable|string',
            'no_ktp' => 'nullable|string',
            'merk_motor' => 'nullable|string',
            'plat_nomor' => 'nullable|string',
            'no_hp' => 'nullable|string|unique:penyewas,no_hp,' . $penyewa->id,
            'alamat' => 'nullable|string',
        ]);

        $penyewa->nama = $request->nama ?? $penyewa->nama;
        $penyewa->no_ktp = $request->no_ktp ?? $penyewa->no_ktp;
        $penyewa->merk_motor = $request->merk_motor ?? $penyewa->merk_motor;
        $penyewa->plat_nomor = $request->plat_nomor ?? $penyewa->plat_nomor;

        if ($request->filled('no_hp')) {
            $penyewa->no_hp = $request->no_hp;
        }

        $penyewa->alamat = $request->alamat ?? $penyewa->alamat;
        $penyewa->keterangan = $request->keterangan;

        $penyewa->save();

        return back()->with('sukses', 'Data penyewa berhasil diperbarui');
    }

    public function hapus(Penyewa $penyewa)
    {
        $penyewa->delete();
        return back()->with('sukses', 'Data penyewa berhasil dihapus');
    }
}