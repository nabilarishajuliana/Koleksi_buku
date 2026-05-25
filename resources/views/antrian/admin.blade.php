@extends('layouts.dashboard')

@section('title', 'Admin Antrian')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-ticket-account"></i>
        </span>
        Admin Antrian
    </h3>
    <div>
        {{-- Indikator SSE --}}
        <span id="sseStatus" class="badge badge-secondary">
            ⏳ Menghubungkan...
        </span>
    </div>
</div>

{{-- PANEL ATAS: Tombol panggil + info nomor sekarang --}}
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card text-center">
            <div class="card-body py-4">
                <p class="text-muted mb-1" style="font-size:0.85rem;">Sedang Dipanggil</p>
                <div id="nomorDipanggil" style="font-size:3.5rem; font-weight:700; color:#4B49AC; line-height:1;">
                    —
                </div>
                <div id="namaDipanggil" style="font-size:1rem; color:#6B7280; margin-top:0.25rem;">
                    —
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body py-4 text-center">
                <p class="text-muted mb-3" style="font-size:0.85rem;">
                    Antrian Menunggu: <strong id="totalMenunggu">0</strong> orang
                </p>
                <button type="button" id="btnPanggil"
                    class="btn btn-primary btn-lg btn-block">
                    📢 Panggil Berikutnya
                </button>
            </div>
        </div>
    </div>
</div>

<div class="row">

    {{-- KOLOM KIRI: Daftar Menunggu --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">⏳ Antrian Menunggu</h5>
                <span class="badge badge-primary" id="badgeMenunggu">0</span>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered mb-0" style="font-size:0.875rem;">
                    <thead class="thead-light">
                        <tr>
                            <th>No.</th>
                            <th>Nama</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="tabelMenunggu">
                        <tr>
                            <td colspan="3" class="text-center text-muted py-3">
                                Belum ada antrian
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- KOLOM KANAN: Daftar Terlambat --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">⚠️ Terlambat / Tidak Hadir</h5>
                <span class="badge badge-warning" id="badgeTerlambat">0</span>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered mb-0" style="font-size:0.875rem;">
                    <thead class="thead-light">
                        <tr>
                            <th>No.</th>
                            <th>Nama</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tabelTerlambat">
                        <tr>
                            <td colspan="3" class="text-center text-muted py-3">
                                Tidak ada
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@endsection

@section('script')
<script>
const csrfToken = '{{ csrf_token() }}';

// ================================================================
// SSE — terima update real-time dari server
// ================================================================
const source = new EventSource('{{ route("antrian.stream") }}');

source.addEventListener('queue-update', function (event) {
    const data = JSON.parse(event.data);
    updateUI(data);

    // Update status badge
    document.getElementById('sseStatus').className = 'badge badge-success';
    document.getElementById('sseStatus').textContent = '🟢 Real-time aktif';
});

source.onerror = function () {
    document.getElementById('sseStatus').className = 'badge badge-danger';
    document.getElementById('sseStatus').textContent = '🔴 Koneksi terputus';
};

// ================================================================
// UPDATE UI berdasarkan data SSE
// ================================================================
function updateUI(data) {
    // Update nomor yang sedang dipanggil
    if (data.dipanggil) {
        document.getElementById('nomorDipanggil').textContent =
            String(data.dipanggil.nomor_antrian).padStart(3, '0');
        document.getElementById('namaDipanggil').textContent = data.dipanggil.nama;
    } else {
        document.getElementById('nomorDipanggil').textContent = '—';
        document.getElementById('namaDipanggil').textContent  = '—';
    }

    // Update total menunggu
    document.getElementById('totalMenunggu').textContent = data.total;
    document.getElementById('badgeMenunggu').textContent = data.total;

    // Update tabel menunggu
    const tbodyMenunggu = document.getElementById('tabelMenunggu');
    if (data.menunggu.length === 0) {
        tbodyMenunggu.innerHTML = `
            <tr><td colspan="3" class="text-center text-muted py-3">Belum ada antrian</td></tr>
        `;
    } else {
        tbodyMenunggu.innerHTML = data.menunggu.map(a => `
            <tr>
                <td><strong>${String(a.nomor_antrian).padStart(3, '0')}</strong></td>
                <td>${a.nama}</td>
                <td><span class="badge badge-primary">Menunggu</span></td>
            </tr>
        `).join('');
    }

    // Update tabel terlambat
    const tbodyTerlambat = document.getElementById('tabelTerlambat');
    document.getElementById('badgeTerlambat').textContent = data.terlambat.length;

    if (data.terlambat.length === 0) {
        tbodyTerlambat.innerHTML = `
            <tr><td colspan="3" class="text-center text-muted py-3">Tidak ada</td></tr>
        `;
    } else {
        tbodyTerlambat.innerHTML = data.terlambat.map(a => `
            <tr>
                <td><strong>${String(a.nomor_antrian).padStart(3, '0')}</strong></td>
                <td>${a.nama}</td>
                <td>
                    <button type="button"
                        class="btn btn-xs btn-warning btn-panggil-terlambat"
                        data-id="${a.id}"
                        title="Panggil ulang">
                        📢 Panggil
                    </button>
                </td>
            </tr>
        `).join('');
    }
}

// ================================================================
// TOMBOL PANGGIL BERIKUTNYA
// ================================================================
document.getElementById('btnPanggil').addEventListener('click', function () {
    this.disabled = true;
    this.textContent = '⏳ Memanggil...';

    fetch('{{ route("antrian.panggil") }}', {
        method : 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'error') {
            alert(data.message);
        }
    })
    .catch(() => alert('Gagal memanggil.'))
    .finally(() => {
        this.disabled = false;
        this.textContent = '📢 Panggil Berikutnya';
    });
});

// ================================================================
// TOMBOL PANGGIL TERLAMBAT (event delegation)
// ================================================================
document.getElementById('tabelTerlambat').addEventListener('click', function (e) {
    const btn = e.target.closest('.btn-panggil-terlambat');
    if (!btn) return;

    const id = btn.dataset.id;
    btn.disabled    = true;
    btn.textContent = '⏳';

    fetch(`/antrian/panggil-terlambat/${id}`, {
        method : 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'error') alert(data.message);
    })
    .catch(() => alert('Gagal.'))
    .finally(() => {
        btn.disabled    = false;
        btn.textContent = '📢 Panggil';
    });
});
</script>
@endsection