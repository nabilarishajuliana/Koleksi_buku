@extends('layouts.dashboard')

@section('title', 'Barcode Scanner')

@section('content')

<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-barcode-scan"></i>
        </span>
        Barcode Scanner
    </h3>
</div>

{{-- Audio beep — tidak terlihat, dipanggil via JavaScript --}}
<audio id="beepSound" src="{{ asset('sounds/beep.mp3') }}" preload="auto"></audio>

<div class="row justify-content-center">
    <div class="col-md-8">

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">📷 Kamera Scanner</h5>
                <button type="button" id="btnReset" class="btn btn-sm btn-outline-secondary" style="display:none;">
                    🔄 Scan Lagi
                </button>
            </div>
            <div class="card-body text-center">

                {{-- Area kamera scanner --}}
                <div id="reader" style="width: 100%; max-width: 500px; margin: 0 auto;"></div>

                {{-- Status scanner --}}
                <div id="statusScanner" class="mt-3">
                    <span class="badge badge-info" style="font-size:0.85rem;">
                        📡 Scanner aktif — arahkan ke barcode
                    </span>
                </div>

            </div>
        </div>

        {{-- Card hasil scan — tersembunyi sampai ada hasil --}}
        <div class="card" id="cardHasil" style="display:none;">
            <div class="card-header bg-gradient-success text-white">
                <h5 class="mb-0">✅ Hasil Scan</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered mb-0">
                    <tr>
                        <th style="width:35%;">ID Barang</th>
                        <td id="hasilId">-</td>
                    </tr>
                    <tr>
                        <th>Nama Barang</th>
                        <td id="hasilNama">-</td>
                    </tr>
                    <tr>
                        <th>Harga</th>
                        <td id="hasilHarga">-</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Card error — muncul kalau barang tidak ditemukan --}}
        <div class="card border-danger" id="cardError" style="display:none;">
            <div class="card-body text-center text-danger">
                <h5>❌ Barang tidak ditemukan</h5>
                <p id="errorMessage" class="mb-0">ID barang tidak ada di database.</p>
            </div>
        </div>

    </div>
</div>

@endsection

@section('script')
{{-- Library html5-qrcode dari CDN --}}
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<script>
// ================================================================
// INISIALISASI SCANNER
// ================================================================
const html5QrCode = new Html5Qrcode("reader");
let sudahScan = false; // flag agar tidak scan berulang

// Konfigurasi scanner
const config = {
    fps    : 10,   // frame per second untuk scanning
    qrbox  : { width: 250, height: 100 }, // area scan (persegi panjang untuk barcode)
    formatsToSupport: [
        Html5QrcodeSupportedFormats.CODE_128,  // format barcode yang kita pakai
        Html5QrcodeSupportedFormats.EAN_13,
        Html5QrcodeSupportedFormats.QR_CODE,   // support QR code juga
    ]
};

// ================================================================
// FUNGSI SUKSES — dipanggil saat berhasil baca barcode
// ================================================================
function onScanSuccess(decodedText, decodedResult) {
    // Kalau sudah scan sebelumnya, abaikan
    if (sudahScan) return;
    sudahScan = true;

    // 1. Bunyi beep
    document.getElementById('beepSound').play();

    // 2. Hentikan scanner
    html5QrCode.stop().then(() => {
        console.log('Scanner berhenti.');
    });

    // 3. Update status
    document.getElementById('statusScanner').innerHTML = `
        <span class="badge badge-success" style="font-size:0.85rem;">
            ✅ Barcode terbaca: ${decodedText}
        </span>
    `;

    // 4. Tampilkan tombol Scan Lagi
    document.getElementById('btnReset').style.display = 'inline-block';

    // 5. Cari barang ke server via AJAX
    cariBarang(decodedText);
}

// ================================================================
// FUNGSI ERROR — dipanggil saat gagal baca (normal, diabaikan)
// ================================================================
function onScanError(errorMessage) {
    // Diabaikan — ini terjadi terus menerus saat belum ada barcode di frame
}

// ================================================================
// FUNGSI CARI BARANG KE SERVER
// ================================================================
function cariBarang(kode) {
    // Buat FormData seperti form biasa
    const formData = new FormData();
    formData.append('kode', kode);
    formData.append('_token', '{{ csrf_token() }}');

    fetch('/api/pos/cari-barang', {
        method : 'POST',
        body   : formData  // kirim sebagai form data, bukan JSON
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            document.getElementById('hasilId').textContent    = data.data.id_barang;
            document.getElementById('hasilNama').textContent  = data.data.nama_barang;
            document.getElementById('hasilHarga').textContent =
                'Rp ' + parseInt(data.data.harga).toLocaleString('id-ID');

            document.getElementById('cardHasil').style.display = 'block';
            document.getElementById('cardError').style.display = 'none';
        } else {
            document.getElementById('errorMessage').textContent =
                'ID "' + kode + '" tidak ditemukan di database.';
            document.getElementById('cardError').style.display  = 'block';
            document.getElementById('cardHasil').style.display  = 'none';
        }
    })
    .catch(err => {
        document.getElementById('errorMessage').textContent = 'Gagal menghubungi server.';
        document.getElementById('cardError').style.display  = 'block';
        document.getElementById('cardHasil').style.display  = 'none';
    });
}
// ================================================================
// TOMBOL SCAN LAGI — reset scanner
// ================================================================
document.getElementById('btnReset').addEventListener('click', function () {
    sudahScan = false;

    // Reset tampilan
    document.getElementById('cardHasil').style.display = 'none';
    document.getElementById('cardError').style.display = 'none';
    document.getElementById('btnReset').style.display  = 'none';
    document.getElementById('statusScanner').innerHTML = `
        <span class="badge badge-info" style="font-size:0.85rem;">
            📡 Scanner aktif — arahkan ke barcode
        </span>
    `;

    // Mulai scanner lagi
    html5QrCode.start(
        { facingMode: "environment" },
        config,
        onScanSuccess,
        onScanError
    );
});

// ================================================================
// MULAI SCANNER SAAT HALAMAN DIBUKA
// ================================================================
html5QrCode.start(
    { facingMode: "environment" }, // pakai kamera belakang
    config,
    onScanSuccess,
    onScanError
).catch(err => {
    document.getElementById('statusScanner').innerHTML = `
        <span class="badge badge-danger" style="font-size:0.85rem;">
            ❌ Tidak bisa akses kamera: ${err}
        </span>
    `;
});
</script>
@endsection