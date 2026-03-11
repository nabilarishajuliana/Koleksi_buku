@extends('layouts.dashboard')

@section('title', 'Studi Kasus 4 - Select & Select2')

@section('content')
<div class="page-header">
    <h3 class="page-title">Studi Kasus 4 — Select & Select2</h3>
</div>

<div class="row">

    {{-- ======================== --}}
    {{-- CARD 1: SELECT BIASA     --}}
    {{-- ======================== --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Select</h5>
            </div>
            <div class="card-body">

                {{-- Input tambah kota --}}
                <div class="form-group mb-3">
                    <label>Tambah Kota</label>
                    <div class="input-group">
                        <input type="text" id="inputKota1" class="form-control"
                            placeholder="Nama kota...">
                        <div class="input-group-append">
                            <button type="button" id="btnTambahKota1" class="btn btn-primary">
                                Tambah
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Select biasa --}}
                <div class="form-group mb-3">
                    <label>Pilih Kota</label>
                    <select id="selectKota1" class="form-control">
                        <option value="">-- Pilih Kota --</option>
                    </select>
                </div>

                {{-- Kota terpilih --}}
                <div class="form-group">
                    <label>Kota Terpilih :</label>
                    <p id="kotaTerpilih1" class="font-weight-bold text-primary mb-0">-</p>
                </div>

            </div>
        </div>
    </div>

    {{-- ======================== --}}
    {{-- CARD 2: SELECT2          --}}
    {{-- ======================== --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Select 2</h5>
            </div>
            <div class="card-body">

                {{-- Input tambah kota --}}
                <div class="form-group mb-3">
                    <label>Tambah Kota</label>
                    <div class="input-group">
                        <input type="text" id="inputKota2" class="form-control"
                            placeholder="Nama kota...">
                        <div class="input-group-append">
                            <button type="button" id="btnTambahKota2" class="btn btn-primary">
                                Tambah
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Select2 --}}
                <div class="form-group mb-3">
                    <label>Pilih Kota</label>
                    <select id="selectKota2" class="form-control">
                        <option value="">-- Pilih Kota --</option>
                    </select>
                </div>

                {{-- Kota terpilih --}}
                <div class="form-group">
                    <label>Kota Terpilih :</label>
                    <p id="kotaTerpilih2" class="font-weight-bold text-primary mb-0">-</p>
                </div>

            </div>
        </div>
    </div>

</div>
@endsection

@section('script')
{{--
    Select2 CDN — load di sini agar hanya aktif di halaman ini.
    Kalau template sudah include Select2 secara global, hapus 2 baris link di bawah.
--}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

{{-- Tambahkan ini: tema Bootstrap untuk Select2 --}}
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap4-theme@1.0.0/dist/select2-bootstrap4.min.css" rel="stylesheet">
<script>
    $(function() {

        // ================================================================
        // INISIALISASI SELECT2
        // Wajib dipanggil sebelum bisa dipakai.
        // $(this) di dalam select2 merujuk ke elemen select itu sendiri.
        // ================================================================
        $('#selectKota2').select2({
            placeholder: '-- Pilih Kota --',
            allowClear: true,
            width: '100%',
            theme: 'bootstrap4' // ← tambahkan ini
        });

        // ================================================================
        // CARD 1 — SELECT BIASA
        // ================================================================

        // Tambah opsi ke select biasa
        $('#btnTambahKota1').click(function() {
            const kota = $('#inputKota1').val().trim();

            // Abaikan kalau input kosong
            if (kota === '') return;

            // Nama kota dijadikan value sekaligus teks tampil di dropdown
            // Ini sesuai ketentuan: "jadikan nama kota sebagai value
            // dan nilai tampil pada element select"
            $('#selectKota1').append(
                `<option value="${kota}">${kota}</option>`
            );

            $('#inputKota1').val(''); // kosongkan input setelah tambah
        });

        // Tampilkan kota terpilih ketika select berubah (event onchange)
        // Ini adalah versi jQuery dari onchange() di modul
        $('#selectKota1').change(function() {
            const terpilih = $(this).val();
            // Kalau pilihan kosong ("-- Pilih Kota --"), tampilkan "-"
            $('#kotaTerpilih1').text(terpilih !== '' ? terpilih : '-');
        });

        // ================================================================
        // CARD 2 — SELECT2
        // ================================================================

        // Tambah opsi ke Select2
        $('#btnTambahKota2').click(function() {
            const kota = $('#inputKota2').val().trim();

            if (kota === '') return;

            // Cara tambah opsi ke Select2 berbeda dari select biasa:
            // Harus buat object Option dulu, lalu append,
            // lalu WAJIB trigger('change') agar UI Select2 ikut refresh.
            // Kalau tidak di-trigger, opsi masuk tapi Select2 tidak tahu.
            const opsi = new Option(kota, kota, false, false);
            $('#selectKota2').append(opsi).trigger('change');

            $('#inputKota2').val('');
        });

        // Tampilkan kota terpilih ketika Select2 berubah
        // Pakai .on('change') bukan .change() — lebih konsisten untuk Select2
        $('#selectKota2').on('change', function() {
            const terpilih = $(this).val();
            $('#kotaTerpilih2').text(terpilih !== '' && terpilih !== null ? terpilih : '-');
        });

    });
</script>
@endsection