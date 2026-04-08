@extends('layouts.app', ['judul' => 'Booking Mobil'])

@section('isi')
    <div class="judul-halaman">Booking Mobil</div>

    <div class="kartu-form">
        <form method="POST" action="{{ route('booking.store', $mobil->id) }}">
            @csrf

            <div class="grid-form-2">
                <div>
                    <label>Nama Customer</label>
                    <input type="text" name="nama_customer" value="{{ old('nama_customer') }}">
                </div>
                <div>
                    <label>No HP Customer</label>
                    <input type="text" name="no_hp_customer" value="{{ old('no_hp_customer') }}">
                </div>

                <div>
                    <label>Jaminan - No KTP</label>
                    <input type="text" name="no_ktp" value="{{ old('no_ktp') }}">
                </div>
                <div>
                    <label>Jaminan - Alamat</label>
                    <input type="text" name="alamat" value="{{ old('alamat') }}">
                </div>

                <div>
                    <label>Jaminan - Plat Motor</label>
                    <input type="text" name="plat_motor_jaminan" value="{{ old('plat_motor_jaminan') }}">
                </div>
                <div>
                    <label>Jaminan - Merk Motor</label>
                    <input type="text" name="merk_motor" value="{{ old('merk_motor') }}">
                </div>

                <div>
                    <label>Durasi Sewa</label>
                    <input type="text" name="durasi_sewa" value="{{ old('durasi_sewa') }}" placeholder="Contoh: 24 jam">
                </div>

                <div>
                    <label>Jam Mobil Diambil</label>
                    <input type="time" name="jam_ambil" value="{{ old('jam_ambil') }}">
                </div>

                {{-- ✅ FIX DI SINI --}}
                <div>
                    <label>Tanggal Booking</label>
                    <input type="date" name="tanggal_booking" 
                        value="{{ old('tanggal_booking', now()->toDateString()) }}" 
                        min="{{ date('Y-m-d') }}">
                </div>

                {{-- ✅ FIX DI SINI --}}
                <div>
                    <label>Tanggal Diambil</label>
                    <input type="date" name="tanggal_ambil" 
                        value="{{ old('tanggal_ambil') }}" 
                        min="{{ date('Y-m-d') }}">
                </div>

                <div>
                    <label>Plat Mobil </label>
                    <input type="text" value="{{ $mobil->plat }}" readonly>
                </div>

                <div>
                    <label>Jenis Mobil </label>
                    <input type="text" value="{{ $mobil->nama_mobil }} - {{ $mobil->transmisi }}" readonly>
                </div>

                <div>
                    <label>Biaya Sewa (Ketik Manual)</label>
                    <input type="number" name="biaya_sewa" value="{{ old('biaya_sewa', 0) }}">
                </div>

                <div>
                    <label>Keterangan</label>
                    <select name="keterangan">
                        <option value="antar rental">antar rental</option>
                        <option value="pribadi" selected>pribadi</option>
                    </select>
                </div>

                {{-- TAMBAH TUJUAN --}}
                <div style="grid-column: 1 / -1;">
                    <label>Tujuan</label>
                    <input type="text" name="tujuan" value="{{ old('tujuan') }}" placeholder="Contoh: Bandara / Dalam Kota / Luar Kota">
                </div>
            </div>

            @if($errors->any())
                <div class="alert-gagal" style="margin-top:10px;">
                    @foreach($errors->all() as $e) <div>- {{ $e }}</div> @endforeach
                </div>
            @endif

            <button class="btn-primary" type="submit" style="margin-top:12px;">Simpan Booking</button>
        </form>
    </div>
@endsection