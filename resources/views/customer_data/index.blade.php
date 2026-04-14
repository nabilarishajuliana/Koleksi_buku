@extends('layouts.dashboard')

@section('title', 'Data Customer')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h3 class="page-title">Data Customer</h3>
    <div>
        <a href="{{ route('customer.tambah1') }}" class="btn btn-primary btn-sm mr-2">
            <i class="mdi mdi-camera"></i> Tambah Customer 1 (BLOB)
        </a>
        <a href="{{ route('customer.tambah2') }}" class="btn btn-success btn-sm">
            <i class="mdi mdi-camera"></i> Tambah Customer 2 (File)
        </a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
</div>
@endif

<div class="card">
    <div class="card-body">
        <table id="tableCustomer" class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Foto</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>Provinsi</th>
                    <th>Kota</th>
                    <th>Kecamatan</th>
                    <th>Kodepos/Kelurahan</th>
                    <th>Tipe Foto</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customers as $c)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        @if($c->foto_blob)
                            {{-- Foto BLOB: akses via route khusus --}}
                            <img src="{{ route('customer.foto', $c->id) }}"
                                alt="foto" width="60" height="60"
                                style="border-radius:8px; object-fit:cover;">
                        @elseif($c->foto_path)
                            {{-- Foto File: akses via storage --}}
                            <img src="{{ asset('storage/' . $c->foto_path) }}"
                                alt="foto" width="60" height="60"
                                style="border-radius:8px; object-fit:cover;">
                        @else
                            <span class="text-muted">Tidak ada foto</span>
                        @endif
                    </td>
                    <td>{{ $c->nama }}</td>
                    <td>{{ $c->alamat }}</td>
                    <td>{{ $c->provinsi }}</td>
                    <td>{{ $c->kota }}</td>
                    <td>{{ $c->kecamatan }}</td>
                    <td>{{ $c->kodepos_kelurahan }}</td>
                    <td>
                        @if($c->foto_blob)
                            <span class="badge badge-primary">BLOB</span>
                        @elseif($c->foto_path)
                            <span class="badge badge-success">File</span>
                        @endif
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
    $('#tableCustomer').DataTable();
});
</script>
@endsection