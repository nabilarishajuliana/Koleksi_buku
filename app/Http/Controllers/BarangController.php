<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class BarangController extends Controller
{
    public function index()
    {
        $barang = Barang::all();
        return view('barang.index', compact('barang'));
    }

    public function cetakIndex()
{
    $barang = Barang::all();
    return view('barang.cetak', compact('barang'));
}

    public function create()
    {
        return view('barang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:100',
            'harga'       => 'required|numeric|min:0',
        ]);

        Barang::create($request->only('nama_barang', 'harga'));
        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit(Barang $barang)
    {
        return view('barang.edit', compact('barang'));
    }

    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:100',
            'harga'       => 'required|numeric|min:0',
        ]);

        $barang->update($request->only('nama_barang', 'harga'));
        return redirect()->route('barang.index')->with('success', 'Barang berhasil diupdate.');
    }

    public function destroy(Barang $barang)
    {
        $barang->delete();
        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus.');
    }

    // public function cetak(Request $request)
    // {
    //     $barang     = Barang::whereIn('id', $request->barang)->get();
    //     $startIndex = (($request->y - 1) * 5) + ($request->x - 1);

    //     $pdf = Pdf::loadView('barang.pdf', compact('barang', 'startIndex'));
    //     return $pdf->stream('tag_harga.pdf');
    // }
    
public function cetak(Request $request)
{
    $barangList = Barang::whereIn('id', $request->barang)->get();
    $startIndex = (($request->y - 1) * 5) + ($request->x - 1);

    $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();

    $barcodes = [];
    foreach ($barangList as $item) {
        $barcodeRaw = $generator->getBarcode(
            $item->id_barang,
            $generator::TYPE_CODE_128,
            1,
            25
        );
        $barcodes[$item->id_barang] = 'data:image/png;base64,' . base64_encode($barcodeRaw);
    }

    $pdf = Pdf::loadView('barang.pdf', compact('barangList', 'startIndex', 'barcodes'))
        ->setPaper([0, 0, 595.28, 462.05], 'landscape');

    return $pdf->stream('tag_harga.pdf');
}
}