<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemesanan Kantin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body class="bg-light">

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">🍽️ Pemesanan Kantin Online</h5>
                </div>
                <div class="card-body">

                    {{-- SELECT VENDOR --}}
                    <div class="form-group mb-3">
                        <label><strong>Pilih Vendor / Kantin</strong></label>
                        <select id="selectVendor" class="form-control">
                            <option value="">-- Pilih Vendor --</option>
                            @foreach($vendors as $v)
                                <option value="{{ $v->id_vendor }}">{{ $v->nama_vendor }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- SELECT MENU (muncul setelah pilih vendor) --}}
                    <div class="form-group mb-3" id="wrapperMenu" style="display:none;">
                        <label><strong>Pilih Menu</strong></label>
                        <select id="selectMenu" class="form-control" disabled>
                            <option value="">-- Pilih Menu --</option>
                        </select>
                    </div>

                    {{-- INFO HARGA --}}
                    <div id="infoHarga" class="alert alert-info" style="display:none;">
                        Harga: <strong id="txtHarga">Rp 0</strong>
                    </div>

                    {{-- INPUT JUMLAH --}}
                    <div class="form-group mb-3" id="wrapperJumlah" style="display:none;">
                        <label><strong>Jumlah</strong></label>
                        <input type="number" id="inputJumlah" class="form-control"
                            value="1" min="1">
                    </div>

                    <button type="button" id="btnTambah" class="btn btn-success" disabled>
                        + Tambah ke Keranjang
                    </button>

                </div>
            </div>

            {{-- TABEL KERANJANG --}}
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="mb-0">🛒 Keranjang</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered" id="tabelKeranjang">
                        <thead class="thead-light">
                            <tr>
                                <th>Menu</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-right font-weight-bold">Total</td>
                                <td colspan="2" class="font-weight-bold" id="totalHarga">Rp 0</td>
                            </tr>
                        </tfoot>
                    </table>

                    <button type="button" id="btnBayar"
                        class="btn btn-primary btn-block btn-lg" disabled>
                        💳 Bayar Sekarang
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
{{-- Snap.js dari Midtrans Sandbox --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
// ================================================================
// STATE
// ================================================================
let keranjang   = [];
let menuData    = {}; // simpan data menu yang sedang dipilih

// ================================================================
// HELPER
// ================================================================
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
        tbody.append('<tr><td colspan="5" class="text-center text-muted">Keranjang kosong</td></tr>');
        updateTotal();
        return;
    }

    keranjang.forEach(function (item, index) {
        tbody.append(`
            <tr>
                <td>${item.nama_menu}</td>
                <td>${formatRupiah(item.harga)}</td>
                <td>
                    <input type="number" class="form-control form-control-sm input-jumlah"
                        data-index="${index}" value="${item.jumlah}" min="1"
                        style="width:70px;">
                </td>
                <td class="subtotal-cell">${formatRupiah(item.subtotal)}</td>
                <td>
                    <button class="btn btn-danger btn-sm btn-hapus" data-index="${index}">
                        🗑
                    </button>
                </td>
            </tr>
        `);
    });

    updateTotal();
}

// ================================================================
// AJAX: Load menu saat vendor dipilih
// ================================================================
$(function () {

    $('#selectVendor').change(function () {
        const idVendor = $(this).val();

        // Reset select menu
        $('#selectMenu').empty().append('<option value="">-- Pilih Menu --</option>');
        $('#selectMenu').prop('disabled', true);
        $('#wrapperMenu').hide();
        $('#wrapperJumlah').hide();
        $('#infoHarga').hide();
        $('#btnTambah').prop('disabled', true);

        if (idVendor === '') return;

        // AJAX ambil menu
        $.ajax({
            url    : `/api/menu/${idVendor}`,
            method : 'GET',
            success: function (response) {
                if (response.status === 'success') {
                    $('#wrapperMenu').show();
                    $.each(response.data, function (i, menu) {
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

    // ── Menu dipilih → tampilkan harga & jumlah ────────────────
    $('#selectMenu').change(function () {
        const selected = $(this).find('option:selected');
        const harga    = selected.data('harga');
        const nama     = selected.data('nama');

        if (!$(this).val()) {
            $('#infoHarga').hide();
            $('#wrapperJumlah').hide();
            $('#btnTambah').prop('disabled', true);
            return;
        }

        // Simpan data menu yang dipilih
        menuData = {
            id_menu  : $(this).val(),
            nama_menu: nama,
            harga    : harga
        };

        $('#txtHarga').text(formatRupiah(harga));
        $('#infoHarga').show();
        $('#wrapperJumlah').show();
        $('#inputJumlah').val(1);
        $('#btnTambah').prop('disabled', false);
    });

    // ── Tombol Tambah ke Keranjang ─────────────────────────────
    $('#btnTambah').click(function () {
        const jumlah   = parseInt($('#inputJumlah').val());
        const subtotal = menuData.harga * jumlah;

        // Cek jika menu sudah ada di keranjang → update jumlah
        const indexAda = keranjang.findIndex(item => item.id_menu === menuData.id_menu);
        if (indexAda !== -1) {
            keranjang[indexAda].jumlah   += jumlah;
            keranjang[indexAda].subtotal  = keranjang[indexAda].harga * keranjang[indexAda].jumlah;
        } else {
            keranjang.push({
                id_menu  : menuData.id_menu,
                nama_menu: menuData.nama_menu,
                harga    : menuData.harga,
                jumlah   : jumlah,
                subtotal : subtotal
            });
        }

        renderKeranjang();

        // Reset pilihan menu
        $('#selectMenu').val('');
        $('#infoHarga').hide();
        $('#wrapperJumlah').hide();
        $('#btnTambah').prop('disabled', true);
    });

    // ── Ubah jumlah di tabel ───────────────────────────────────
    $(document).on('change', '.input-jumlah', function () {
        const index  = $(this).data('index');
        const jumlah = parseInt($(this).val());
        if (jumlah <= 0) { $(this).val(1); return; }

        keranjang[index].jumlah   = jumlah;
        keranjang[index].subtotal = keranjang[index].harga * jumlah;
        $(this).closest('tr').find('.subtotal-cell').text(formatRupiah(keranjang[index].subtotal));
        updateTotal();
    });

    // ── Hapus item dari keranjang ──────────────────────────────
    $(document).on('click', '.btn-hapus', function () {
        keranjang.splice($(this).data('index'), 1);
        renderKeranjang();
    });

    // ── Tombol Bayar → Checkout → tampilkan Snap Midtrans ─────
    $('#btnBayar').click(function () {
        const total = keranjang.reduce((sum, item) => sum + item.subtotal, 0);

        $('#btnBayar').prop('disabled', true)
            .html('<span class="spinner-border spinner-border-sm mr-2"></span>Memproses...');

        $.ajax({
            url         : '{{ route("api.checkout") }}',
            method      : 'POST',
            contentType : 'application/json',
            data        : JSON.stringify({
                _token : '{{ csrf_token() }}',
                total  : total,
                items  : keranjang
            }),
            success: function (response) {
                if (response.status === 'success') {
                    // Buka popup pembayaran Midtrans dengan snap_token
                    snap.pay(response.snap_token, {
                        onSuccess: function (result) {
                            Swal.fire({
                                icon : 'success',
                                title: 'Pembayaran Berhasil!',
                                html : `Halo <strong>${response.customer}</strong>!<br>
                                        Pesanan kamu berhasil dibayar.`
                            }).then(function () {
                                // Kosongkan keranjang
                                keranjang = [];
                                renderKeranjang();
                                $('#selectVendor').val('');
                                $('#wrapperMenu').hide();
                            });
                        },
                        onPending: function (result) {
                            Swal.fire('Menunggu Pembayaran',
                                'Selesaikan pembayaran kamu ya!', 'info');
                        },
                        onError: function (result) {
                            Swal.fire('Gagal', 'Pembayaran gagal.', 'error');
                        },
                        onClose: function () {
                            Swal.fire('Info', 'Kamu menutup popup pembayaran.', 'warning');
                        }
                    });
                }
            },
            error: function () {
                Swal.fire('Error!', 'Gagal memproses pesanan.', 'error');
            },
            complete: function () {
                $('#btnBayar').prop('disabled', false).html('💳 Bayar Sekarang');
            }
        });
    });

});
</script>
</body>
</html>