@extends('layouts.dashboard')

@section('title', 'Tambah Barang')

@section('content')
<div class="page-header">
    <h3 class="page-title">Tambah Barang</h3>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('barang.store') }}">
            @csrf

            <div class="form-group mb-3">
                <label>Nama Barang</label>
                <input type="text" name="nama_barang"
                    class="form-control @error('nama_barang') is-invalid @enderror"
                    value="{{ old('nama_barang') }}" required>
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
                    <input type="number" name="harga"
                        class="form-control @error('harga') is-invalid @enderror"
                        value="{{ old('harga') }}" min="0" required>
                    @error('harga')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <a href="{{ route('barang.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>
@endsection