<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomerDataController extends Controller
{
    // ── Halaman daftar semua customer ──────────────────────────
    public function index()
    {
        $customers = Customer::latest()->get();
        return view('customer_data.index', compact('customers'));
    }

    // ── Halaman form Tambah Customer 1 (BLOB) ─────────────────
    public function createBlob()
    {
        return view('customer_data.tambah1');
    }

    // ── Simpan Customer 1 (foto sebagai BLOB di database) ──────
    public function storeBlob(Request $request)
    {
        $request->validate([
            'nama'              => 'required|string|max:100',
            'alamat'            => 'required|string',
            'provinsi'          => 'required|string',
            'kota'              => 'required|string',
            'kecamatan'         => 'required|string',
            'kodepos_kelurahan' => 'required|string',
            'foto_base64'       => 'required|string', // foto dari kamera dalam base64
        ]);

        // Foto dari kamera dikirim sebagai base64 string
        // Format: "data:image/png;base64,iVBORw0KGgo..."
        // Kita ambil hanya bagian setelah koma
        $base64String = $request->foto_base64;
        $base64Data   = substr($base64String, strpos($base64String, ',') + 1);

        // Decode base64 jadi binary — ini yang disimpan sebagai BLOB
        $fotoBinary = base64_decode($base64Data);

        Customer::create([
            'nama'              => $request->nama,
            'alamat'            => $request->alamat,
            'provinsi'          => $request->provinsi,
            'kota'              => $request->kota,
            'kecamatan'         => $request->kecamatan,
            'kodepos_kelurahan' => $request->kodepos_kelurahan,
            'foto_blob'         => $fotoBinary, // binary langsung ke DB
        ]);

        return redirect()->route('customer.index')
            ->with('success', 'Customer berhasil ditambahkan (BLOB).');
    }

    // ── Halaman form Tambah Customer 2 (File) ─────────────────
    public function createFile()
    {
        return view('customer_data.tambah2');
    }

    // ── Simpan Customer 2 (foto sebagai file di storage) ───────
    public function storeFile(Request $request)
    {
        $request->validate([
            'nama'              => 'required|string|max:100',
            'alamat'            => 'required|string',
            'provinsi'          => 'required|string',
            'kota'              => 'required|string',
            'kecamatan'         => 'required|string',
            'kodepos_kelurahan' => 'required|string',
            'foto_base64'       => 'required|string',
        ]);

        $base64String = $request->foto_base64;
        $base64Data   = substr($base64String, strpos($base64String, ',') + 1);

        // Generate nama file unik
        $namaFile = 'customer_' . time() . '_' . uniqid() . '.png';

        // Simpan file ke storage/app/public/customers/
        Storage::disk('public')->put(
            'customers/' . $namaFile,
            base64_decode($base64Data)
        );

        // Yang disimpan di DB hanya path-nya
        $fotoPath = 'customers/' . $namaFile;

        Customer::create([
            'nama'              => $request->nama,
            'alamat'            => $request->alamat,
            'provinsi'          => $request->provinsi,
            'kota'              => $request->kota,
            'kecamatan'         => $request->kecamatan,
            'kodepos_kelurahan' => $request->kodepos_kelurahan,
            'foto_path'         => $fotoPath, // hanya path, bukan binary
        ]);

        return redirect()->route('customer.index')
            ->with('success', 'Customer berhasil ditambahkan (File).');
    }

    // ── Tampilkan foto BLOB sebagai gambar ─────────────────────
    // Dipakai di view untuk <img src="{{ route('customer.foto', $c->id) }}">
    public function fotoBlob($id)
    {
        $customer = Customer::findOrFail($id);

        if (!$customer->foto_blob) {
            abort(404);
        }

        return response($customer->foto_blob)
            ->header('Content-Type', 'image/png');
    }
}