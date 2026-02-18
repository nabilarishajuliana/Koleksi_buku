@extends('layouts.dashboard')

@section('title', 'Buku')

@section('content')

<div class="page-header">
    <h3 class="page-title">Data Buku</h3>
</div>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<div class="card">
    <div class="card-body">

        <a href="{{ route('buku.create') }}" class="btn btn-primary mb-3">
            + Tambah Buku
        </a>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Judul</th>
                    <th>Pengarang</th>
                    <th>Kategori</th>
                    <th width="200">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($buku as $b)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $b->kode }}</td>
                    <td>{{ $b->judul }}</td>
                    <td>{{ $b->pengarang }}</td>
                    <td>{{ $b->kategori->nama_kategori }}</td>
                    <td>
                        <a href="{{ route('buku.edit', $b->idbuku) }}" class="btn btn-warning btn-sm">Edit</a>

                        <form action="{{ route('buku.destroy', $b->idbuku) }}"
                              method="POST"
                              style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin hapus?')">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>

@endsection
