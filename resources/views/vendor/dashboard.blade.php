<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Vendor — {{ session('vendor_nama') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --primary: #5B4FCF;
            --primary-dark: #4338A8;
            --primary-light: #EEF0FF;
            --success: #10B981;
            --success-light: #ECFDF5;
            --danger: #E53E3E;
            --danger-light: #FFF5F5;
            --warning: #F59E0B;
            --text: #1A1A2E;
            --muted: #6B7280;
            --border: #E5E7EB;
            --bg: #F3F4F8;
            --white: #FFFFFF;
            --sidebar-w: 240px;
            --navbar-h: 60px;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
        }

        /* ── NAVBAR ──────────────────────────── */
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

        .vendor-badge {
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .vendor-avatar {
            width: 34px; height: 34px;
            background: var(--primary-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            font-weight: 700;
            color: var(--primary);
        }

        .vendor-info strong {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            line-height: 1.2;
        }

        .vendor-info span {
            font-size: 0.75rem;
            color: var(--muted);
        }

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

        .btn-logout:hover {
            border-color: var(--danger);
            color: var(--danger);
            background: var(--danger-light);
        }

        /* ── SIDEBAR ─────────────────────────── */
        .sidebar {
            position: fixed;
            top: var(--navbar-h);
            left: 0;
            width: var(--sidebar-w);
            height: calc(100vh - var(--navbar-h));
            background: var(--white);
            border-right: 1px solid var(--border);
            padding: 1.25rem 0.75rem;
            overflow-y: auto;
        }

        .sidebar-label {
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--muted);
            padding: 0 0.75rem;
            margin-bottom: 0.5rem;
            margin-top: 1rem;
        }

        .sidebar-label:first-child { margin-top: 0; }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            padding: 0.6rem 0.75rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--muted);
            text-decoration: none;
            transition: all 0.15s;
            margin-bottom: 0.1rem;
        }

        .nav-item:hover {
            background: var(--bg);
            color: var(--text);
        }

        .nav-item.active {
            background: var(--primary-light);
            color: var(--primary);
            font-weight: 600;
        }

        .nav-item .icon { font-size: 1rem; width: 20px; text-align: center; }

        /* ── MAIN CONTENT ────────────────────── */
        .main {
            margin-left: var(--sidebar-w);
            margin-top: var(--navbar-h);
            padding: 2rem;
            min-height: calc(100vh - var(--navbar-h));
        }

        .page-header {
            margin-bottom: 1.5rem;
        }

        .page-header h2 {
            font-size: 1.35rem;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        .page-header p {
            font-size: 0.85rem;
            color: var(--muted);
            margin-top: 0.2rem;
        }

        /* ── ALERT ───────────────────────────── */
        .alert {
            padding: 0.75rem 1rem;
            border-radius: 10px;
            font-size: 0.85rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .alert-success { background: var(--success-light); color: var(--success); border: 1px solid #A7F3D0; }
        .alert-danger  { background: var(--danger-light);  color: var(--danger);  border: 1px solid #FED7D7; }

        /* ── CARD ────────────────────────────── */
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
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-header h5 {
            font-size: 0.95rem;
            font-weight: 700;
        }

        .card-body { padding: 1.25rem; }

        .card-body.p-0 { padding: 0; }

        /* ── FORM ────────────────────────────── */
        .form-group { margin-bottom: 1rem; }

        label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 0.4rem;
        }

        .form-control {
            width: 100%;
            padding: 0.65rem 0.9rem;
            border: 1.5px solid var(--border);
            border-radius: 8px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.875rem;
            color: var(--text);
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(91,79,207,0.1);
        }

        .input-group {
            display: flex;
        }

        .input-group-text {
            padding: 0.65rem 0.75rem;
            background: var(--bg);
            border: 1.5px solid var(--border);
            border-right: none;
            border-radius: 8px 0 0 8px;
            font-size: 0.85rem;
            color: var(--muted);
            white-space: nowrap;
        }

        .input-group .form-control {
            border-radius: 0 8px 8px 0;
        }

        /* ── BUTTONS ─────────────────────────── */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            padding: 0.6rem 1.1rem;
            border-radius: 8px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
            width: 100%;
        }

        .btn-primary:hover:not(:disabled) {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(91,79,207,0.3);
        }

        .btn-primary:disabled { opacity: 0.7; cursor: not-allowed; }

        .btn-danger {
            background: var(--danger-light);
            color: var(--danger);
            padding: 0.4rem 0.7rem;
            font-size: 0.8rem;
        }

        .btn-danger:hover { background: var(--danger); color: white; }

        /* ── MENU LIST ───────────────────────── */
        .menu-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem 1.25rem;
            border-bottom: 1px solid var(--border);
            transition: background 0.15s;
        }

        .menu-item:last-child { border-bottom: none; }
        .menu-item:hover { background: var(--bg); }

        .menu-item-name {
            font-size: 0.875rem;
            font-weight: 600;
        }

        .menu-item-price {
            font-size: 0.78rem;
            color: var(--muted);
            margin-top: 0.1rem;
        }

        /* ── BADGE ───────────────────────────── */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.65rem;
            border-radius: 100px;
            font-size: 0.72rem;
            font-weight: 600;
        }

        .badge-success { background: var(--success-light); color: var(--success); }
        .badge-primary { background: var(--primary-light); color: var(--primary); }

        /* ── TABLE ───────────────────────────── */
        table.dataTable {
            width: 100% !important;
            font-size: 0.85rem;
        }

        table.dataTable thead th {
            font-weight: 700;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--muted);
            background: var(--bg);
            border-bottom: 1px solid var(--border) !important;
            padding: 0.75rem 1rem;
        }

        table.dataTable tbody td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }

        table.dataTable tbody tr:hover td { background: var(--bg); }

        /* ── GRID ────────────────────────────── */
        .row { display: flex; gap: 1.25rem; }
        .col-4 { flex: 0 0 320px; }
        .col-8 { flex: 1; min-width: 0; }

        /* ── EMPTY STATE ─────────────────────── */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: var(--muted);
        }

        .empty-state .icon { font-size: 2.5rem; margin-bottom: 0.75rem; }
        .empty-state p { font-size: 0.875rem; }

        /* ── SPINNER ─────────────────────────── */
        .spinner {
            width: 14px; height: 14px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
            display: none;
        }

        @keyframes spin { to { transform: rotate(360deg); } }

        @media (max-width: 768px) {
            .row { flex-direction: column; }
            .col-4, .col-8 { flex: 1; }
            .navbar { padding-left: 1rem; }
            .sidebar { display: none; }
            .main { margin-left: 0; }
        }
    </style>
</head>
<body>

{{-- NAVBAR --}}
<div class="navbar-brand">
    🐱 Catto Canteen
</div>
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
    <a href="{{ route('vendor.dashboard') }}" class="nav-item active">
        <span class="icon">🏠</span> Dashboard
    </a>
    <a href="{{ url('/pesan') }}" target="_blank" class="nav-item">
        <span class="icon">🛒</span> Halaman Pesan
    </a>
</aside>

{{-- MAIN CONTENT --}}
<main class="main">

    {{-- Alerts --}}
    @if(session('success'))
    <div class="alert alert-success">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger">⚠️ {{ session('error') }}</div>
    @endif

    <div class="page-header">
        <h2>Dashboard Vendor</h2>
        <p>Kelola menu dan lihat pesanan yang masuk</p>
    </div>

    <div class="row">

        {{-- KOLOM KIRI --}}
        <div class="col-4">

            {{-- Card Tambah Menu --}}
            <div class="card">
                <div class="card-header">
                    <h5>➕ Tambah Menu</h5>
                </div>
                <div class="card-body">
                    <form id="formMenu" method="POST" action="{{ route('vendor.menu.store') }}">
                        @csrf
                        <div class="form-group">
                            <label>Nama Menu</label>
                            <input type="text" name="nama_menu" id="nama_menu"
                                class="form-control" required placeholder="contoh: Nasi Goreng">
                        </div>
                        <div class="form-group">
                            <label>Harga</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="harga" id="harga"
                                    class="form-control" min="0" required placeholder="0">
                            </div>
                        </div>
                    </form>
                    <button type="button" id="btnSimpanMenu" class="btn btn-primary">
                        <span class="spinner" id="spinnerMenu"></span>
                        <span id="btnMenuText">Simpan Menu</span>
                    </button>
                </div>
            </div>

            {{-- Card Daftar Menu --}}
            <div class="card">
                <div class="card-header">
                    <h5>🍽️ Daftar Menu</h5>
                    <span class="badge badge-primary">{{ $vendor->menu->count() }} item</span>
                </div>
                <div class="card-body p-0">
                    @forelse($vendor->menu as $m)
                    <div class="menu-item">
                        <div>
                            <div class="menu-item-name">{{ $m->nama_menu }}</div>
                            <div class="menu-item-price">Rp {{ number_format($m->harga, 0, ',', '.') }}</div>
                        </div>
                        <form action="{{ route('vendor.menu.destroy', $m->id_menu) }}"
                            method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm('Hapus menu ini?')">🗑</button>
                        </form>
                    </div>
                    @empty
                    <div class="empty-state">
                        <div class="icon">🍽️</div>
                        <p>Belum ada menu.<br>Tambahkan di atas!</p>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- KOLOM KANAN --}}
        <div class="col-8">
            <div class="card">
                <div class="card-header">
                    <h5>✅ Pesanan Lunas</h5>
                    <span class="badge badge-success">{{ $pesanan->count() }} pesanan</span>
                </div>
                <div class="card-body">
                    @if($pesanan->isEmpty())
                    <div class="empty-state">
                        <div class="icon">📋</div>
                        <p>Belum ada pesanan lunas.</p>
                    </div>
                    @else
                    <table id="tabelPesanan" class="dataTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Detail</th>
                                <th>Total</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pesanan as $p)
                            <tr>
                                <td><strong>#{{ $p->id_pesanan }}</strong></td>
                                <td>{{ $p->nama_customer }}</td>
                                <td>
                                    @foreach($p->detail as $d)
                                    <div style="font-size:0.82rem;">
                                        {{ $d->menu->nama_menu }} × {{ $d->jumlah }}
                                        <span style="color:var(--muted);">= Rp {{ number_format($d->subtotal, 0, ',', '.') }}</span>
                                    </div>
                                    @endforeach
                                </td>
                                <td><strong>Rp {{ number_format($p->total, 0, ',', '.') }}</strong></td>
                                <td style="font-size:0.8rem; color:var(--muted);">
                                    {{ \Carbon\Carbon::parse($p->created_at)->format('d/m/Y H:i') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
        </div>

    </div>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
$(function () {

    // DataTables
    if ($('#tabelPesanan').length) {
        $('#tabelPesanan').DataTable({
            order: [[0, 'desc']],
            language: { emptyTable: "Belum ada pesanan lunas." }
        });
    }

    // Spinner tombol simpan menu (SC1)
    $('#btnSimpanMenu').click(function () {
        const form = document.getElementById('formMenu');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }
        $(this).prop('disabled', true);
        $('#spinnerMenu').show();
        $('#btnMenuText').text('Menyimpan...');
        form.submit();
    });

});
</script>
</body>
</html>