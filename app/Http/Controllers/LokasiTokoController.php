<?php

namespace App\Http\Controllers;

use App\Models\LokasiToko;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LokasiTokoController extends Controller
{
    // ── Halaman utama kunjungan toko ───────────────────────────
    public function index()
    {
        $tokoList = LokasiToko::all();
        return view('kunjungan_toko.index', compact('tokoList'));
    }

    // ── Simpan toko baru (input titik awal) ───────────────────
    public function store(Request $request)
    {
        $request->validate([
            'barcode'   => 'required|string|max:8|unique:lokasi_toko,barcode',
            'nama_toko' => 'required|string|max:50',
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
            'accuracy'  => 'required|numeric',
        ]);

        LokasiToko::create($request->only([
            'barcode', 'nama_toko', 'latitude', 'longitude', 'accuracy'
        ]));

        return redirect()->route('kunjungan_toko.index')
            ->with('success', 'Toko berhasil ditambahkan.');
    }

    // ── Hapus toko ────────────────────────────────────────────
    public function destroy($barcode)
    {
        LokasiToko::findOrFail($barcode)->delete();
        return redirect()->route('kunjungan_toko.index')
            ->with('success', 'Toko berhasil dihapus.');
    }

    // ── Cetak barcode toko sebagai PDF ────────────────────────
    public function cetakBarcode($barcode)
    {
        $toko = LokasiToko::findOrFail($barcode);

        // Generate barcode PNG pakai library yang sama dengan tag harga
        $generator  = new \Picqer\Barcode\BarcodeGeneratorPNG();
        $barcodeRaw = $generator->getBarcode(
            $toko->barcode,
            $generator::TYPE_CODE_128,
            2,   // lebar bar
            60   // tinggi bar (lebih besar dari tag harga biar mudah di-scan)
        );

        $barcodeBase64 = 'data:image/png;base64,' . base64_encode($barcodeRaw);

        $pdf = Pdf::loadView('kunjungan_toko.barcode_pdf', compact('toko', 'barcodeBase64'));
        return $pdf->stream('barcode_toko_' . $toko->barcode . '.pdf');
    }

    // ── API: ambil data toko berdasarkan barcode (dipanggil setelah scan) ──
    public function getToko($barcode)
    {
        $toko = LokasiToko::where('barcode', $barcode)->first();

        if (!$toko) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Toko dengan barcode "' . $barcode . '" tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data'   => [
                'barcode'   => $toko->barcode,
                'nama_toko' => $toko->nama_toko,
                'latitude'  => $toko->latitude,
                'longitude' => $toko->longitude,
                'accuracy'  => $toko->accuracy,
            ]
        ]);
    }
}