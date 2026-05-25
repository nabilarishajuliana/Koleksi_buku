<?php

namespace App\Http\Controllers;

use App\Models\Antrian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AntrianController extends Controller
{
    // ── Halaman Guest: form daftar antrian ─────────────────────
    public function guest()
    {
        return view('antrian.guest');
    }

    // ── Proses daftar antrian dari Guest ───────────────────────
    public function daftar(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:50'
        ]);

        // Hitung nomor antrian berikutnya
        // Ambil nomor terakhir dari DB hari ini
        $nomorTerakhir = Antrian::whereDate('created_at', today())
            ->max('nomor_antrian') ?? 0;

        $nomorBaru = $nomorTerakhir + 1;

        // Simpan ke database
        $antrian = Antrian::create([
            'nomor_antrian' => $nomorBaru,
            'nama'          => $request->nama,
            'status'        => 'menunggu',
            'waktu_daftar'  => now(),
        ]);

        // Update Cache — beri tahu semua SSE client ada antrian baru
        $this->updateCache();

        // Redirect ke halaman tiket guest di tab baru
        // (di view nanti kita pakai JavaScript untuk open tab baru)
        return response()->json([
            'status'        => 'success',
            'nomor_antrian' => $nomorBaru,
            'nama'          => $request->nama,
            'id'            => $antrian->id,
        ]);
    }

    // ── Halaman tiket antrian guest ────────────────────────────
    public function tiket($id)
    {
        $antrian = Antrian::findOrFail($id);
        return view('antrian.tiket', compact('antrian'));
    }

    // ── Halaman Admin: kelola antrian ──────────────────────────
    public function admin()
    {
        $antrian = Antrian::whereIn('status', ['menunggu', 'dipanggil'])
            ->whereDate('created_at', today())
            ->orderBy('nomor_antrian')
            ->get();

        $terlambat = Antrian::where('status', 'terlambat')
            ->whereDate('created_at', today())
            ->orderBy('nomor_antrian')
            ->get();

        return view('antrian.admin', compact('antrian', 'terlambat'));
    }

    // ── Admin: panggil nomor antrian berikutnya ────────────────
    public function panggil(Request $request)
    {
        // Kalau ada yang sedang dipanggil tapi tidak hadir
        // → tandai sebagai terlambat dulu
        $sedangDipanggil = Antrian::where('status', 'dipanggil')
            ->whereDate('created_at', today())
            ->first();

        if ($sedangDipanggil) {
            $sedangDipanggil->update(['status' => 'terlambat']);
        }

        // Ambil antrian berikutnya yang masih menunggu
        $berikutnya = Antrian::where('status', 'menunggu')
            ->whereDate('created_at', today())
            ->orderBy('nomor_antrian')
            ->first();

        if (!$berikutnya) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Tidak ada antrian yang menunggu.'
            ]);
        }

        // Update status jadi dipanggil
        $berikutnya->update([
            'status'        => 'dipanggil',
            'waktu_panggil' => now(),
        ]);

        // Update Cache → SSE akan kirim update ke semua client
        $this->updateCache();

        return response()->json([
            'status'        => 'success',
            'nomor_antrian' => $berikutnya->nomor_antrian,
            'nama'          => $berikutnya->nama,
        ]);
    }

    // ── Admin: panggil ulang yang terlambat ───────────────────
    public function panggilTerlambat(Request $request, $id)
    {
        $antrian = Antrian::findOrFail($id);

        // Yang sedang dipanggil → terlambat dulu
        $sedangDipanggil = Antrian::where('status', 'dipanggil')
            ->whereDate('created_at', today())
            ->first();

        if ($sedangDipanggil) {
            $sedangDipanggil->update(['status' => 'terlambat']);
        }

        // Panggil yang terlambat ini
        $antrian->update([
            'status'        => 'dipanggil',
            'waktu_panggil' => now(),
        ]);

        $this->updateCache();

        return response()->json([
            'status'        => 'success',
            'nomor_antrian' => $antrian->nomor_antrian,
            'nama'          => $antrian->nama,
        ]);
    }

    // ── Halaman Papan Antrian (layar publik) ───────────────────
    public function papan()
    {
        return view('antrian.papan');
    }

    // ── SSE Stream — endpoint yang terus terbuka ───────────────
    // Browser connect ke sini dan terima update real-time
    public function stream(Request $request)
    {
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        set_time_limit(0);
        ini_set('output_buffering', 'off');
        ini_set('zlib.output_compression', false);
        
        return response()->stream(function () {

            // Kirim data awal saat client pertama connect
            $data = $this->getAntrianData();
            echo 'event: queue-update' . PHP_EOL;
            echo 'data: ' . json_encode($data) . PHP_EOL;
            echo PHP_EOL;
            ob_flush();
            flush();

            // Simpan waktu terakhir update yang sudah dikirim
            $lastVersion = Cache::get('antrian_version', 0);

            // Loop terus sampai client disconnect
            while (true) {

                // Cek apakah ada update baru di Cache
                $currentVersion = Cache::get('antrian_version', 0);

                if ($currentVersion !== $lastVersion) {
                    // Ada update baru → kirim ke client
                    $lastVersion = $currentVersion;
                    $data        = $this->getAntrianData();

                    echo 'event: queue-update' . PHP_EOL;
                    echo 'data: ' . json_encode($data) . PHP_EOL;
                    echo PHP_EOL;
                    ob_flush();
                    flush();
                }

                // Keep-alive ping setiap loop
                // Mencegah koneksi timeout
                echo ': keep-alive' . PHP_EOL;
                echo PHP_EOL;
                ob_flush();
                flush();

                // Cek apakah client masih terhubung
                if (connection_aborted()) break;

                sleep(1); // cek setiap 1 detik
            }
        }, 200, [
            'Content-Type'      => 'text/event-stream',
            'Cache-Control'     => 'no-cache',
            'X-Accel-Buffering' => 'no', // penting untuk Nginx
            'Connection'        => 'keep-alive',
        ]);
    }

    // ── Helper: ambil data antrian terkini ─────────────────────
    private function getAntrianData()
    {
        $menunggu = Antrian::where('status', 'menunggu')
            ->whereDate('created_at', today())
            ->orderBy('nomor_antrian')
            ->get(['id', 'nomor_antrian', 'nama', 'status']);

        $dipanggil = Antrian::where('status', 'dipanggil')
            ->whereDate('created_at', today())
            ->orderBy('waktu_panggil', 'desc')
            ->first();

        $terlambat = Antrian::where('status', 'terlambat')
            ->whereDate('created_at', today())
            ->orderBy('nomor_antrian')
            ->get(['id', 'nomor_antrian', 'nama', 'status']);

        return [
            'menunggu'  => $menunggu,
            'dipanggil' => $dipanggil,
            'terlambat' => $terlambat,
            'total'     => $menunggu->count(),
            'timestamp' => now()->toTimeString(),
        ];
    }

    // ── Helper: update Cache version → trigger SSE update ──────
    private function updateCache()
    {
        // Increment version number → SSE stream akan deteksi perubahan
        $version = Cache::get('antrian_version', 0);
        Cache::put('antrian_version', $version + 1, now()->addHours(24));
    }
}
