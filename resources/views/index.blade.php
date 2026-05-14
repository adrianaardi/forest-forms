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
        /* ── overlay ── */
        .ticket-modal-overlay {
            display: flex;
            position: fixed; inset: 0;
            background: rgba(0, 0, 0, 0);
            justify-content: center; align-items: center;
            z-index: 999;
            pointer-events: none;
            transition: background 0.3s ease;
        }
        .ticket-modal-overlay.active {
            background: rgba(0, 0, 0, 0.45);
            pointer-events: auto;
        }

        /* ── modal ── */
        .ticket-modal {
            background:#f7f4f4;
            border-radius: 14px;
            padding: 0;
            width: 100%;
            max-width: 480px;
            box-shadow: 0 16px 48px rgba(0, 0, 0, 0);
            transform: translateY(24px) scale(0.96);
            opacity: 0;
            transition:
                transform 0.35s cubic-bezier(0.22, 1, 0.36, 1),
                opacity   0.3s ease,
                box-shadow 0.3s ease;
            overflow: hidden;
        }
        .ticket-modal-overlay.active .ticket-modal {
            transform: translateY(0) scale(1);
            opacity: 1;
            box-shadow: 0 16px 48px rgba(0, 0, 0, 0.18);
        }

        /* ── header ── */
        .ticket-modal-header {
            background: #2C3E50;
            padding: 1rem 1.25rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .ticket-modal-header h3 {
            color:#f7f4f4;
            font-size: 14px;
            margin: 0;
            font-weight: 500;
        }
        .ticket-modal-close {
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.6);
            font-size: 22px;
            cursor: pointer;
            line-height: 1;
            padding: 0;
            transition: color 0.2s ease, transform 0.2s ease;
            display: flex; align-items: center;
        }
        .ticket-modal-close:hover {
            color:#f7f4f4;
            transform: rotate(90deg) scale(1.1);
        }

        /* ── body ── */
        .ticket-modal-body { padding: 1.25rem; }

        .ticket-field { margin-bottom: 0.75rem; }
        .ticket-field label {
            font-size: 11px;
            color: #999;
            display: block;
            margin-bottom: 3px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .ticket-field p {
            font-size: 13px;
            color: #222;
            margin: 0;
            font-weight: 500;
        }

        .ticket-field-row { display: flex; gap: 1rem; }
        .ticket-field-row .ticket-field { flex: 1; }

        /* ── field fade-in stagger ── */
        .ticket-modal-overlay.active .ticket-field,
        .ticket-modal-overlay.active .ticket-field-row {
            animation: fieldFadeUp 0.35s ease both;
        }
        .ticket-modal-overlay.active .ticket-field:nth-child(1),
        .ticket-modal-overlay.active .ticket-field-row:nth-child(1) { animation-delay: 0.12s; }
        .ticket-modal-overlay.active .ticket-field:nth-child(2),
        .ticket-modal-overlay.active .ticket-field-row:nth-child(2) { animation-delay: 0.18s; }
        .ticket-modal-overlay.active .ticket-field:nth-child(3),
        .ticket-modal-overlay.active .ticket-field-row:nth-child(3) { animation-delay: 0.24s; }
        .ticket-modal-overlay.active .ticket-field:nth-child(4),
        .ticket-modal-overlay.active .ticket-field-row:nth-child(4) { animation-delay: 0.30s; }

        @keyframes fieldFadeUp {
            from { opacity: 0; transform: translateY(6px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── not found ── */
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
        <p>
            Gerbang digital rasmi bagi warga Jabatan Hutan Sarawak. 
            Sila pilih perkhidmatan di bawah untuk urusan pendigitalan borang, 
            aduan teknikal, dan tempahan ruang kerja yang lebih efisien.
        </p>
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

    <p class="section-title">Perkhidmatan Yang Disediakan</p>
    <div class="cards">
        <!-- Aduan ICT -->
        <a href="/forms/ict-aduan" class="card">
            <div class="card-icon icon-ict">💻</div>
            <h3>Aplikasi Aduan ICT</h3>
            <p>Saluran pantas untuk melaporkan isu teknikal atau kerosakan aset ICT bagi memastikan kelancaran operasi harian anda.</p>
            <span class="card-link">Hantar Aduan →</span>
        </a>

        <!-- Muat Naik Portal -->
        <a href="/forms/portal-upload" class="card">
            <div class="card-icon icon-upload">📂</div>
            <h3>Aplikasi Muat Naik Portal</h3>
            <p>Permudahkan proses pengemaskinian maklumat jabatan dengan menghantar permohonan muat naik kandungan ke portal rasmi.</p>
            <span class="card-link">Hantar Permohonan →</span>
        </a>

        <!-- Tempahan Bilik -->
        <a href="/booking/calendar" class="card">
            <div class="card-icon icon-track">📅</div>
            <h3>Aplikasi Menempah Bilik Mesyuarat</h3>
            <p>Sistem pengurusan ruang mesyuarat secara real-time untuk koordinasi perbincangan dan acara jabatan yang lebih teratur.</p>
            <span class="card-link">Tempah Sekarang →</span>
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
    <div class="ticket-modal" role="dialog" aria-modal="true" aria-labelledby="ticketModalTitle">
        <div class="ticket-modal-header">
            <h3 id="ticketModalTitle">Status No. Rujukan — {{ $tiket }}</h3>
            <button class="ticket-modal-close" onclick="closeTicketModal()" aria-label="Tutup">×</button>
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
    const overlay = document.getElementById('ticketModal');
    const modal   = overlay.querySelector('.ticket-modal');

    function openTicketModal() {
        overlay.classList.add('active');
        // trap focus inside modal
        modal.querySelector('.ticket-modal-close')?.focus();
    }

    function closeTicketModal() {
        overlay.classList.remove('active');
    }

    // close on Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && overlay.classList.contains('active')) {
            closeTicketModal();
        }
    });

    // auto-open after a short delay so the entry animation is visible
    window.addEventListener('DOMContentLoaded', function() {
        // slight delay lets the page paint first, making the animation feel intentional
        requestAnimationFrame(function() {
            setTimeout(openTicketModal, 80);
        });
    });
</script>
@endisset

</body>
</html>
