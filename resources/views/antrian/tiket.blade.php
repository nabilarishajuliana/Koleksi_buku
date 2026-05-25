<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket Antrian #{{ $antrian->nomor_antrian }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .tiket {
            background: white;
            border-radius: 20px;
            width: 100%;
            max-width: 360px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
            overflow: hidden;
        }

        .tiket-header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            padding: 2rem;
            text-align: center;
            color: white;
        }

        .tiket-header p {
            font-size: 0.85rem;
            opacity: 0.8;
            margin-bottom: 0.5rem;
        }

        .nomor-antrian {
            font-size: 5rem;
            font-weight: 700;
            line-height: 1;
        }

        .tiket-body {
            padding: 1.5rem 2rem;
            text-align: center;
        }

        .nama {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1A1A2E;
            margin-bottom: 0.25rem;
        }

        .waktu {
            font-size: 0.82rem;
            color: #6B7280;
            margin-bottom: 1.5rem;
        }

        .info-box {
            background: #F3F4F8;
            border-radius: 10px;
            padding: 1rem;
            font-size: 0.82rem;
            color: #6B7280;
            line-height: 1.6;
        }

        .status-badge {
            display: inline-block;
            padding: 0.4rem 1rem;
            border-radius: 100px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-top: 1rem;
        }

        .status-menunggu  { background: #EFF6FF; color: #1D4ED8; }
        .status-dipanggil { background: #ECFDF5; color: #065F46; }
        .status-terlambat { background: #FFF7ED; color: #C2410C; }

        .tiket-footer {
            padding: 1rem 2rem;
            border-top: 1px dashed #E5E7EB;
            text-align: center;
            font-size: 0.75rem;
            color: #9CA3AF;
        }
    </style>
</head>
<body>
<div class="tiket">
    <div class="tiket-header">
        <p>Nomor Antrian Kamu</p>
        <div class="nomor-antrian">{{ str_pad($antrian->nomor_antrian, 3, '0', STR_PAD_LEFT) }}</div>
    </div>
    <div class="tiket-body">
        <div class="nama">{{ $antrian->nama }}</div>
        <div class="waktu">
            Daftar: {{ \Carbon\Carbon::parse($antrian->waktu_daftar)->format('H:i') }} WIB
        </div>
        <div class="info-box">
            📢 Perhatikan papan antrian dan dengarkan pengumuman. Harap tetap berada di area tunggu.
        </div>
        <div class="status-badge status-{{ $antrian->status }}">
            @if($antrian->status === 'menunggu')   ⏳ Menunggu
            @elseif($antrian->status === 'dipanggil') ✅ Dipanggil!
            @else ⚠️ Terlambat
            @endif
        </div>
    </div>
    <div class="tiket-footer">
        Simpan halaman ini sebagai referensi nomor antrian kamu
    </div>
</div>
</body>
</html>