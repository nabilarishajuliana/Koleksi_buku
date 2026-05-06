<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Vendor;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    // Dashboard vendor
    public function index()
    {
        // Ambil data vendor yang sedang login dari session
        $vendorId = session('vendor_id');
        $vendor   = Vendor::with('menu')->findOrFail($vendorId);

        // Ambil pesanan lunas
        $pesanan = Pesanan::with(['detail.menu'])
            ->where('status_bayar', 'lunas')
            ->latest()
            ->get();

        return view('vendor.dashboard', compact('vendor', 'pesanan'));
    }

    // Tambah menu baru
    public function storeMenu(Request $request)
    {
        $request->validate([
            'nama_menu' => 'required|string|max:100',
            'harga'     => 'required|numeric|min:0',
        ]);

        Menu::create([
            'id_vendor' => session('vendor_id'), // otomatis pakai vendor yg login
            'nama_menu' => $request->nama_menu,
            'harga'     => $request->harga,
        ]);

        return redirect()->route('vendor.dashboard')
            ->with('success', 'Menu berhasil ditambahkan.');
    }

    // Hapus menu
    public function destroyMenu($id)
    {
        $menu = Menu::findOrFail($id);

        // Pastikan menu milik vendor yang login
        if ($menu->id_vendor != session('vendor_id')) {
            return redirect()->route('vendor.dashboard')
                ->with('error', 'Tidak diizinkan menghapus menu ini.');
        }

        $menu->delete();
        return redirect()->route('vendor.dashboard')
            ->with('success', 'Menu berhasil dihapus.');
    }

    // ── Halaman QR Scanner untuk vendor ───────────────────────────
    public function qrScanner()
    {
        return view('vendor.qr_scanner');
    }

    // ── API: ambil detail pesanan dari id_pesanan (hasil scan QR) ──
    public function getPesanan($id_pesanan)
    {
        $vendorId = session('vendor_id');

        // Cari pesanan beserta detail yang menu-nya milik vendor ini
        $pesanan = \App\Models\Pesanan::with([
            'detail' => function ($query) use ($vendorId) {
                // Filter hanya detail yang menu-nya milik vendor yang login
                $query->whereHas('menu', function ($q) use ($vendorId) {
                    $q->where('id_vendor', $vendorId);
                })->with('menu');
            }
        ])->where('id_pesanan', $id_pesanan)->first();

        if (!$pesanan) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Pesanan tidak ditemukan.'
            ], 404);
        }

        if ($pesanan->detail->isEmpty()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Pesanan ini tidak mengandung menu dari kantin kamu.'
            ], 404);
        }

        return response()->json([
            'status'  => 'success',
            'data'    => [
                'id_pesanan'    => $pesanan->id_pesanan,
                'nama_customer' => $pesanan->nama_customer,
                'status_bayar'  => $pesanan->status_bayar,
                'total'         => $pesanan->total,
                'detail'        => $pesanan->detail->map(function ($d) {
                    return [
                        'nama_menu' => $d->menu->nama_menu,
                        'harga'     => $d->menu->harga,
                        'jumlah'    => $d->jumlah,
                        'subtotal'  => $d->subtotal,
                    ];
                })
            ]
        ]);
    }
}
