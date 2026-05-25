<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Papan Antrian</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #0F0F1A;
            color: white;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-size: 1.5rem;
            font-weight: 700;
        }

        .header .waktu {
            font-size: 1.2rem;
            font-weight: 600;
            opacity: 0.9;
        }

        .main {
            flex: 1;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            padding: 2rem;
        }

        /* Panel kiri: nomor dipanggil */
        .panel-dipanggil {
            background: linear-gradient(135deg, #1a1a2e, #16213e);
            border: 2px solid #667eea;
            border-radius: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            text-align: center;
        }

        .label-dipanggil {
            font-size: 1rem;
            font-weight: 500;
            color: #9CA3AF;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            margin-bottom: 1rem;
        }

        .nomor-besar {
            font-size: 8rem;
            font-weight: 800;
            color: #667eea;
            line-height: 1;
            margin-bottom: 0.5rem;
            transition: all 0.5s ease;
        }

        .nama-besar {
            font-size: 2rem;
            font-weight: 600;
            color: white;
            margin-bottom: 0.5rem;
        }

        .flash {
            animation: flashEffect 0.5s ease-in-out 3;
        }

        @keyframes flashEffect {
            0%, 100% { opacity: 1; }
            50%       { opacity: 0.3; }
        }

        /* Panel kanan: antrian menunggu */
        .panel-menunggu {
            background: #1a1a2e;
            border-radius: 20px;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .panel-menunggu-header {
            background: rgba(102, 126, 234, 0.2);
            padding: 1rem 1.5rem;
            font-size: 0.85rem;
            font-weight: 600;
            color: #9CA3AF;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        .list-menunggu {
            flex: 1;
            overflow-y: auto;
            padding: 1rem;
        }

        .item-antrian {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            margin-bottom: 0.5rem;
            background: rgba(255,255,255,0.05);
        }

        .item-antrian .nomor {
            font-size: 1.5rem;
            font-weight: 700;
            color: #667eea;
            min-width: 60px;
        }

        .item-antrian .nama {
            font-size: 1rem;
            color: white;
        }

        /* Footer */
        .footer {
            background: #1a1a2e;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.82rem;
            color: #6B7280;
        }

        .sse-status { display: flex; align-items: center; gap: 0.5rem; }
        .dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: #10B981;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50%       { opacity: 0.4; }
        }

        /* Tombol aktifkan suara */
        #btnAktifkanSuara {
            position: fixed;
            top: 1rem; right: 1rem;
            background: rgba(102,126,234,0.9);
            color: white;
            border: none;
            border-radius: 100px;
            padding: 0.5rem 1.25rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            z-index: 100;
        }

        #btnAktifkanSuara.aktif {
            background: rgba(16, 185, 129, 0.9);
        }
    </style>
</head>
<body>

{{-- Audio dingdong --}}
<audio id="audioDingdong" src="{{ asset('sounds/dingdong.mp3') }}" preload="auto"></audio>

{{-- Tombol aktifkan suara (wajib diklik dulu) --}}
<button id="btnAktifkanSuara">🔇 Aktifkan Suara</button>

<div class="header">
    <h1>🏥 Papan Antrian</h1>
    <div class="waktu" id="jamSekarang">00:00:00</div>
</div>

<div class="main">

    {{-- Panel Dipanggil --}}
    <div class="panel-dipanggil">
        <div class="label-dipanggil">Sedang Dipanggil</div>
        <div class="nomor-besar" id="nomorPapan">—</div>
        <div class="nama-besar" id="namaPapan">—</div>
        <div style="font-size:0.82rem; color:#6B7280; margin-top:0.5rem;">
            Silakan masuk ke ruangan
        </div>
    </div>

    {{-- Panel Menunggu --}}
    <div class="panel-menunggu">
        <div class="panel-menunggu-header">
            Antrian Menunggu (<span id="totalPapan">0</span>)
        </div>
        <div class="list-menunggu" id="listMenungguPapan">
            <div style="text-align:center; color:#6B7280; padding:2rem; font-size:0.85rem;">
                Belum ada antrian
            </div>
        </div>
    </div>

</div>

<div class="footer">
    <div class="sse-status">
        <div class="dot" id="dot"></div>
        <span id="sseStatusPapan">Menghubungkan ke server...</span>
    </div>
    <div>Update terakhir: <span id="lastUpdate">—</span></div>
</div>

<script>
// ================================================================
// JAM REAL-TIME
// ================================================================
function updateJam() {
    const now = new Date();
    document.getElementById('jamSekarang').textContent =
        now.toLocaleTimeString('id-ID');
}
setInterval(updateJam, 1000);
updateJam();

// ================================================================
// STATE SUARA
// ================================================================
let suaraAktif   = false;
let nomorSebelumnya = null; // untuk deteksi nomor baru dipanggil

document.getElementById('btnAktifkanSuara').addEventListener('click', function () {
    suaraAktif = true;
    this.textContent = '🔊 Suara Aktif';
    this.classList.add('aktif');

    // Test suara sekali untuk unlock audio policy browser
    const audio = document.getElementById('audioDingdong');
    audio.volume = 0.01;
    audio.play().then(() => { audio.pause(); audio.volume = 1; });
});

// ================================================================
// FUNGSI SUARA PANGGILAN
// ================================================================
function bunyikanPanggilan(nomor, nama) {
    if (!suaraAktif) return;

    const audio = document.getElementById('audioDingdong');
    audio.currentTime = 0;
    audio.play();

    // Setelah dingdong selesai → Web Speech API baca teks
    audio.onended = function () {
        if (!('speechSynthesis' in window)) return;

        window.speechSynthesis.cancel();

        const pesan = new SpeechSynthesisUtterance(
            `Nomor antrian ${nomor}. ${nama}. Silakan masuk.`
        );
        pesan.lang   = 'id-ID';
        pesan.rate   = 0.85;
        pesan.pitch  = 1.0;
        pesan.volume = 1.0;

        window.speechSynthesis.speak(pesan);
    };
}

// ================================================================
// SSE — terima update dari server
// ================================================================
const source = new EventSource('{{ route("antrian.stream") }}');

source.addEventListener('queue-update', function (event) {
    const data = JSON.parse(event.data);

    // Update status koneksi
    document.getElementById('sseStatusPapan').textContent = 'Terhubung — Real-time aktif';
    document.getElementById('dot').style.background       = '#10B981';
    document.getElementById('lastUpdate').textContent     = data.timestamp;

    // Update nomor dipanggil
    if (data.dipanggil) {
        const nomorBaru = data.dipanggil.nomor_antrian;
        const namaBaru  = data.dipanggil.nama;

        // Kalau nomor berubah → animasi flash + bunyi
        if (nomorBaru !== nomorSebelumnya) {
            nomorSebelumnya = nomorBaru;

            // Animasi flash
            const elNomor = document.getElementById('nomorPapan');
            elNomor.classList.remove('flash');
            void elNomor.offsetWidth; // reset animasi
            elNomor.classList.add('flash');

            // Bunyi panggilan
            bunyikanPanggilan(nomorBaru, namaBaru);
        }

        document.getElementById('nomorPapan').textContent =
            String(nomorBaru).padStart(3, '0');
        document.getElementById('namaPapan').textContent = namaBaru;
    } else {
        document.getElementById('nomorPapan').textContent = '—';
        document.getElementById('namaPapan').textContent  = '—';
    }

    // Update list menunggu
    document.getElementById('totalPapan').textContent = data.total;
    const listEl = document.getElementById('listMenungguPapan');

    if (data.menunggu.length === 0) {
        listEl.innerHTML = `
            <div style="text-align:center; color:#6B7280; padding:2rem; font-size:0.85rem;">
                Belum ada antrian
            </div>
        `;
    } else {
        listEl.innerHTML = data.menunggu.map(a => `
            <div class="item-antrian">
                <div class="nomor">${String(a.nomor_antrian).padStart(3, '0')}</div>
                <div class="nama">${a.nama}</div>
            </div>
        `).join('');
    }
});

source.onerror = function () {
    document.getElementById('sseStatusPapan').textContent = 'Koneksi terputus — mencoba reconnect...';
    document.getElementById('dot').style.background       = '#EF4444';
};
</script>

</body>
</html>