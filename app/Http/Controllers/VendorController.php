<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Vendor;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    // ── Dashboard vendor: lihat pesanan lunas ──────────────────
    // Sesuai modul: vendor hanya lihat pesanan dengan status "Lunas"
    public function index()
    {
        // Ambil semua pesanan yang sudah lunas beserta detailnya
        $pesanan = Pesanan::with(['detail.menu'])
            ->where('status_bayar', 'lunas')
            ->latest()
            ->get();

        $vendors = Vendor::all();

        return view('vendor.index', compact('pesanan', 'vendors'));
    }

    // ── Tambah menu baru ───────────────────────────────────────
    public function storeMenu(Request $request)
    {
        $request->validate([
            'id_vendor' => 'required|exists:vendor,id_vendor',
            'nama_menu' => 'required|string|max:100',
            'harga'     => 'required|numeric|min:0',
        ]);

        Menu::create($request->only('id_vendor', 'nama_menu', 'harga'));

        return redirect()->route('vendor.index')
            ->with('success', 'Menu berhasil ditambahkan.');
    }

    // ── Hapus menu ─────────────────────────────────────────────
    public function destroyMenu($id)
    {
        Menu::findOrFail($id)->delete();
        return redirect()->route('vendor.index')
            ->with('success', 'Menu berhasil dihapus.');
    }
}