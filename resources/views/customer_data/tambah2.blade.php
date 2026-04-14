@extends('layouts.dashboard')

@section('title', 'Tambah Customer 2 — File')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        Tambah Customer 2
        <span class="badge badge-success ml-2">Foto File</span>
    </h3>
</div>

<div class="card">
    <div class="card-body">

        {{-- ACTION berbeda: store_file --}}
        <form id="formCustomer2" method="POST" action="{{ route('customer.store_file') }}">
            @csrf
            <input type="hidden" name="foto_base64" id="foto_base64_2">

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
                        <select id="sel_provinsi2" class="form-control" required>
                            <option value="">-- Pilih Provinsi --</option>
                        </select>
                        <input type="hidden" name="provinsi" id="val_provinsi2">
                    </div>

                    <div class="form-group mb-3">
                        <label>Kota</label>
                        <select id="sel_kota2" class="form-control" required disabled>
                            <option value="">-- Pilih Kota --</option>
                        </select>
                        <input type="hidden" name="kota" id="val_kota2">
                    </div>

                    <div class="form-group mb-3">
                        <label>Kecamatan</label>
                        <select id="sel_kecamatan2" class="form-control" required disabled>
                            <option value="">-- Pilih Kecamatan --</option>
                        </select>
                        <input type="hidden" name="kecamatan" id="val_kecamatan2">
                    </div>

                    <div class="form-group mb-3">
                        <label>Kodepos / Kelurahan</label>
                        <select id="sel_kelurahan2" class="form-control" required disabled>
                            <option value="">-- Pilih Kelurahan --</option>
                        </select>
                        <input type="hidden" name="kodepos_kelurahan" id="val_kelurahan2">
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
                        <img id="previewFoto2" src="" alt="preview"
                            style="width:100%; height:100%; object-fit:cover; display:none;">
                        <span id="placeholderFoto2" class="text-muted"
                            style="font-size:0.8rem; text-align:center; padding:1rem;">
                            Belum ada foto
                        </span>
                    </div>
                    <button type="button" id="btnAmbilFoto2" class="btn btn-info">
                        <i class="mdi mdi-camera"></i> Ambil Foto
                    </button>
                </div>
            </div>

        </form>

        <div class="mt-4">
            <a href="{{ route('customer.index') }}" class="btn btn-secondary">Batal</a>
            <button type="button" id="btnSimpan2" class="btn btn-success">
                <i class="mdi mdi-content-save"></i> Simpan Data
            </button>
        </div>

    </div>
</div>

{{-- MODAL KAMERA --}}
<div class="modal fade" id="modalKamera2" tabindex="-1">
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
                        <video id="video2" autoplay playsinline
                            style="width:100%; border-radius:8px; background:#000; min-height:200px;">
                        </video>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted mb-1" style="font-size:0.8rem;">Snapshot</p>
                        <canvas id="canvas2" width="320" height="240"
                            style="width:100%; border-radius:8px; border:1px solid #ddd; display:none;">
                        </canvas>
                        <div id="placeholderCanvas2" style="
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
                    <select id="pilihKamera2" class="form-control form-control-sm d-inline-block" style="width:auto;">
                    </select>
                </div>
                <div>
                    <button type="button" id="btnCapture2" class="btn btn-warning">
                        <i class="mdi mdi-camera"></i> Ambil Foto
                    </button>
                    <button type="button" id="btnSimpanFoto2" class="btn btn-success" disabled>
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
let stream2      = null;
let fotoBase64_2 = null;
let devices2     = [];

// ── SELECT BERJENJANG ──────────────────────────────────────────
$(function () {

    $.ajax({
        url: '{{ route("api.provinsi") }}',
        method: 'GET',
        success: function (res) {
            $.each(res.data, function (i, item) {
                $('#sel_provinsi2').append(
                    `<option value="${item.id}" data-nama="${item.name}">${item.name}</option>`
                );
            });
        }
    });

    $('#sel_provinsi2').change(function () {
        const id   = $(this).val();
        const nama = $(this).find('option:selected').data('nama');
        $('#val_provinsi2').val(nama);
        $('#sel_kota2').empty().append('<option value="">-- Pilih Kota --</option>').prop('disabled', true);
        $('#sel_kecamatan2').empty().append('<option value="">-- Pilih Kecamatan --</option>').prop('disabled', true);
        $('#sel_kelurahan2').empty().append('<option value="">-- Pilih Kelurahan --</option>').prop('disabled', true);
        $('#val_kota2, #val_kecamatan2, #val_kelurahan2').val('');
        if (!id) return;
        $.ajax({
            url: `/api/kota/${id}`, method: 'GET',
            success: function (res) {
                $.each(res.data, function (i, item) {
                    $('#sel_kota2').append(`<option value="${item.id}" data-nama="${item.name}">${item.name}</option>`);
                });
                $('#sel_kota2').prop('disabled', false);
            }
        });
    });

    $('#sel_kota2').change(function () {
        const id   = $(this).val();
        const nama = $(this).find('option:selected').data('nama');
        $('#val_kota2').val(nama);
        $('#sel_kecamatan2').empty().append('<option value="">-- Pilih Kecamatan --</option>').prop('disabled', true);
        $('#sel_kelurahan2').empty().append('<option value="">-- Pilih Kelurahan --</option>').prop('disabled', true);
        $('#val_kecamatan2, #val_kelurahan2').val('');
        if (!id) return;
        $.ajax({
            url: `/api/kecamatan/${id}`, method: 'GET',
            success: function (res) {
                $.each(res.data, function (i, item) {
                    $('#sel_kecamatan2').append(`<option value="${item.id}" data-nama="${item.name}">${item.name}</option>`);
                });
                $('#sel_kecamatan2').prop('disabled', false);
            }
        });
    });

    $('#sel_kecamatan2').change(function () {
        const id   = $(this).val();
        const nama = $(this).find('option:selected').data('nama');
        $('#val_kecamatan2').val(nama);
        $('#sel_kelurahan2').empty().append('<option value="">-- Pilih Kelurahan --</option>').prop('disabled', true);
        $('#val_kelurahan2').val('');
        if (!id) return;
        $.ajax({
            url: `/api/kelurahan/${id}`, method: 'GET',
            success: function (res) {
                $.each(res.data, function (i, item) {
                    $('#sel_kelurahan2').append(`<option value="${item.id}" data-nama="${item.name}">${item.name}</option>`);
                });
                $('#sel_kelurahan2').prop('disabled', false);
            }
        });
    });

    $('#sel_kelurahan2').change(function () {
        $('#val_kelurahan2').val($(this).find('option:selected').data('nama'));
    });

});

// ── KAMERA ─────────────────────────────────────────────────────
async function startCamera2(deviceId = null) {
    if (stream2) stream2.getTracks().forEach(t => t.stop());
    const constraints = {
        video: deviceId ? { deviceId: { exact: deviceId } } : { facingMode: 'user' },
        audio: false
    };
    try {
        stream2 = await navigator.mediaDevices.getUserMedia(constraints);
        document.getElementById('video2').srcObject = stream2;
    } catch (err) {
        alert('Tidak bisa akses kamera: ' + err.message);
    }
}

async function loadDevices2() {
    const all = await navigator.mediaDevices.enumerateDevices();
    devices2  = all.filter(d => d.kind === 'videoinput');
    const sel = document.getElementById('pilihKamera2');
    sel.innerHTML = '';
    devices2.forEach(function (d, i) {
        const opt = document.createElement('option');
        opt.value = d.deviceId;
        opt.text  = d.label || `Kamera ${i + 1}`;
        sel.appendChild(opt);
    });
}

document.getElementById('btnAmbilFoto2').addEventListener('click', async function () {
    $('#modalKamera2').modal('show');
    await loadDevices2();
    await startCamera2();
});

document.getElementById('pilihKamera2').addEventListener('change', async function () {
    await startCamera2(this.value);
});

document.getElementById('btnCapture2').addEventListener('click', function () {
    const video  = document.getElementById('video2');
    const canvas = document.getElementById('canvas2');
    const ctx    = canvas.getContext('2d');
    canvas.width  = video.videoWidth  || 320;
    canvas.height = video.videoHeight || 240;
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
    canvas.style.display = 'block';
    document.getElementById('placeholderCanvas2').style.display = 'none';
    fotoBase64_2 = canvas.toDataURL('image/png');
    document.getElementById('btnSimpanFoto2').disabled = false;
});

document.getElementById('btnSimpanFoto2').addEventListener('click', function () {
    if (!fotoBase64_2) return;
    document.getElementById('foto_base64_2').value = fotoBase64_2;
    const preview     = document.getElementById('previewFoto2');
    const placeholder = document.getElementById('placeholderFoto2');
    preview.src               = fotoBase64_2;
    preview.style.display     = 'block';
    placeholder.style.display = 'none';
    if (stream2) stream2.getTracks().forEach(t => t.stop());
    $('#modalKamera2').modal('hide');
});

$('#modalKamera2').on('hidden.bs.modal', function () {
    if (stream2) { stream2.getTracks().forEach(t => t.stop()); stream2 = null; }
});

// ── SIMPAN DATA ────────────────────────────────────────────────
$(function () {
    $('#btnSimpan2').click(function () {
        const form = document.getElementById('formCustomer2');
        if (!form.checkValidity()) { form.reportValidity(); return; }
        if (!document.getElementById('foto_base64_2').value) {
            alert('Foto belum diambil! Klik tombol Ambil Foto terlebih dahulu.');
            return;
        }
        $(this).prop('disabled', true)
            .html('<span class="spinner-border spinner-border-sm mr-1"></span> Menyimpan...');
        form.submit();
    });
});
</script>
@endsection