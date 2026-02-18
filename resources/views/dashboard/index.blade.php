@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('content')

<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-home"></i>
        </span> Dashboard
    </h3>
</div>

<div class="row">
    <div class="col-md-4 stretch-card grid-margin">
        <div class="card bg-gradient-danger text-white">
            <div class="card-body">
                <h4>Total Buku</h4>
                <h2>{{ \App\Models\Buku::count() }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-4 stretch-card grid-margin">
        <div class="card bg-gradient-success text-white">
            <div class="card-body">
                <h4>Total Kategori</h4>
                <h2>{{ \App\Models\Kategori::count() }}</h2>
            </div>
        </div>
    </div>
</div>

@endsection
