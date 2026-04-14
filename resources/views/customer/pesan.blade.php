<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Makanan — Catto Canteen</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *,
        *::before,
        *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --cream: #FAF6EF;
            --brown: #3B1F0A;
            --orange: #E8622A;
            --muted: #9B7E65;
            --light: #F0E8DA;
            --white: #FFFFFF;
            --border: rgba(59, 31, 10, 0.1);
            --success: #2D9B6F;
            --danger: #C0392B;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            background-color: var(--cream);
            color: var(--brown);
            font-family: 'DM Sans', sans-serif;
            min-height: 100vh;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 0;
            opacity: 0.5;
        }

        /* ── NAVBAR ── */
        nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.25rem 3rem;
            background: rgba(250, 246, 239, 0.9);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
        }

        .nav-logo {
            font-family: 'Playfair Display', serif;
            font-size: 1.3rem;
            font-weight: 900;
            color: var(--brown);
            letter-spacing: -0.02em;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .nav-logo span {
            color: var(--orange);
        }

        .nav-back {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--muted);
            text-decoration: none;
            padding: 0.45rem 1rem;
            border: 1.5px solid var(--border);
            border-radius: 100px;
            transition: all 0.2s;
        }

        .nav-back:hover {
            border-color: var(--brown);
            color: var(--brown);
        }

        /* ── MAIN WRAPPER ── */
        .wrapper {
            position: relative;
            z-index: 1;
            max-width: 820px;
            margin: 0 auto;
            padding: 6rem 1.5rem 4rem;
        }

        /* ── PAGE HEADER ── */
        .page-header {
            margin-bottom: 2rem;
        }

        .page-eyebrow {
            font-size: 0.72rem;
            font-weight: 500;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--orange);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .page-eyebrow::before {
            content: '';
            display: block;
            width: 1.5rem;
            height: 1.5px;
            background: var(--orange);
        }

        .page-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.25rem;
            font-weight: 900;
            letter-spacing: -0.03em;
            line-height: 1.1;
        }

        .page-desc {
            font-size: 0.95rem;
            color: var(--muted);
            margin-top: 0.4rem;
            font-weight: 300;
        }

        /* ── CARD ── */
        .card {
            background: var(--white);
            border-radius: 20px;
            border: 1px solid var(--border);
            box-shadow: 0 4px 24px rgba(59, 31, 10, 0.06);
            margin-bottom: 1.5rem;
            overflow: hidden;
        }

        .card-header {
            padding: 1.1rem 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .card-header h5 {
            font-family: 'Playfair Display', serif;
            font-size: 1rem;
            font-weight: 700;
            margin: 0;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* ── FORM ELEMENTS ── */
        .form-group {
            margin-bottom: 1.1rem;
        }

        label {
            display: block;
            font-size: 0.82rem;
            font-weight: 500;
            color: var(--brown);
            margin-bottom: 0.4rem;
            letter-spacing: 0.01em;
        }

        select,
        input[type="number"] {
            width: 100%;
            padding: 0.7rem 1rem;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem;
            color: var(--brown);
            background: var(--cream);
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            appearance: none;
            -webkit-appearance: none;
        }

        select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%239B7E65' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            padding-right: 2.5rem;
        }

        select:focus,
        input[type="number"]:focus {
            border-color: var(--orange);
            box-shadow: 0 0 0 3px rgba(232, 98, 42, 0.1);
            background-color: var(--white);
        }

        select:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* ── INFO HARGA ── */
        .info-harga {
            display: none;
            background: var(--light);
            border: 1px solid rgba(59, 31, 10, 0.08);
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
            color: var(--muted);
            margin-bottom: 1.1rem;
        }

        .info-harga strong {
            color: var(--orange);
            font-size: 1rem;
        }

        /* ── BUTTONS ── */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            padding: 0.7rem 1.4rem;
            border-radius: 100px;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            border: none;
            transition: all 0.2s ease;
        }

        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .btn-tambah {
            background: var(--brown);
            color: var(--cream);
        }

        .btn-tambah:hover:not(:disabled) {
            background: var(--orange);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(232, 98, 42, 0.25);
        }

        .btn-bayar {
            width: 100%;
            padding: 0.9rem;
            background: var(--orange);
            color: var(--white);
            border-radius: 100px;
            font-size: 1rem;
            font-weight: 500;
        }

        .btn-bayar:hover:not(:disabled) {
            background: #C94F1A;
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(232, 98, 42, 0.35);
        }

        .btn-hapus {
            background: #FFF0EE;
            color: var(--danger);
            padding: 0.35rem 0.7rem;
            border-radius: 8px;
            font-size: 0.8rem;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-hapus:hover {
            background: var(--danger);
            color: white;
        }

        /* ── TABLE ── */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.875rem;
        }

        thead th {
            padding: 0.6rem 0.75rem;
            font-size: 0.72rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--muted);
            border-bottom: 1.5px solid var(--border);
            text-align: left;
        }

        tbody td {
            padding: 0.75rem;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        tbody tr:hover td {
            background: var(--cream);
        }

        tfoot td {
            padding: 0.75rem;
            font-weight: 500;
        }

        tfoot .total-label {
            text-align: right;
            color: var(--muted);
            font-size: 0.85rem;
        }

        tfoot .total-value {
            font-family: 'Playfair Display', serif;
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--orange);
        }

        /* Input jumlah di tabel */
        .input-jumlah-tabel {
            width: 70px;
            padding: 0.35rem 0.6rem;
            border: 1.5px solid var(--border);
            border-radius: 8px;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.85rem;
            color: var(--brown);
            background: var(--cream);
            outline: none;
        }

        .input-jumlah-tabel:focus {
            border-color: var(--orange);
        }

        /* ── EMPTY STATE ── */
        .empty-state {
            text-align: center;
            padding: 2.5rem 1rem;
            color: var(--muted);
        }

        .empty-state .icon {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            font-size: 0.875rem;
        }

        /* ── SPINNER ── */
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .spinner {
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.4);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 600px) {
            nav {
                padding: 1rem 1.25rem;
            }

            .wrapper {
                padding: 5rem 1rem 3rem;
            }

            .page-title {
                font-size: 1.75rem;
            }
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav>
        <a href="{{ url('/') }}" class="nav-logo">
            🐱 Catto <span>Canteen</span>
        </a>
        <a href="{{ url('/') }}" class="nav-back">
            ← Kembali
        </a>
    </nav>

    <!-- MAIN CONTENT -->
    <div class="wrapper">

        <!-- PAGE HEADER -->
        <div class="page-header">
            <div class="page-eyebrow">Pemesanan Online</div>
            <h1 class="page-title">Pesan makanan kamu 🍽️</h1>
            <p class="page-desc">Pilih kantin dan menu favoritmu, bayar sekali, tinggal ambil.</p>
        </div>

        <!-- CARD PILIH MENU -->
        <div class="card">
            <div class="card-header">
                <h5>Pilih Menu</h5>
            </div>
            <div class="card-body">

                <!-- SELECT VENDOR -->
                <div class="form-group">
                    <label>Vendor / Kantin</label>
                    <select id="selectVendor">
                        <option value="">-- Pilih Vendor --</option>
                        @foreach($vendors as $v)
                        <option value="{{ $v->id_vendor }}">{{ $v->nama_vendor }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- SELECT MENU -->
                <div class="form-group" id="wrapperMenu" style="display:none;">
                    <label>Menu</label>
                    <select id="selectMenu" disabled>
                        <option value="">-- Pilih Menu --</option>
                    </select>
                </div>

                <!-- INFO HARGA -->
                <div class="info-harga" id="infoHarga">
                    Harga: <strong id="txtHarga">Rp 0</strong>
                </div>

                <!-- INPUT JUMLAH -->
                <div class="form-group" id="wrapperJumlah" style="display:none;">
                    <label>Jumlah</label>
                    <input type="number" id="inputJumlah" value="1" min="1">
                </div>

                <button type="button" id="btnTambah" class="btn btn-tambah" disabled>
                    + Tambah ke Keranjang
                </button>

            </div>
        </div>

        <!-- CARD KERANJANG -->
        <div class="card">
            <div class="card-header">
                <h5>🛒 Keranjang Belanja</h5>
            </div>
            <div class="card-body">
                <table id="tabelKeranjang">
                    <thead>
                        <tr>
                            <th>Menu</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr id="emptyRow">
                            <td colspan="5">
                                <div class="empty-state">
                                    <div class="icon">🛒</div>
                                    <p>Keranjang masih kosong.<br>Tambahkan menu di atas!</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="total-label">Total Pembayaran</td>
                            <td colspan="2" class="total-value" id="totalHarga">Rp 0</td>
                        </tr>
                    </tfoot>
                </table>

                <div style="margin-top: 1.25rem;">
                    <button type="button" id="btnBayar" class="btn btn-bayar" disabled>
                        💳 Bayar Sekarang
                    </button>
                </div>
            </div>
        </div>

    </div>

    <!-- SCRIPTS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>

    <script>
        let keranjang = [];
        let menuData = {};

        function formatRupiah(angka) {
            return 'Rp ' + parseInt(angka).toLocaleString('id-ID');
        }

        function updateTotal() {
            const total = keranjang.reduce((sum, item) => sum + item.subtotal, 0);
            $('#totalHarga').text(formatRupiah(total));
            $('#btnBayar').prop('disabled', keranjang.length === 0);
        }

        function renderKeranjang() {
            const tbody = $('#tabelKeranjang tbody');
            tbody.empty();

            if (keranjang.length === 0) {
                tbody.append(`
            <tr id="emptyRow">
                <td colspan="5">
                    <div class="empty-state">
                        <div class="icon">🛒</div>
                        <p>Keranjang masih kosong.<br>Tambahkan menu di atas!</p>
                    </div>
                </td>
            </tr>
        `);
                updateTotal();
                return;
            }

            keranjang.forEach(function(item, index) {
                tbody.append(`
            <tr>
                <td><strong>${item.nama_menu}</strong></td>
                <td>${formatRupiah(item.harga)}</td>
                <td>
                    <input type="number" class="input-jumlah-tabel input-jumlah"
                        data-index="${index}" value="${item.jumlah}" min="1">
                </td>
                <td class="subtotal-cell" style="color:var(--orange);font-weight:500;">
                    ${formatRupiah(item.subtotal)}
                </td>
                <td>
                    <button class="btn-hapus btn-hapus-item" data-index="${index}">🗑</button>
                </td>
            </tr>
        `);
            });

            updateTotal();
        }

        $(function() {

            // Pilih vendor → load menu
            $('#selectVendor').change(function() {
                const idVendor = $(this).val();
                $('#selectMenu').empty().append('<option value="">-- Pilih Menu --</option>').prop('disabled', true);
                $('#wrapperMenu, #wrapperJumlah, #infoHarga').hide();
                $('#btnTambah').prop('disabled', true);

                if (!idVendor) return;

                $.ajax({
                    url: `/api/menu/${idVendor}`,
                    method: 'GET',
                    success: function(response) {
                        if (response.status === 'success') {
                            $('#wrapperMenu').show();
                            $.each(response.data, function(i, menu) {
                                $('#selectMenu').append(
                                    `<option value="${menu.id_menu}"
                                data-harga="${menu.harga}"
                                data-nama="${menu.nama_menu}">
                                ${menu.nama_menu} — ${formatRupiah(menu.harga)}
                            </option>`
                                );
                            });
                            $('#selectMenu').prop('disabled', false);
                        }
                    }
                });
            });

            // Pilih menu → tampilkan harga & jumlah
            $('#selectMenu').change(function() {
                const selected = $(this).find('option:selected');
                const val = $(this).val();

                if (!val) {
                    $('#infoHarga, #wrapperJumlah').hide();
                    $('#btnTambah').prop('disabled', true);
                    return;
                }

                menuData = {
                    id_menu: val,
                    nama_menu: selected.data('nama'),
                    harga: selected.data('harga')
                };

                $('#txtHarga').text(formatRupiah(menuData.harga));
                $('#infoHarga').show();
                $('#wrapperJumlah').show();
                $('#inputJumlah').val(1);
                $('#btnTambah').prop('disabled', false);
            });

            // Tambah ke keranjang
            $('#btnTambah').click(function() {
                const jumlah = parseInt($('#inputJumlah').val());
                const subtotal = menuData.harga * jumlah;
                const indexAda = keranjang.findIndex(item => item.id_menu === menuData.id_menu);

                if (indexAda !== -1) {
                    keranjang[indexAda].jumlah += jumlah;
                    keranjang[indexAda].subtotal = keranjang[indexAda].harga * keranjang[indexAda].jumlah;
                } else {
                    keranjang.push({
                        ...menuData,
                        jumlah,
                        subtotal
                    });
                }

                renderKeranjang();
                $('#selectMenu').val('');
                $('#infoHarga, #wrapperJumlah').hide();
                $('#btnTambah').prop('disabled', true);
            });

            // Ubah jumlah di tabel
            $(document).on('change', '.input-jumlah', function() {
                const index = $(this).data('index');
                const jumlah = parseInt($(this).val());
                if (jumlah <= 0) {
                    $(this).val(1);
                    return;
                }

                keranjang[index].jumlah = jumlah;
                keranjang[index].subtotal = keranjang[index].harga * jumlah;
                $(this).closest('tr').find('.subtotal-cell').text(formatRupiah(keranjang[index].subtotal));
                updateTotal();
            });

            // Hapus item
            $(document).on('click', '.btn-hapus-item', function() {
                keranjang.splice($(this).data('index'), 1);
                renderKeranjang();
            });

            // Bayar
            $('#btnBayar').click(function() {
                const total = keranjang.reduce((sum, item) => sum + item.subtotal, 0);

                const $btn = $(this);
                $btn.prop('disabled', true).html(`
            <span class="spinner"></span> Memproses...
        `);

                $.ajax({
                    url: '{{ route("api.checkout") }}',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        _token: '{{ csrf_token() }}',
                        total: total,
                        items: keranjang
                    }),
                    success: function(response) {
                        if (response.status === 'success') {
                            snap.pay(response.snap_token, {
                                onSuccess: function() {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Pembayaran Berhasil! 🎉',
                                        confirmButtonColor: '#E8622A',
                                        confirmButtonText: 'Selesai',
                                        // Tampilkan QR code + info pesanan di dalam SWAL2
                                        html: `
            <p style="margin-bottom:0.75rem;">
                Halo <strong>${response.customer}</strong>!<br>
                Pesananmu berhasil dibayar. Tunjukkan QR code ini saat mengambil pesanan.
            </p>
            <div style="
                background: #FAF6EF;
                border: 1px solid rgba(59,31,10,0.1);
                border-radius: 12px;
                padding: 1rem;
                margin-bottom: 0.75rem;
            ">
                <p style="font-size:0.78rem; color:#9B7E65; margin-bottom:0.5rem;">
                    ID Pesanan
                </p>
                <p style="font-size:1.3rem; font-weight:700; color:#3B1F0A; margin-bottom:1rem;">
                    #${response.id_pesanan}
                </p>
                <img src="${response.qr_code}"
                    alt="QR Code Pesanan"
                    style="
                        width: 160px;
                        height: 160px;
                        display: block;
                        margin: 0 auto;
                        border-radius: 8px;
                    ">
                <p style="font-size:0.72rem; color:#9B7E65; margin-top:0.5rem;">
                    Scan QR code ini untuk konfirmasi pesanan
                </p>
            </div>
        `,
                                    }).then(function() {
                                        // Reset halaman setelah user klik Selesai
                                        keranjang = [];
                                        renderKeranjang();
                                        $('#selectVendor').val('');
                                        $('#wrapperMenu, #wrapperJumlah, #infoHarga').hide();
                                    });
                                },
                                onPending: function() {
                                    Swal.fire('Menunggu Pembayaran', 'Selesaikan pembayaranmu ya!', 'info');
                                },
                                onError: function() {
                                    Swal.fire('Gagal', 'Pembayaran gagal. Coba lagi.', 'error');
                                },
                                onClose: function() {
                                    Swal.fire('Info', 'Kamu menutup popup pembayaran.', 'warning');
                                }
                            });
                        }
                    },
                    error: function() {
                        Swal.fire('Error!', 'Gagal memproses pesanan.', 'error');
                    },
                    complete: function() {
                        $btn.prop('disabled', false).html('💳 Bayar Sekarang');
                    }
                });
            });

        });
    </script>
</body>

</html>