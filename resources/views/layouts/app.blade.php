<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $judul ?? 'Rental Mobil - Admin Panel' }}</title>
    <link rel="stylesheet" href="{{ asset('css/style-admin.css') }}">
</head>
<body>
<div class="wadah">
    <aside class="sidebar">

        @php
            $adminObj = null;
            $foto = null;

            $adminId = session('admin_id');
            if($adminId){
                $adminObj = \App\Models\Admin::find($adminId);
                if($adminObj && $adminObj->foto){
                    $foto = asset($adminObj->foto);
                }
            }

            $namaAdmin = $adminObj?->nama ?? 'Rental Mobil';
            $tanggalHariIni = now()->format('d-m-Y');
        @endphp

        {{-- LOGO SIDEBAR (MODEL DASHBOARD, FONT DIKECILIN + TANGGAL DI BAWAH) --}}
        <a class="logo" href="{{ route('admin.profil') }}" style="display:block;text-decoration:none;">
            <div style="
                background:#2f6dff;
                border-radius:18px;
                padding:16px;
                display:flex;
                gap:12px;
                align-items:center;
            ">
                <div style="
                    width:60px;
                    height:60px;
                    border-radius:16px;
                    background:#ffffff;
                    display:flex;
                    align-items:center;
                    justify-content:center;
                    overflow:hidden;
                    flex-shrink:0;
                ">
                    @if($foto)
                        <img src="{{ $foto }}" alt="Foto Admin" style="width:100%;height:100%;object-fit:cover;display:block;">
                    @else
                        <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-weight:900;color:#2f6dff;">
                            RM
                        </div>
                    @endif
                </div>

                <div style="line-height:1.15;">
                    {{-- NAMA (dikecilin) --}}
                    <div style="font-weight:900;color:#ffffff;font-size:14px;letter-spacing:.2px;">
                        {{ strtoupper($namaAdmin) }}
                    </div>

                    {{-- ADMIN PANEL --}}
                    <div style="font-size:12px;color:#e6eeff;margin-top:4px;">
                        Admin Panel
                    </div>

                    {{-- TANGGAL DI BAWAH ADMIN PANEL --}}
                    <div style="font-size:12px;color:#e6eeff;margin-top:2px;">
                        {{ $tanggalHariIni }}
                    </div>
                </div>
            </div>
        </a>

        <nav class="menu">
            <a class="menu-item {{ request()->is('dashboard') ? 'aktif' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
            <a class="menu-item {{ request()->is('mobil*') ? 'aktif' : '' }}" href="{{ route('mobil.index') }}">Data Mobil</a>
            <a class="menu-item {{ request()->is('transaksi*') ? 'aktif' : '' }}" href="{{ route('transaksi.index') }}">Transaksi</a>
            <a class="menu-item {{ request()->is('data-penyewa*') ? 'aktif' : '' }}" href="{{ route('penyewa.index') }}">Data Penyewa</a>
            <a class="menu-item {{ request()->is('laporan*') ? 'aktif' : '' }}" href="{{ route('laporan.index') }}">Laporan</a>
        </nav>

        <form action="{{ route('logout') }}" method="POST" class="logout-form">
            @csrf
            <button class="btn-logout" type="submit">Logout</button>
        </form>
    </aside>

    <main class="konten">
        @if(session('sukses'))
            <div class="alert-sukses">{{ session('sukses') }}</div>
        @endif
        @if(session('gagal'))
            <div class="alert-gagal">{{ session('gagal') }}</div>
        @endif

        @yield('isi')
    </main>
</div>

<script src="{{ asset('js/admin.js') }}"></script>
@stack('skrip')
</body>
</html>
