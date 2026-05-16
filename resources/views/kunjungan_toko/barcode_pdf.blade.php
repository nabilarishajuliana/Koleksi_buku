<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Barcode Toko — {{ $toko->barcode }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 20px;
        }

        .toko-name {
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .barcode-img {
            display: block;
            margin: 0 auto 8px;
            width: 200px;
            height: 60px;
        }

        .barcode-code {
            font-size: 12pt;
            color: #555;
        }

        .toko-info {
            font-size: 8pt;
            color: #888;
            margin-top: 8px;
        }
    </style>
</head>
<body>
    <div class="toko-name">{{ $toko->nama_toko }}</div>
    <img class="barcode-img" src="{{ $barcodeBase64 }}" alt="{{ $toko->barcode }}">
    <div class="barcode-code">{{ $toko->barcode }}</div>
    <div class="toko-info">
        Lat: {{ $toko->latitude }} | Lng: {{ $toko->longitude }}
    </div>
</body>
</html>