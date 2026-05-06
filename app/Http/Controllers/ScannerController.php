<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class ScannerController extends Controller
{
    // ── Halaman Barcode Scanner ────────────────────────────────
    public function barcode()
    {
        return view('scanner.barcode');
    }
}