@extends('layouts.dashboard')

@section('title', 'Kunjungan Toko')

@section('content')

<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-map-marker-radius"></i>
        </span>
        Kunjungan Toko
    </h3>
</div>

{{-- ALERT --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
</div>
@endif
@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show">
    {{ session('error') }}
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
</div>
@endif

<div class="row">

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- KOLOM KIRI: List Toko + Input Titik Awal                 --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="col-md-5">

        {{-- LIST TOKO --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">🏪 List Toko</h5>
            </div>
            <div class="card-body p-0">
                @if($tokoList->isEmpty())
                    <p class="text-muted text-center py-3">Belum ada toko.</p>
                @else
                <div class="table-responsive">
                    <table class="table table-bordered mb-0" style="font-size:0.85rem;">
                        <thead class="thead-light">
                            <tr>
                                <th>Barcode</th>
                                <th>Nama Toko</th>
                                <th>Lat</th>
                                <th>Lng</th>
                                <th>Acc</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tokoList as $toko)
                            <tr>
                                <td><code>{{ $toko->barcode }}</code></td>
                                <td>{{ $toko->nama_toko }}</td>
                                <td>{{ number_format($toko->latitude, 5) }}</td>
                                <td>{{ number_format($toko->longitude, 5) }}</td>
                                <td>{{ $toko->accuracy }}m</td>
                                <td>
                                    {{-- Tombol cetak barcode --}}
                                    <a href="{{ route('kunjungan_toko.cetak', $toko->barcode) }}"
                                        target="_blank"
                                        class="btn btn-xs btn-info mb-1" title="Cetak Barcode">
                                        <i class="mdi mdi-barcode"></i>
                                    </a>
                                    {{-- Tombol hapus --}}
                                    <form action="{{ route('kunjungan_toko.destroy', $toko->barcode) }}"
                                        method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-xs btn-danger"
                                            onclick="return confirm('Hapus toko ini?')"
                                            title="Hapus">
                                            <i class="mdi mdi-delete"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>

        {{-- INPUT TITIK AWAL --}}
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">📍 Input Titik Awal Toko</h5>
            </div>
            <div class="card-body">
                <form id="formToko" method="POST" action="{{ route('kunjungan_toko.store') }}">
                    @csrf

                    <div class="form-group mb-3">
                        <label>Kode Barcode <small class="text-muted">(maks 8 karakter)</small></label>
                        <input type="text" name="barcode" id="barcode"
                            class="form-control" required maxlength="8"
                            placeholder="contoh: TK000001"
                            style="text-transform:uppercase;">
                    </div>

                    <div class="form-group mb-3">
                        <label>Nama Toko</label>
                        <input type="text" name="nama_toko" id="nama_toko"
                            class="form-control" required maxlength="50"
                            placeholder="contoh: Toko Maju Jaya">
                    </div>

                    <div class="form-group mb-2">
                        <label>Latitude</label>
                        <input type="number" name="latitude" id="inputLatToko"
                            class="form-control" required step="any"
                            placeholder="contoh: -7.250445">
                    </div>

                    <div class="form-group mb-2">
                        <label>Longitude</label>
                        <input type="number" name="longitude" id="inputLngToko"
                            class="form-control" required step="any"
                            placeholder="contoh: 112.768845">
                    </div>

                    <div class="form-group mb-3">
                        <label>Accuracy (meter)</label>
                        <input type="number" name="accuracy" id="inputAccToko"
                            class="form-control" required step="any"
                            placeholder="contoh: 15">
                    </div>

                </form>

                {{-- Tombol LUAR form --}}
                <div class="d-flex gap-2">
                    <button type="button" id="btnGeolokToko" class="btn btn-info">
                        <i class="mdi mdi-crosshairs-gps"></i> Geoloc
                    </button>
                    <button type="button" id="btnSimpanToko" class="btn btn-primary">
                        <i class="mdi mdi-content-save"></i> Submit
                    </button>
                </div>

                {{-- Status ambil GPS --}}
                <div id="statusGeolokToko" class="mt-2" style="display:none; font-size:0.82rem;"></div>

            </div>
        </div>

    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- KOLOM KANAN: Titik Kunjungan                             --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">🗺️ Titik Kunjungan Sales</h5>
            </div>
            <div class="card-body">

                {{-- BARCODE SCANNER --}}
                <div class="mb-3">
                    <label class="font-weight-bold mb-2 d-block">
                        1. Scan Barcode Toko
                    </label>
                    <div id="readerKunjungan" style="max-width:100%;"></div>
                    <div id="statusScanKunjungan" class="mt-2">
                        <span class="badge badge-info">📡 Scanner aktif — arahkan ke barcode toko</span>
                    </div>
                    <button type="button" id="btnScanLagi" class="btn btn-sm btn-outline-secondary mt-2" style="display:none;">
                        🔄 Scan Lagi
                    </button>
                </div>

                <hr>

                {{-- HASIL SCAN + VERIFIKASI --}}
                <div id="hasilKunjungan" style="display:none;">

                    {{-- Data toko dari DB --}}
                    <label class="font-weight-bold mb-2 d-block">
                        2. Data Toko (dari Database)
                    </label>
                    <table class="table table-sm table-bordered mb-3">
                        <tr>
                            <th style="width:35%;">Barcode</th>
                            <td id="tokoBarcode">-</td>
                        </tr>
                        <tr>
                            <th>Nama Toko</th>
                            <td id="tokoNama">-</td>
                        </tr>
                        <tr>
                            <th>Latitude Toko</th>
                            <td id="tokoLat">-</td>
                        </tr>
                        <tr>
                            <th>Longitude Toko</th>
                            <td id="tokoLng">-</td>
                        </tr>
                        <tr>
                            <th>Accuracy Toko</th>
                            <td id="tokoAcc">-</td>
                        </tr>
                    </table>

                    {{-- Tombol ambil lokasi sales --}}
                    <label class="font-weight-bold mb-2 d-block">
                        3. Ambil Lokasi Sales Sekarang
                    </label>
                    <button type="button" id="btnAmbilLokasi" class="btn btn-warning mb-3">
                        <i class="mdi mdi-crosshairs-gps"></i> Ambil Lokasi
                    </button>
                    <div id="statusAmbilLokasi" style="display:none; font-size:0.82rem;" class="mb-3"></div>

                    {{-- Hasil verifikasi --}}
                    <div id="hasilVerifikasi" style="display:none;">
                        <label class="font-weight-bold mb-2 d-block">
                            4. Hasil Verifikasi Kunjungan
                        </label>
                        <table class="table table-sm table-bordered mb-3">
                            <tr>
                                <th style="width:45%;">Latitude Sales</th>
                                <td id="salesLat">-</td>
                            </tr>
                            <tr>
                                <th>Longitude Sales</th>
                                <td id="salesLng">-</td>
                            </tr>
                            <tr>
                                <th>Accuracy Sales</th>
                                <td id="salesAcc">-</td>
                            </tr>
                            <tr>
                                <th>Jarak Aktual</th>
                                <td id="jarakAktual">-</td>
                            </tr>
                            <tr>
                                <th>Threshold Efektif</th>
                                <td id="thresholdEfektif">-</td>
                            </tr>
                        </table>

                        {{-- Badge hasil DITERIMA / DITOLAK --}}
                        <div id="badgeHasil" class="text-center py-3" style="font-size:1.5rem; border-radius:8px;"></div>
                    </div>

                </div>

            </div>
        </div>
    </div>

</div>

@endsection

@section('script')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<audio id="beepSound" src="{{ asset('sounds/beep.mp3') }}" preload="auto"></audio>

<script>
// ================================================================
// VARIABEL STATE
// ================================================================
let sudahScan   = false;
let dataToko    = null;   // data toko yang berhasil di-scan
const THRESHOLD = 300;    // jarak maksimum dalam meter (bisa diubah)

// ================================================================
// FORMULA HAVERSINE — hitung jarak 2 titik GPS dalam meter
// ================================================================
function haversine(lat1, lng1, lat2, lng2) {
    const R    = 6371000;
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLng = (lng2 - lng1) * Math.PI / 180;
    const a    = Math.sin(dLat / 2) ** 2 +
                 Math.cos(lat1 * Math.PI / 180) *
                 Math.cos(lat2 * Math.PI / 180) *
                 Math.sin(dLng / 2) ** 2;
    const c    = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    return R * c; // meter
}

// ================================================================
// FUNGSI AMBIL GPS AKURAT (dari lampiran modul)
// Terus coba sampai accuracy ≤ targetAccuracy atau timeout
// ================================================================
function getAccuratePosition(targetAccuracy = 50, maxWait = 20000) {
    return new Promise((resolve, reject) => {
        let bestResult = null;
        const startTime = Date.now();

        const watchId = navigator.geolocation.watchPosition(
            (position) => {
                const acc = position.coords.accuracy;

                // Simpan hasil terbaik sejauh ini
                if (!bestResult || acc < bestResult.coords.accuracy) {
                    bestResult = position;
                }

                // Kalau sudah cukup akurat, berhenti
                if (acc <= targetAccuracy) {
                    navigator.geolocation.clearWatch(watchId);
                    resolve(bestResult);
                }

                // Kalau timeout, pakai hasil terbaik yang ada
                if (Date.now() - startTime >= maxWait) {
                    navigator.geolocation.clearWatch(watchId);
                    if (bestResult) resolve(bestResult);
                    else reject(new Error('Timeout, tidak dapat posisi'));
                }
            },
            (error) => reject(error),
            { enableHighAccuracy: true, maximumAge: 0, timeout: maxWait }
        );
    });
}

// ================================================================
// SCANNER BARCODE — untuk scan barcode toko
// ================================================================
const scanner = new Html5Qrcode('readerKunjungan');

const configScanner = {
    fps  : 10,
    qrbox: { width: 300, height: 100 },
    formatsToSupport: [Html5QrcodeSupportedFormats.CODE_128]
};

function onScanSuccess(decodedText) {
    if (sudahScan) return;
    sudahScan = true;

    // Bunyi beep
    document.getElementById('beepSound').play();

    // Hentikan scanner
    scanner.stop();

    // Update status
    document.getElementById('statusScanKunjungan').innerHTML =
        `<span class="badge badge-success">✅ Barcode terbaca: ${decodedText}</span>`;
    document.getElementById('btnScanLagi').style.display = 'inline-block';

    // Ambil data toko dari server
    fetch(`/api/toko/${decodedText}`)
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                dataToko = data.data;

                // Tampilkan data toko
                document.getElementById('tokoBarcode').textContent = dataToko.barcode;
                document.getElementById('tokoNama').textContent    = dataToko.nama_toko;
                document.getElementById('tokoLat').textContent     = dataToko.latitude;
                document.getElementById('tokoLng').textContent     = dataToko.longitude;
                document.getElementById('tokoAcc').textContent     = dataToko.accuracy + ' meter';

                document.getElementById('hasilKunjungan').style.display  = 'block';
                document.getElementById('hasilVerifikasi').style.display = 'none';
            } else {
                alert('❌ ' + data.message);
            }
        })
        .catch(() => alert('Gagal menghubungi server.'));
}

function onScanError(err) { /* diabaikan */ }

// Mulai scanner
scanner.start(
    { facingMode: 'environment' },
    configScanner,
    onScanSuccess,
    onScanError
).catch(err => {
    document.getElementById('statusScanKunjungan').innerHTML =
        `<span class="badge badge-danger">❌ Tidak bisa akses kamera</span>`;
});

// Tombol Scan Lagi
document.getElementById('btnScanLagi').addEventListener('click', function () {
    sudahScan = false;
    dataToko  = null;

    document.getElementById('hasilKunjungan').style.display  = 'none';
    document.getElementById('hasilVerifikasi').style.display = 'none';
    document.getElementById('btnScanLagi').style.display     = 'none';
    document.getElementById('statusScanKunjungan').innerHTML =
        '<span class="badge badge-info">📡 Scanner aktif — arahkan ke barcode toko</span>';

    scanner.start(
        { facingMode: 'environment' },
        configScanner,
        onScanSuccess,
        onScanError
    );
});

// ================================================================
// TOMBOL AMBIL LOKASI SALES
// ================================================================
document.getElementById('btnAmbilLokasi').addEventListener('click', async function () {
    if (!dataToko) {
        alert('Scan barcode toko dulu!');
        return;
    }

    const statusEl = document.getElementById('statusAmbilLokasi');
    statusEl.style.display  = 'block';
    statusEl.innerHTML      = '⏳ Mengambil lokasi... (tunggu sampai 20 detik)';
    this.disabled           = true;

    try {
        // Ambil GPS dengan akurasi terbaik
        const pos = await getAccuratePosition(50, 20000);

        const salesLat = pos.coords.latitude;
        const salesLng = pos.coords.longitude;
        const salesAcc = pos.coords.accuracy;

        statusEl.innerHTML = `✅ Lokasi berhasil diambil (accuracy: ${salesAcc.toFixed(1)}m)`;

        // ── HITUNG JARAK HAVERSINE ──────────────────────────────
        const jarakAktual = haversine(
            dataToko.latitude, dataToko.longitude,
            salesLat,          salesLng
        );

        // Threshold efektif = threshold + accuracy toko + accuracy sales
        const thresholdEfektif = THRESHOLD + parseFloat(dataToko.accuracy) + salesAcc;

        const diterima = jarakAktual <= thresholdEfektif;

        // ── TAMPILKAN HASIL ─────────────────────────────────────
        document.getElementById('salesLat').textContent        = salesLat.toFixed(6);
        document.getElementById('salesLng').textContent        = salesLng.toFixed(6);
        document.getElementById('salesAcc').textContent        = salesAcc.toFixed(1) + ' meter';
        document.getElementById('jarakAktual').textContent     = jarakAktual.toFixed(1) + ' meter';
        document.getElementById('thresholdEfektif').textContent = thresholdEfektif.toFixed(1) + ' meter';

        const badgeEl = document.getElementById('badgeHasil');
        if (diterima) {
            badgeEl.style.background = '#d4edda';
            badgeEl.style.color      = '#155724';
            badgeEl.innerHTML        = `✅ DITERIMA<br>
                <small style="font-size:0.8rem;">
                    Jarak ${jarakAktual.toFixed(0)}m ≤ Threshold ${thresholdEfektif.toFixed(0)}m
                </small>`;
        } else {
            badgeEl.style.background = '#f8d7da';
            badgeEl.style.color      = '#721c24';
            badgeEl.innerHTML        = `❌ DITOLAK<br>
                <small style="font-size:0.8rem;">
                    Jarak ${jarakAktual.toFixed(0)}m > Threshold ${thresholdEfektif.toFixed(0)}m
                </small>`;
        }

        document.getElementById('hasilVerifikasi').style.display = 'block';

    } catch (err) {
        statusEl.innerHTML = '❌ Gagal ambil lokasi: ' + err.message;
    } finally {
        this.disabled = false;
    }
});

// ================================================================
// TOMBOL GEOLOC — untuk input titik awal toko
// ================================================================
document.getElementById('btnGeolokToko').addEventListener('click', async function () {
    const statusEl = document.getElementById('statusGeolokToko');
    statusEl.style.display = 'block';
    statusEl.innerHTML     = '⏳ Mengambil koordinat GPS...';
    this.disabled          = true;

    try {
        const pos = await getAccuratePosition(50, 20000);

        document.getElementById('inputLatToko').value = pos.coords.latitude;
        document.getElementById('inputLngToko').value = pos.coords.longitude;
        document.getElementById('inputAccToko').value = pos.coords.accuracy.toFixed(1);

        statusEl.innerHTML = `✅ Koordinat berhasil diambil (accuracy: ${pos.coords.accuracy.toFixed(1)}m)`;
    } catch (err) {
        statusEl.innerHTML = '❌ Gagal: ' + err.message;
    } finally {
        this.disabled = false;
    }
});

// ================================================================
// TOMBOL SUBMIT FORM TOKO (dengan spinner)
// ================================================================
$(function () {
    $('#btnSimpanToko').click(function () {
        const form = document.getElementById('formToko');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }
        $(this).prop('disabled', true)
            .html('<span class="spinner-border spinner-border-sm mr-1"></span> Menyimpan...');
        form.submit();
    });
});
</script>
@endsection