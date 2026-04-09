@extends('layouts.app', ['judul' => 'Laporan'])

@section('isi')
    <div class="judul-halaman">Laporan</div>

    <div style="margin: 10px 0 14px 0;">
        <button type="button" class="btn-cetak-pdf" onclick="bukaModal('modalPdf')">
            + Cetak PDF
        </button>
    </div>

    @if(session('sukses'))
        <div class="alert-sukses">{{ session('sukses') }}</div>
    @endif

    <div class="baris-kartu" style="margin-top:10px;">
        <div class="kartu-info">
            <div class="kartu-judul">Total Pemasukan</div>
            <div class="kartu-angka">Rp {{ number_format($totalPemasukan,0,',','.') }}</div>

            <div style="margin-top:12px;">
                <button class="btn-kecil btn-pengeluaran" data-id="{{ $laporans->first()?->id ?? '' }}"
                        style="opacity: {{ $laporans->count() ? '1' : '.5' }};"
                        {{ $laporans->count() ? '' : 'disabled' }}>
                    + Pengeluaran
                </button>
                <div class="teks-kecil" style="margin-top:6px;color:#667;">
                    (Tambah pengeluaran ke laporan terbaru)
                </div>
            </div>
        </div>

        <div class="kartu-info">
            <div class="kartu-judul">Total Pengeluaran</div>
            <div class="kartu-angka">Rp {{ number_format($totalPengeluaran,0,',','.') }}</div>
        </div>

        <div class="kartu-info">
            <div class="kartu-judul">Penghasilan Akhir</div>
            <div class="kartu-angka">Rp {{ number_format($penghasilanAkhir,0,',','.') }}</div>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-top:12px;">
        <div class="kartu-form">
            <div class="judul-seksi" style="font-size:18px;">Data Pemasukan</div>
            <div class="tabel-responsive">
                <table class="tabel">
                    <thead>
                    <tr>
                        <th>Nama Customer</th>
                        <th>Tanggal Ambil</th>
                        <th>Durasi Sewa</th>
                        <th>Total Pemasukan</th>
                        <th>Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($laporans as $l)
                        <tr>
                            <td>{{ strtoupper($l->nama_customer) }}</td>
                            <td>{{ $l->tanggal_ambil ?? '-' }}</td>
                            <td>{{ $l->durasi_sewa ?? '-' }}</td>
                            <td>Rp {{ number_format($l->total_pemasukan,0,',','.') }}</td>
                            <td class="aksi">
                                <button type="button"
                                        class="btn-kecil btn-edit-laporan"
                                        data-id="{{ $l->id }}"
                                        data-nama_customer="{{ $l->nama_customer }}"
                                        data-tanggal_ambil="{{ $l->tanggal_ambil }}"
                                        data-durasi_sewa="{{ $l->durasi_sewa }}"
                                        data-total_pemasukan="{{ $l->total_pemasukan }}">
                                    Edit
                                </button>

                                <form method="POST" action="{{ route('laporan.hapus', $l->id) }}">
                                    @csrf
                                    <button class="btn-hapus" type="submit" onclick="return confirm('Yakin hapus laporan?')">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5">Belum ada laporan pemasukan.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="kartu-form">
            <div class="judul-seksi" style="font-size:18px;">Data Pengeluaran</div>
            <div class="tabel-responsive">
                <table class="tabel">
                    <thead>
                    <tr>
                        <th>Nama Customer</th>
                        <th>Jenis Pengeluaran</th>
                        <th>Total Pengeluaran</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php $ada = false; @endphp

                    @foreach($laporans as $l)
                        @foreach($l->pengeluarans as $p)
                            @php $ada = true; @endphp
                            <tr>
                                <td>{{ strtoupper($l->nama_customer) }}</td>
                                <td>{{ $p->jenis_pengeluaran }}</td>
                                <td>Rp {{ number_format($p->total_pengeluaran,0,',','.') }}</td>
                                <td>{{ $p->tanggal ?? ($p->created_at?->format('Y-m-d') ?? '-') }}</td>
                                <td class="aksi">
                                    <button type="button"
                                            class="btn-kecil btn-edit-pengeluaran"
                                            data-id="{{ $p->id }}"
                                            data-jenis="{{ $p->jenis_pengeluaran }}"
                                            data-total="{{ $p->total_pengeluaran }}"
                                            data-tanggal="{{ $p->tanggal ?? ($p->created_at?->format('Y-m-d') ?? '') }}">
                                        Edit
                                    </button>

                                    <form method="POST" action="{{ route('pengeluaran.hapus', $p->id) }}">
                                        @csrf
                                        <button class="btn-hapus" type="submit" onclick="return confirm('Yakin hapus pengeluaran?')">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach

                    @if(!$ada)
                        <tr><td colspan="5">Belum ada data pengeluaran.</td></tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- MODAL PDF --}}
    <div class="modal" id="modalPdf">
        <div class="modal-konten modal-lebar">
            <div class="modal-judul">Jadikan PDF</div>

            <form method="GET" action="{{ route('laporan.pdf') }}" class="form-modal-grid" target="_blank">
                <div class="field">
                    <label>Tanggal Awal</label>
                    <input type="date" name="tanggal_awal" required>
                </div>

                <div class="field">
                    <label>Tanggal Akhir</label>
                    <input type="date" name="tanggal_akhir" required>
                </div>

                <div class="modal-baris">
                    <button type="button" class="btn-hapus" onclick="tutupModal('modalPdf')">Tutup</button>
                    <button type="submit" class="btn-primary">Download PDF</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL EDIT LAPORAN --}}
    <div class="modal" id="modalEditLaporan">
        <div class="modal-konten modal-lebar">
            <div class="modal-judul">Edit Pemasukan</div>

            <form id="formEditLaporan" method="POST" class="form-modal-grid">
                @csrf

                <div class="field">
                    <label>Nama Customer</label>
                    <input type="text" name="nama_customer" id="e_nama_customer">
                </div>

                <div class="field">
                    <label>Tanggal Ambil</label>
                    <input type="date" name="tanggal_ambil" id="e_tanggal_ambil">
                </div>

                <div class="field">
                    <label>Durasi Sewa</label>
                    <input type="text" name="durasi_sewa" id="e_durasi_sewa" placeholder="contoh: 2 hari / 12 jam">
                </div>

                <div class="field">
                    <label>Total Pemasukan</label>
                    <input type="number" name="total_pemasukan" id="e_total_pemasukan" value="0">
                </div>

                <div class="modal-baris">
                    <button type="button" class="btn-hapus" onclick="tutupModal('modalEditLaporan')">Tutup</button>
                    <button type="submit" class="btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL TAMBAH PENGELUARAN --}}
    <div class="modal" id="modalPengeluaran">
        <div class="modal-konten modal-lebar">
            <div class="modal-judul">Tambah Pengeluaran</div>

            <form id="formPengeluaran" method="POST" class="form-modal-grid">
                @csrf

                <div class="field" style="grid-column:1/-1;">
                    <label>Jenis Pengeluaran</label>
                    <input type="text" name="jenis_pengeluaran" placeholder="Contoh: Servis / Bensin / Parkir">
                </div>

                <div class="field">
                    <label>Tanggal</label>
                    <input type="date" name="tanggal" value="{{ now()->format('Y-m-d') }}">
                </div>

                <div class="field">
                    <label>Total Pengeluaran</label>
                    <input type="number" name="total_pengeluaran" value="0">
                </div>

                <div class="modal-baris">
                    <button type="button" class="btn-hapus" onclick="tutupModal('modalPengeluaran')">Tutup</button>
                    <button type="submit" class="btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL EDIT PENGELUARAN --}}
    <div class="modal" id="modalEditPengeluaran">
        <div class="modal-konten modal-lebar">
            <div class="modal-judul">Edit Pengeluaran</div>

            <form id="formEditPengeluaran" method="POST" class="form-modal-grid">
                @csrf

                <div class="field" style="grid-column:1/-1;">
                    <label>Jenis Pengeluaran</label>
                    <input type="text" name="jenis_pengeluaran" id="e_jenis_pengeluaran">
                </div>

                <div class="field">
                    <label>Tanggal</label>
                    <input type="date" name="tanggal" id="e_tanggal_pengeluaran">
                </div>

                <div class="field">
                    <label>Total Pengeluaran</label>
                    <input type="number" name="total_pengeluaran" id="e_total_pengeluaran" value="0">
                </div>

                <div class="modal-baris">
                    <button type="button" class="btn-hapus" onclick="tutupModal('modalEditPengeluaran')">Tutup</button>
                    <button type="submit" class="btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .btn-cetak-pdf{
            background: linear-gradient(135deg, #4f7cff, #2d5bff);
            color: #fff;
            border: none;
            border-radius: 16px;
            padding: 12px 20px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 8px 18px rgba(45, 91, 255, 0.18);
            transition: all 0.2s ease;
        }

        .btn-cetak-pdf:hover{
            transform: translateY(-1px);
            opacity: .95;
        }

        @media (max-width: 980px){
            .laporan-grid-dua{grid-template-columns:1fr !important;}
        }
    </style>
@endsection