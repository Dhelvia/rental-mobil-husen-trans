<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan PDF</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #000;
        }

        h2, h3 {
            margin-bottom: 6px;
        }

        p {
            margin: 4px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 18px;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        th {
            background: #eaeaea;
        }
    </style>
</head>
<body>
    <h2>Laporan Rental Mobil</h2>
    <p>Periode: {{ $tanggalAwal }} s/d {{ $tanggalAkhir }}</p>
    <p>Total Pemasukan: Rp {{ number_format($totalPemasukan,0,',','.') }}</p>
    <p>Total Pengeluaran: Rp {{ number_format($totalPengeluaran,0,',','.') }}</p>
    <p>Penghasilan Akhir: Rp {{ number_format($penghasilanAkhir,0,',','.') }}</p>

    <h3>Data Pemasukan</h3>
    <table>
        <thead>
            <tr>
                <th>Nama Customer</th>
                <th>Tanggal Ambil</th>
                <th>Durasi Sewa</th>
                <th>Total Pemasukan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporans as $l)
                <tr>
                    <td>{{ strtoupper($l->nama_customer) }}</td>
                    <td>{{ $l->tanggal_ambil ?? '-' }}</td>
                    <td>{{ $l->durasi_sewa ?? '-' }}</td>
                    <td>Rp {{ number_format($l->total_pemasukan,0,',','.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">Tidak ada data pemasukan pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h3>Data Pengeluaran</h3>
    <table>
        <thead>
            <tr>
                <th>Nama Customer</th>
                <th>Jenis Pengeluaran</th>
                <th>Total Pengeluaran</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pengeluarans as $p)
                <tr>
                    <td>{{ strtoupper($p->laporan->nama_customer ?? '-') }}</td>
                    <td>{{ $p->jenis_pengeluaran }}</td>
                    <td>Rp {{ number_format($p->total_pengeluaran,0,',','.') }}</td>
                    <td>{{ $p->created_at?->format('Y-m-d') ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">Tidak ada data pengeluaran pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>