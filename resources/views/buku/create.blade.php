@extends('layouts.dashboard')

@section('title', 'Tambah Buku')

@section('content')

<div class="page-header">
    <h3 class="page-title">Tambah Buku</h3>
</div>

<div class="card">
    <div class="card-body">

        <form action="{{ route('buku.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Kode</label>
                <input type="text" name="kode" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Judul</label>
                <input type="text" name="judul" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Pengarang</label>
                <input type="text" name="pengarang" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Kategori</label>
                <select name="idkategori" class="form-control" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($kategori as $k)
                        <option value="{{ $k->idkategori }}">
                            {{ $k->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button class="btn btn-success mt-3">Simpan</button>
            <a href="{{ route('buku.index') }}" class="btn btn-secondary mt-3">Kembali</a>

        </form>

    </div>
</div>

@endsection
