<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Jabatan Hutan Sarawak — Portal Perkhidmatan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500&display=swap" rel="stylesheet"/>
    <link rel="icon" href="{{ asset('images/logo-icon.png')}}">
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <style>
        .ticket-modal-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,0.4);
            justify-content: center; align-items: center;
            z-index: 999;
        }
        .ticket-modal-overlay.active { display: flex; }
        .ticket-modal {
            background: #fff; border-radius: 12px;
            padding: 0; width: 100%; max-width: 480px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.15);
            transform: translateY(20px) scale(0.97);
            opacity: 0;
            transition: transform 0.25s ease, opacity 0.25s ease;
            overflow: hidden;
        }
        .ticket-modal-overlay.active .ticket-modal {
            transform: translateY(0) scale(1);
            opacity: 1;
        }
        .ticket-modal-header {
            background: #1a4731; padding: 1rem 1.25rem;
            display: flex; justify-content: space-between; align-items: center;
        }
        .ticket-modal-header h3 { color: #fff; font-size: 14px; margin: 0; font-weight: 500; }
        .ticket-modal-close {
            background: none; border: none; color: rgba(255,255,255,0.7);
            font-size: 20px; cursor: pointer; line-height: 1; padding: 0;
            transition: color 0.15s;
        }
        .ticket-modal-close:hover { color: #fff; }
        .ticket-modal-body { padding: 1.25rem; }
        .ticket-field { margin-bottom: 0.75rem; }
        .ticket-field label { font-size: 11px; color: #999; display: block; margin-bottom: 3px; text-transform: uppercase; letter-spacing: 0.05em; }
        .ticket-field p { font-size: 13px; color: #222; margin: 0; font-weight: 500; }
        .ticket-field-row { display: flex; gap: 1rem; }
        .ticket-field-row .ticket-field { flex: 1; }
        .ticket-not-found { text-align: center; padding: 1rem 0; }
        .ticket-not-found p { color: #a32d2d; font-size: 13px; }
    </style>
</head>
<body>

<header>
    <div class="logo"></div>
    <div>
        <h1>Jabatan Hutan Sarawak</h1>
        <p>Hub Aplikasi Perkhidmatan Atas Talian</p>
    </div>
</header>

<x-navbar />

<div class="hero">
    <div class="hero-text">
        <h2>Selamat Datang ke Portal Perkhidmatan</h2>
        <p>Portal ini menyediakan perkhidmatan dalaman untuk kakitangan Jabatan Hutan Sarawak. Sila pilih perkhidmatan yang diperlukan di bawah.</p>
    </div>
    <div class="hero-search">
        <form method="POST" action="/semak-tiket">
            @csrf
            <label>Masukkan No. Rujukan anda untuk semak status</label>
            <div class="hero-search-row">
                <input type="text" name="no_tiket"
                    value="{{ old('no_tiket', $tiket ?? '') }}"
                    placeholder="Cth: JHS/ICT/A/2026(1)"
                    required>
                <button type="submit">Semak</button>
            </div>
        </form>
    </div>
</div>

<div class="section">
    @if(session('success'))
        <div style="background:#eaf3de; border:1px solid #c0dd97; color:#3b6d11; padding:0.75rem 1rem; border-radius:8px; margin-bottom:1rem; font-size:14px;">
            {{ session('success') }}
        </div>
    @endif

    <p class="section-title">Perkhidmatan Yang Ditawarkan</p>
    <div class="cards">
        <a href="/forms/ict-aduan" class="card">
            <div class="card-icon icon-ict">💻</div>
            <h3>Borang Aduan ICT</h3>
            <p>Laporkan kerosakan atau masalah berkaitan peralatan ICT dan digital di pejabat anda.</p>
            <span class="card-link">Hantar aduan →</span>
        </a>
        <a href="/forms/portal-upload" class="card">
            <div class="card-icon icon-upload">📂</div>
            <h3>Borang Permohonan Muat Naik Portal</h3>
            <p>Hantar permohonan untuk memuat naik kandungan baharu ke portal rasmi jabatan.</p>
            <span class="card-link">Hantar permohonan →</span>
        </a>
        <a href="/booking/calendar" class="card">
            <div class="card-icon icon-track">📅</div>
            <h3>Tempah Bilik Mesyuarat</h3>
            <p>Lihat kekosongan dan tempah bilik mesyuarat di sini.</p>
            <span class="card-link">Tempah sekarang →</span>
        </a>
    </div>
</div>

<footer>
    <div><strong>Jabatan Hutan Sarawak</strong> &nbsp;|&nbsp; Bangunan Baitul Makmur II, Medan Raya, Petra Jaya, 93050 Kuching, Sarawak</div>
    <div>© <?php echo date("Y"); ?> Jabatan Hutan Sarawak. Hak Cipta Terpelihara.</div>
</footer>

{{-- Ticket result modal --}}
@isset($tiket)
<div class="ticket-modal-overlay" id="ticketModal" onclick="if(event.target===this)closeTicketModal()">
    <div class="ticket-modal">
        <div class="ticket-modal-header">
            <h3>Status No. Rujukan — {{ $tiket }}</h3>
            <button class="ticket-modal-close" onclick="closeTicketModal()">×</button>
        </div>
        <div class="ticket-modal-body">
            @if($result)
                <div class="ticket-field-row">
                    <div class="ticket-field">
                        <label>No. Rujukan</label>
                        <p>{{ $result->no_tiket }}</p>
                    </div>
                    <div class="ticket-field">
                        <label>Nama</label>
                        <p>{{ $result->nama }}</p>
                    </div>
                </div>

                @if($type === 'ict')
                    <div class="ticket-field-row">
                        <div class="ticket-field">
                            <label>Kategori Masalah</label>
                            <p>{{ $result->kategori_masalah }}</p>
                        </div>
                        <div class="ticket-field">
                            <label>Tarikh Aduan</label>
                            <p>{{ \Carbon\Carbon::parse($result->tarikh_aduan)->format('d/m/Y') }}</p>
                        </div>
                    </div>
                @else
                    <div class="ticket-field-row">
                        <div class="ticket-field">
                            <label>Tajuk Maklumat</label>
                            <p>{{ $result->tajuk_maklumat }}</p>
                        </div>
                        <div class="ticket-field">
                            <label>Tarikh Hantar</label>
                            <p>{{ \Carbon\Carbon::parse($result->created_at)->format('d/m/Y') }}</p>
                        </div>
                    </div>
                @endif

                <div class="ticket-field" style="margin-top:0.25rem;">
                    <label>Status</label>
                    @php $status = $result->status; @endphp
                    @if(in_array($status, ['Belum Selesai', 'Pending']))
                        <span class="badge badge-pending">{{ $status }}</span>
                    @elseif(in_array($status, ['Dalam Tindakan', 'Dalam Semakan']))
                        <span class="badge badge-progress">{{ $status }}</span>
                    @else
                        <span class="badge badge-done">{{ $status }}</span>
                    @endif
                </div>

                @if($type === 'mnb' && !empty($result->catatan_semakan))
                    <div class="ticket-field" style="margin-top:0.75rem;">
                        <label>Catatan Penyelia</label>
                        <div style="background:#f9fafb; border:1px solid #eee; border-radius:8px; padding:0.75rem; font-size:13px; color:#333; line-height:1.6; margin-top:4px;">
                            {{ $result->catatan_semakan }}
                        </div>
                    </div>
                @endif
            @else
                <div class="ticket-not-found">
                    <p style="font-size:32px; margin-bottom:0.5rem;">🔍</p>
                    <p>Tiket <strong>{{ $tiket }}</strong> tidak dijumpai.</p>
                    <p style="color:#777; margin-top:0.25rem;">Sila semak semula No. Rujukan anda.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    // auto open on page load if ticket was searched
    window.addEventListener('DOMContentLoaded', function() {
        document.getElementById('ticketModal').classList.add('active');
    });

    function closeTicketModal() {
        const overlay = document.getElementById('ticketModal');
        const modal   = overlay.querySelector('.ticket-modal');
        modal.style.transform = 'translateY(10px) scale(0.97)';
        modal.style.opacity   = '0';
        setTimeout(() => { overlay.classList.remove('active'); }, 220);
    }
</script>
@endisset

</body>
</html>