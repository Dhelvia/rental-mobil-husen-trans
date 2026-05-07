<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use App\Models\Admin;

class AuthController extends Controller
{
    public function tampilLogin()
    {
        return view('auth.login');
    }

    public function prosesLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return back()->with('gagal', 'Email atau password salah');
        }

        // session login
        session([
            'admin_id' => $admin->id,
            'admin_nama' => $admin->nama,
        ]);

        // remember me (30 hari)
        if ($request->filled('ingat_saya')) {
            Cookie::queue('ingat_admin', $admin->id, 60 * 24 * 30);
        } else {
           
            Cookie::queue(Cookie::forget('ingat_admin'));
        }

        return redirect()->route('dashboard');
    }

    public function logout()
    {
        session()->forget(['admin_id', 'admin_nama']);

        // hapus cookie remember me
        Cookie::queue(Cookie::forget('ingat_admin'));

        return redirect()->route('login');
    }
}
