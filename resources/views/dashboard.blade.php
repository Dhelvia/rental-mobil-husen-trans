@extends('layouts.app', ['judul' => 'Dashboard'])

@section('isi')
    <div class="baris-kartu">
        <div class="kartu-info">
            <div class="kartu-judul">Total Mobil Tersedia</div>
            <div class="kartu-angka">{{ $totalMobilTersedia }}</div>
        </div>
        <div class="kartu-info">
            <div class="kartu-judul">Total Riwayat Penyewa</div>
            <div class="kartu-angka">{{ $totalRiwayatPenyewa }}</div>
        </div>
        <div class="kartu-info">
            <div class="kartu-judul">Transaksi Hari Ini</div>
            <div class="kartu-angka">{{ $transaksiHariIni }}</div>
        </div>
        <div class="kartu-info">
            <div class="kartu-judul">Total Saldo Saat Ini</div>
            <div class="kartu-angka">Rp {{ number_format($saldo,0,',','.') }}</div>
        </div>
    </div>

    <div class="kartu-grafik">
        <div class="judul-seksi">Grafik Mingguan</div>
        <canvas id="grafikMingguan" height="120"></canvas>
    </div>
@endsection

@push('skrip')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('grafikMingguan');

    const labels = @json($labels);
    const pemasukan = @json($dataPemasukan);
    const pengeluaran = @json($dataPengeluaran);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                { label: 'Pemasukan', data: pemasukan, tension: 0.3 },
                { label: 'Pengeluaran', data: pengeluaran, tension: 0.3 }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'top' } },
            scales: { y: { beginAtZero: true } }
        }
    });
</script>
@endpush
