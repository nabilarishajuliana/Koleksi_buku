@extends('layouts.dashboard')

@section('title', 'Data Barang')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h3 class="page-title">Data Barang</h3>
    <a href="{{ route('barang.create') }}" class="btn btn-primary">
        <i class="mdi mdi-plus"></i> Tambah Barang
    </a>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
</div>
@endif

<div class="card">
    <div class="card-body">
        <table id="tableBarang" class="table table-bordered">
            <thead>
                <tr>
                    <th>ID Barang</th>
                    <th>Nama Barang</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($barang as $b)
                <tr>
                    <td>{{ $b->id_barang }}</td>
                    <td>{{ $b->nama_barang }}</td>
                    <td>Rp {{ number_format($b->harga, 0, ',', '.') }}</td>
                    <td>
                        <a href="{{ route('barang.edit', $b->id) }}" class="btn btn-warning btn-sm">
                            <i class="mdi mdi-pencil"></i> Edit
                        </a>
                        <form action="{{ route('barang.destroy', $b->id) }}"
                            method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Yakin ingin menghapus barang ini?')">
                                <i class="mdi mdi-delete"></i> Hapus
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

@section('script')
<script>
    $(function () {
        $('#tableBarang').DataTable();
    });
</script>
@endsection