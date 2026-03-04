<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Label Harga</title>
    <style>
        @page { size: 210mm 163mm; margin: 0; }
        body { margin:0; font-family: Arial, sans-serif; background-color: #fff; }
        table.label-sheet {
            border-spacing: 3mm 2mm;
            table-layout: fixed;
            width: 210mm;
            height: 163mm;
        }
        table.label-sheet td {
            width: 38mm;
            height: 18mm;
            padding: 0;
            text-align: center;
            vertical-align: middle;
            background-color: #fff;
        }
        .label-nama  { font-size: 6pt;   font-weight: bold; }
        .label-harga { font-size: 7.5pt; font-weight: bold; }
        .label-id    { font-size: 4.5pt; color: #888; }
    </style>
</head>
<body>
@php
    $cols         = 5;
    $rows         = 8;
    $barangIndex  = 0;
    $totalBarang  = count($barang);
@endphp
<table class="label-sheet">
    <colgroup>
        <col style="width:38mm">
        <col style="width:38mm">
        <col style="width:38mm">
        <col style="width:38mm">
        <col style="width:38mm">
    </colgroup>
    @for ($row = 0; $row < $rows; $row++)
    <tr style="height:18mm">
        @for ($col = 0; $col < $cols; $col++)
        @php
            $i          = $row * $cols + $col;
            $shouldFill = $i >= $startIndex && $barangIndex < $totalBarang;
        @endphp
        <td>
            @if ($shouldFill)
                @php $item = $barang[$barangIndex]; $barangIndex++; @endphp
                <div class="label-nama">{{ Str::limit($item->nama_barang, 30) }}</div>
                <div class="label-harga">Rp {{ number_format($item->harga, 0, ',', '.') }}</div>
                <div class="label-id">{{ $item->id_barang }}</div>
            @endif
        </td>
        @endfor
    </tr>
    @endfor
</table>
</body>
</html>