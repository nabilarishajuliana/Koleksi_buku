@extends('layouts.dashboard')

@section('title', 'Edit Barang')

@section('content')
<div class="page-header">
    <h3 class="page-title">Edit Barang</h3>
</div>

<div class="card">
    <div class="card-body">

        {{-- Form TANPA button submit di dalamnya --}}
        <form id="formBarang" method="POST" action="{{ route('barang.update', $barang->id) }}">
            @csrf
            @method('PUT')

            <div class="form-group mb-3">
                <label>ID Barang</label>
                <input type="text" class="form-control" value="{{ $barang->id_barang }}" disabled>
            </div>

            <div class="form-group mb-3">
                <label>Nama Barang</label>
                <input type="text" name="nama_barang" id="nama_barang"
                    class="form-control @error('nama_barang') is-invalid @enderror"
                    value="{{ old('nama_barang', $barang->nama_barang) }}" required>
                @error('nama_barang')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-4">
                <label>Harga</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Rp</span>
                    </div>
                    <input type="number" name="harga" id="harga"
                        class="form-control @error('harga') is-invalid @enderror"
                        value="{{ old('harga', $barang->harga) }}" min="0" required>
                    @error('harga')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

        </form>

        {{-- Button di LUAR form --}}
        <a href="{{ route('barang.index') }}" class="btn btn-secondary">Batal</a>
        <button type="button" id="btnUpdate" class="btn btn-warning">
            <i class="mdi mdi-content-save-edit"></i> Update
        </button>

    </div>
</div>
@endsection

@section('script')
<script>
$(function () {
    $('#btnUpdate').click(function () {
        const form = document.getElementById('formBarang');

        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        $('#btnUpdate')
            .prop('disabled', true)
            .html('<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...');

        form.submit();
    });
});
</script>
@endsection