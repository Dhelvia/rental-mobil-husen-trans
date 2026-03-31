@extends('layouts.app', ['judul' => 'Transaksi'])

@section('isi')
    <div class="baris-atas">
        <div class="judul-halaman">Transaksi</div>
    </div>

    <div class="kartu-form">
        <div class="tabel-responsive">
            <table class="tabel">
                <thead>
                <tr>
                    <th>Mobil</th>
                    <th>Customer</th>
                    <th>Tujuan / Jaminan</th>
                    <th>Tanggal Booking</th>
                    <th>Tanggal Ambil</th>
                    <th>Biaya</th>
                    <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                @forelse($transaksis as $t)
                    <tr>
                        <td>
                            <b>{{ strtoupper($t->mobil->nama_mobil) }}</b><br>
                            <span class="teks-kecil">{{ $t->mobil->plat }}</span>
                        </td>

                        <td>
                            {{ $t->nama_customer }}<br>
                            <span class="teks-kecil">{{ $t->no_hp_customer }}</span>
                        </td>

                        {{-- GANTI KOLOM STATUS JADI TUJUAN + MERK MOTOR --}}
                        <td>
                            <div style="font-weight:600;">
                                Tujuan: {{ $t->tujuan ?? '-' }}
                            </div>
                            <div class="teks-kecil" style="color:#666;">
                                Merk Motor: {{ $t->merk_motor ?? '-' }}
                            </div>
                        </td>

                        <td>
                            {{ $t->tanggal_booking 
                            ? \Carbon\Carbon::parse($t->tanggal_booking)->format('d-m-Y') 
                            : '-' }}
                        </td>

                        <td>
    @if($t->tanggal_ambil)
        {{ \Carbon\Carbon::parse($t->tanggal_ambil)->format('d-m-Y') }}
        @if($t->jam_ambil)
            ({{ \Carbon\Carbon::parse($t->jam_ambil)->format('H.i') }})
        @endif
    @else
        -
    @endif
</td>


                        <td>Rp {{ number_format($t->biaya_sewa,0,',','.') }}</td>

                        <td class="aksi">
                            {{-- DROPDOWN STATUS --}}
                            <form method="POST" action="{{ route('transaksi.status', $t->id) }}" style="display:inline-block;">
                                @csrf
                                <select name="status" class="select-status" onchange="this.form.submit()">
                                    <option value="booking" {{ $t->status=='booking'?'selected':'' }}>BOOKING</option>
                                    <option value="diambil" {{ $t->status=='diambil'?'selected':'' }}>JALAN</option>
                                    <option value="selesai" {{ $t->status=='selesai'?'selected':'' }}>SELESAI</option>
                                </select>
                            </form>

                            {{-- EDIT MODAL --}}
                            <button class="btn-kecil btn-edit-modal"
                                    type="button"
                                    data-id="{{ $t->id }}"
                                    data-no_ktp="{{ $t->no_ktp }}"
                                    data-alamat="{{ $t->alamat }}"
                                    data-plat_motor="{{ $t->plat_motor_jaminan }}"
                                    data-merk_motor="{{ $t->merk_motor }}"
                                    data-tujuan="{{ $t->tujuan }}"
                                    data-durasi="{{ $t->durasi_sewa }}"
                                    data-jam="{{ $t->jam_ambil }}"
                                    data-tanggal="{{ $t->tanggal_ambil }}"
                                    data-biaya="{{ $t->biaya_sewa }}">
                                Edit
                            </button>

                            {{-- HAPUS --}}
                            <form method="POST" action="{{ route('transaksi.hapus', $t->id) }}" style="display:inline-block;">
                                @csrf
                                <button class="btn-hapus" type="submit" onclick="return confirm('Yakin hapus transaksi?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7">Belum ada transaksi.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal" id="modalEdit">
        <div class="modal-konten modal-lebar">
            <div class="modal-judul">Edit Transaksi</div>

            <form id="formEdit" method="POST" class="form-modal-grid">
                @csrf

                <div class="field">
                    <label>No KTP</label>
                    <input type="text" name="no_ktp" id="e_no_ktp">
                </div>

                <div class="field">
                    <label>Alamat</label>
                    <input type="text" name="alamat" id="e_alamat">
                </div>

                <div class="field">
                    <label>Plat Motor Jaminan</label>
                    <input type="text" name="plat_motor_jaminan" id="e_plat_motor">
                </div>

                <div class="field">
                    <label>Merk Motor</label>
                    <input type="text" name="merk_motor" id="e_merk_motor">
                </div>

                <div class="field">
                    <label>Tujuan</label>
                    <input type="text" name="tujuan" id="e_tujuan">
                </div>

                <div class="field">
                    <label>Durasi Sewa</label>
                    <input type="text" name="durasi_sewa" id="e_durasi">
                </div>

                <div class="field">
                    <label>Jam Diambil</label>
                    <input type="time" name="jam_ambil" id="e_jam">
                </div>

                <div class="field">
                    <label>Tanggal Diambil</label>
                    <input type="date" name="tanggal_ambil" id="e_tanggal">
                </div>

                <div class="field">
                    <label>Harga Mobil</label>
                    <input type="number" name="biaya_sewa" id="e_biaya">
                </div>

                <div class="modal-aksi">
                    <button type="button" class="btn-gelap" onclick="tutupModal('modalEdit')">Tutup</button>
                    <button type="submit" class="btn-biru">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
