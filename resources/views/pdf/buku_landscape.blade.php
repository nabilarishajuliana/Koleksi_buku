<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Data Buku</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            background-color: #ffffff;
        }

        .container {
            background: #ffffff;
            padding: 25px;
            border-radius: 8px;
        }

        .header {
            background-color: #6C5CE7;
            color: white;
            padding: 15px;
            text-align: center;
            border-radius: 6px;
        }

        .header h2 {
            margin: 0;
            font-size: 20px;
            letter-spacing: 1px;
        }

        .sub-info {
            text-align: right;
            font-size: 12px;
            margin-top: 10px;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 12px;
        }

        th {
            background-color: #A29BFE;
            color: white;
            padding: 10px;
            text-transform: uppercase;
            font-size: 11px;
        }

        td {
            padding: 8px;
            border-bottom: 1px solid #e0e0e0;
        }

        tr:nth-child(even) {
            background-color: #f9f9ff;
        }

        tr:hover {
            background-color: #ecebff;
        }

        .footer {
            margin-top: 20px;
            font-size: 11px;
            text-align: center;
            color: #888;
        }
    </style>
</head>
<body>

<div class="container">

    <div class="header">
        <h2>LAPORAN DATA BUKU</h2>
    </div>

    <div class="sub-info">
        Dicetak pada: {{ date('d-m-Y H:i') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Judul Buku</th>
                <th>pengarang</th>
                <th>Kategori</th>
                <th>Tanggal Dibuat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($buku as $index => $item)
                <tr>
                    <td align="center">{{ $index + 1 }}</td>
                    <td>{{ $item->judul }}</td>
                    <td>{{ $item->pengarang }}</td>
                    <td align="center">{{ $item->kategori->nama_kategori ?? '-' }}</td>
                    <td align="center">{{ $item->created_at->format('d-m-Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Sistem Informasi Koleksi Buku © {{ date('Y') }}
    </div>

</div>

</body>
</html>