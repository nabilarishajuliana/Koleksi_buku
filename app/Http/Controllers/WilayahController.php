<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WilayahController extends Controller
{
    private $baseUrl = 'https://www.emsifa.com/api-wilayah-indonesia/api';

    // Halaman tampilan
    public function index()
    {
        return view('ajax.wilayah');
    }

    // Ambil semua provinsi
    public function provinsi()
    {
        $response = Http::get("{$this->baseUrl}/provinces.json");

        return response()->json([
            'status'  => 'success',
            'code'    => 200,
            'message' => 'Data provinsi berhasil diambil',
            'data'    => $response->json()
        ]);
    }

    // Ambil kota berdasarkan id provinsi
    public function kota($id_provinsi)
    {
        $response = Http::get("{$this->baseUrl}/regencies/{$id_provinsi}.json");

        return response()->json([
            'status'  => 'success',
            'code'    => 200,
            'message' => 'Data kota berhasil diambil',
            'data'    => $response->json()
        ]);
    }

    // Ambil kecamatan berdasarkan id kota
    public function kecamatan($id_kota)
    {
        $response = Http::get("{$this->baseUrl}/districts/{$id_kota}.json");

        return response()->json([
            'status'  => 'success',
            'code'    => 200,
            'message' => 'Data kecamatan berhasil diambil',
            'data'    => $response->json()
        ]);
    }

    // Ambil kelurahan berdasarkan id kecamatan
    public function kelurahan($id_kecamatan)
    {
        $response = Http::get("{$this->baseUrl}/villages/{$id_kecamatan}.json");

        return response()->json([
            'status'  => 'success',
            'code'    => 200,
            'message' => 'Data kelurahan berhasil diambil',
            'data'    => $response->json()
        ]);
    }
}