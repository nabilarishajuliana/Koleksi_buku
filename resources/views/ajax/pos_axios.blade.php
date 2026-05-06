@extends('layouts.dashboard')

@section('title', 'POS - Kasir (Axios)')

@section('content')
<div class="page-header">
    <h3 class="page-title">Point Of Sales — Kasir <span class="badge badge-success">Axios</span></h3>
</div>

{{-- FORM INPUT BARANG --}}
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Input Barang</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label>Pilih Barang</label>
                    <select id="kode_barang" class="form-control">
                        <option value="">-- Pilih Barang --</option>
                    </select>
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
        <button type="button" id="btnTambahkan" class="btn btn-success" disabled>
            <i class="mdi mdi-plus"></i> Tambahkan
        </button>
    </div>
</div>

{{-- TABEL KERANJANG --}}
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Keranjang Belanja</h5>
    </div>
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
    let keranjang = [];

    function formatRupiah(angka) {
        return 'Rp ' + parseInt(angka).toLocaleString('id-ID');
    }

    function updateTotal() {
        const total = keranjang.reduce((sum, item) => sum + item.subtotal, 0);
        $('#totalHarga').text(formatRupiah(total));
        $('#btnBayar').prop('disabled', keranjang.length === 0);
    }

    function renderTabel() {
        const tbody = $('#tabelKeranjang tbody');
        tbody.empty();

        if (keranjang.length === 0) {
            tbody.append('<tr><td colspan="6" class="text-center text-muted">Keranjang kosong</td></tr>');
            updateTotal();
            return;
        }

        keranjang.forEach(function(item, index) {
            tbody.append(`
            <tr>
                <td>${item.id_barang}</td>
                <td>${item.nama}</td>
                <td>${formatRupiah(item.harga)}</td>
                <td>
                    <input type="number" class="form-control form-control-sm input-jumlah"
                        data-index="${index}" value="${item.jumlah}" min="1" style="width:80px;">
                </td>
                <td class="subtotal-cell">${formatRupiah(item.subtotal)}</td>
                <td>
                    <button class="btn btn-danger btn-sm btn-hapus" data-index="${index}">
                        <i class="mdi mdi-delete"></i>
                    </button>
                </td>
            </tr>
        `);
        });

        updateTotal();
    }

    function resetForm() {
        $('#kode_barang').val('');
        $('#nama_barang').val('');
        $('#harga_barang').val('');
        $('#jumlah').val(1);
        $('#btnTambahkan').prop('disabled', true);
        $('#kode_barang').focus();
    }

    // Setup CSRF token global untuk semua request Axios
    axios.defaults.headers.common['X-CSRF-TOKEN'] = '{{ csrf_token() }}';

    $(function() {

        // ============================================================
        // LOAD DROPDOWN BARANG (AXIOS)
        // ============================================================
        axios.get('{{ route("api.barang") }}')
            .then(function(response) {
                const select = $('#kode_barang');
                select.empty();
                select.append(`<option value="">-- Pilih Barang --</option>`);

                response.data.data.forEach(function(item) {
                    select.append(`
                <option value="${item.id_barang}" 
                    data-nama="${item.nama_barang}" 
                    data-harga="${item.harga}">
                    ${item.nama_barang} (Rp ${parseInt(item.harga).toLocaleString('id-ID')})
                </option>
            `);
                });
            })
            .catch(function(error) {
                console.log('Error load barang:', error);
            });


        // ============================================================
        // SAAT PILIH BARANG → AUTO ISI
        // ============================================================
        $('#kode_barang').change(function() {
            const selected = $(this).find(':selected');

            const nama = selected.data('nama');
            const harga = selected.data('harga');

            if (!nama) {
                $('#nama_barang').val('');
                $('#harga_barang').val('');
                $('#btnTambahkan').prop('disabled', true);
                return;
            }

            $('#nama_barang').val(nama);
            $('#harga_barang').val(harga);
            $('#jumlah').val(1);
            $('#btnTambahkan').prop('disabled', false);
        });
        // ── Tombol Tambahkan ───────────────────────────────────────
        $('#btnTambahkan').click(function() {
            const kode = $('#kode_barang').val();
            const nama = $('#nama_barang').val();
            const harga = parseInt($('#harga_barang').val());
            const jumlah = parseInt($('#jumlah').val());

            if (jumlah <= 0) {
                Swal.fire('Perhatian', 'Jumlah harus lebih dari 0.', 'warning');
                return;
            }

            const subtotal = harga * jumlah;
            const indexAda = keranjang.findIndex(item => item.id_barang === kode);

            if (indexAda !== -1) {
                keranjang[indexAda].jumlah += jumlah;
                keranjang[indexAda].subtotal = keranjang[indexAda].harga * keranjang[indexAda].jumlah;
            } else {
                keranjang.push({
                    id_barang: kode,
                    nama,
                    harga,
                    jumlah,
                    subtotal
                });
            }

            renderTabel();
            resetForm();
        });

        // ── Ubah jumlah di tabel ───────────────────────────────────
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

        // ── Hapus baris ────────────────────────────────────────────
        $(document).on('click', '.btn-hapus', function() {
            keranjang.splice($(this).data('index'), 1);
            renderTabel();
        });

        // ── Tombol Bayar (Axios) ────────────────────────────────────
        $('#btnBayar').click(function() {
            const total = keranjang.reduce((sum, item) => sum + item.subtotal, 0);

            $('#btnBayar').prop('disabled', true)
                .html('<span class="spinner-border spinner-border-sm me-1"></span> Memproses...');

            axios.post('{{ route("api.pos.bayar") }}', {
                    total: total,
                    items: keranjang
                })
                .then(function(response) {
                    const res = response.data;
                    if (res.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Pembayaran Berhasil!',
                            text: `Transaksi #${res.data.id_penjualan} berhasil disimpan.`
                        }).then(function() {
                            keranjang = [];
                            renderTabel();
                            resetForm();
                        });
                    }
                })
                .catch(function(error) {
                    const msg = error.response?.data?.message || 'Terjadi kesalahan saat menyimpan transaksi.';
                    Swal.fire('Error!', msg, 'error');
                })
                .finally(function() {
                    $('#btnBayar').prop('disabled', false)
                        .html('<i class="mdi mdi-cash"></i> Bayar');
                });
        });

    });
</script>
@endsection