@extends('layouts.app')

@section('title', 'Verifikasi OTP')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body text-center">

                <h4>Masukkan Kode OTP</h4>
                <p>Kode OTP sudah dikirim ke email kamu</p>

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('otp.verify') }}">
                    @csrf

                    <input type="text"
                           name="otp"
                           maxlength="6"
                           class="form-control text-center mb-3"
                           placeholder="Masukkan 6 digit OTP"
                           required>

                    <button class="btn btn-primary">
                        Verifikasi
                    </button>
                </form>

            </div>
        </div>
    </div>
</div>

@endsection
