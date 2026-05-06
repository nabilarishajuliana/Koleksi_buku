<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>QR Scanner — {{ session('vendor_nama') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --primary:       #5B4FCF;
            --primary-light: #EEF0FF;
            --success:       #10B981;
            --success-light: #ECFDF5;
            --danger:        #E53E3E;
            --danger-light:  #FFF5F5;
            --warning:       #F59E0B;
            --text:          #1A1A2E;
            --muted:         #6B7280;
            --border:        #E5E7EB;
            --bg:            #F3F4F8;
            --white:         #FFFFFF;
            --sidebar-w:     240px;
            --navbar-h:      60px;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
        }

        /* NAVBAR */
        .navbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            height: var(--navbar-h);
            background: var(--white);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem 0 calc(var(--sidebar-w) + 1.5rem);
            z-index: 100;
        }

        .navbar-brand {
            position: fixed;
            left: 0;
            width: var(--sidebar-w);
            height: var(--navbar-h);
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--primary);
            background: var(--white);
            border-bottom: 1px solid var(--border);
            border-right: 1px solid var(--border);
            gap: 0.5rem;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .vendor-badge { display: flex; align-items: center; gap: 0.6rem; }

        .vendor-avatar {
            width: 34px; height: 34px;
            background: var(--primary-light);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem; font-weight: 700; color: var(--primary);
        }

        .vendor-info strong { display: block; font-size: 0.85rem; font-weight: 600; line-height: 1.2; }
        .vendor-info span { font-size: 0.75rem; color: var(--muted); }

        .btn-logout {
            padding: 0.4rem 0.9rem;
            border: 1.5px solid var(--border);
            border-radius: 8px;
            background: transparent;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--muted);
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-logout:hover { border-color: var(--danger); color: var(--danger); background: var(--danger-light); }

        /* SIDEBAR */
        .sidebar {
            position: fixed;
            top: var(--navbar-h); left: 0;
            width: var(--sidebar-w);
            height: calc(100vh - var(--navbar-h));
            background: var(--white);
            border-right: 1px solid var(--border);
            padding: 1.25rem 0.75rem;
            overflow-y: auto;
        }

        .sidebar-label {
            font-size: 0.68rem; font-weight: 700;
            letter-spacing: 0.1em; text-transform: uppercase;
            color: var(--muted);
            padding: 0 0.75rem;
            margin-bottom: 0.5rem; margin-top: 1rem;
        }

        .sidebar-label:first-child { margin-top: 0; }

        .nav-item {
            display: flex; align-items: center; gap: 0.65rem;
            padding: 0.6rem 0.75rem; border-radius: 8px;
            font-size: 0.875rem; font-weight: 500;
            color: var(--muted); text-decoration: none;
            transition: all 0.15s; margin-bottom: 0.1rem;
        }

        .nav-item:hover { background: var(--bg); color: var(--text); }
        .nav-item.active { background: var(--primary-light); color: var(--primary); font-weight: 600; }
        .nav-item .icon { font-size: 1rem; width: 20px; text-align: center; }

        /* MAIN */
        .main {
            margin-left: var(--sidebar-w);
            margin-top: var(--navbar-h);
            padding: 2rem;
        }

        .page-header { margin-bottom: 1.5rem; }
        .page-header h2 { font-size: 1.35rem; font-weight: 700; letter-spacing: -0.02em; }
        .page-header p { font-size: 0.85rem; color: var(--muted); margin-top: 0.2rem; }

        /* CARD */
        .card {
            background: var(--white);
            border-radius: 14px;
            border: 1px solid var(--border);
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
            margin-bottom: 1.25rem;
        }

        .card-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
        }

        .card-header h5 { font-size: 0.95rem; font-weight: 700; }
        .card-body { padding: 1.25rem; }

        /* SCANNER */
        #reader {
            width: 100%;
            max-width: 480px;
            margin: 0 auto;
            border-radius: 12px;
            overflow: hidden;
        }

        /* HASIL */
        .info-row {
            display: flex; justify-content: space-between;
            padding: 0.6rem 0; border-bottom: 1px solid var(--border);
            font-size: 0.875rem;
        }

        .info-row:last-child { border-bottom: none; }
        .info-label { color: var(--muted); }
        .info-value { font-weight: 600; }

        .badge-lunas {
            display: inline-flex; align-items: center;
            padding: 0.2rem 0.65rem; border-radius: 100px;
            font-size: 0.72rem; font-weight: 600;
            background: var(--success-light); color: var(--success);
        }

        .badge-pending {
            display: inline-flex; align-items: center;
            padding: 0.2rem 0.65rem; border-radius: 100px;
            font-size: 0.72rem; font-weight: 600;
            background: #FFF7ED; color: #C2410C;
        }

        table { width: 100%; border-collapse: collapse; font-size: 0.85rem; }
        thead th {
            padding: 0.6rem 0.75rem;
            font-size: 0.72rem; font-weight: 500;
            text-transform: uppercase; letter-spacing: 0.05em;
            color: var(--muted); background: var(--bg);
            border-bottom: 1px solid var(--border); text-align: left;
        }
        tbody td { padding: 0.7rem 0.75rem; border-bottom: 1px solid var(--border); }
        tbody tr:last-child td { border-bottom: none; }

        .btn-reset {
            display: inline-flex; align-items: center; gap: 0.4rem;
            padding: 0.5rem 1rem; border-radius: 8px;
            background: var(--primary-light); color: var(--primary);
            border: none; font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.85rem; font-weight: 600; cursor: pointer;
            transition: all 0.2s;
        }

        .btn-reset:hover { background: var(--primary); color: white; }

        .status-badge {
            display: inline-flex; align-items: center; gap: 0.4rem;
            padding: 0.4rem 0.9rem; border-radius: 100px;
            font-size: 0.82rem; font-weight: 600;
        }

        .status-scanning { background: #EFF6FF; color: #1D4ED8; }
        .status-success  { background: var(--success-light); color: var(--success); }
        .status-error    { background: var(--danger-light); color: var(--danger); }
    </style>
</head>
<body>

{{-- Audio beep --}}
<audio id="beepSound" src="{{ asset('sounds/beep.mp3') }}" preload="auto"></audio>

{{-- NAVBAR --}}
<div class="navbar-brand">🐱 Catto Canteen</div>
<nav class="navbar">
    <div></div>
    <div class="navbar-right">
        <div class="vendor-badge">
            <div class="vendor-avatar">{{ substr(session('vendor_nama'), 0, 1) }}</div>
            <div class="vendor-info">
                <strong>{{ session('vendor_nama') }}</strong>
                <span>Vendor</span>
            </div>
        </div>
        <form method="POST" action="{{ route('vendor.logout') }}" style="margin:0;">
            @csrf
            <button type="submit" class="btn-logout">Logout</button>
        </form>
    </div>
</nav>

{{-- SIDEBAR --}}
<aside class="sidebar">
    <div class="sidebar-label">Menu</div>
    <a href="{{ route('vendor.dashboard') }}" class="nav-item">
        <span class="icon">🏠</span> Dashboard
    </a>
    <a href="{{ route('vendor.qr_scanner') }}" class="nav-item active">
        <span class="icon">📷</span> QR Scanner
    </a>
    <a href="{{ url('/pesan') }}" target="_blank" class="nav-item">
        <span class="icon">🛒</span> Halaman Pesan
    </a>
</aside>

{{-- MAIN --}}
<main class="main">

    <div class="page-header">
        <h2>QR Code Scanner</h2>
        <p>Scan QR Code customer untuk melihat detail pesanan</p>
    </div>

    <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">

        {{-- KOLOM KIRI: SCANNER --}}
        <div>
            <div class="card">
                <div class="card-header">
                    <h5>📷 Kamera Scanner</h5>
                    <button type="button" id="btnReset" class="btn-reset" style="display:none;">
                        🔄 Scan Lagi
                    </button>
                </div>
                <div class="card-body">
                    <div id="reader"></div>
                    <div style="text-align:center; margin-top:1rem;">
                        <span id="statusBadge" class="status-badge status-scanning">
                            📡 Scanner aktif
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: HASIL --}}
        <div>
            {{-- Default: instruksi --}}
            <div class="card" id="cardInstruksi">
                <div class="card-body" style="text-align:center; padding:3rem 1.5rem;">
                    <div style="font-size:3rem; margin-bottom:1rem;">📱</div>
                    <p style="color:var(--muted); font-size:0.875rem;">
                        Arahkan kamera ke QR Code customer.<br>
                        Hasil scan akan tampil di sini.
                    </p>
                </div>
            </div>

            {{-- Hasil sukses --}}
            <div id="cardHasil" style="display:none;">
                <div class="card mb-3">
                    <div class="card-header">
                        <h5>📋 Info Pesanan</h5>
                    </div>
                    <div class="card-body">
                        <div class="info-row">
                            <span class="info-label">ID Pesanan</span>
                            <span class="info-value" id="hasilIdPesanan">-</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Customer</span>
                            <span class="info-value" id="hasilCustomer">-</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Status Bayar</span>
                            <span id="hasilStatus">-</span>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5>🍽️ Menu Dipesan</h5>
                    </div>
                    <div class="card-body" style="padding:0;">
                        <table>
                            <thead>
                                <tr>
                                    <th>Menu</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody id="hasilDetail"></tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Error --}}
            <div class="card" id="cardError" style="display:none; border-color:var(--danger);">
                <div class="card-body" style="text-align:center; padding:2rem;">
                    <div style="font-size:2rem; margin-bottom:0.5rem;">❌</div>
                    <p id="errorMsg" style="color:var(--danger); font-size:0.875rem;"></p>
                </div>
            </div>
        </div>

    </div>
</main>

<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
const html5QrCode = new Html5Qrcode("reader");
let sudahScan = false;

const config = {
    fps   : 10,
    qrbox : { width: 250, height: 250 },
    formatsToSupport: [Html5QrcodeSupportedFormats.QR_CODE]
};

function formatRupiah(angka) {
    return 'Rp ' + parseInt(angka).toLocaleString('id-ID');
}

// Berhasil scan QR Code
function onScanSuccess(decodedText) {
    if (sudahScan) return;
    sudahScan = true;

    // Bunyi beep
    document.getElementById('beepSound').play();

    // Hentikan scanner
    html5QrCode.stop();

    // Update status
    document.getElementById('statusBadge').className = 'status-badge status-success';
    document.getElementById('statusBadge').textContent = '✅ QR terbaca: #' + decodedText;
    document.getElementById('btnReset').style.display = 'inline-flex';

    // Sembunyikan semua card hasil dulu
    document.getElementById('cardInstruksi').style.display = 'none';
    document.getElementById('cardHasil').style.display     = 'none';
    document.getElementById('cardError').style.display     = 'none';

    // Ambil data pesanan dari server
    fetch(`/vendor/api/pesanan/${decodedText}`, {
        method : 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            const d = data.data;

            document.getElementById('hasilIdPesanan').textContent = '#' + d.id_pesanan;
            document.getElementById('hasilCustomer').textContent  = d.nama_customer;

            // Badge status bayar
            const statusEl = document.getElementById('hasilStatus');
            if (d.status_bayar === 'lunas') {
                statusEl.innerHTML = '<span class="badge-lunas">✅ Lunas</span>';
            } else {
                statusEl.innerHTML = '<span class="badge-pending">⏳ Pending</span>';
            }

            // Render detail menu
            const tbody = document.getElementById('hasilDetail');
            tbody.innerHTML = '';
            d.detail.forEach(function (item) {
                tbody.innerHTML += `
                    <tr>
                        <td><strong>${item.nama_menu}</strong></td>
                        <td>× ${item.jumlah}</td>
                        <td style="color:#5B4FCF; font-weight:500;">
                            ${formatRupiah(item.subtotal)}
                        </td>
                    </tr>
                `;
            });

            document.getElementById('cardHasil').style.display = 'block';

        } else {
            document.getElementById('errorMsg').textContent = data.message;
            document.getElementById('cardError').style.display = 'block';
        }
    })
    .catch(() => {
        document.getElementById('errorMsg').textContent = 'Gagal menghubungi server.';
        document.getElementById('cardError').style.display = 'block';
    });
}

function onScanError(err) {
    // Diabaikan
}

// Tombol Scan Lagi
document.getElementById('btnReset').addEventListener('click', function () {
    sudahScan = false;

    document.getElementById('cardInstruksi').style.display = 'block';
    document.getElementById('cardHasil').style.display     = 'none';
    document.getElementById('cardError').style.display     = 'none';
    document.getElementById('btnReset').style.display      = 'none';
    document.getElementById('statusBadge').className       = 'status-badge status-scanning';
    document.getElementById('statusBadge').textContent     = '📡 Scanner aktif';

    html5QrCode.start(
        { facingMode: "environment" },
        config, onScanSuccess, onScanError
    );
});

// Mulai scanner
html5QrCode.start(
    { facingMode: "environment" },
    config, onScanSuccess, onScanError
).catch(err => {
    document.getElementById('statusBadge').className   = 'status-badge status-error';
    document.getElementById('statusBadge').textContent = '❌ Tidak bisa akses kamera';
});
</script>
</body>
</html>