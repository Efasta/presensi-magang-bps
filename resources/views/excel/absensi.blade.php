<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan - {{ Str::limit($user->name, 20, '') }}</title>
    <style>
        * {
            font-family: 'Calibri', 'Arial', sans-serif;
            font-size: 11pt;
        }

        body {
            margin: 30px;
        }

        h2 {
            text-align: center;
            margin-bottom: 4px;
        }

        h3 {
            text-align: center;
            margin: 0;
            font-weight: normal;
            font-size: 12pt;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px 8px;
        }

        th {
            background-color: #e5e7eb;
            text-align: center;
        }

        td {
            text-align: center;
        }

        .rekap {
            margin-top: 25px;
        }

        .rekap h4 {
            margin-bottom: 8px;
        }

        .rekap table {
            width: 60%;
        }

        .rekap th {
            background-color: #f3f4f6;
        }

        .footer {
            margin-top: 40px;
            text-align: right;
            font-size: 10pt;
            color: #444;
        }
    </style>
</head>

<body>
    <h2>Laporan Absensi</h2>
    <h3>{{ $user->name }}</h3>

    <table>
        <thead>
            <tr>
                <th style="width: 5%; min-width: 40px;">No</th>
                <th style="width: 18%; min-width: 110px;">Tanggal</th>
                <th style="width: 15%; min-width: 90px;">Jam Masuk</th>
                <th style="width: 15%; min-width: 90px;">Jam Keluar</th>
                <th style="width: 15%; min-width: 80px;">Status</th>
                <th style="width: 32%; min-width: 180px;">Keterangan</th>
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
                    <td style="text-align: left;">{{ $absensi->judul ?? '-' }}</td>
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

    <div class="footer">
        <p>Dibuat pada: {{ now()->format('d F Y, H:i') }}</p>
    </div>
</body>

</html>
