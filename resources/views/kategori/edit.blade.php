@extends('layouts.dashboard')

@section('title', 'Edit Kategori')

@section('content')

<div class="page-header">
    <h3 class="page-title">Edit Kategori</h3>
</div>

<div class="card">
    <div class="card-body">

        <form action="{{ route('kategori.update', $kategori->idkategori) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Nama Kategori</label>
                <input type="text"
                       name="nama_kategori"
                       value="{{ $kategori->nama_kategori }}"
                       class="form-control"
                       required>
            </div>

            <button class="btn btn-success mt-3">Update</button>
            <a href="{{ route('kategori.index') }}" class="btn btn-secondary mt-3">Kembali</a>

        </form>

    </div>
</div>

@endsection
