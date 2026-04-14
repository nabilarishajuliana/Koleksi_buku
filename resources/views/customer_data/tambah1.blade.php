@extends('layouts.dashboard')

@section('title', 'Tambah Customer 1 — BLOB')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        Tambah Customer 1
        <span class="badge badge-primary ml-2">Foto BLOB</span>
    </h3>
</div>

<div class="card">
    <div class="card-body">

        <form id="formCustomer1" method="POST" action="{{ route('customer.store_blob') }}">
            @csrf
            <input type="hidden" name="foto_base64" id="foto_base64_1">

            <div class="row">
                <div class="col-md-6">

                    <div class="form-group mb-3">
                        <label>Nama</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label>Alamat</label>
                        <input type="text" name="alamat" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label>Provinsi</label>
                        <select id="sel_provinsi" class="form-control" required>
                            <option value="">-- Pilih Provinsi --</option>
                        </select>
                        {{-- Hidden input menyimpan nama provinsi --}}
                        <input type="hidden" name="provinsi" id="val_provinsi">
                    </div>

                    <div class="form-group mb-3">
                        <label>Kota</label>
                        <select id="sel_kota" class="form-control" required disabled>
                            <option value="">-- Pilih Kota --</option>
                        </select>
                        <input type="hidden" name="kota" id="val_kota">
                    </div>

                    <div class="form-group mb-3">
                        <label>Kecamatan</label>
                        <select id="sel_kecamatan" class="form-control" required disabled>
                            <option value="">-- Pilih Kecamatan --</option>
                        </select>
                        <input type="hidden" name="kecamatan" id="val_kecamatan">
                    </div>

                    <div class="form-group mb-3">
                        <label>Kodepos / Kelurahan</label>
                        <select id="sel_kelurahan" class="form-control" required disabled>
                            <option value="">-- Pilih Kelurahan --</option>
                        </select>
                        <input type="hidden" name="kodepos_kelurahan" id="val_kelurahan">
                    </div>

                </div>

                <div class="col-md-6 d-flex flex-column align-items-start">
                    <label>Foto</label>
                    <div style="
                        width:180px; height:180px;
                        border:2px dashed #ccc; border-radius:10px;
                        display:flex; align-items:center; justify-content:center;
                        overflow:hidden; background:#f8f9fa; margin-bottom:1rem;
                    ">
                        <img id="previewFoto1" src="" alt="preview"
                            style="width:100%; height:100%; object-fit:cover; display:none;">
                        <span id="placeholderFoto1" class="text-muted" style="font-size:0.8rem; text-align:center; padding:1rem;">
                            Belum ada foto
                        </span>
                    </div>
                    <button type="button" id="btnAmbilFoto1" class="btn btn-info">
                        <i class="mdi mdi-camera"></i> Ambil Foto
                    </button>
                </div>
            </div>

        </form>

        <div class="mt-4">
            <a href="{{ route('customer.index') }}" class="btn btn-secondary">Batal</a>
            <button type="button" id="btnSimpan1" class="btn btn-primary">
                <i class="mdi mdi-content-save"></i> Simpan Data
            </button>
        </div>

    </div>
</div>

{{-- MODAL KAMERA --}}
<div class="modal fade" id="modalKamera1" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">📷 Ambil Foto</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <p class="text-muted mb-1" style="font-size:0.8rem;">Video</p>
                        <video id="video1" autoplay playsinline
                            style="width:100%; border-radius:8px; background:#000; min-height:200px;">
                        </video>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted mb-1" style="font-size:0.8rem;">Snapshot</p>
                        <canvas id="canvas1" width="320" height="240"
                            style="width:100%; border-radius:8px; border:1px solid #ddd; display:none;">
                        </canvas>
                        <div id="placeholderCanvas1" style="
                            width:100%; height:200px; border:1px dashed #ddd;
                            border-radius:8px; display:flex; align-items:center;
                            justify-content:center; color:#aaa; font-size:0.8rem;
                        ">
                            Snapshot akan muncul di sini
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <div>
                    <label style="font-size:0.8rem; margin-bottom:0;">Pilihan Kamera:</label>
                    <select id="pilihKamera1" class="form-control form-control-sm d-inline-block" style="width:auto;">
                    </select>
                </div>
                <div>
                    <button type="button" id="btnCapture1" class="btn btn-warning">
                        <i class="mdi mdi-camera"></i> Ambil Foto
                    </button>
                    <button type="button" id="btnSimpanFoto1" class="btn btn-success" disabled>
                        <i class="mdi mdi-check"></i> Simpan Foto
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
// ================================================================
// STATE
// ================================================================
let stream1      = null;
let fotoBase64_1 = null;
let currentDeviceIndex1 = 0;
let devices1     = [];

// ================================================================
// SELECT BERJENJANG WILAYAH
// ID dipakai untuk request ke API, Nama disimpan ke hidden input
// ================================================================
$(function () {

    // Load provinsi saat halaman dibuka
    $.ajax({
        url: '{{ route("api.provinsi") }}',
        method: 'GET',
        success: function (res) {
            $.each(res.data, function (i, item) {
                $('#sel_provinsi').append(
                    `<option value="${item.id}" data-nama="${item.name}">${item.name}</option>`
                );
            });
        }
    });

    // Provinsi berubah
    $('#sel_provinsi').change(function () {
        const id   = $(this).val();
        const nama = $(this).find('option:selected').data('nama');

        $('#val_provinsi').val(nama); // simpan nama ke hidden input

        $('#sel_kota').empty().append('<option value="">-- Pilih Kota --</option>').prop('disabled', true);
        $('#sel_kecamatan').empty().append('<option value="">-- Pilih Kecamatan --</option>').prop('disabled', true);
        $('#sel_kelurahan').empty().append('<option value="">-- Pilih Kelurahan --</option>').prop('disabled', true);
        $('#val_kota, #val_kecamatan, #val_kelurahan').val('');

        if (!id) return;

        $.ajax({
            url: `/api/kota/${id}`,
            method: 'GET',
            success: function (res) {
                $.each(res.data, function (i, item) {
                    $('#sel_kota').append(
                        `<option value="${item.id}" data-nama="${item.name}">${item.name}</option>`
                    );
                });
                $('#sel_kota').prop('disabled', false);
            }
        });
    });

    // Kota berubah
    $('#sel_kota').change(function () {
        const id   = $(this).val();
        const nama = $(this).find('option:selected').data('nama');

        $('#val_kota').val(nama);

        $('#sel_kecamatan').empty().append('<option value="">-- Pilih Kecamatan --</option>').prop('disabled', true);
        $('#sel_kelurahan').empty().append('<option value="">-- Pilih Kelurahan --</option>').prop('disabled', true);
        $('#val_kecamatan, #val_kelurahan').val('');

        if (!id) return;

        $.ajax({
            url: `/api/kecamatan/${id}`,
            method: 'GET',
            success: function (res) {
                $.each(res.data, function (i, item) {
                    $('#sel_kecamatan').append(
                        `<option value="${item.id}" data-nama="${item.name}">${item.name}</option>`
                    );
                });
                $('#sel_kecamatan').prop('disabled', false);
            }
        });
    });

    // Kecamatan berubah
    $('#sel_kecamatan').change(function () {
        const id   = $(this).val();
        const nama = $(this).find('option:selected').data('nama');

        $('#val_kecamatan').val(nama);

        $('#sel_kelurahan').empty().append('<option value="">-- Pilih Kelurahan --</option>').prop('disabled', true);
        $('#val_kelurahan').val('');

        if (!id) return;

        $.ajax({
            url: `/api/kelurahan/${id}`,
            method: 'GET',
            success: function (res) {
                $.each(res.data, function (i, item) {
                    $('#sel_kelurahan').append(
                        `<option value="${item.id}" data-nama="${item.name}">${item.name}</option>`
                    );
                });
                $('#sel_kelurahan').prop('disabled', false);
            }
        });
    });

    // Kelurahan berubah
    $('#sel_kelurahan').change(function () {
        const nama = $(this).find('option:selected').data('nama');
        $('#val_kelurahan').val(nama);
    });

});

// ================================================================
// KAMERA — HTML5 MediaDevices API
// ================================================================

// Fungsi untuk mulai stream kamera
async function startCamera1(deviceId = null) {
    // Hentikan stream yang sedang berjalan dulu
    if (stream1) {
        stream1.getTracks().forEach(track => track.stop());
    }

    const constraints = {
        video: deviceId ? { deviceId: { exact: deviceId } } : { facingMode: 'user' },
        audio: false
    };

    try {
        // Minta izin akses kamera ke browser
        stream1 = await navigator.mediaDevices.getUserMedia(constraints);
        document.getElementById('video1').srcObject = stream1;
    } catch (err) {
        alert('Tidak bisa akses kamera: ' + err.message);
    }
}

// Fungsi untuk load daftar kamera yang tersedia
async function loadDevices1() {
    const allDevices = await navigator.mediaDevices.enumerateDevices();
    devices1 = allDevices.filter(d => d.kind === 'videoinput');

    const select = document.getElementById('pilihKamera1');
    select.innerHTML = '';
    devices1.forEach(function (device, index) {
        const opt = document.createElement('option');
        opt.value = device.deviceId;
        opt.text  = device.label || `Kamera ${index + 1}`;
        select.appendChild(opt);
    });
}

// Tombol Ambil Foto → buka modal + nyalakan kamera
document.getElementById('btnAmbilFoto1').addEventListener('click', async function () {
    $('#modalKamera1').modal('show');
    await loadDevices1();
    await startCamera1();
});

// Pilihan kamera berubah → ganti stream
document.getElementById('pilihKamera1').addEventListener('change', async function () {
    await startCamera1(this.value);
});

// Tombol Ambil Foto (capture) → ambil frame dari video ke canvas
document.getElementById('btnCapture1').addEventListener('click', function () {
    const video  = document.getElementById('video1');
    const canvas = document.getElementById('canvas1');
    const ctx    = canvas.getContext('2d');

    // Set ukuran canvas sesuai video
    canvas.width  = video.videoWidth  || 320;
    canvas.height = video.videoHeight || 240;

    // Gambar frame video ke canvas
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

    // Tampilkan canvas, sembunyikan placeholder
    canvas.style.display = 'block';
    document.getElementById('placeholderCanvas1').style.display = 'none';

    // Simpan sebagai base64 PNG
    fotoBase64_1 = canvas.toDataURL('image/png');

    // Aktifkan tombol Simpan Foto
    document.getElementById('btnSimpanFoto1').disabled = false;
});

// Tombol Simpan Foto → masukkan base64 ke hidden input, tutup modal
document.getElementById('btnSimpanFoto1').addEventListener('click', function () {
    if (!fotoBase64_1) return;

    // Masukkan base64 ke hidden input agar ikut tersubmit
    document.getElementById('foto_base64_1').value = fotoBase64_1;

    // Tampilkan preview foto di form utama
    const preview     = document.getElementById('previewFoto1');
    const placeholder = document.getElementById('placeholderFoto1');
    preview.src       = fotoBase64_1;
    preview.style.display     = 'block';
    placeholder.style.display = 'none';

    // Hentikan kamera dan tutup modal
    if (stream1) stream1.getTracks().forEach(t => t.stop());
    $('#modalKamera1').modal('hide');
});

// Saat modal ditutup → pastikan kamera berhenti
$('#modalKamera1').on('hidden.bs.modal', function () {
    if (stream1) {
        stream1.getTracks().forEach(t => t.stop());
        stream1 = null;
    }
});

// ================================================================
// TOMBOL SIMPAN DATA (spinner + validasi)
// ================================================================
$(function () {
    $('#btnSimpan1').click(function () {
        // Validasi form HTML5
        const form = document.getElementById('formCustomer1');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        // Validasi foto sudah diambil
        if (!document.getElementById('foto_base64_1').value) {
            alert('Foto belum diambil! Klik tombol Ambil Foto terlebih dahulu.');
            return;
        }

        // Spinner
        $(this).prop('disabled', true)
            .html('<span class="spinner-border spinner-border-sm mr-1"></span> Menyimpan...');

        form.submit();
    });
});
</script>
@endsection