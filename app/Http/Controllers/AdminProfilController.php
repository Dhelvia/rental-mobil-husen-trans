<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;

class AdminProfilController extends Controller
{
    public function edit()
    {
        $admin = Admin::findOrFail(session('admin_id'));
        return view('admin.profil', compact('admin'));
    }

    public function update(Request $request)
    {
        $admin = Admin::findOrFail(session('admin_id'));

        $request->validate([
    'nama' => 'required',
    'foto' => 'nullable|image|max:2048'
], [
    'nama.required' => 'Nama wajib diisi',
]);

$data = [
    'nama' => $request->nama,
];


        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $namaFile = 'admin_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/admin'), $namaFile);
            $data['foto'] = 'uploads/admin/' . $namaFile;
        }

        $admin->update($data);
        session(['admin_foto' => $admin->foto]); // TAMBAH INI
        session(['admin_nama' => $admin->nama]);

        return back()->with('sukses', 'Profil berhasil diperbarui');
    }
}
