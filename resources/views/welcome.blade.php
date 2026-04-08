<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Catto Canteen — Pesan Makan Online</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --cream:   #FAF6EF;
            --brown:   #3B1F0A;
            --orange:  #E8622A;
            --gold:    #C9952A;
            --muted:   #9B7E65;
            --light:   #F0E8DA;
        }

        html { scroll-behavior: smooth; }

        body {
            background-color: var(--cream);
            color: var(--brown);
            font-family: 'DM Sans', sans-serif;
            min-height: 100vh;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 0;
            opacity: 0.5;
        }

        /* ── NAVBAR ── */
        nav {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.25rem 3rem;
            background: rgba(250, 246, 239, 0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(59, 31, 10, 0.08);
        }

        .nav-logo {
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem;
            font-weight: 900;
            color: var(--brown);
            letter-spacing: -0.02em;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            text-decoration: none;
        }

        .nav-logo span { color: var(--orange); }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .btn-outline {
            padding: 0.5rem 1.25rem;
            border: 1.5px solid var(--brown);
            border-radius: 100px;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--brown);
            text-decoration: none;
            transition: all 0.2s ease;
            background: transparent;
        }

        .btn-outline:hover {
            background: var(--brown);
            color: var(--cream);
        }

        .btn-fill {
            padding: 0.5rem 1.25rem;
            border: 1.5px solid var(--orange);
            border-radius: 100px;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--cream);
            text-decoration: none;
            background: var(--orange);
            transition: all 0.2s ease;
        }

        .btn-fill:hover {
            background: #C94F1A;
            border-color: #C94F1A;
        }

        /* ── HERO ── */
        .hero {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
            align-items: center;
            padding: 6rem 3rem 3rem;
            gap: 3rem;
            position: relative;
            z-index: 1;
        }

        .hero-text { max-width: 560px; }

        .hero-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.75rem;
            font-weight: 500;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--orange);
            margin-bottom: 1.5rem;
            opacity: 0;
            animation: fadeUp 0.6s ease forwards 0.1s;
        }

        .hero-eyebrow::before {
            content: '';
            display: block;
            width: 2rem;
            height: 1.5px;
            background: var(--orange);
        }

        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(3rem, 5vw, 5rem);
            font-weight: 900;
            line-height: 1.0;
            letter-spacing: -0.03em;
            color: var(--brown);
            margin-bottom: 1.5rem;
            opacity: 0;
            animation: fadeUp 0.6s ease forwards 0.25s;
        }

        .hero-title em {
            font-style: italic;
            color: var(--orange);
        }

        .hero-desc {
            font-size: 1.05rem;
            font-weight: 300;
            line-height: 1.7;
            color: var(--muted);
            margin-bottom: 2.5rem;
            opacity: 0;
            animation: fadeUp 0.6s ease forwards 0.4s;
        }

        .hero-cta {
            display: flex;
            align-items: center;
            gap: 1rem;
            opacity: 0;
            animation: fadeUp 0.6s ease forwards 0.55s;
        }

        .cta-primary {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.875rem 2rem;
            background: var(--brown);
            color: var(--cream);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            border-radius: 100px;
            transition: all 0.25s ease;
            border: 2px solid var(--brown);
        }

        .cta-primary:hover {
            background: var(--orange);
            border-color: var(--orange);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(232, 98, 42, 0.3);
        }

        .cta-primary svg { transition: transform 0.2s ease; }
        .cta-primary:hover svg { transform: translateX(3px); }

        .cta-secondary {
            font-size: 0.9rem;
            color: var(--muted);
            text-decoration: none;
            font-weight: 400;
            display: flex;
            align-items: center;
            gap: 0.3rem;
            transition: color 0.2s ease;
        }

        .cta-secondary:hover { color: var(--brown); }

        /* ── HERO VISUAL ── */
        .hero-visual {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            animation: fadeIn 0.8s ease forwards 0.4s;
        }

        .hero-card-stack {
            position: relative;
            width: 320px;
            height: 420px;
        }

        .food-card {
            position: absolute;
            width: 260px;
            background: white;
            border-radius: 20px;
            padding: 1.25rem;
            box-shadow: 0 20px 60px rgba(59, 31, 10, 0.12);
        }

        .food-card:nth-child(1) {
            top: 0; left: 0;
            transform: rotate(-4deg);
            background: var(--light);
        }

        .food-card:nth-child(2) {
            top: 40px; left: 40px;
            transform: rotate(2deg);
            background: white;
            animation: float 4s ease-in-out infinite;
        }

        .food-card:nth-child(3) {
            top: 20px; left: 20px;
            width: 60px; height: 60px;
            border-radius: 50%;
            background: var(--orange);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            box-shadow: 0 8px 20px rgba(232, 98, 42, 0.4);
            z-index: 10;
            animation: float 3s ease-in-out infinite 0.5s;
        }

        .food-emoji { font-size: 3rem; text-align: center; margin-bottom: 0.75rem; }
        .food-name { font-family: 'Playfair Display', serif; font-weight: 700; font-size: 1.1rem; color: var(--brown); margin-bottom: 0.25rem; }
        .food-vendor { font-size: 0.75rem; color: var(--muted); margin-bottom: 0.75rem; }
        .food-price { font-weight: 500; color: var(--orange); font-size: 0.95rem; }

        /* ── BG CIRCLES ── */
        .bg-circle { position: absolute; border-radius: 50%; pointer-events: none; }
        .bg-circle-1 { width: 500px; height: 500px; background: radial-gradient(circle, rgba(232,98,42,0.07) 0%, transparent 70%); top: -100px; right: -100px; }
        .bg-circle-2 { width: 300px; height: 300px; background: radial-gradient(circle, rgba(201,149,42,0.08) 0%, transparent 70%); bottom: 50px; left: -80px; }

        /* ── FEATURES STRIP ── */
        .features-strip {
            position: relative;
            z-index: 1;
            background: var(--brown);
            padding: 2rem 3rem;
            display: flex;
            justify-content: center;
            gap: 4rem;
        }

        .feature-item { display: flex; align-items: center; gap: 0.75rem; color: var(--cream); }
        .feature-icon { font-size: 1.5rem; }
        .feature-text strong { display: block; font-size: 0.9rem; font-weight: 500; }
        .feature-text span { font-size: 0.75rem; opacity: 0.6; }

        /* ── HOW IT WORKS ── */
        .how-section {
            position: relative;
            z-index: 1;
            padding: 5rem 3rem;
            text-align: center;
        }

        .section-label { font-size: 0.72rem; letter-spacing: 0.18em; text-transform: uppercase; color: var(--orange); font-weight: 500; margin-bottom: 1rem; }

        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2rem, 3.5vw, 3rem);
            font-weight: 900;
            color: var(--brown);
            margin-bottom: 3.5rem;
            letter-spacing: -0.02em;
        }

        .steps-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem; max-width: 800px; margin: 0 auto; }

        .step-card {
            background: white;
            border-radius: 16px;
            padding: 2rem 1.5rem;
            border: 1px solid rgba(59,31,10,0.07);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .step-card:hover { transform: translateY(-6px); box-shadow: 0 20px 40px rgba(59, 31, 10, 0.1); }

        .step-num {
            width: 42px; height: 42px;
            background: var(--light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Playfair Display', serif;
            font-weight: 900;
            font-size: 1rem;
            color: var(--orange);
            margin: 0 auto 1rem;
        }

        .step-title { font-family: 'Playfair Display', serif; font-weight: 700; font-size: 1rem; color: var(--brown); margin-bottom: 0.5rem; }
        .step-desc { font-size: 0.85rem; color: var(--muted); line-height: 1.6; }

        /* ── CTA BANNER ── */
        .cta-banner {
            position: relative;
            z-index: 1;
            margin: 0 3rem 5rem;
            background: var(--orange);
            border-radius: 24px;
            padding: 3.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            overflow: hidden;
        }

        .cta-banner::before {
            content: '🍜';
            position: absolute;
            font-size: 8rem;
            right: 12%;
            top: 50%;
            transform: translateY(-50%);
            opacity: 0.15;
        }

        .cta-banner-text h2 { font-family: 'Playfair Display', serif; font-size: 2rem; font-weight: 900; color: white; margin-bottom: 0.5rem; }
        .cta-banner-text p { color: rgba(255,255,255,0.8); font-size: 0.95rem; }

        .cta-banner-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.875rem 2rem;
            background: white;
            color: var(--orange);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            border-radius: 100px;
            transition: all 0.25s ease;
            white-space: nowrap;
        }

        .cta-banner-btn:hover { background: var(--brown); color: white; transform: translateY(-2px); }

        /* ── FOOTER ── */
        footer {
            position: relative;
            z-index: 1;
            border-top: 1px solid rgba(59,31,10,0.1);
            padding: 1.5rem 3rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        footer p { font-size: 0.8rem; color: var(--muted); }

        .footer-links {
            display: flex;
            gap: 1.25rem;
        }

        .footer-links a {
            font-size: 0.8rem;
            color: var(--muted);
            text-decoration: none;
            transition: color 0.2s;
        }

        .footer-links a:hover { color: var(--brown); }

        /* ── ANIMATIONS ── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }

        @keyframes float {
            0%, 100% { transform: rotate(2deg) translateY(0); }
            50%       { transform: rotate(2deg) translateY(-10px); }
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 768px) {
            nav { padding: 1rem 1.5rem; }
            .hero { grid-template-columns: 1fr; padding: 5rem 1.5rem 3rem; text-align: center; }
            .hero-eyebrow { justify-content: center; }
            .hero-cta { justify-content: center; flex-wrap: wrap; }
            .hero-visual { display: none; }
            .features-strip { flex-direction: column; gap: 1.5rem; padding: 2rem 1.5rem; }
            .steps-grid { grid-template-columns: 1fr; }
            .how-section { padding: 3rem 1.5rem; }
            .cta-banner { margin: 0 1.5rem 3rem; flex-direction: column; gap: 1.5rem; text-align: center; }
            footer { flex-direction: column; gap: 0.75rem; text-align: center; padding: 1.5rem; }
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav>
    <a href="{{ url('/') }}" class="nav-logo">
        🐱 Catto <span>Canteen</span>
    </a>
    <div class="nav-links">
        {{-- Tombol Login Vendor (selalu tampil) --}}
        <a href="{{ route('vendor.login') }}" class="btn-outline">Login Vendor</a>

        {{-- Tombol untuk user koleksi buku --}}
        @if (Route::has('login'))
            @auth
                <a href="{{ url('/dashboard') }}" class="btn-fill">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn-fill">Masuk</a>
            @endauth
        @endif
    </div>
</nav>

<!-- DECORATIVE BG -->
<div class="bg-circle bg-circle-1"></div>
<div class="bg-circle bg-circle-2"></div>

<!-- HERO -->
<section class="hero">
    <div class="hero-text">
        <div class="hero-eyebrow">Kantin Online · Cepat & Mudah</div>
        <h1 class="hero-title">
            Makan siang jadi lebih <em>gampang</em>
        </h1>
        <p class="hero-desc">
            Pesan makanan dari berbagai kantin favoritmu, bayar langsung, dan tinggal ambil. Tidak perlu antri panjang lagi.
        </p>
        <div class="hero-cta">
            <a href="{{ route('customer.pesan') }}" class="cta-primary">
                Pesan Sekarang
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                    <path d="M3 8H13M13 8L9 4M13 8L9 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
            {{-- Link vendor login di hero (selalu tampil) --}}
            <a href="{{ route('vendor.login') }}" class="cta-secondary">
                Masuk sebagai vendor →
            </a>
        </div>
    </div>

    <div class="hero-visual">
        <div class="hero-card-stack">
            <div class="food-card">
                <div class="food-emoji">🍱</div>
                <div class="food-name">Nasi Kotak Spesial</div>
                <div class="food-vendor">Kantin Bu Sari</div>
                <div class="food-price">Rp 18.000</div>
            </div>
            <div class="food-card">
                <div class="food-emoji">🍜</div>
                <div class="food-name">Mie Ayam Jumbo</div>
                <div class="food-vendor">Warung Pak Budi</div>
                <div class="food-price">Rp 13.000</div>
            </div>
            <div class="food-card">🛒</div>
        </div>
    </div>
</section>

<!-- FEATURES STRIP -->
<div class="features-strip">
    <div class="feature-item">
        <div class="feature-icon">⚡</div>
        <div class="feature-text">
            <strong>Pesan Cepat</strong>
            <span>Tanpa daftar akun</span>
        </div>
    </div>
    <div class="feature-item">
        <div class="feature-icon">💳</div>
        <div class="feature-text">
            <strong>Bayar Online</strong>
            <span>Via VA, QRIS, & kartu</span>
        </div>
    </div>
    <div class="feature-item">
        <div class="feature-icon">🏪</div>
        <div class="feature-text">
            <strong>Multi Vendor</strong>
            <span>Berbagai pilihan kantin</span>
        </div>
    </div>
    <div class="feature-item">
        <div class="feature-icon">🔒</div>
        <div class="feature-text">
            <strong>Aman & Terpercaya</strong>
            <span>Powered by Midtrans</span>
        </div>
    </div>
</div>

<!-- HOW IT WORKS -->
<section class="how-section">
    <div class="section-label">Cara Pemesanan</div>
    <h2 class="section-title">Mudah dalam 3 langkah</h2>
    <div class="steps-grid">
        <div class="step-card">
            <div class="step-num">1</div>
            <div class="step-title">Pilih Kantin & Menu</div>
            <p class="step-desc">Pilih kantin favoritmu, lalu pilih menu yang kamu mau. Bisa lebih dari satu!</p>
        </div>
        <div class="step-card">
            <div class="step-num">2</div>
            <div class="step-title">Bayar Online</div>
            <p class="step-desc">Bayar langsung lewat Virtual Account, QRIS, atau kartu kredit/debit.</p>
        </div>
        <div class="step-card">
            <div class="step-num">3</div>
            <div class="step-title">Ambil Pesanan</div>
            <p class="step-desc">Setelah pembayaran terkonfirmasi, tinggal datang dan ambil pesananmu!</p>
        </div>
    </div>
</section>

<!-- CTA BANNER -->
<div class="cta-banner">
    <div class="cta-banner-text">
        <h2>Lapar? Pesan sekarang!</h2>
        <p>Tidak perlu daftar akun. Langsung pesan dan bayar.</p>
    </div>
    <a href="{{ route('customer.pesan') }}" class="cta-banner-btn">
        Mulai Pesan
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
            <path d="M3 8H13M13 8L9 4M13 8L9 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </a>
</div>

<!-- FOOTER -->
<footer>
    <p>© {{ date('Y') }} Catto Canteen. Dibuat dengan ❤️ untuk kemudahan makan siang.</p>
    <div class="footer-links">
        <a href="{{ route('vendor.login') }}">Portal Vendor</a>
        @guest
        <a href="{{ route('login') }}">Login Admin</a>
        @endguest
        @auth
        <a href="{{ url('/dashboard') }}">Dashboard</a>
        @endauth
    </div>
</footer>

</body>
</html>