<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use Illuminate\Http\Request;

class PosController extends Controller
{
    // Halaman tampilan POS
    public function index()
    {
        return view('ajax.pos');
    }

    // Halaman POS versi Axios (TAMBAHKAN INI)
public function indexAxios()
{
    return view('ajax.pos_axios');
}

    // AJAX: cari barang berdasarkan kode
    // Dipanggil saat kasir tekan Enter di input kode barang
    public function cariBarang(Request $request)
    {
        $barang = Barang::where('id_barang', $request->kode)->first();

        if (!$barang) {
            return response()->json([
                'status'  => 'error',
                'code'    => 404,
                'message' => 'Barang tidak ditemukan',
                'data'    => null
            ], 404);
        }

        return response()->json([
            'status'  => 'success',
            'code'    => 200,
            'message' => 'Barang ditemukan',
            'data'    => [
                'id_barang'   => $barang->id_barang,
                'nama_barang' => $barang->nama_barang,
                'harga'       => $barang->harga,
            ]
        ]);
    }

    // AJAX: simpan transaksi ke database
    // Dipanggil saat kasir klik tombol Bayar
//     public function bayar(Request $request)
// {
//     $data = json_decode($request->getContent(), true);

//     if (!$data || !isset($data['items']) || !isset($data['total'])) {
//         return response()->json([
//             'status'  => 'error',
//             'message' => 'Data tidak valid',
//         ], 422);
//     }

//     $total = $data['total'];
//     $items = $data['items'];

//     // Simpan header penjualan
//     $penjualan = Penjualan::create([
//         'timestamp' => now(),
//         'total'     => $total,
//     ]);

//     // Simpan detail
//     foreach ($items as $item) {
//         // Cari barang berdasarkan id_barang (kode) untuk dapat id bigint
//         $barang = \App\Models\Barang::where('id_barang', $item['id_barang'])->first();

//         if (!$barang) {
//             // Hapus penjualan yang terlanjur dibuat jika ada barang tidak ditemukan
//             $penjualan->delete();
//             return response()->json([
//                 'status'  => 'error',
//                 'message' => 'Barang dengan kode ' . $item['id_barang'] . ' tidak ditemukan',
//             ], 404);
//         }

//         PenjualanDetail::create([
//             'id_penjualan' => $penjualan->id_penjualan,
//             'id_barang_fk' => $barang->id,  // ← id bigint dari tabel barang
//             'jumlah'       => $item['jumlah'],
//             'subtotal'     => $item['subtotal'],
//         ]);
//     }

//     return response()->json([
//         'status'  => 'success',
//         'code'    => 200,
//         'message' => 'Transaksi berhasil disimpan',
//         'data'    => [
//             'id_penjualan' => $penjualan->id_penjualan,
//         ]
//     ]);
// }


public function bayar(Request $request)
{
    $total = $request->input('total');
    $items = $request->input('items');

    if (!$total || !$items || count($items) === 0) {
        return response()->json([
            'status'  => 'error',
            'message' => 'Data tidak valid',
        ], 422);
    }

    $penjualan = Penjualan::create([
        'timestamp' => now(),
        'total'     => $total,
    ]);

    foreach ($items as $item) {
        $barang = \App\Models\Barang::where('id_barang', $item['id_barang'])->first();
        if (!$barang) continue;

        PenjualanDetail::create([
            'id_penjualan' => $penjualan->id_penjualan,
            'id_barang_fk' => $barang->id,
            'jumlah'       => $item['jumlah'],
            'subtotal'     => $item['subtotal'],
        ]);
    }

    return response()->json([
        'status'  => 'success',
        'code'    => 200,
        'message' => 'Transaksi berhasil disimpan',
        'data'    => ['id_penjualan' => $penjualan->id_penjualan]
    ]);
}
}
