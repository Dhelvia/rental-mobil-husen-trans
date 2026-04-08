@extends('layouts.app', ['judul' => 'Data Mobil'])

@section('isi')
    <div class="baris-atas">
        <div class="judul-halaman">Daftar Mobil</div>
        <a class="btn-primary" href="{{ route('mobil.create') }}">+ Tambah Mobil</a>
    </div>

    <div class="grid-mobil">
        @forelse($mobils as $m)
            <div class="kartu-mobil">
                <div class="gambar-mobil">
                    @if($m->gambar)
                        <img src="{{ asset($m->gambar) }}" alt="gambar mobil">
                    @else
                        <div class="placeholder-gambar">Tidak ada gambar</div>
                    @endif
                </div>

                <div class="isi-mobil">
                    <div class="nama-mobil">{{ strtoupper($m->nama_mobil) }}</div>
                    <div class="teks-kecil"><b>Plat:</b> {{ $m->plat }}</div>
                    <div class="teks-kecil"><b>Warna:</b> {{ $m->warna ?? '-' }}</div>
                    <div class="teks-kecil"><b>Transmisi:</b> {{ $m->transmisi }}</div>

                    <div class="kotak-harga">
                        <div><b>List Harga:</b></div>
                        <ul>
                            <li>6 Jam: Rp {{ number_format($m->harga_6_jam,0,',','.') }}</li>
                            <li>12 Jam: Rp {{ number_format($m->harga_12_jam,0,',','.') }}</li>
                            <li>24 Jam: Rp {{ number_format($m->harga_24_jam,0,',','.') }}</li>
                            <li>Per Hari: Rp {{ number_format($m->harga_per_hari,0,',','.') }}</li>
                        </ul>
                    </div>

                    <div class="baris-tombol">
                        <a class="btn-book" href="{{ route('booking.create', $m->id) }}">Book →</a>
                    </div>

                    <div class="baris-tombol">
                        <a class="btn-kecil" href="{{ route('mobil.edit', $m->id) }}">Edit</a>

                        <form method="POST" action="{{ route('mobil.destroy', $m->id) }}">
                            @csrf
                            @method('DELETE')
                            <button class="btn-hapus" type="submit" onclick="return confirm('Yakin hapus mobil?')">Hapus</button>
                        </form>
                    </div>

                    {{-- 🔥 LOGIKA FINAL SESUAI KEBUTUHAN --}}
                    @php
                        $dipakai = $m->transaksis
                            ->whereIn('status', ['booking','diambil'])
                            ->count();
                    @endphp

                    <div class="badge-tersedia {{ $dipakai ? 'merah' : 'hijau' }}">
                        {{ $dipakai ? 'Tidak Tersedia' : 'Tersedia' }}
                    </div>

                </div>
            </div>
        @empty
            <div class="kosong">Belum ada data mobil.</div>
        @endforelse
    </div>
@endsection