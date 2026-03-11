@extends('layouts.dashboard')

@section('title', 'Studi Kasus 2 & 3 - DataTables')

@section('content')
<div class="page-header">
    <h3 class="page-title">Studi Kasus 2 & 3 — DataTables</h3>
</div>

{{-- FORM TAMBAH --}}
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Tambah Barang</h5>
    </div>
    <div class="card-body">
        <form id="formTambah">
            <div class="form-group mb-3">
                <label>Nama Barang</label>
                <input type="text" id="nama_barang" class="form-control"
                    placeholder="Masukkan nama barang" required>
            </div>
            <div class="form-group mb-3">
                <label>Harga</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Rp</span>
                    </div>
                    <input type="number" id="harga" class="form-control"
                        placeholder="0" min="0" required>
                </div>
            </div>
        </form>
        <button type="button" id="btnTambah" class="btn btn-primary">
            <i class="mdi mdi-plus"></i> Tambah
        </button>
    </div>
</div>

{{-- TABEL DATATABLES --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Data Barang</h5>
        <small class="text-muted">Klik baris untuk edit / hapus</small>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-hover" id="tabelBarang">
            <thead>
                <tr>
                    <th>ID Barang</th>
                    <th>Nama Barang</th>
                    <th>Harga</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

{{-- MODAL EDIT / HAPUS --}}
<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Barang</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formEdit">
                    <div class="form-group mb-3">
                        <label>ID Barang</label>
                        <input type="text" id="edit_id" class="form-control" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label>Nama Barang</label>
                        <input type="text" id="edit_nama" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Harga</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input type="number" id="edit_harga" class="form-control"
                                min="0" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" id="btnHapus" class="btn btn-danger">
                    <i class="mdi mdi-delete"></i> Hapus
                </button>
                <button type="button" id="btnUbah" class="btn btn-warning">
                    <i class="mdi mdi-content-save-edit"></i> Ubah
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<style>
    #tabelBarang tbody tr { cursor: pointer; }
</style>
<script>
$(function () {

    let counter    = 1;
    let rowDipilih = null; // DataTables row object

    // ================================================================
    // INISIALISASI DATATABLES
    // ================================================================
    const dt = $('#tabelBarang').DataTable({
        language: {
            emptyTable: "Belum ada data. Tambahkan barang di atas."
        }
    });

    // ================================================================
    // TOMBOL TAMBAH (SC2)
    // ================================================================
    $('#btnTambah').click(function () {
        const form = document.getElementById('formTambah');

        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        $('#btnTambah')
            .prop('disabled', true)
            .html('<span class="spinner-border spinner-border-sm me-1"></span> Menambahkan...');

        const nama     = $('#nama_barang').val();
        const harga    = parseInt($('#harga').val());
        const idBarang = 'BRG' + String(counter).padStart(4, '0');

        dt.row.add([
            idBarang,
            nama,
            'Rp ' + harga.toLocaleString('id-ID')
        ]).draw();

        $('#nama_barang').val('');
        $('#harga').val('');
        counter++;

        $('#btnTambah')
            .prop('disabled', false)
            .html('<i class="mdi mdi-plus"></i> Tambah');
    });

    // ================================================================
    // KLIK BARIS → BUKA MODAL (SC3)
    // ================================================================
    $('#tabelBarang tbody').on('click', 'tr', function () {
        rowDipilih = dt.row(this);

        const data  = rowDipilih.data();
        const harga = data[2].replace('Rp ', '').replace(/\./g, '');

        $('#edit_id').val(data[0]);
        $('#edit_nama').val(data[1]);
        $('#edit_harga').val(harga);

        $('#modalEdit').modal('show');
    });

    // ================================================================
    // TOMBOL UBAH (SC3)
    // ================================================================
    $('#btnUbah').click(function () {
        const formEdit = document.getElementById('formEdit');

        if (!formEdit.checkValidity()) {
            formEdit.reportValidity();
            return;
        }

        $('#btnUbah')
            .prop('disabled', true)
            .html('<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...');

        const id    = $('#edit_id').val();
        const nama  = $('#edit_nama').val();
        const harga = parseInt($('#edit_harga').val());

        rowDipilih.data([
            id,
            nama,
            'Rp ' + harga.toLocaleString('id-ID')
        ]).draw();

        $('#modalEdit').modal('hide');

        $('#btnUbah')
            .prop('disabled', false)
            .html('<i class="mdi mdi-content-save-edit"></i> Ubah');
    });

    // ================================================================
    // TOMBOL HAPUS (SC3)
    // ================================================================
    $('#btnHapus').click(function () {
        $('#btnHapus')
            .prop('disabled', true)
            .html('<span class="spinner-border spinner-border-sm me-1"></span> Menghapus...');

        rowDipilih.remove().draw();

        $('#modalEdit').modal('hide');

        $('#btnHapus')
            .prop('disabled', false)
            .html('<i class="mdi mdi-delete"></i> Hapus');
    });

});
</script>
@endsection