@extends('layouts.dashboard')

@section('title', 'Edit Buku')

@section('content')
<div class="page-header">
    <h3 class="page-title">Edit Buku</h3>
</div>

<div class="card">
    <div class="card-body">

        {{-- Form TANPA button submit di dalamnya --}}
        <form id="formBuku" action="{{ route('buku.update', $buku->idbuku) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group mb-3">
                <label>Kode</label>
                <input type="text" name="kode" id="kode"
                    class="form-control @error('kode') is-invalid @enderror"
                    value="{{ old('kode', $buku->kode) }}" required>
                @error('kode')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label>Judul</label>
                <input type="text" name="judul" id="judul"
                    class="form-control @error('judul') is-invalid @enderror"
                    value="{{ old('judul', $buku->judul) }}" required>
                @error('judul')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label>Pengarang</label>
                <input type="text" name="pengarang" id="pengarang"
                    class="form-control @error('pengarang') is-invalid @enderror"
                    value="{{ old('pengarang', $buku->pengarang) }}" required>
                @error('pengarang')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-4">
                <label>Kategori</label>
                <select name="idkategori" id="idkategori"
                    class="form-control @error('idkategori') is-invalid @enderror" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($kategori as $k)
                        <option value="{{ $k->idkategori }}"
                            {{ old('idkategori', $buku->idkategori) == $k->idkategori ? 'selected' : '' }}>
                            {{ $k->nama_kategori }}
                        </option>
                    @endforeach
                </select>
                @error('idkategori')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

        </form>

        {{-- Button di LUAR form --}}
        <a href="{{ route('buku.index') }}" class="btn btn-secondary">Kembali</a>
        <button type="button" id="btnUpdate" class="btn btn-success">
            <i class="mdi mdi-content-save-edit"></i> Update
        </button>

    </div>
</div>
@endsection

@section('script')
<script>
$(function () {
    $('#btnUpdate').click(function () {
        const form = document.getElementById('formBuku');

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