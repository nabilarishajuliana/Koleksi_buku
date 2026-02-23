<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function bukuLandscape()
    {
        $buku = Buku::with('kategori')->get();

        $pdf = Pdf::loadView('pdf.buku_landscape', compact('buku'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('laporan-data-buku.pdf');
    }

    public function undanganPortrait()
    {
        $data = [
            'nama_event' => 'Literasi Digital 2026',
            'tanggal' => '15 Maret 2026',
            'tempat' => 'Aula Universitas Airlangga',
        ];

        $pdf = Pdf::loadView('pdf.undangan_portrait', $data)
            ->setPaper('a4', 'portrait');

        return $pdf->download('undangan-event.pdf');
    }
}
