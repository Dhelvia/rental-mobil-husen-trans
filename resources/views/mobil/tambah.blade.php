@extends('layouts.app', ['judul' => 'Tambah Mobil'])

@section('isi')
    <div class="judul-halaman">Tambah Mobil</div>

    <div class="kartu-form">
        <form method="POST" action="{{ route('mobil.store') }}" enctype="multipart/form-data">
            @csrf

            <label>Nama Mobil</label>
            <input type="text" name="nama_mobil" value="{{ old('nama_mobil') }}" placeholder="Contoh: Brio">

            <label>Plat Mobil</label>
            <input type="text" name="plat" value="{{ old('plat') }}" placeholder="Contoh: AD 1129 EC">

            <label>Warna</label>
            <input type="text" name="warna" value="{{ old('warna') }}" placeholder="Contoh: Putih">

            <label>Transmisi</label>
            <select name="transmisi">
                <option value="Manual">Manual</option>
                <option value="Automatic">Automatic</option>
            </select>

            <label>Gambar Mobil (opsional)</label>
            <input type="file" name="gambar">

            <label>Harga 6 Jam</label>
            <input type="number" name="harga_6_jam" value="{{ old('harga_6_jam', 0) }}">

            <label>Harga 12 Jam</label>
            <input type="number" name="harga_12_jam" value="{{ old('harga_12_jam', 0) }}">

            <label>Harga 24 Jam</label>
            <input type="number" name="harga_24_jam" value="{{ old('harga_24_jam', 0) }}">

            <label>Harga Per Hari</label>
            <input type="number" name="harga_per_hari" value="{{ old('harga_per_hari', 0) }}">

            @if($errors->any())
                <div class="alert-gagal" style="margin-top:10px;">
                    @foreach($errors->all() as $e) <div>- {{ $e }}</div> @endforeach
                </div>
            @endif

            <button class="btn-primary" type="submit" style="margin-top:12px;">Simpan Mobil</button>
        </form>
    </div>
@endsection
