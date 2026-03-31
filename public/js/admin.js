function bukaModal(id){
  document.getElementById(id).classList.add('tampil');
}
function tutupModal(id){
  document.getElementById(id).classList.remove('tampil');
}

document.addEventListener('click', function(e){

  // ===== EDIT PENYEWA =====
  if(e.target.classList.contains('btn-edit-penyewa')){
    const id = e.target.dataset.id;

    // kalau kamu cuma edit keterangan saja
    const elKet = document.getElementById('e_keterangan_penyewa');
    if(elKet) elKet.value = (e.target.dataset.keterangan || 'lancar');

    const form = document.getElementById('formEditPenyewa');
    if(form) form.action = '/data-penyewa/' + id + '/update';

    bukaModal('modalEditPenyewa');
    return;
  }

  // ===== TAMBAH PENGELUARAN =====
  if(e.target.classList.contains('btn-pengeluaran')){
    const id = e.target.dataset.id;

    const form = document.getElementById('formPengeluaran');
    if(form) form.action = '/laporan/' + id + '/pengeluaran';

    bukaModal('modalPengeluaran');
    return;
  }

  // ===== EDIT PENGELUARAN =====
  if(e.target.classList.contains('btn-edit-pengeluaran')){
    const id = e.target.dataset.id;

    document.getElementById('e_jenis_pengeluaran').value = e.target.dataset.jenis || '';
    document.getElementById('e_total_pengeluaran').value = e.target.dataset.total || '0';
    document.getElementById('e_tanggal_pengeluaran').value = e.target.dataset.tanggal || '';

    const form = document.getElementById('formEditPengeluaran');
    if(form) form.action = '/pengeluaran/' + id + '/update';

    bukaModal('modalEditPengeluaran');
    return;
  }

  // ===== EDIT LAPORAN (PEMASUKAN) =====
  if(e.target.classList.contains('btn-edit-laporan')){
    const id = e.target.dataset.id;

    document.getElementById('e_nama_customer').value = e.target.dataset.nama_customer || '';
    document.getElementById('e_tanggal_ambil').value = e.target.dataset.tanggal_ambil || '';
    document.getElementById('e_durasi_sewa').value = e.target.dataset.durasi_sewa || '';
    document.getElementById('e_total_pemasukan').value = e.target.dataset.total_pemasukan || '0';

    const form = document.getElementById('formEditLaporan');
    if(form) form.action = '/laporan/' + id + '/update';

    bukaModal('modalEditLaporan');
    return;
  }

  // ===== EDIT TRANSAKSI (MODAL) =====
  // tombol di blade: class="btn-kecil btn-edit-modal"
  if(e.target.classList.contains('btn-edit-modal')){
    const id = e.target.dataset.id;

    document.getElementById('e_no_ktp').value = e.target.dataset.no_ktp || '';
    document.getElementById('e_alamat').value = e.target.dataset.alamat || '';
    document.getElementById('e_plat_motor').value = e.target.dataset.plat_motor || '';
    document.getElementById('e_merk_motor').value = e.target.dataset.merk_motor || '';
    document.getElementById('e_tujuan').value = e.target.dataset.tujuan || '';
    document.getElementById('e_durasi').value = e.target.dataset.durasi || '';
    document.getElementById('e_jam').value = e.target.dataset.jam || '';
    document.getElementById('e_tanggal').value = e.target.dataset.tanggal || '';
    document.getElementById('e_biaya').value = e.target.dataset.biaya || '0';

    const form = document.getElementById('formEdit');
    if(form) form.action = '/transaksi/' + id + '/update-modal';

    bukaModal('modalEdit');
    return;
  }

  // ===== KLIK BACKGROUND MODAL = TUTUP =====
  if(e.target.classList.contains('modal')){
    if(e.target.id === 'modalEditPenyewa') tutupModal('modalEditPenyewa');
    if(e.target.id === 'modalPengeluaran') tutupModal('modalPengeluaran');
    if(e.target.id === 'modalEditPengeluaran') tutupModal('modalEditPengeluaran');
    if(e.target.id === 'modalEditLaporan') tutupModal('modalEditLaporan');
    if(e.target.id === 'modalEdit') tutupModal('modalEdit'); // transaksi
    return;
  }

});
