<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vendor Login — Catto Canteen</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --primary: #5B4FCF;
            --primary-dark: #4338A8;
            --primary-light: #EEF0FF;
            --danger: #E53E3E;
            --text: #1A1A2E;
            --muted: #6B7280;
            --border: #E5E7EB;
            --bg: #F8F9FF;
            --white: #FFFFFF;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* Decorative background blobs */
        body::before {
            content: '';
            position: fixed;
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(91,79,207,0.08) 0%, transparent 70%);
            top: -200px; right: -200px;
            pointer-events: none;
        }

        body::after {
            content: '';
            position: fixed;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(91,79,207,0.05) 0%, transparent 70%);
            bottom: -100px; left: -100px;
            pointer-events: none;
        }

        .login-wrapper {
            width: 100%;
            max-width: 420px;
            padding: 1.5rem;
            position: relative;
            z-index: 1;
        }

        .login-card {
            background: var(--white);
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 4px 24px rgba(91, 79, 207, 0.08),
                        0 1px 4px rgba(0,0,0,0.05);
            border: 1px solid rgba(91,79,207,0.08);
        }

        .login-logo {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-logo .logo-icon {
            width: 64px; height: 64px;
            background: var(--primary-light);
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .login-logo h1 {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--text);
            letter-spacing: -0.02em;
        }

        .login-logo p {
            font-size: 0.85rem;
            color: var(--muted);
            margin-top: 0.25rem;
        }

        .login-title {
            margin-bottom: 1.5rem;
        }

        .login-title h2 {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 0.25rem;
        }

        .login-title p {
            font-size: 0.85rem;
            color: var(--muted);
        }

        .alert {
            padding: 0.75rem 1rem;
            border-radius: 10px;
            font-size: 0.85rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .alert-danger {
            background: #FFF5F5;
            color: var(--danger);
            border: 1px solid #FED7D7;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 0.5rem;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.9rem;
            color: var(--text);
            background: var(--white);
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(91,79,207,0.12);
        }

        .form-control.is-invalid {
            border-color: var(--danger);
        }

        .invalid-feedback {
            font-size: 0.78rem;
            color: var(--danger);
            margin-top: 0.35rem;
        }

        .btn-login {
            width: 100%;
            padding: 0.85rem;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 10px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .btn-login:hover:not(:disabled) {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(91,79,207,0.3);
        }

        .btn-login:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .spinner {
            width: 16px; height: 16px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
            display: none;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .back-link {
            text-align: center;
            margin-top: 1.5rem;
        }

        .back-link a {
            font-size: 0.83rem;
            color: var(--muted);
            text-decoration: none;
            transition: color 0.2s;
        }

        .back-link a:hover { color: var(--primary); }

        .divider {
            height: 1px;
            background: var(--border);
            margin: 1.5rem 0;
        }
    </style>
</head>
<body>

<div class="login-wrapper">
    <div class="login-card">

        <div class="login-logo">
            <div class="logo-icon">🐱</div>
            <h1>Catto Canteen</h1>
            <p>Portal Vendor</p>
        </div>

        <div class="divider"></div>

        <div class="login-title">
            <h2>Selamat datang! 👋</h2>
            <p>Masuk untuk kelola menu dan pesanan kamu.</p>
        </div>

        @if(session('error'))
        <div class="alert alert-danger">
            ⚠️ {{ session('error') }}
        </div>
        @endif

        <form id="formLogin" method="POST" action="{{ route('vendor.login.post') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email"
                    class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                    placeholder="vendor@email.com"
                    value="{{ old('email') }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password"
                    class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                    placeholder="••••••••" required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

        </form>

        <button type="button" id="btnLogin" class="btn-login">
            <span class="spinner" id="spinner"></span>
            <span id="btnText">Masuk</span>
        </button>

        <div class="back-link">
            <a href="{{ url('/') }}">← Kembali ke halaman utama</a>
        </div>

    </div>
</div>

<script>
document.getElementById('btnLogin').addEventListener('click', function () {
    const form = document.getElementById('formLogin');

    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    this.disabled = true;
    document.getElementById('spinner').style.display = 'block';
    document.getElementById('btnText').textContent = 'Masuk...';

    form.submit();
});
</script>
</body>
</html>