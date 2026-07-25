<!DOCTYPE html>
<html lang="ms">
<head>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hub Aplikasi Perkhidmatan</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link rel="icon" href="{{ asset('images/logo-icon.png')}}">
</head>
<body>

<x-header />

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
    <p class="section-title">Perkhidmatan Yang Disediakan</p>
    <div class="cards">
        <a href="/forms/ict-aduan" class="card">
            <div class="card-icon icon-ict">💻</div>
            <h3>Aplikasi Aduan ICT</h3>
            <p>Saluran pantas untuk melaporkan isu teknikal atau kerosakan aset ICT bagi memastikan kelancaran operasi harian anda.</p>
            <span class="card-link">Hantar Aduan →</span>
        </a>

        <a href="/forms/portal-upload" class="card">
            <div class="card-icon icon-upload">📂</div>
            <h3>Aplikasi Pengurusan Laman Web</h3>
            <p>Permudahkan proses pengemaskinian maklumat jabatan dengan menghantar permohonan muat naik kandungan ke portal rasmi.</p>
            <span class="card-link">Hantar Permohonan →</span>
        </a>

        <a href="/booking/calendar" class="card">
            <div class="card-icon icon-track">📅</div>
            <h3>Aplikasi Menempah Bilik Mesyuarat</h3>
            <p>Sistem pengurusan ruang mesyuarat secara real-time untuk koordinasi perbincangan dan acara jabatan yang lebih teratur.</p>
            <span class="card-link">Tempah Sekarang →</span>
        </a>

        <a href="/display/pergerakan" class="card">
            <div class="card-icon icon-track">📌</div>
            <h3>Aplikasi Pergerakan Pegawai</h3>
            <p>Pantau pergerakan pegawai dan program Jabatan Hutan Sarawak secara langsung melalui paparan papan pemuka berpusat.</p>
            <span class="card-link">Lihat Pergerakan →</span>
        </a>
    </div>
</div>

<x-footer />

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

                <div class="ticket-field">
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
                    <div class="ticket-field">
                        <label>Catatan Penyelia</label>
                        <div>
                            {{ $result->catatan_semakan }}
                        </div>
                    </div>
                @endif
            @else
                <div class="ticket-not-found">
                    <p>🔍</p>
                    <p>Tiket <strong>{{ $tiket }}</strong> tidak dijumpai.</p>
                    <p>Sila semak semula No. Rujukan anda.</p>
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
        requestAnimationFrame(function() {
            setTimeout(openTicketModal, 80);
        });
    });
</script>
@endisset

@if(session('new_tiket'))
<div class="ticket-modal-overlay" id="newTicketModal">
    <div class="ticket-modal" role="dialog">
        <div class="ticket-modal-header">
            <h3>Permohonan Berjaya Dihantar!</h3>
            <button class="ticket-modal-close" onclick="closeNewTicket()" aria-label="Tutup">×</button>
        </div>
        <div class="ticket-modal-body">
            <p>
                Permohonan anda telah berjaya dihantar. Sila simpan No. Rujukan berikut untuk semakan dan rujukan anda.
            </p>
            <div>
                <div>No. Rujukan</div>
                <div style="font-weight: bold; font-size: 1.5em;">{{ session('new_tiket') }}</div>
            </div>
            <p>Simpan nombor ini untuk semak status permohonan anda pada masa hadapan.</p>
            <button onclick="closeNewTicket()">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
    const newTicketOverlay = document.getElementById('newTicketModal');

    function closeNewTicket() {
        newTicketOverlay.classList.remove('active');
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeNewTicket();
    });

    window.addEventListener('DOMContentLoaded', function() {
        requestAnimationFrame(function() {
            setTimeout(function() {
                newTicketOverlay.classList.add('active');
            }, 80);
        });
    });
</script>
@endif

</body>
</html>