@extends('layouts.dashboard')

@section('title', 'POS - Kasir')

@section('content')
<div class="page-header">
    <h3 class="page-title">Point Of Sales — Kasir</h3>
</div>

{{-- FORM INPUT BARANG --}}
<div class="card mb-4">
    <div class="card-header"><h5 class="mb-0">Input Barang</h5></div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label>Kode Barang</label>
                    <input type="text" id="kode_barang" class="form-control"
                        placeholder="Ketik kode barang lalu tekan Enter">
                </div>
                <div class="form-group mb-3">
                    <label>Nama Barang</label>
                    <input type="text" id="nama_barang" class="form-control"
                        placeholder="Otomatis terisi" readonly
                        style="background-color:#fff0f0;">
                </div>
                <div class="form-group mb-3">
                    <label>Harga Barang</label>
                    <input type="text" id="harga_barang" class="form-control"
                        placeholder="Otomatis terisi" readonly
                        style="background-color:#fff0f0;">
                </div>
                <div class="form-group mb-3">
                    <label>Jumlah</label>
                    <input type="number" id="jumlah" class="form-control"
                        placeholder="0" min="1" value="1">
                </div>
            </div>
        </div>

        {{-- Disabled by default, aktif hanya jika barang ditemukan --}}
        <button type="button" id="btnTambahkan" class="btn btn-success" disabled>
            <i class="mdi mdi-plus"></i> Tambahkan
        </button>
    </div>
</div>

{{-- TABEL KERANJANG --}}
<div class="card mb-4">
    <div class="card-header"><h5 class="mb-0">Keranjang Belanja</h5></div>
    <div class="card-body">
        <table class="table table-bordered" id="tabelKeranjang">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-right font-weight-bold">Total</td>
                    <td colspan="2" class="font-weight-bold" id="totalHarga">Rp 0</td>
                </tr>
            </tfoot>
        </table>

        <button type="button" id="btnBayar" class="btn btn-success float-right" disabled>
            <i class="mdi mdi-cash"></i> Bayar
        </button>
    </div>
</div>

@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
// ================================================================
// STATE: semua item keranjang disimpan di array ini
// ================================================================
let keranjang = [];

// ================================================================
// HELPER: format angka jadi "Rp 12.000"
// ================================================================
function formatRupiah(angka) {
    return 'Rp ' + parseInt(angka).toLocaleString('id-ID');
}

// ================================================================
// HELPER: hitung total dan update tampilan
// ================================================================
function updateTotal() {
    const total = keranjang.reduce((sum, item) => sum + item.subtotal, 0);
    $('#totalHarga').text(formatRupiah(total));
    $('#btnBayar').prop('disabled', keranjang.length === 0);
}

// ================================================================
// HELPER: render ulang semua baris tabel dari array keranjang
// ================================================================
function renderTabel() {
    const tbody = $('#tabelKeranjang tbody');
    tbody.empty();

    if (keranjang.length === 0) {
        tbody.append('<tr><td colspan="6" class="text-center text-muted">Keranjang kosong</td></tr>');
        updateTotal();
        return;
    }

    keranjang.forEach(function (item, index) {
        const baris = `
            <tr>
                <td>${item.id_barang}</td>
                <td>${item.nama}</td>
                <td>${formatRupiah(item.harga)}</td>
                <td>
                    <input type="number" class="form-control form-control-sm input-jumlah"
                        data-index="${index}" value="${item.jumlah}" min="1"
                        style="width:80px;">
                </td>
                <td class="subtotal-cell">${formatRupiah(item.subtotal)}</td>
                <td>
                    <button class="btn btn-danger btn-sm btn-hapus" data-index="${index}">
                        <i class="mdi mdi-delete"></i>
                    </button>
                </td>
            </tr>
        `;
        tbody.append(baris);
    });

    updateTotal();
}

// ================================================================
// HELPER: reset form ke kondisi awal
// ================================================================
function resetForm() {
    $('#kode_barang').val('');
    $('#nama_barang').val('');
    $('#harga_barang').val('');
    $('#jumlah').val(1);
    $('#btnTambahkan').prop('disabled', true);
    $('#kode_barang').focus();
}


$(function () {

    // ============================================================
    // VERSI AJAX JQUERY
    // ============================================================

    // Tekan Enter di input kode → cari barang ke server
    $('#kode_barang').keypress(function (e) {
        if (e.which !== 13) return; // 13 = kode keyboard Enter

        const kode = $(this).val().trim();
        if (kode === '') return;

        $('#nama_barang').val('');
        $('#harga_barang').val('');
        $('#btnTambahkan').prop('disabled', true);

        $.ajax({
            url    : '{{ route("api.pos.cari") }}',
            method : 'POST',
            data   : {
                _token : '{{ csrf_token() }}',
                kode   : kode
            },
            success: function (response) {
                if (response.status === 'success') {
                    $('#nama_barang').val(response.data.nama_barang);
                    $('#harga_barang').val(response.data.harga);
                    $('#jumlah').val(1);
                    $('#btnTambahkan').prop('disabled', false);
                    $('#jumlah').focus();
                }
            },
            error: function (xhr) {
                Swal.fire('Tidak Ditemukan', 'Kode barang tidak ada di database.', 'warning');
                $('#kode_barang').select();
            }
        });
    });

    // Tombol Tambahkan → masukkan ke keranjang
    $('#btnTambahkan').click(function () {
        const kode   = $('#kode_barang').val().trim();
        const nama   = $('#nama_barang').val();
        const harga  = parseInt($('#harga_barang').val());
        const jumlah = parseInt($('#jumlah').val());

        if (jumlah <= 0) {
            Swal.fire('Perhatian', 'Jumlah harus lebih dari 0.', 'warning');
            return;
        }

        const subtotal  = harga * jumlah;

        // Cek jika kode sudah ada di keranjang → update jumlah saja
        const indexAda = keranjang.findIndex(item => item.id_barang === kode);
        if (indexAda !== -1) {
            keranjang[indexAda].jumlah   += jumlah;
            keranjang[indexAda].subtotal  = keranjang[indexAda].harga * keranjang[indexAda].jumlah;
        } else {
            keranjang.push({ id_barang: kode, nama, harga, jumlah, subtotal });
        }

        renderTabel();
        resetForm();
    });

    // Ubah jumlah langsung di tabel
    $(document).on('change', '.input-jumlah', function () {
        const index  = $(this).data('index');
        const jumlah = parseInt($(this).val());

        if (jumlah <= 0) { $(this).val(1); return; }

        keranjang[index].jumlah   = jumlah;
        keranjang[index].subtotal = keranjang[index].harga * jumlah;

        $(this).closest('tr').find('.subtotal-cell')
            .text(formatRupiah(keranjang[index].subtotal));

        updateTotal();
    });

    // Hapus baris dari keranjang
    $(document).on('click', '.btn-hapus', function () {
        const index = $(this).data('index');
        keranjang.splice(index, 1);
        renderTabel();
    });

    // Tombol Bayar — versi Ajax
$('#btnBayar').click(function () {
    const total = keranjang.reduce((sum, item) => sum + item.subtotal, 0);

    $('#btnBayar').prop('disabled', true)
        .html('<span class="spinner-border spinner-border-sm me-1"></span> Memproses...');

    $.ajax({
        url        : '{{ route("api.pos.bayar") }}',
        method     : 'POST',
        contentType: 'application/json',
        headers    : { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        data       : JSON.stringify({
            total : total,
            items : keranjang
        }),
        success: function (response) {
            if (response.status === 'success') {
                Swal.fire({
                    icon : 'success',
                    title: 'Pembayaran Berhasil!',
                    text : `Transaksi #${response.data.id_penjualan} berhasil disimpan.`
                }).then(function () {
                    keranjang = [];
                    renderTabel();
                    resetForm();
                });
            }
        },
        error: function (xhr) {
            console.log(xhr.responseText);
            Swal.fire('Error!', 'Terjadi kesalahan saat menyimpan transaksi.', 'error');
        },
        complete: function () {
            $('#btnBayar').prop('disabled', false)
                .html('<i class="mdi mdi-cash"></i> Bayar');
        }
    });
});

});


// ============================================================
// VERSI AXIOS
// Uncomment blok ini dan comment blok Ajax di atas
// jika ingin pakai versi Axios
// ============================================================

/*
// Setup CSRF token global untuk semua request Axios
axios.defaults.headers.common['X-CSRF-TOKEN'] = '{{ csrf_token() }}';

$(function () {

    // Tekan Enter → cari barang versi Axios
    $('#kode_barang').keypress(function (e) {
        if (e.which !== 13) return;

        const kode = $(this).val().trim();
        if (kode === '') return;

        $('#nama_barang').val('');
        $('#harga_barang').val('');
        $('#btnTambahkan').prop('disabled', true);

        // Perbedaan Axios vs Ajax:
        // Ajax  : $.ajax({ url, method, data, success: fn, error: fn })
        // Axios : axios.post(url, data).then(fn).catch(fn)
        axios.post('{{ route("api.pos.cari") }}', { kode: kode })
            .then(function (response) {
                // Di Axios, response dari server ada di response.data
                // Jadi response.data = { status, code, message, data: {...} }
                const res = response.data;
                if (res.status === 'success') {
                    $('#nama_barang').val(res.data.nama_barang);
                    $('#harga_barang').val(res.data.harga);
                    $('#jumlah').val(1);
                    $('#btnTambahkan').prop('disabled', false);
                    $('#jumlah').focus();
                }
            })
            .catch(function (error) {
                Swal.fire('Tidak Ditemukan', 'Kode barang tidak ada di database.', 'warning');
                $('#kode_barang').select();
            });
    });

    // Tombol Bayar — versi Axios
    $('#btnBayar').click(function () {
        const total = keranjang.reduce((sum, item) => sum + item.subtotal, 0);

        $('#btnBayar').prop('disabled', true)
            .html('<span class="spinner-border spinner-border-sm me-1"></span> Memproses...');

        axios.post('{{ route("api.pos.bayar") }}', { total: total, items: keranjang })
            .then(function (response) {
                const res = response.data;
                if (res.status === 'success') {
                    Swal.fire({
                        icon : 'success',
                        title: 'Pembayaran Berhasil!',
                        text : `Transaksi #${res.data.id_penjualan} berhasil disimpan.`
                    }).then(function () {
                        keranjang = [];
                        renderTabel();
                        resetForm();
                    });
                }
            })
            .catch(function (error) {
                Swal.fire('Error!', 'Terjadi kesalahan saat menyimpan transaksi.', 'error');
            })
            .finally(function () {
                // .finally() = sama dengan complete: di Ajax
                // Selalu dijalankan baik sukses maupun error
                $('#btnBayar').prop('disabled', false)
                    .html('<i class="mdi mdi-cash"></i> Bayar');
            });
    });

    // Event ubah jumlah & hapus baris sama persis dengan versi Ajax
    // (tidak ada bedanya karena ini murni manipulasi DOM, tidak ada request ke server)
    $(document).on('change', '.input-jumlah', function () {
        const index  = $(this).data('index');
        const jumlah = parseInt($(this).val());
        if (jumlah <= 0) { $(this).val(1); return; }
        keranjang[index].jumlah   = jumlah;
        keranjang[index].subtotal = keranjang[index].harga * jumlah;
        $(this).closest('tr').find('.subtotal-cell')
            .text(formatRupiah(keranjang[index].subtotal));
        updateTotal();
    });

    $(document).on('click', '.btn-hapus', function () {
        keranjang.splice($(this).data('index'), 1);
        renderTabel();
    });

});
*/
</script>
@endsection