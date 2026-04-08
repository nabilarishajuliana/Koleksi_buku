<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VendorAuth
{
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah vendor sudah login via session
        if (!session('vendor_id')) {
            return redirect()->route('vendor.login')
                ->with('error', 'Silahkan login terlebih dahulu.');
        }

        return $next($request);
    }
}