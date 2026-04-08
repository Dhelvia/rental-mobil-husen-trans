@extends('layouts.app', ['judul' => 'Data Penyewa'])

@section('isi')
    <div class="judul-halaman">Data Penyewa (Selesai Sewa)</div>

    @if(session('sukses'))
        <div class="alert-sukses">{{ session('sukses') }}</div>
    @endif

    {{-- SEARCH --}}
    <form method="GET" action="{{ route('penyewa.index') }}" style="margin-bottom:15px;">
        <input type="text" name="keyword" placeholder="Cari nama penyewa..."
               value="{{ $keyword ?? '' }}" style="padding:6px; width:250px;">
        
        <button type="submit" class="btn-primary">Cari</button>

        @if(request('keyword'))
            <a href="{{ route('penyewa.index') }}" class="btn-kecil">Reset</a>
        @endif
    </form>

    <div class="kartu-form">
        <div class="tabel-responsive">
            <table class="tabel">
                <thead>
                <tr>
                    <th>Nama</th>
                    <th>No KTP</th>
                    <th>Merk Motor</th>
                    <th>Plat Nomor</th>
                    <th>No HP</th>
                    <th>Alamat</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                @forelse($penyewas as $p)
                    <tr>
                        {{-- 🔥 NAMA + HIGHLIGHT AMAN --}}
                        <td>
                            @php
                                $nama = strtoupper($p->nama);
                            @endphp

                            @if(!empty($keyword))
                                {!! str_ireplace(
                                    strtoupper($keyword),
                                    '<mark style="background-color: yellow;">'.strtoupper($keyword).'</mark>',
                                    $nama
                                ) !!}
                            @else
                                {{ $nama }}
                            @endif
                        </td>

                        <td>{{ $p->no_ktp ?? '-' }}</td>
                        <td>{{ $p->merk_motor ?? '-' }}</td>
                        <td>{{ $p->plat_nomor ?? '-' }}</td>
                        <td>{{ $p->no_hp ?? '-' }}</td>
                        <td>{{ $p->alamat ?? '-' }}</td>

                        <td>
                            <form method="POST" action="{{ route('penyewa.update', $p->id) }}" class="form-keterangan">
                                @csrf
                                <select name="keterangan" class="select-status" onchange="this.form.submit()">
                                    <option value="lancar" {{ ($p->keterangan ?? 'lancar')=='lancar'?'selected':'' }}>LANCAR</option>
                                    <option value="ruwet" {{ ($p->keterangan ?? 'lancar')=='ruwet'?'selected':'' }}>RUWET</option>
                                    <option value="suka bon" {{ ($p->keterangan ?? 'lancar')=='suka bon'?'selected':'' }}>SUKA BON</option>
                                </select>
                            </form>
                        </td>

                        <td class="aksi">
                            <button type="button"
                                    class="btn-kecil btn-edit-penyewa"
                                    data-id="{{ $p->id }}"
                                    data-nama="{{ $p->nama }}"
                                    data-no_ktp="{{ $p->no_ktp }}"
                                    data-merk_motor="{{ $p->merk_motor }}"
                                    data-plat_nomor="{{ $p->plat_nomor }}"
                                    data-no_hp="{{ $p->no_hp }}"
                                    data-alamat="{{ $p->alamat }}"
                                    data-keterangan="{{ $p->keterangan ?? 'lancar' }}">
                                Edit
                            </button>

                            <form method="POST" action="{{ route('penyewa.hapus', $p->id) }}" style="display:inline-block;">
                                @csrf
                                <button class="btn-hapus" type="submit" onclick="return confirm('Yakin hapus data penyewa?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8">Belum ada data penyewa.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL EDIT PENYEWA -->
    <div class="modal" id="modalEditPenyewa">
        <div class="modal-konten modal-lebar">
            <div class="modal-judul">Edit Data Penyewa</div>

            <form id="formEditPenyewa" method="POST" class="form-modal-grid">
                @csrf

                <div class="field">
                    <label>Nama</label>
                    <input type="text" name="nama" id="e_nama">
                </div>

                <div class="field">
                    <label>No KTP</label>
                    <input type="text" name="no_ktp" id="e_no_ktp">
                </div>

                <div class="field">
                    <label>Merk Motor</label>
                    <input type="text" name="merk_motor" id="e_merk_motor">
                </div>

                <div class="field">
                    <label>Plat Motor</label>
                    <input type="text" name="plat_nomor" id="e_plat_nomor">
                </div>

                <div class="field">
                    <label>No HP</label>
                    <input type="text" name="no_hp" id="e_no_hp">
                </div>

                <div class="field">
                    <label>Alamat</label>
                    <input type="text" name="alamat" id="e_alamat">
                </div>

                <div class="field" style="grid-column:1/-1;">
                    <label>Keterangan</label>
                    <select name="keterangan" id="e_keterangan">
                        <option value="lancar">LANCAR</option>
                        <option value="ruwet">RUWET</option>
                        <option value="suka bon">SUKA BON</option>
                    </select>
                </div>

                <div class="modal-aksi">
                    <button type="button" class="btn-gelap" id="btnTutupPenyewa">Tutup</button>
                    <button type="submit" class="btn-biru">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- 🔥 FIX EVENT CLICK (ANTI ERROR) --}}
    <script>
      function bukaModal(id){ document.getElementById(id).classList.add('tampil'); }
      function tutupModal(id){ document.getElementById(id).classList.remove('tampil'); }

      document.getElementById('btnTutupPenyewa')?.addEventListener('click', function(){
        tutupModal('modalEditPenyewa');
      });

      document.addEventListener('click', function(e){
        const btn = e.target.closest('.btn-edit-penyewa'); // 🔥 FIX UTAMA

        if(btn){
          const id = btn.dataset.id;

          document.getElementById('e_nama').value = btn.dataset.nama || '';
          document.getElementById('e_no_ktp').value = btn.dataset.no_ktp || '';
          document.getElementById('e_merk_motor').value = btn.dataset.merk_motor || '';
          document.getElementById('e_plat_nomor').value = btn.dataset.plat_nomor || '';
          document.getElementById('e_no_hp').value = btn.dataset.no_hp || '';
          document.getElementById('e_alamat').value = btn.dataset.alamat || '';
          document.getElementById('e_keterangan').value = btn.dataset.keterangan || 'lancar';

          const form = document.getElementById('formEditPenyewa');
          form.action = '/data-penyewa/' + id + '/update';

          bukaModal('modalEditPenyewa');
        }

        if(e.target.classList.contains('modal')){
          if(e.target.id === 'modalEditPenyewa') tutupModal('modalEditPenyewa');
        }
      });
    </script>
@endsection