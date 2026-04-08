@extends('layouts.dashboard')

@section('title', 'AJAX - Wilayah Indonesia')

@section('content')
<div class="page-header">
    <h3 class="page-title">AJAX & Axios — Wilayah Indonesia</h3>
</div>

<div class="row">

    {{-- CARD 1: VERSI AJAX JQUERY --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Versi Ajax jQuery</h5>
            </div>
            <div class="card-body">

                <div class="form-group mb-3">
                    <label>Provinsi</label>
                    <select id="ajax_provinsi" class="form-control">
                        <option value="">-- Pilih Provinsi --</option>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label>Kota</label>
                    <select id="ajax_kota" class="form-control" disabled>
                        <option value="">-- Pilih Kota --</option>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label>Kecamatan</label>
                    <select id="ajax_kecamatan" class="form-control" disabled>
                        <option value="">-- Pilih Kecamatan --</option>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label>Kelurahan</label>
                    <select id="ajax_kelurahan" class="form-control" disabled>
                        <option value="">-- Pilih Kelurahan --</option>
                    </select>
                </div>

            </div>
        </div>
    </div>

    {{-- CARD 2: VERSI AXIOS --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Versi Axios</h5>
            </div>
            <div class="card-body">

                <div class="form-group mb-3">
                    <label>Provinsi</label>
                    <select id="axios_provinsi" class="form-control">
                        <option value="">-- Pilih Provinsi --</option>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label>Kota</label>
                    <select id="axios_kota" class="form-control" disabled>
                        <option value="">-- Pilih Kota --</option>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label>Kecamatan</label>
                    <select id="axios_kecamatan" class="form-control" disabled>
                        <option value="">-- Pilih Kecamatan --</option>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label>Kelurahan</label>
                    <select id="axios_kelurahan" class="form-control" disabled>
                        <option value="">-- Pilih Kelurahan --</option>
                    </select>
                </div>

            </div>
        </div>
    </div>

</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>

// ================================================================
// HELPER: isi opsi ke dalam select
// ================================================================
function renderOpsi(selectId, data, labelKey, valueKey, placeholder) {
    const select = $('#' + selectId);
    select.empty();
    select.append(`<option value="">-- ${placeholder} --</option>`);
    $.each(data, function (i, item) {
        select.append(`<option value="${item[valueKey]}">${item[labelKey]}</option>`);
    });
    select.prop('disabled', false);
}

// ================================================================
// HELPER: kosongkan & disable select
// ================================================================
function resetSelect(selectId, placeholder) {
    const select = $('#' + selectId);
    select.empty();
    select.append(`<option value="">-- ${placeholder} --</option>`);
    select.prop('disabled', true);
}


// ================================================================
// VERSI AJAX JQUERY
// ================================================================
$(function () {

    // Load provinsi saat halaman dibuka pertama kali
    $.ajax({
        url    : '{{ route("api.provinsi") }}',
        method : 'GET',
        success: function (response) {
            renderOpsi('ajax_provinsi', response.data, 'name', 'id', 'Pilih Provinsi');
        },
        error: function (xhr) {
            console.log('Error load provinsi:', xhr);
        }
    });

    // Provinsi berubah → load kota, reset kecamatan & kelurahan
    $('#ajax_provinsi').change(function () {
        const idProvinsi = $(this).val();

        resetSelect('ajax_kota',      'Pilih Kota');
        resetSelect('ajax_kecamatan', 'Pilih Kecamatan');
        resetSelect('ajax_kelurahan', 'Pilih Kelurahan');

        if (idProvinsi === '') return;

        $.ajax({
            url    : `/api/kota/${idProvinsi}`,
            method : 'GET',
            success: function (response) {
                renderOpsi('ajax_kota', response.data, 'name', 'id', 'Pilih Kota');
            },
            error: function (xhr) { console.log('Error load kota:', xhr); }
        });
    });

    // Kota berubah → load kecamatan, reset kelurahan
    $('#ajax_kota').change(function () {
        const idKota = $(this).val();

        resetSelect('ajax_kecamatan', 'Pilih Kecamatan');
        resetSelect('ajax_kelurahan', 'Pilih Kelurahan');

        if (idKota === '') return;

        $.ajax({
            url    : `/api/kecamatan/${idKota}`,
            method : 'GET',
            success: function (response) {
                renderOpsi('ajax_kecamatan', response.data, 'name', 'id', 'Pilih Kecamatan');
            },
            error: function (xhr) { console.log('Error load kecamatan:', xhr); }
        });
    });

    // Kecamatan berubah → load kelurahan
    $('#ajax_kecamatan').change(function () {
        const idKecamatan = $(this).val();

        resetSelect('ajax_kelurahan', 'Pilih Kelurahan');

        if (idKecamatan === '') return;

        $.ajax({
            url    : `/api/kelurahan/${idKecamatan}`,
            method : 'GET',
            success: function (response) {
                renderOpsi('ajax_kelurahan', response.data, 'name', 'id', 'Pilih Kelurahan');
            },
            error: function (xhr) { console.log('Error load kelurahan:', xhr); }
        });
    });

});


// ================================================================
// VERSI AXIOS
// Konsep sama, perbedaan:
// - Tidak pakai callback (success/error), pakai Promise (.then/.catch)
// - Response data ada di response.data (Axios bungkus satu lapis lagi)
// - response.data.data = array data dari server kita
// ================================================================

// Load provinsi versi Axios
axios.get('{{ route("api.provinsi") }}')
    .then(function (response) {
        renderOpsi('axios_provinsi', response.data.data, 'name', 'id', 'Pilih Provinsi');
    })
    .catch(function (error) {
        console.log('Error load provinsi (axios):', error);
    });

// Provinsi berubah (Axios)
document.getElementById('axios_provinsi').addEventListener('change', function () {
    const idProvinsi = this.value;

    resetSelect('axios_kota',      'Pilih Kota');
    resetSelect('axios_kecamatan', 'Pilih Kecamatan');
    resetSelect('axios_kelurahan', 'Pilih Kelurahan');

    if (idProvinsi === '') return;

    axios.get(`/api/kota/${idProvinsi}`)
        .then(function (response) {
            renderOpsi('axios_kota', response.data.data, 'name', 'id', 'Pilih Kota');
        })
        .catch(function (error) { console.log('Error load kota (axios):', error); });
});

// Kota berubah (Axios)
document.getElementById('axios_kota').addEventListener('change', function () {
    const idKota = this.value;

    resetSelect('axios_kecamatan', 'Pilih Kecamatan');
    resetSelect('axios_kelurahan', 'Pilih Kelurahan');

    if (idKota === '') return;

    axios.get(`/api/kecamatan/${idKota}`)
        .then(function (response) {
            renderOpsi('axios_kecamatan', response.data.data, 'name', 'id', 'Pilih Kecamatan');
        })
        .catch(function (error) { console.log('Error load kecamatan (axios):', error); });
});

// Kecamatan berubah (Axios)
document.getElementById('axios_kecamatan').addEventListener('change', function () {
    const idKecamatan = this.value;

    resetSelect('axios_kelurahan', 'Pilih Kelurahan');

    if (idKecamatan === '') return;

    axios.get(`/api/kelurahan/${idKecamatan}`)
        .then(function (response) {
            renderOpsi('axios_kelurahan', response.data.data, 'name', 'id', 'Pilih Kelurahan');
        })
        .catch(function (error) { console.log('Error load kelurahan (axios):', error); });
});

</script>
@endsection