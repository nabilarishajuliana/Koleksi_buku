<!-- <!DOCTYPE html>
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
</html> -->

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Undangan Event</title>

    <style>
        @page {
            size: A4 portrait;
            margin: 40px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            color: #333;
            line-height: 1.6;
        }

        .border-wrapper {
            border: 2px solid #000;
            padding: 25px;
            position: relative;
        }

        .corner {
            position: absolute;
            font-size: 22px;
            font-weight: bold;
        }

        .corner-top-left { top: 10px; left: 10px; }
        .corner-top-right { top: 10px; right: 10px; }
        .corner-bottom-left { bottom: 10px; left: 10px; }
        .corner-bottom-right { bottom: 10px; right: 10px; }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .title {
            font-size: 26px;
            font-weight: bold;
        }

        .subtitle {
            font-size: 12px;
            letter-spacing: 2px;
        }

        .info-box {
            border: 1px solid #000;
            padding: 10px;
            font-size: 13px;
            width: 200px;
        }

        .content {
            font-size: 14px;
            margin-top: 20px;
            text-align: justify;
        }

        .footer {
            margin-top: 50px;
            text-align: right;
        }

        .footer p {
            margin: 3px 0;
        }
    </style>
</head>

<body>

<div class="border-wrapper">

    <!-- Decorative Corners -->
    <!-- <div class="corner corner-top-left">❖</div>
    <div class="corner corner-top-right">❖</div>
    <div class="corner corner-bottom-left">❖</div>
    <div class="corner corner-bottom-right">❖</div> -->

    <!-- Header -->
    <div class="header">
        <div>
            <div class="title">UNDANGAN</div>
            <div class="subtitle">KEGIATAN PERPUSTAKAAN</div>
        </div>

        <div class="info-box">
            Kepada Yth:<br>
            {{ Auth::user()->name }}<br>
            Di Tempat
        </div>
    </div>

    <!-- Content -->
    <div class="content">
        Assalamualaikum Warahmatullahi Wabarakatuh,<br><br>

        Dengan hormat, sehubungan dengan adanya kegiatan <b>Launching Koleksi Buku Baru 2026</b>,
        kami mengundang Bapak/Ibu untuk hadir pada acara yang akan dilaksanakan pada:

        <br><br>

        <b>Hari/Tanggal :</b> Sabtu, 27 Februari 2026<br>
        <b>Waktu :</b> 09.00 WIB - Selesai<br>
        <b>Tempat :</b> Aula Perpustakaan Koleksi Buku

        <br><br>

        Acara ini bertujuan untuk memperkenalkan koleksi buku terbaru serta meningkatkan
        minat baca masyarakat.

        <br><br>

        Demikian undangan ini kami sampaikan. Atas perhatian dan kehadirannya kami ucapkan terima kasih.

        <br><br>

        Wassalamualaikum Warahmatullahi Wabarakatuh.
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Hormat Kami,</p>
        <br><br>
        <p><b>Admin Koleksi Buku</b></p>
    </div>

</div>

</body>
</html>