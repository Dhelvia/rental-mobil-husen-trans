@extends('layouts.app', ['judul' => 'Edit Mobil'])

@section('isi')
    <div class="judul-halaman">Edit Mobil</div>

    <div class="kartu-form">
        <form method="POST" action="{{ route('mobil.update', $mobil->id) }}" enctype="multipart/form-data">
            @csrf

            <label>Nama Mobil</label>
            <input type="text" name="nama_mobil" value="{{ old('nama_mobil', $mobil->nama_mobil) }}">

            <label>Plat Mobil</label>
            <input type="text" name="plat" value="{{ old('plat', $mobil->plat) }}">

            <label>Warna</label>
            <input type="text" name="warna" value="{{ old('warna', $mobil->warna) }}">

            <label>Transmisi</label>
            <select name="transmisi">
                <option value="Manual" {{ $mobil->transmisi==='Manual'?'selected':'' }}>Manual</option>
                <option value="Automatic" {{ $mobil->transmisi==='Automatic'?'selected':'' }}>Automatic</option>
            </select>

            <label>Gambar Mobil (opsional)</label>
            <input type="file" name="gambar">

            @if($mobil->gambar)
                <div style="margin-top:10px;">
                    <img src="{{ asset($mobil->gambar) }}" style="width:200px;border-radius:14px;">
                </div>
            @endif

            <label>Harga 6 Jam</label>
            <input type="number" name="harga_6_jam" value="{{ old('harga_6_jam', $mobil->harga_6_jam) }}">

            <label>Harga 12 Jam</label>
            <input type="number" name="harga_12_jam" value="{{ old('harga_12_jam', $mobil->harga_12_jam) }}">

            <label>Harga 24 Jam</label>
            <input type="number" name="harga_24_jam" value="{{ old('harga_24_jam', $mobil->harga_24_jam) }}">

            <label>Harga Per Hari</label>
            <input type="number" name="harga_per_hari" value="{{ old('harga_per_hari', $mobil->harga_per_hari) }}">

            @if($errors->any())
                <div class="alert-gagal" style="margin-top:10px;">
                    @foreach($errors->all() as $e) <div>- {{ $e }}</div> @endforeach
                </div>
            @endif

            <button class="btn-primary" type="submit" style="margin-top:12px;">Simpan Perubahan</button>
        </form>
    </div>
@endsection
