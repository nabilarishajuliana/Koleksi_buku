<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Koleksi Buku</title>

    <!-- CSS GLOBAL PURPLE -->
    <link rel="stylesheet" href="{{ asset('template/assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/css/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('template/assets/images/favicon.png') }}" />
</head>

<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth">
                <div class="row flex-grow">
                    <div class="col-lg-4 mx-auto">
                        <div class="auth-form-light text-left p-5">

                            <div class="brand-logo text-center">
                                <img src="{{ asset('template/assets/images/logo.svg') }}" alt="logo">
                            </div>

                            <h4 class="text-center">Halo! Selamat Datang 👋</h4>
                            <h6 class="font-weight-light text-center">
                                Silakan login untuk melanjutkan.
                            </h6>

                            <!-- {{-- ALERT ERROR LOGIN --}}
                        @if(session('error'))
                            <div class="alert alert-danger mt-3">
                                {{ session('error') }}
                            </div>
                        @endif -->

                            <form class="pt-3" method="POST" action="{{ route('login') }}">
                                @csrf

                                {{-- EMAIL --}}
                                <div class="form-group">
                                    <input type="email"
                                        name="email"
                                        value="{{ old('email') }}"
                                        class="form-control form-control-lg @error('email') is-invalid @enderror"
                                        placeholder="Email"
                                        required
                                        autofocus>

                                    @error('email')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                {{-- PASSWORD --}}
                                <div class="form-group">
                                    <input type="password"
                                        name="password"
                                        class="form-control form-control-lg @error('password') is-invalid @enderror"
                                        placeholder="Password"
                                        required>

                                    @error('password')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                {{-- REMEMBER --}}
                                <div class="form-group">
                                    <div class="form-check">
                                        <label class="form-check-label text-muted">
                                            <input type="checkbox"
                                                class="form-check-input"
                                                name="remember"
                                                {{ old('remember') ? 'checked' : '' }}>
                                            Ingat Saya
                                            <i class="input-helper"></i>
                                        </label>
                                    </div>
                                </div>

                                {{-- BUTTON --}}
                                <div class="mt-3">
                                    <button type="submit"
                                        class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">
                                        LOGIN
                                    </button>
                                </div>

                                <div class="mt-3 text-center">
                                    <a href="{{ route('google.login') }}"
                                        class="btn btn-danger btn-block">
                                        Login with Google
                                    </a>
                                </div>


                                {{-- FORGOT PASSWORD --}}
                                <div class="my-2 d-flex justify-content-between align-items-center">
                                    @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}"
                                        class="auth-link text-primary">
                                        Lupa password?
                                    </a>
                                    @endif
                                </div>

                                {{-- REGISTER --}}
                                <div class="text-center mt-4 font-weight-light">
                                    Belum punya akun?
                                    <a href="{{ route('register') }}" class="text-primary">
                                        Daftar
                                    </a>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- JS GLOBAL PURPLE --}}
    <script src="{{ asset('template/assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('template/assets/js/off-canvas.js') }}"></script>
    <script src="{{ asset('template/assets/js/misc.js') }}"></script>

</body>

</html>