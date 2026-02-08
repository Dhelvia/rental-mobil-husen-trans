<?php

namespace App\Http\Controllers;

use App\Models\Penyewa;
use Illuminate\Http\Request;

class PenyewaController extends Controller
{
    public function index()
    {
        $penyewas = Penyewa::latest()->get();
        return view('penyewa.index', compact('penyewas'));
    }

   public function update(Request $request, Penyewa $penyewa)
{
    $request->validate([
        'keterangan' => 'required|in:ruwet,lancar,suka bon',
        'nama' => 'nullable|string',
        'no_ktp' => 'nullable|string',
        'merk_motor' => 'nullable|string',
        'plat_nomor' => 'nullable|string',
        'no_hp' => 'nullable|string',
        'alamat' => 'nullable|string',
    ]);

    $penyewa->nama = $request->nama ?? $penyewa->nama;
    $penyewa->no_ktp = $request->no_ktp;
    $penyewa->merk_motor = $request->merk_motor;
    $penyewa->plat_nomor = $request->plat_nomor;
    $penyewa->no_hp = $request->no_hp;
    $penyewa->alamat = $request->alamat;
    $penyewa->keterangan = $request->keterangan;

    $penyewa->save();

    return back()->with('sukses', 'Data penyewa berhasil diperbarui');
}
}
