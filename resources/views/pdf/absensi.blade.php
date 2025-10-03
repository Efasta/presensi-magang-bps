<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Absensi - {{ $user->name }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        th {
            background: #f0f0f0;
        }

        h2 {
            text-align: center;
            margin-bottom: 10px;
        }

        .rekap {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <h2>Laporan Absensi<br>{{ $user->name }}</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Jam Keluar</th>
                <th>Status</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($absensis as $i => $absensi)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $absensi->tanggal ?? '-' }}</td>
                    <td>{{ $absensi->jam_masuk ?? '-' }}</td>
                    <td>{{ $absensi->jam_keluar ?? '-' }}</td>
                    <td>{{ $absensi->status->nama ?? '-' }}</td>
                    <td>{{ $absensi->judul ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="rekap">
        <h4>Rekap Kehadiran:</h4>
        <table>
            <thead>
                <tr>
                    <th>Hadir</th>
                    <th>Izin</th>
                    <th>Sakit</th>
                    <th>Absen</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $rekap['Hadir'] ?? 0 }}x</td>
                    <td>{{ $rekap['Izin'] ?? 0 }}x</td>
                    <td>{{ $rekap['Sakit'] ?? 0 }}x</td>
                    <td>{{ $rekap['Absen'] ?? 0 }}x</td>
                </tr>
            </tbody>
        </table>
    </div>

</body>

</html>
