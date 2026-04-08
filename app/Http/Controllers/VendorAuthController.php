<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class VendorAuthController extends Controller
{
    // Tampilkan form login vendor
    public function showLogin()
    {
        // Kalau sudah login, redirect ke dashboard
        if (session('vendor_id')) {
            return redirect()->route('vendor.dashboard');
        }

        return view('vendor.login');
    }

    // Proses login vendor
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $vendor = Vendor::where('email', $request->email)->first();

        if (!$vendor || !Hash::check($request->password, $vendor->password)) {
            return back()->with('error', 'Email atau password salah.');
        }

        // Simpan data vendor ke session
        session([
            'vendor_id'   => $vendor->id_vendor,
            'vendor_nama' => $vendor->nama_vendor,
            'vendor_email'=> $vendor->email,
        ]);

        return redirect()->route('vendor.dashboard')
            ->with('success', 'Selamat datang, ' . $vendor->nama_vendor . '!');
    }

    // Logout vendor
    public function logout()
    {
        session()->forget(['vendor_id', 'vendor_nama', 'vendor_email']);
        return redirect()->route('vendor.login')
            ->with('success', 'Berhasil logout.');
    }
}