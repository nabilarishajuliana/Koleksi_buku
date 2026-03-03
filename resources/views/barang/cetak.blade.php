@extends('layouts.dashboard')

@section('title', 'Cetak Tag Harga')

@section('content')
<div class="page-header">
    <h3 class="page-title">Cetak Tag Harga</h3>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('barang.cetak') }}">
            @csrf
            <div class="row mb-3">
                <div class="col-md-2">
                    <label>Posisi X (1-5)</label>
                    <input type="number" name="x" min="1" max="5" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <label>Posisi Y (1-8)</label>
                    <input type="number" name="y" min="1" max="8" class="form-control" required>
                </div>
            </div>

            <p class="text-muted">Pilih barang yang ingin dicetak:</p>
            <table id="tableCetak" class="table table-bordered">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="checkAll"> Pilih</th>
                        <th>ID Barang</th>
                        <th>Nama Barang</th>
                        <th>Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($barang as $b)
                    <tr>
                        <td>
                            <input type="checkbox" name="barang[]"
                                value="{{ $b->id }}" class="check-item">
                        </td>
                        <td>{{ $b->id_barang }}</td>
                        <td>{{ $b->nama_barang }}</td>
                        <td>Rp {{ number_format($b->harga, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <button type="submit" class="btn btn-success mt-2">
                <i class="mdi mdi-printer"></i> Cetak Tag Harga
            </button>
        </form>
    </div>
</div>
@endsection

@section('script')
<script>
    $(function () {
        $('#tableCetak').DataTable();

        $('#checkAll').on('change', function () {
            $('.check-item').prop('checked', this.checked);
        });
    });
</script>
@endsection