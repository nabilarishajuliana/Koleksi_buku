<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
/*
 * KALIBRASI KERTAS LABEL TOM & JERRY NO. 108
 * Ukuran 1 label : 38mm x 18mm
 * Layout         : 5 kolom x 8 baris = 40 label
 * Margin kiri    : 8mm dari tepi kertas
 * Margin atas    : 13mm dari tepi kertas
 * Gutter H       : 1.7mm antar kolom
 * Gutter V       : 0mm (label nempel vertikal)
*/

@page {
    size: 210mm 165mm;
    margin: 0mm;
}

body {
    width: 210mm;
    height: 165mm;
    margin: 0;
    padding: 0;
    padding-top: 7mm;  /* ← naikan nilainya sesuai kebutuhan */
    font-family: Arial, Helvetica, sans-serif;
}

.page {
    width: 210mm;
    height: 165mm;
    page-break-after: always;
}

table.label-sheet {
    width: 210mm;
    height: 158mm;  /* ← 165 - 7 = 158mm */
    border-collapse: collapse;   /* ← pakai collapse, bukan separate */
    table-layout: fixed;         /* ← WAJIB: paksa lebar kolom rata */
}

table.label-sheet tr {
    height: 19.75mm;  /* ← 158mm / 8 baris */
}

table.label-sheet td {
       width: 42mm;
    height: 19.75mm;
    max-height: 19.75mm;
    overflow: hidden;
    text-align: center;
    vertical-align: middle;
    padding: 0;                  /* ← hapus padding dari td */
    /* border: 0.3pt dashed #ccc; */ /* uncomment untuk debug */
}

.label-content {
    display: block;
    width: 42mm;
    height: 19.75mm;
    max-height: 19.75mm;
    overflow: hidden;
    text-align: center;
    padding: 1mm;
}

.nama {
    font-size: 6.5pt;
    font-weight: bold;
    line-height: 1.2;
    margin-bottom: 1mm;
}

.harga {
    font-size: 8.5pt;
    font-weight: bold;
    line-height: 1;
}

.id-barang {
    font-size: 5pt;
    color: #666;
    margin-top: 0.8mm;
}
</style>
</head>
<body>

@php
    $indexBarang  = 0;
    $totalBarang  = count($barang);
    $slotHalaman1 = 40 - $startIndex;
    $totalHalaman = 1;
    if ($totalBarang > $slotHalaman1) {
        $sisa = $totalBarang - $slotHalaman1;
        $totalHalaman += (int) ceil($sisa / 40);
    }
@endphp

@for ($page = 0; $page < $totalHalaman; $page++)

<div class="page">
    <table class="label-sheet">

        @for ($i = 0; $i < 8; $i++)
        <tr>
            @for ($j = 0; $j < 5; $j++)

            @php
                $currentSlot = ($i * 5) + $j;
                $slotAwal    = ($page === 0) ? $startIndex : 0;
            @endphp

            <td>
                @if ($currentSlot >= $slotAwal && $indexBarang < $totalBarang)
                    <div class="label-content">
                        <div class="nama">{{ $barang[$indexBarang]->nama_barang }}</div>
                        <div class="harga">Rp {{ number_format($barang[$indexBarang]->harga, 0, ',', '.') }}</div>
                        <div class="id-barang">{{ $barang[$indexBarang]->id_barang }}</div>
                    </div>
                    @php $indexBarang++; @endphp
                @endif
            </td>

            @endfor
        </tr>
        @endfor

    </table>
</div>

@endfor

</body>
</html>