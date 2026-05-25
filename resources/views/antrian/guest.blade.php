<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar Antrian</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
            text-align: center;
        }

        .icon { font-size: 3rem; margin-bottom: 1rem; }

        h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1A1A2E;
            margin-bottom: 0.5rem;
        }

        p {
            font-size: 0.9rem;
            color: #6B7280;
            margin-bottom: 2rem;
        }

        .form-group { margin-bottom: 1.25rem; text-align: left; }

        label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: #1A1A2E;
            margin-bottom: 0.4rem;
        }

        input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1.5px solid #E5E7EB;
            border-radius: 10px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.9rem;
            outline: none;
            transition: border-color 0.2s;
        }

        input:focus { border-color: #667eea; }

        .btn {
            width: 100%;
            padding: 0.875rem;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            border-radius: 10px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: opacity 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn:hover { opacity: 0.9; }
        .btn:disabled { opacity: 0.6; cursor: not-allowed; }

        .spinner {
            width: 16px; height: 16px;
            border: 2px solid rgba(255,255,255,0.4);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
            display: none;
        }

        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</head>
<body>
<div class="card">
    <div class="icon">🎫</div>
    <h1>Daftar Antrian</h1>
    <p>Masukkan nama kamu untuk mendapatkan nomor antrian</p>

    <div class="form-group">
        <label>Nama Lengkap</label>
        <input type="text" id="inputNama" placeholder="contoh: Budi Santoso"
            maxlength="50" required>
    </div>

    <button type="button" id="btnDaftar" class="btn">
        <span class="spinner" id="spinner"></span>
        <span id="btnText">Ambil Nomor Antrian</span>
    </button>
</div>

<script>
document.getElementById('btnDaftar').addEventListener('click', function () {
    const nama = document.getElementById('inputNama').value.trim();

    if (!nama) {
        alert('Nama tidak boleh kosong!');
        return;
    }

    // Tampilkan spinner
    this.disabled = true;
    document.getElementById('spinner').style.display = 'block';
    document.getElementById('btnText').textContent   = 'Memproses...';

    // Kirim ke server
    fetch('{{ route("antrian.daftar") }}', {
        method : 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ nama: nama })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            // Buka tiket di tab baru
            window.open(`/guest/tiket/${data.id}`, '_blank');

            // Reset form
            document.getElementById('inputNama').value = '';
        } else {
            alert('Gagal mendaftar. Coba lagi.');
        }
    })
    .catch(() => alert('Gagal menghubungi server.'))
    .finally(() => {
        this.disabled = false;
        document.getElementById('spinner').style.display = 'none';
        document.getElementById('btnText').textContent   = 'Ambil Nomor Antrian';
    });
});

// Enter key
document.getElementById('inputNama').addEventListener('keypress', function (e) {
    if (e.key === 'Enter') document.getElementById('btnDaftar').click();
});
</script>
</body>
</html>