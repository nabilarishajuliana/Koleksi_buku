<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Vendor;
use App\Models\Pesanan;
use App\Models\PesananDetail;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CustomerController extends Controller
{
    // ── Halaman pemesanan (tidak perlu login) ──────────────────
    public function index()
    {
        $vendors = Vendor::all();
        return view('customer.pesan', compact('vendors'));
    }

    // ── AJAX: ambil menu berdasarkan vendor ────────────────────
    public function getMenu($id_vendor)
    {
        $menu = Menu::where('id_vendor', $id_vendor)->get();

        return response()->json([
            'status' => 'success',
            'data'   => $menu
        ]);
    }

    // ── AJAX: buat pesanan + generate token Midtrans ───────────
    // public function checkout(Request $request)
    // {
    //     $request->validate([
    //         'total' => 'required|numeric|min:1',
    //         'items' => 'required|array|min:1',
    //     ]);

    //     // Generate nama customer otomatis: Guest_0000001
    //     $lastPesanan   = Pesanan::latest('id_pesanan')->first();
    //     $nextNumber    = $lastPesanan ? $lastPesanan->id_pesanan + 1 : 1;
    //     $namaCustomer  = 'Guest_' . str_pad($nextNumber, 7, '0', STR_PAD_LEFT);

    //     // Simpan pesanan
    //     $pesanan = Pesanan::create([
    //         'nama_customer' => $namaCustomer,
    //         'total'         => $request->total,
    //         'status_bayar'  => 'pending',
    //     ]);

    //     // Simpan detail pesanan
    //     foreach ($request->items as $item) {
    //         PesananDetail::create([
    //             'id_pesanan' => $pesanan->id_pesanan,
    //             'id_menu'    => $item['id_menu'],
    //             'jumlah'     => $item['jumlah'],
    //             'subtotal'   => $item['subtotal'],
    //         ]);
    //     }

    //     // Buat order_id unik untuk Midtrans
    //     $orderId = 'ORDER-' . $pesanan->id_pesanan . '-' . time();

    //     // Simpan ke tabel pembayaran
    //     Pembayaran::create([
    //         'id_pesanan'        => $pesanan->id_pesanan,
    //         'midtrans_order_id' => $orderId,
    //         'status'            => 'pending',
    //         'jumlah_bayar'      => $request->total,
    //     ]);

    //     // Setup Midtrans
    //     Config::$serverKey    = config('midtrans.server_key');
    //     Config::$isProduction = config('midtrans.is_production');
    //     Config::$isSanitized  = true;
    //     Config::$is3ds        = true;

    //     // Parameter yang dikirim ke Midtrans
    //     $params = [
    //         'transaction_details' => [
    //             'order_id'     => $orderId,
    //             'gross_amount' => (int) $request->total,
    //         ],
    //         'customer_details' => [
    //             'first_name' => $namaCustomer,
    //         ],
    //         // Item details untuk ditampilkan di halaman Midtrans
    //         'item_details' => array_map(function ($item) {
    //             return [
    //                 'id'       => $item['id_menu'],
    //                 'price'    => $item['harga'],
    //                 'quantity' => $item['jumlah'],
    //                 'name'     => $item['nama_menu'],
    //             ];
    //         }, $request->items),
    //     ];

    //     // Generate Snap Token dari Midtrans
    //     $snapToken = Snap::getSnapToken($params);

    //     // Simpan snap token ke tabel pesanan
    //     $pesanan->update(['snap_token' => $snapToken]);

    //     return response()->json([
    //         'status'     => 'success',
    //         'snap_token' => $snapToken,
    //         'order_id'   => $orderId,
    //         'customer'   => $namaCustomer,
    //     ]);
    // }

    public function checkout(Request $request)
    {
        $request->validate([
            'total' => 'required|numeric|min:1',
            'items' => 'required|array|min:1',
        ]);

        // Generate nama customer otomatis: Guest_0000001
        $lastPesanan  = Pesanan::latest('id_pesanan')->first();
        $nextNumber   = $lastPesanan ? $lastPesanan->id_pesanan + 1 : 1;
        $namaCustomer = 'Guest_' . str_pad($nextNumber, 7, '0', STR_PAD_LEFT);

        // Simpan pesanan
        $pesanan = Pesanan::create([
            'nama_customer' => $namaCustomer,
            'total'         => $request->total,
            'status_bayar'  => 'pending',
        ]);

        // Simpan detail pesanan
        foreach ($request->items as $item) {
            PesananDetail::create([
                'id_pesanan' => $pesanan->id_pesanan,
                'id_menu'    => $item['id_menu'],
                'jumlah'     => $item['jumlah'],
                'subtotal'   => $item['subtotal'],
            ]);
        }

        // Buat order_id unik untuk Midtrans
        $orderId = 'ORDER-' . $pesanan->id_pesanan . '-' . time();

        // Simpan ke tabel pembayaran
        Pembayaran::create([
            'id_pesanan'        => $pesanan->id_pesanan,
            'midtrans_order_id' => $orderId,
            'status'            => 'pending',
            'jumlah_bayar'      => $request->total,
        ]);

        // Setup Midtrans
        Config::$serverKey    = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized  = true;
        Config::$is3ds        = true;

        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => (int) $request->total,
            ],
            'customer_details' => [
                'first_name' => $namaCustomer,
            ],
            'item_details' => array_map(function ($item) {
                return [
                    'id'       => $item['id_menu'],
                    'price'    => $item['harga'],
                    'quantity' => $item['jumlah'],
                    'name'     => $item['nama_menu'],
                ];
            }, $request->items),
        ];

        $snapToken = Snap::getSnapToken($params);
        $pesanan->update(['snap_token' => $snapToken]);

        // ── GENERATE QR CODE ──────────────────────────────────────
        // QR code berisi id_pesanan
        // Di-encode ke base64 PNG agar bisa ditampilkan sebagai <img> di frontend
        $qrCodeSvg = QrCode::format('svg')
    ->size(200)
    ->errorCorrection('H')
    ->generate((string) $pesanan->id_pesanan);

$qrCodeBase64 = 'data:image/svg+xml;base64,' . base64_encode($qrCodeSvg);
        // ─────────────────────────────────────────────────────────

        return response()->json([
            'status'     => 'success',
            'snap_token' => $snapToken,
            'order_id'   => $orderId,
            'customer'   => $namaCustomer,
            'id_pesanan' => $pesanan->id_pesanan,  // ← tambahkan ini
            'qr_code'    => $qrCodeBase64,          // ← tambahkan ini
        ]);
    }

    // ── Webhook: dipanggil Midtrans otomatis setelah bayar ─────
    // Ini yang mengubah status jadi "lunas"
    public function webhook(Request $request)
    {

        $serverKey    = config('midtrans.server_key');
        $orderId      = $request->order_id;
        $statusCode   = $request->status_code;
        $grossAmount  = $request->gross_amount;

        // Validasi signature dari Midtrans (keamanan)
        $signatureKey = hash(
            'sha512',
            $orderId . $statusCode . $grossAmount . $serverKey
        );

        if ($signatureKey !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // Cari data pembayaran
        $pembayaran = Pembayaran::where('midtrans_order_id', $orderId)->first();

        if (!$pembayaran) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Update status berdasarkan notifikasi Midtrans
        $transactionStatus = $request->transaction_status;
        $paymentType       = $request->payment_type;

        if ($transactionStatus === 'settlement' || $transactionStatus === 'capture') {
            // Pembayaran berhasil → update status jadi lunas
            $pembayaran->update([
                'status'       => 'settlement',
                'payment_type' => $paymentType,
            ]);

            $pembayaran->pesanan->update(['status_bayar' => 'lunas']);
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $pembayaran->update(['status' => $transactionStatus]);
        }

        return response()->json(['message' => 'OK']);
    }
}
