@extends('layouts.app', ['judul' => 'Kas Laka'])

@section('isi')
    <div class="judul-halaman">Kas Laka</div>

    @if($errors->any())
        <div class="alert-gagal">
            <ul style="margin:0; padding-left:18px;">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Ringkasan --}}
    <div class="baris-kartu">
        <div class="kartu-info">
            <div class="kartu-judul">Total Pemasukan</div>
            <div class="kartu-angka">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</div>
        </div>
        <div class="kartu-info">
            <div class="kartu-judul">Total Pengeluaran</div>
            <div class="kartu-angka">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
        </div>
        <div class="kartu-info">
            <div class="kartu-judul">Saldo Saat Ini</div>
            <div class="kartu-angka">Rp {{ number_format($saldo, 0, ',', '.') }}</div>
        </div>
        <div class="kartu-info">
            <div class="kartu-judul">Catatan</div>
            <div class="teks-kecil">
                Bisa upload beberapa foto kerusakan (opsional). Cocok untuk bukti laka/bengkel.
            </div>
        </div>
    </div>

    {{-- Form tambah --}}
    <div class="kartu-form" style="margin-bottom:14px;">
        <div class="judul-seksi">Tambah Data Kas Laka</div>

        <form method="POST" action="{{ route('kaslaka.store') }}" class="form-modal-grid" enctype="multipart/form-data">
            @csrf

            <div class="field">
                <label>Tanggal</label>
                <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}">
            </div>

            <div class="field">
                <label>Jenis</label>
                <select name="jenis">
                    <option value="pemasukan" {{ old('jenis')=='pemasukan'?'selected':'' }}>PEMASUKAN</option>
                    <option value="pengeluaran" {{ old('jenis')=='pengeluaran'?'selected':'' }}>PENGELUARAN</option>
                </select>
            </div>

            <div class="field">
                <label>Kategori</label>
                <input type="text" name="kategori" placeholder="contoh: denda laka / bengkel / operasional" value="{{ old('kategori') }}">
            </div>

            <div class="field">
                <label>Nominal</label>
                <input type="number" name="nominal" min="0" step="1" placeholder="contoh: 1000000" value="{{ old('nominal') }}">
            </div>

            <div class="field">
                <label>Transaksi (Opsional)</label>
                <select name="transaksi_id">
                    <option value="">- Tidak terkait transaksi -</option>
                    @foreach($transaksis as $t)
                        <option value="{{ $t->id }}" {{ old('transaksi_id')==$t->id?'selected':'' }}>
                            ID {{ $t->id }} - {{ $t->nama_customer }} ({{ $t->tanggal_ambil ?? '-' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label>Foto Kerusakan (Opsional)</label>
                <input type="file" name="foto[]" multiple accept="image/*">
                <small style="opacity:.7;">Bisa upload beberapa foto (max 6 foto, max 3MB per foto).</small>
            </div>

            <div class="field" style="grid-column:1/-1;">
                <label>Keterangan</label>
                <input type="text" name="keterangan" placeholder="contoh: Kerusakan body pintu kanan, dll" value="{{ old('keterangan') }}">
            </div>

            <div class="modal-aksi">
                <button type="submit" class="btn-primary">Simpan</button>
            </div>
        </form>
    </div>

    {{-- Tabel --}}
    <div class="kartu-form">
        <div class="baris-atas">
            <div class="judul-seksi" style="margin:0;">Riwayat Kas Laka</div>

            <form method="GET" action="{{ route('kaslaka.index') }}" style="display:flex; gap:8px; flex-wrap:wrap;">
                <select name="jenis" class="select-status">
                    <option value="">Semua Jenis</option>
                    <option value="pemasukan" {{ request('jenis')=='pemasukan'?'selected':'' }}>PEMASUKAN</option>
                    <option value="pengeluaran" {{ request('jenis')=='pengeluaran'?'selected':'' }}>PENGELUARAN</option>
                </select>

                <input type="text" name="kategori" placeholder="Cari kategori..." value="{{ request('kategori') }}" style="width:180px;">
                <input type="number" name="transaksi_id" placeholder="Transaksi ID" value="{{ request('transaksi_id') }}" style="width:140px;">

                <button type="submit" class="btn-kecil">Filter</button>
            </form>
        </div>

        <div class="tabel-responsive">
            <table class="tabel">
                <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Jenis</th>
                    <th>Kategori</th>
                    <th>Nominal</th>
                    <th>Transaksi</th>
                    <th>Foto</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                @forelse($items as $k)
                    @php
                        $fotoArr = is_array($k->foto) ? $k->foto : [];
                    @endphp
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($k->tanggal)->format('d-m-Y') }}</td>
                        <td style="font-weight:900;">{{ strtoupper($k->jenis) }}</td>
                        <td>{{ $k->kategori }}</td>
                        <td>Rp {{ number_format($k->nominal, 0, ',', '.') }}</td>
                        <td>{{ $k->transaksi_id ? 'ID '.$k->transaksi_id : '-' }}</td>
                        <td>
                            @if(count($fotoArr))
                                <div style="display:flex; gap:6px; flex-wrap:wrap;">
                                    @foreach($fotoArr as $fp)
                                        <a href="{{ asset('storage/'.$fp) }}" target="_blank" title="Lihat foto">
                                            <img src="{{ asset('storage/'.$fp) }}"
                                                 style="width:44px;height:44px;object-fit:cover;border-radius:10px;border:1px solid #e1eaff;">
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $k->keterangan ?? '-' }}</td>
                        <td class="aksi">
                            <button type="button"
                                    class="btn-kecil btn-edit-kas"
                                    data-id="{{ $k->id }}"
                                    data-tanggal="{{ \Carbon\Carbon::parse($k->tanggal)->format('Y-m-d') }}"
                                    data-jenis="{{ $k->jenis }}"
                                    data-kategori="{{ $k->kategori }}"
                                    data-nominal="{{ (int)$k->nominal }}"
                                    data-transaksi_id="{{ $k->transaksi_id ?? '' }}"
                                    data-keterangan="{{ $k->keterangan ?? '' }}"
                                    data-hasfoto="{{ count($fotoArr) ? 1 : 0 }}">
                                Edit
                            </button>

                            <form method="POST" action="{{ route('kaslaka.hapus', $k->id) }}" style="display:inline-block;">
                                @csrf
                                <button class="btn-hapus" type="submit" onclick="return confirm('Yakin hapus data kas ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8">Belum ada data kas.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top:12px;">
            {{ $items->links() }}
        </div>
    </div>

    {{-- MODAL EDIT --}}
    <div class="modal" id="modalEditKas">
        <div class="modal-konten modal-lebar">
            <div class="modal-judul">Edit Kas Laka</div>

            <form id="formEditKas" method="POST" class="form-modal-grid" enctype="multipart/form-data">
                @csrf

                <div class="field">
                    <label>Tanggal</label>
                    <input type="date" name="tanggal" id="e_tanggal">
                </div>

                <div class="field">
                    <label>Jenis</label>
                    <select name="jenis" id="e_jenis">
                        <option value="pemasukan">PEMASUKAN</option>
                        <option value="pengeluaran">PENGELUARAN</option>
                    </select>
                </div>

                <div class="field">
                    <label>Kategori</label>
                    <input type="text" name="kategori" id="e_kategori">
                </div>

                <div class="field">
                    <label>Nominal</label>
                    <input type="number" name="nominal" min="0" step="1" id="e_nominal">
                </div>

                <div class="field">
                    <label>Transaksi (Opsional)</label>
                    <select name="transaksi_id" id="e_transaksi_id">
                        <option value="">- Tidak terkait transaksi -</option>
                        @foreach($transaksis as $t)
                            <option value="{{ $t->id }}">ID {{ $t->id }} - {{ $t->nama_customer }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="field">
                    <label>Tambah Foto (Opsional)</label>
                    <input type="file" name="foto[]" multiple accept="image/*">
                    <small style="opacity:.7;">Foto baru akan ditambahkan ke foto lama.</small>
                </div>

                <div class="field" style="grid-column:1/-1; display:flex; flex-direction:row; align-items:center; gap:10px;">
                    <input type="checkbox" name="hapus_foto_lama" value="1" id="e_hapus_foto_lama" style="width:auto;">
                    <label for="e_hapus_foto_lama" style="margin:0; font-weight:900;">Hapus semua foto lama</label>
                </div>

                <div class="field" style="grid-column:1/-1;">
                    <label>Keterangan</label>
                    <input type="text" name="keterangan" id="e_keterangan">
                </div>

                <div class="modal-aksi">
                    <button type="button" class="btn-hapus" id="btnTutupKas">Tutup</button>
                    <button type="submit" class="btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function bukaModal(id){ document.getElementById(id).classList.add('tampil'); }
        function tutupModal(id){ document.getElementById(id).classList.remove('tampil'); }

        document.getElementById('btnTutupKas')?.addEventListener('click', function(){
            tutupModal('modalEditKas');
        });

        document.addEventListener('click', function(e){
            if(e.target.classList.contains('btn-edit-kas')){
                const id = e.target.dataset.id;

                document.getElementById('e_tanggal').value = e.target.dataset.tanggal || '';
                document.getElementById('e_jenis').value = e.target.dataset.jenis || 'pemasukan';
                document.getElementById('e_kategori').value = e.target.dataset.kategori || '';
                document.getElementById('e_nominal').value = e.target.dataset.nominal || 0;
                document.getElementById('e_transaksi_id').value = e.target.dataset.transaksi_id || '';
                document.getElementById('e_keterangan').value = e.target.dataset.keterangan || '';

                // reset checkbox hapus foto lama
                document.getElementById('e_hapus_foto_lama').checked = false;

                const form = document.getElementById('formEditKas');
                form.action = '/kas-laka/' + id + '/update';

                bukaModal('modalEditKas');
            }

            if(e.target.classList.contains('modal')){
                if(e.target.id === 'modalEditKas') tutupModal('modalEditKas');
            }
        });
    </script>
@endsection
