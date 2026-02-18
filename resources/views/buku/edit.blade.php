@extends('layouts.dashboard')

@section('title', 'Edit Buku')

@section('content')

<div class="page-header">
    <h3 class="page-title">Edit Buku</h3>
</div>

<div class="card">
    <div class="card-body">

        <form action="{{ route('buku.update', $buku->idbuku) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Kode</label>
                <input type="text"
                       name="kode"
                       value="{{ $buku->kode }}"
                       class="form-control"
                       required>
            </div>

            <div class="form-group">
                <label>Judul</label>
                <input type="text"
                       name="judul"
                       value="{{ $buku->judul }}"
                       class="form-control"
                       required>
            </div>

            <div class="form-group">
                <label>Pengarang</label>
                <input type="text"
                       name="pengarang"
                       value="{{ $buku->pengarang }}"
                       class="form-control"
                       required>
            </div>

            <div class="form-group">
                <label>Kategori</label>
                <select name="idkategori" class="form-control" required>
                    @foreach($kategori as $k)
                        <option value="{{ $k->idkategori }}"
                            {{ $k->idkategori == $buku->idkategori ? 'selected' : '' }}>
                            {{ $k->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button class="btn btn-success mt-3">Update</button>
            <a href="{{ route('buku.index') }}" class="btn btn-secondary mt-3">Kembali</a>

        </form>

    </div>
</div>

@endsection
