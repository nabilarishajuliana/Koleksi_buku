<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Undangan Event</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            padding: 40px;
            background-color: #f4f6fb;
        }

        .header {
            text-align: center;
            padding: 20px;
            background-color: #6C5CE7;
            color: white;
            border-radius: 8px;
        }

        .header h2 {
            margin: 0;
        }

        .content {
            margin-top: 40px;
            text-align: center;
        }

        .content h3 {
            color: #6C5CE7;
            margin-bottom: 20px;
        }

        .card {
            margin-top: 30px;
            padding: 30px;
            border: 2px dashed #A29BFE;
            border-radius: 12px;
            background-color: #ffffff;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #888;
        }

        .btn {
            margin-top: 20px;
            font-size: 14px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>SISTEM INFORMASI KOLEKSI BUKU</h2>
        <p>Universitas Airlangga</p>
    </div>

    <div class="content">
        <h3>Undangan Resmi</h3>

        <div class="card">
            <p>Kami dengan hormat mengundang Anda untuk menghadiri acara:</p>

            <h2>{{ $nama_event }}</h2>

            <p>
                <strong>Tanggal:</strong> {{ $tanggal }} <br>
                <strong>Tempat:</strong> {{ $tempat }}
            </p>

            <p>
                Terima kasih telah menjadi pengguna aktif
                Sistem Informasi Koleksi Buku.
            </p>
        </div>
    </div>

    <div class="footer">
        Dicetak pada: {{ date('d-m-Y H:i') }} <br>
        © {{ date('Y') }} Sistem Informasi Koleksi Buku
    </div>

</body>
</html>