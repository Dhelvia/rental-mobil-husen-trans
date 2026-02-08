<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use App\Models\Admin;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // kalau session sudah ada
        if (session()->has('admin_id')) {
            return $next($request);
        }

        // cek cookie remember me
        if (Cookie::has('ingat_admin')) {
            $admin = Admin::find(Cookie::get('ingat_admin'));

            if ($admin) {
                session([
                    'admin_id' => $admin->id,
                    'admin_nama' => $admin->nama,
                ]);

                return $next($request);
            }
        }

        return redirect()->route('login');
    }
}
