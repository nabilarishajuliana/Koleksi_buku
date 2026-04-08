@extends('layouts.dashboard')

@section('title', 'Dashboard Vendor')

@section('content')
<div class="page-header">
    <h3 class="page-title">Dashboard Vendor</h3>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
</div>
@endif

<div class="row">

    {{-- ================================ --}}
    {{-- CARD TAMBAH MENU                 --}}
    {{-- ================================ --}}
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Tambah Menu</h5>
            </div>
            <div class="card-body">
                <form id="formMenu" method="POST" action="{{ route('vendor.menu.store') }}">
                    @csrf

                    <div class="form-group mb-3">
                        <label>Vendor / Kantin</label>
                        <select name="id_vendor" id="id_vendor"
                            class="form-control" required>
                            <option value="">-- Pilih Vendor --</option>
                            @foreach($vendors as $v)
                                <option value="{{ $v->id_vendor }}">
                                    {{ $v->nama_vendor }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label>Nama Menu</label>
                        <input type="text" name="nama_menu" id="nama_menu"
                            class="form-control" required
                            placeholder="contoh: Nasi Goreng">
                    </div>

                    <div class="form-group mb-3">
                        <label>Harga</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input type="number" name="harga" id="harga"
                                class="form-control" min="0" required
                                placeholder="0">
                        </div>
                    </div>

                </form>

                {{-- Button di LUAR form (ketentuan SC1 spinner) --}}
                <button type="button" id="btnSimpanMenu" class="btn btn-primary btn-block">
                    <i class="mdi mdi-plus"></i> Simpan Menu
                </button>
            </div>
        </div>

        {{-- DAFTAR MENU PER VENDOR --}}
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Daftar Menu</h5>
            </div>
            <div class="card-body p-0">
                @foreach($vendors as $v)
                <div class="px-3 pt-3">
                    <strong>{{ $v->nama_vendor }}</strong>
                </div>
                <ul class="list-group list-group-flush mb-2">
                    @forelse($v->menu as $m)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>
                            {{ $m->nama_menu }}<br>
                            <small class="text-muted">Rp {{ number_format($m->harga, 0, ',', '.') }}</small>
                        </span>
                        <form action="{{ route('vendor.menu.destroy', $m->id_menu) }}"
                            method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Hapus menu ini?')">
                                <i class="mdi mdi-delete"></i>
                            </button>
                        </form>
                    </li>
                    @empty
                    <li class="list-group-item text-muted">Belum ada menu</li>
                    @endforelse
                </ul>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ================================ --}}
    {{-- CARD PESANAN LUNAS               --}}
    {{-- ================================ --}}
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Pesanan Lunas</h5>
            </div>
            <div class="card-body">
                @if($pesanan->isEmpty())
                    <p class="text-muted text-center">Belum ada pesanan lunas.</p>
                @else
                <div class="table-responsive">
                    <table class="table table-bordered" id="tabelPesanan">
                        <thead>
                            <tr>
                                <th>ID Pesanan</th>
                                <th>Customer</th>
                                <th>Detail Pesanan</th>
                                <th>Total</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pesanan as $p)
                            <tr>
                                <td>#{{ $p->id_pesanan }}</td>
                                <td>{{ $p->nama_customer }}</td>
                                <td>
                                    <ul class="mb-0 pl-3">
                                        @foreach($p->detail as $d)
                                        <li>
                                            {{ $d->menu->nama_menu }}
                                            × {{ $d->jumlah }}
                                            = Rp {{ number_format($d->subtotal, 0, ',', '.') }}
                                        </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                                <td>{{ $p->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection

@section('script')
<script>
$(function () {
    $('#tabelPesanan').DataTable({
        order: [[0, 'desc']]
    });

    // Spinner untuk tombol simpan menu
    $('#btnSimpanMenu').click(function () {
        const form = document.getElementById('formMenu');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        $(this).prop('disabled', true)
            .html('<span class="spinner-border spinner-border-sm mr-1"></span> Menyimpan...');

        form.submit();
    });
});
</script>
@endsection