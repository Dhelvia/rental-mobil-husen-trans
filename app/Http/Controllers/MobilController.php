<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mobil;

class MobilController extends Controller
{
    public function index()
    {
        
        $mobils = Mobil::with('transaksis')->latest()->get();
        return view('mobil.index', compact('mobils'));
    }

    public function create()
    {
        return view('mobil.tambah');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_mobil' => 'required',
            'plat' => 'required',
            'transmisi' => 'required',
            'gambar' => 'nullable|image|max:4096',
            'harga_6_jam' => 'required|numeric',
            'harga_12_jam' => 'required|numeric',
            'harga_24_jam' => 'required|numeric',
            'harga_per_hari' => 'required|numeric',
        ], [
            'nama_mobil.required' => 'Nama mobil wajib diisi',
            'plat.required' => 'Plat wajib diisi',
        ]);

        $gambarPath = null;

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $namaFile = 'mobil_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/mobil'), $namaFile);
            $gambarPath = 'uploads/mobil/' . $namaFile;
        }

        Mobil::create([
            'nama_mobil' => $request->nama_mobil,
            'plat' => $request->plat,
            'warna' => $request->warna,
            'transmisi' => $request->transmisi,
            'gambar' => $gambarPath,
            'harga_6_jam' => $request->harga_6_jam,
            'harga_12_jam' => $request->harga_12_jam,
            'harga_24_jam' => $request->harga_24_jam,
            'harga_per_hari' => $request->harga_per_hari,
            'tersedia' => true, // default
        ]);

        return redirect()->route('mobil.index')->with('sukses', 'Mobil berhasil ditambahkan');
    }

    public function edit(Mobil $mobil)
    {
        return view('mobil.edit', compact('mobil'));
    }

    public function update(Request $request, Mobil $mobil)
    {
        $request->validate([
            'nama_mobil' => 'required',
            'plat' => 'required',
            'transmisi' => 'required',
            'gambar' => 'nullable|image|max:4096',
            'harga_6_jam' => 'required|numeric',
            'harga_12_jam' => 'required|numeric',
            'harga_24_jam' => 'required|numeric',
            'harga_per_hari' => 'required|numeric',
        ]);

        $data = [
            'nama_mobil' => $request->nama_mobil,
            'plat' => $request->plat,
            'warna' => $request->warna,
            'transmisi' => $request->transmisi,
            'harga_6_jam' => $request->harga_6_jam,
            'harga_12_jam' => $request->harga_12_jam,
            'harga_24_jam' => $request->harga_24_jam,
            'harga_per_hari' => $request->harga_per_hari,
        ];

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $namaFile = 'mobil_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/mobil'), $namaFile);
            $data['gambar'] = 'uploads/mobil/' . $namaFile;
        }

        $mobil->update($data);

        return redirect()->route('mobil.index')->with('sukses', 'Mobil berhasil diperbarui');
    }

    public function destroy(Mobil $mobil)
    {
        $mobil->delete();
        return back()->with('sukses', 'Mobil berhasil dihapus');
    }
}