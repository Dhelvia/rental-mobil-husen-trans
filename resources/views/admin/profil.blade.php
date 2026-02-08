@extends('layouts.app', ['judul' => 'Profil Admin'])

@section('isi')
    <div class="judul-halaman">Ubah Profil Admin</div>

    <div class="kartu-form">
        <form method="POST" action="{{ route('admin.profil.update') }}" enctype="multipart/form-data">
            @csrf

            <label>Nama</label>
            <input type="text" name="nama" value="{{ old('nama', $admin->nama) }}">

            <label>Foto (opsional)</label>
            <input type="file" name="foto">

            @if($admin->foto)
                <div style="margin-top:10px;">
                    <img src="{{ asset($admin->foto) }}" style="width:120px;border-radius:14px;">
                </div>
            @endif

            @if($errors->any())
                <div class="alert-gagal" style="margin-top:10px;">
                    @foreach($errors->all() as $e) <div>- {{ $e }}</div> @endforeach
                </div>
            @endif

            <button class="btn-primary" type="submit" style="margin-top:12px;">Simpan</button>
        </form>
    </div>
@endsection
