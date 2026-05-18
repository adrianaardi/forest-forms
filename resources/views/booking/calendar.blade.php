<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalendar — {{ $bilik?->nama_bilik ?? 'Tempahan' }}</title>
    <link rel="icon" href="{{ asset('images/logo-icon.png')}}">
<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Google+Sans+Flex:opsz,wght@6..144,1..1000&family=Lora:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet"><link rel="stylesheet" href="{{ asset('style.css') }}">    <style>
        /* ── layout ── */
        .bk-wrap { display: flex; height: calc(100vh - 180px); overflow: hidden; }

        /* ── sidebar ── */
        .bk-sidebar {
            width: 210px; flex-shrink: 0;
            background: #fafafa;
            border-right: 1px solid #e8e8e8;
            overflow-y: auto; padding: 0.75rem 0;
            transition: width 0.25s ease;
        }
        .bk-sidebar-section { padding: 0 0.75rem; margin-bottom: 0.75rem; }
        .bk-sidebar-label {
            font-size: 10px; font-weight: 600; text-transform: uppercase;
            letter-spacing: 0.07em; color: #313e69; margin-bottom: 0.4rem; display: block;
        }
        .bk-room-link {
            display: block; padding: 6px 10px; border-radius: 8px;
            font-size: 12px; text-decoration: none; color: #555;
            transition: background 0.15s, color 0.15s, transform 0.1s;
            margin-bottom: 2px;
        }
        .bk-room-link span { font-size: 10px; color: #8b7979; display: block; margin-top: 1px; }
        .bk-room-link:hover { background: #eaf3de; color: #2C3E50; transform: translateX(2px); }
        .bk-room-link.active { background: #2C3E50; color:#f7f4f4; }
        .bk-room-link.active span { color: rgba(255,255,255,0.6); }

        /* ── mini calendar ── */
        .mini-cal { padding: 0.75rem; border-bottom: 1px solid #eee; margin-bottom: 0.75rem; }
        .mini-cal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem; }
        .mini-cal-header span { font-size: 12px; font-weight: 500; color: #333; }
        .mini-cal-header a {
            font-size: 14px; color: #aaa; text-decoration: none;
            padding: 2px 6px; border-radius: 4px;
            transition: background 0.15s, color 0.15s;
        }
        .mini-cal-header a:hover { background: #eaf3de; color: #2C3E50; }
        .mini-cal-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 2px; }
        .mini-cal-dow { font-size: 9px; color: #bbb; text-align: center; padding: 2px 0; font-weight: 600; }
        .mini-cal-day {
            font-size: 10px; text-align: center; padding: 4px 2px;
            border-radius: 5px; line-height: 1.4;
            transition: transform 0.1s, opacity 0.15s;
        }
        .mini-cal-day:not(.empty):not(.past) { cursor: pointer; }
        .mini-cal-day:not(.empty):not(.past):hover { transform: scale(1.15); opacity: 0.85; }
        .mini-cal-day.empty { cursor: default; }
        .mini-cal-day.past { color: #ddd; cursor: default; }
        .mini-cal-day.available { background: #eaf3de; color: #27500a; }
        .mini-cal-day.partial { background: #fef9e7; color: #854f0b; }
        .mini-cal-day.full { background: #fdf0f0; color: #a32d2d; }
        .mini-cal-day.today-dot { outline: 2px solid #2C3E50; outline-offset: -1px; font-weight: 600; }
        .mini-cal-day.in-week { outline: 2px solid #7ec0c9; outline-offset: -1px; }
        .mini-cal-legend { display: flex; flex-wrap: wrap; gap: 5px; margin-top: 8px; }
        .mini-cal-legend span { font-size: 9px; display: flex; align-items: center; gap: 3px; color: #777; }
        .mini-cal-legend .dot { width: 8px; height: 8px; border-radius: 2px; flex-shrink: 0; }

        /* ── guide ── */
        .bk-guide-text {
            font-size: 11px; color: #777; line-height: 1.5;
            background: #f3f7f2; border-left: 3px solid #7ec0c9;
            padding: 8px 10px; border-radius: 0 6px 6px 0;
        }

        /* ── main ── */
        .bk-main { flex: 1; display: flex; flex-direction: column; overflow: hidden; }
        .bk-toolbar {
            display: flex; align-items: center; gap: 0.6rem;
            padding: 0.75rem 1rem; border-bottom: 1px solid #e8e8e8;
            background:#f7f4f4; flex-shrink: 0; flex-wrap: wrap;
        }
        .bk-toolbar-title { font-size: 13px; font-weight: 500; flex: 1; color: #333; }
        .bk-btn {
            padding: 5px 12px; border: 1px solid #e0e0e0; border-radius: 6px;
            background:#f7f4f4; font-size: 12px; cursor: pointer;
            text-decoration: none; color: #555;
            transition: background 0.15s, border-color 0.15s;
        }
        .bk-btn:hover { background: #f5f5f5; border-color: #ccc; }
        .bk-btn-today { border-color: #2C3E50; color: #2C3E50; font-weight: 500; }
        .bk-btn-today:hover { background: #eaf3de; }

        /* ── grid ── */
        .bk-grid-wrap { flex: 1; overflow-y: auto; scroll-behavior: smooth; }
        .bk-grid { display: grid; grid-template-columns: 48px repeat(7, 1fr); min-width: 600px; }
        .bk-col-header {
            text-align: center; padding: 8px 2px;
            border-bottom: 2px solid #e8e8e8; border-right: 1px solid #f0f0f0;
            font-size: 11px; background:#f7f4f4;
            position: sticky; top: 0; z-index: 2;
        }
        .bk-col-header .dname { color: #aaa; font-weight: 400; font-size: 10px; text-transform: uppercase; letter-spacing: 0.05em; }
        .bk-col-header .dnum {
            font-size: 18px; font-weight: 500; width: 32px; height: 32px;
            line-height: 32px; border-radius: 50%; margin: 3px auto 0;
            transition: background 0.2s, color 0.2s;
        }
        .bk-col-header .dnum.today { background: #2C3E50; color:#f7f4f4; }
        .bk-time-gutter {
            font-size: 10px; color: #ccc; text-align: right;
            padding: 2px 8px 0 0; height: 48px; border-right: 1px solid #eee;
        }
        .bk-cell {
            border-right: 1px solid #f0f0f0; border-bottom: 1px solid #f8f8f8;
            height: 48px; position: relative;
            transition: background 0.1s;
        }
        .bk-cell:not(.past-cell) { cursor: pointer; }
        .bk-cell:not(.past-cell):hover { background: #f0f9f4 !important; }
        .row-light { background:#f7f4f4; }
        .row-dark { background: #fafafa; }
        .past-cell { background: #f7f7f7 !important; cursor: not-allowed; opacity: 0.5; }

        /* ── events ── */
        .bk-event {
            position: absolute; left: 2px; right: 2px; border-radius: 5px;
            padding: 3px 5px; font-size: 10px; overflow: hidden; cursor: pointer;
            z-index: 1; line-height: 1.3;
            box-shadow: 0 1px 4px rgba(0,0,0,0.12);
            transition: opacity 0.15s, transform 0.1s;
        }
        .bk-event:hover { opacity: 0.88; transform: scale(1.01); }

        /* ── modals ── */
        .modal-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,0);
            justify-content: center; align-items: center; z-index: 999;
            transition: background 0.25s;
            overflow: hidden;
        }
        .modal-overlay.active { display: flex; background: rgba(0,0,0,0.4); }
        .modal-overlay .modal {
            transform: translateY(20px) scale(0.97);
            opacity: 0;
            transition: transform 0.25s ease, opacity 0.25s ease;
        }
        .modal-overlay.active .modal {
            transform: translateY(0) scale(1);
            opacity: 1;
        }

        .btn-secondary {
            padding: 8px 16px; font-size: 13px; border-radius: 6px;
            border: 1px solid #ddd; background: #f5f5f5; color: #555; cursor: pointer;
            transition: background 0.15s;
        }
        .btn-secondary:hover { background: #eaeaea; }

        /* ── booking modal error/success banners ── */
        #bk-error, #bk-success {
            display: none;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            margin-bottom: 0.75rem;
            font-size: 13px;
            line-height: 1.5;
        }
        #bk-error   { background: #fdf0f0; border: 1px solid #f5c1c1; color: #a32d2d; }
        #bk-success { background: #eaf3de; border: 1px solid #c0dd97; color: #27500a; }
        #bk-error ul   { margin: 0; padding-left: 1.2rem; }
        #bk-submit-btn:disabled { opacity: 0.6; cursor: not-allowed; }

        @media (max-width: 700px) { .bk-sidebar { display: none; } }
    </style>
</head>
<body>

<header>
    <div class="logo"></div>
    <div>
        <h1>Jabatan Hutan Sarawak</h1>
        <p> Hub Aplikasi Perkhidmatan Atas Talian</p>
    </div>
</header>
<x-navbar :breadcrumbs="[['label' => 'Tempah Bilik Mesyuarat', 'url' => '/booking/calendar'], ['label' => $bilik?->nama_bilik ?? 'Kalendar']]" />
        @if(session('daftar_success'))
<div id="daftar-modal" style="position:fixed; inset:0; background:rgba(0,0,0,0.4); display:flex; justify-content:center; align-items:center; z-index:9999;">
    <div style="background:#fff; border-radius:14px; padding:2rem; max-width:400px; width:90%; text-align:center; box-shadow:0 8px 32px rgba(0,0,0,0.15); animation:slideUp 0.3s ease;">
        <div style="font-size:42px; margin-bottom:0.75rem;">🎉</div>
        <h3 style="font-size:15px; font-weight:500; margin-bottom:0.5rem; color:#1a1a1a;">Pendaftaran Berjaya!</h3>
        <p style="font-size:13px; color:#666; line-height:1.6; margin-bottom:1.25rem;">
            Akaun anda telah didaftarkan. Sila tunggu kelulusan admin sebelum anda boleh membuat tempahan.
            Anda akan dihubungi melalui emel apabila akaun diluluskan.
        </p>
        <button onclick="document.getElementById('daftar-modal').remove()"
            style="background:#2C3E50; color:#fff; border:none; padding:10px 24px; border-radius:8px; font-size:13px; cursor:pointer; transition:background 0.15s;">
            Faham, Terima Kasih
        </button>
    </div>
</div>
<style>
@keyframes slideUp {
    from { transform: translateY(20px) scale(0.97); opacity: 0; }
    to   { transform: translateY(0) scale(1); opacity: 1; }
}
</style>
@endif

@if(session('success') || session('info'))
    <div id="flash-msg" style="position:fixed; top:1rem; right:1rem; z-index:9999; padding:0.75rem 1.25rem; border-radius:8px; font-size:13px; font-weight:500; box-shadow:0 4px 16px rgba(0,0,0,0.12); transition:opacity 0.5s, transform 0.5s; transform:translateY(0);
        {{ session('success') ? 'background:#eaf3de; color:#27500a; border:1px solid #c0dd97;' : 'background:#e6f1fb; color:#0c447c; border:1px solid #b5d4f4;' }}">
        {{ session('success') ?? session('info') }}
    </div>
    <script>
        setTimeout(function() {
            var el = document.getElementById('flash-msg');
            if (el) {
                el.style.opacity = '0';
                el.style.transform = 'translateY(-10px)';
                setTimeout(function() { el.remove(); }, 500);
            }
        }, 3000);
    </script>
@endif

@php
    $today         = \Carbon\Carbon::today();
    $prevWeek      = $weekStart->copy()->subWeek()->toDateString();
    $nextWeek      = $weekStart->copy()->addWeek()->toDateString();
    $thisWeek      = $today->copy()->startOfWeek(\Carbon\Carbon::MONDAY)->toDateString();
    $days          = collect(range(0, 6))->map(fn($i) => $weekStart->copy()->addDays($i));
    $hours         = range(8, 16);
    $totalSlotMins = 9 * 60;

    $miniMonth       = $weekStart->copy()->startOfMonth();
    $miniPrevMonth   = $miniMonth->copy()->subMonth()->toDateString();
    $miniNextMonth   = $miniMonth->copy()->addMonth()->toDateString();
    $miniDaysInMonth = $miniMonth->daysInMonth;
    $miniFirstDow    = $miniMonth->dayOfWeek;

    $miniBookingSummary = [];
    if ($bilik) {
        $monthBookings = \App\Models\BookingRequest::where('bilik_id', $bilik->id)
            ->where('status', 'confirmed')
            ->whereYear('tarikh', $miniMonth->year)
            ->whereMonth('tarikh', $miniMonth->month)
            ->get();

        for ($d = 1; $d <= $miniDaysInMonth; $d++) {
            $ds    = $miniMonth->copy()->setDay($d)->toDateString();
            $dayBk = $monthBookings->where('tarikh', $ds);
            $bookedMins = 0;
            foreach ($dayBk as $bk) {
                $bookedMins += \Carbon\Carbon::parse($bk->masa_mula)->diffInMinutes(\Carbon\Carbon::parse($bk->masa_tamat));
            }
            $ratio = $bookedMins / $totalSlotMins;
            $miniBookingSummary[$ds] = $ratio >= 1 ? 'full' : ($ratio > 0 ? 'partial' : 'available');
        }
    }
@endphp

<div class="bk-wrap">

    {{-- Sidebar --}}
    <div class="bk-sidebar">

        @if($bilik)
        <div class="mini-cal">
            <div class="mini-cal-header">
                <a href="/booking/calendar?bilik={{ $bilik->id }}&week={{ $miniPrevMonth }}">‹</a>
                <span>{{ $miniMonth->translatedFormat('M Y') }}</span>
                <a href="/booking/calendar?bilik={{ $bilik->id }}&week={{ $miniNextMonth }}">›</a>
            </div>
            <div class="mini-cal-grid">
                @foreach(['A','I','S','R','K','J','S'] as $dow)
                    <div class="mini-cal-dow">{{ $dow }}</div>
                @endforeach
                @for($i = 0; $i < $miniFirstDow; $i++)
                    <div class="mini-cal-day empty"></div>
                @endfor
                @for($d = 1; $d <= $miniDaysInMonth; $d++)
                    @php
                        $ds      = $miniMonth->copy()->setDay($d)->toDateString();
                        $isPast  = \Carbon\Carbon::parse($ds)->lt($today);
                        $isToday = $ds === $today->toDateString();
                        $inWeek  = $ds >= $weekStart->toDateString() && $ds <= $weekEnd->toDateString();
                        $cls     = $isPast ? 'past' : ($miniBookingSummary[$ds] ?? 'available');
                    @endphp
                    <div class="mini-cal-day {{ $cls }} {{ $isToday ? 'today-dot' : '' }} {{ $inWeek && !$isPast ? 'in-week' : '' }}"
                        @if(!$isPast) onclick="window.location='/booking/calendar?bilik={{ $bilik->id }}&week={{ $ds }}'" @endif>
                        {{ $d }}
                    </div>
                @endfor
            </div>
            <div class="mini-cal-legend">
                <span><span class="dot" style="background:#eaf3de;"></span>Tersedia</span>
                <span><span class="dot" style="background:#fef9e7;"></span>Sebahagian</span>
                <span><span class="dot" style="background:#fdf0f0;"></span>Penuh</span>
            </div>
        </div>
        @endif

        <div class="bk-sidebar-section">
            <div class="bk-guide-text">Pilih bilik untuk melihat ketersediaan dan membuat tempahan.</div>
        </div>

        @foreach($bilikList as $aras => $rooms)
            <div class="bk-sidebar-section">
                <span class="bk-sidebar-label">{{ $aras }}</span>
                @foreach($rooms as $room)
                    <a href="/booking/calendar?bilik={{ $room->id }}&week={{ $weekStart->toDateString() }}"
                       class="bk-room-link {{ $bilik && $bilik->id == $room->id ? 'active' : '' }}">
                        {{ $room->nama_bilik }}
                        <span>{{ $room->wing }}</span>
                    </a>
                @endforeach
            </div>
        @endforeach

    </div>

    {{-- Main --}}
    <div class="bk-main">

        <div class="bk-toolbar">
            <a href="/booking/calendar?bilik={{ $bilik?->id }}&week={{ $prevWeek }}" class="bk-btn">‹</a>
            <a href="/booking/calendar?bilik={{ $bilik?->id }}&week={{ $nextWeek }}" class="bk-btn">›</a>
            <a href="/booking/calendar?bilik={{ $bilik?->id }}&week={{ $thisWeek }}" class="bk-btn bk-btn-today">Hari Ini</a>
            <span class="bk-toolbar-title">
                {{ $weekStart->translatedFormat('d M') }} — {{ $weekEnd->translatedFormat('d M Y') }}
                @if($bilik)
                    &nbsp;·&nbsp;
                    <span style="color:#2C3E50; font-weight:500;">{{ $bilik->nama_bilik }}</span>
                    <span style="color:#bbb; font-size:11px;"> {{ $bilik->aras }}, {{ $bilik->wing }}</span>
                @endif
            </span>
            @if($bilik)
                @auth('booking_user')
                    <a href="/booking/book" class="btn-submit" style="text-decoration:none; font-size:12px; padding:6px 14px;">+ Tempah</a>
                    @else
                        <button onclick="openModal('loginModal')" style="font-size:12px; padding:6px 14px; background:#194169; color:#fff; border:none; border-radius:6px; cursor:pointer;">Log Masuk untuk Tempah</button>
                    @endauth
            @endif
        </div>

        <div class="bk-grid-wrap"
             data-auth="{{ Auth::guard('booking_user')->check() ? '1' : '0' }}"
             data-bilik="{{ $bilik?->id }}">
            <div class="bk-grid">

                <div class="bk-col-header"></div>
                @foreach($days as $day)
                    <div class="bk-col-header">
                        <div class="dname">{{ $day->translatedFormat('D') }}</div>
                        <div class="dnum {{ $day->isToday() ? 'today' : '' }}">{{ $day->format('j') }}</div>
                    </div>
                @endforeach

                @foreach($hours as $hour)
                    <div class="bk-time-gutter">{{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:00</div>
                    @foreach($days as $day)
                        @php
                            $dateStr     = $day->toDateString();
                            $isPast      = \Carbon\Carbon::parse($dateStr)->lt($today);
                            $dayBookings = $bookings->filter(fn($b) =>
                                $b->tarikh === $dateStr &&
                                (int)substr($b->masa_mula, 0, 2) <= $hour &&
                                (int)substr($b->masa_tamat, 0, 2) > $hour
                            );
                        @endphp
                        <div class="bk-cell {{ $loop->parent->index % 2 === 0 ? 'row-light' : 'row-dark' }} {{ $isPast ? 'past-cell' : '' }}"
                            @if(!$isPast) onclick="openBookSlot('{{ $dateStr }}', '{{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:00')" @endif>
                            @foreach($dayBookings as $b)
                                @php
                                    $startsHere = (int)substr($b->masa_mula, 0, 2) === $hour;
                                    $mins = \Carbon\Carbon::parse($b->masa_mula)->diffInMinutes(\Carbon\Carbon::parse($b->masa_tamat));
                                    $h = ($mins / 60) * 48;
                                    $isOwn = Auth::guard('booking_user')->check() &&
                                             Auth::guard('booking_user')->user()->id === $b->user_id;
                                @endphp
                                @if($startsHere)
                                    <div class="bk-event"
                                        style="height:{{ max($h - 4, 14) }}px; background:{{ $isOwn ? '#7ec0c9' : '#2C3E50' }}; color:{{ $isOwn ? '#202c38' : '#fff' }};"
                                        onclick='event.stopPropagation(); showEvent(
                                            @json($b->tajuk_mesyuarat),
                                            @json($b->user->name),
                                            @json($b->user->bahagian ?? "-"),
                                            @json($b->user->phone ?? "-"),
                                            @json(substr($b->masa_mula,0,5)),
                                            @json(substr($b->masa_tamat,0,5)),
                                            @json($day->translatedFormat("d F Y")),
                                            @json($b->remarks ?? "-"),
                                            @json($b->id),
                                            @json($b->cancel_token),
                                            @json($isOwn)
                                        )'
                                        title="{{ $b->tajuk_mesyuarat }} — {{ $b->user->name }}">
                                        <strong>{{ Str::limit($b->tajuk_mesyuarat, 20) }}</strong><br>
                                        {{ substr($b->masa_mula,0,5) }}–{{ substr($b->masa_tamat,0,5) }}
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endforeach
                @endforeach

            </div>
        </div>
    </div>
</div>

{{-- Event detail modal --}}
<div class="modal-overlay" id="eventModal">
    <div class="modal" style="max-width:440px;">
        <div class="modal-header">
            <h2 id="ev-tajuk" style="font-size:14px;"></h2>
            <button class="modal-close" onclick="closeEvent()">×</button>
        </div>
        <div class="modal-body">
            <div class="detail-group">
                <div class="detail-row">
                    <div class="detail-field"><label>Bilik</label><p>{{ $bilik?->nama_bilik ?? '-' }}</p></div>
                    <div class="detail-field"><label>Tarikh</label><p id="ev-tarikh"></p></div>
                </div>
                <div class="detail-row">
                    <div class="detail-field"><label>Masa</label><p id="ev-masa"></p></div>
                    <div class="detail-field"><label>Pemohon</label><p id="ev-nama"></p></div>
                </div>

                <div class="detail-row">
                    <div class="detail-field"><label>Bahagian</label><p id="ev-bahagian"></p></div>
                    <div class="detail-field"><label>No. Telefon</label><p id="ev-phone"></p></div>
                </div>
                <div class="detail-field" style="margin-top:0.5rem;">
                    <label>Catatan</label>
                    <p id="ev-remarks" style="color:#555;"></p>
                </div>
            </div>
            <div id="ev-cancel-wrap" style="display:none; margin-top:1rem; padding-top:1rem; border-top:1px solid #f0f0f0;">
                <p style="font-size:12px; color:#999; margin-bottom:0.5rem;">Ini adalah tempahan anda.</p>
                <form id="ev-cancel-form" method="POST">
                    @csrf
                    <button type="submit" class="btn-delete" style="padding:8px 16px; font-size:13px; cursor:pointer;"
                        onclick="return confirm('Batalkan tempahan ini?')">
                        Batalkan Tempahan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Book modal --}}
<div class="modal-overlay" id="bookModal">
    <div class="modal" style="max-width:480px;">
        <div class="modal-header">
            <h2 style="font-size:14px;">Buat Tempahan</h2>
            <button class="modal-close" onclick="closeBookModal()">×</button>
        </div>
        <div class="modal-body">

            {{-- Error banner (shown on validation/conflict errors) --}}
            <div id="bk-error"></div>

            {{-- Success banner (briefly shown before page reloads) --}}
            <div id="bk-success"></div>

            <form id="bk-form">
                @csrf
                <input type="hidden" name="bilik_id" value="{{ $bilik?->id }}">
                <div class="form-section">
                    @if($bilik)
                    <div style="background:#f0f4f1; border:1px solid #dde8e1; border-radius:8px; padding:0.6rem 0.9rem; margin-bottom:0.75rem; font-size:12px; color:#555; display:flex; align-items:center; gap:6px;">
                        <span style="font-size:15px;">🌿</span>
                        <span><strong>{{ $bilik->nama_bilik }}</strong> — {{ $bilik->aras }}, {{ $bilik->wing }}</span>
                    </div>
                    @endif
                    <div class="field">
                        <label>Tajuk Mesyuarat <span class="required">*</span></label>
                        <input type="text" name="tajuk_mesyuarat" placeholder="Cth: Mesyuarat Jabatan Q2" required>
                    </div>
                    <div class="field">
                        <label>Catatan</label>
                        <textarea name="remarks" rows="2" style="resize:none;"></textarea>
                    </div>
                    <div class="field">
                        <label>Tarikh <span class="required">*</span></label>
                        <input type="date" id="bk-tarikh" name="tarikh" min="{{ \Carbon\Carbon::today()->toDateString() }}" required>
                    </div>
                    <div class="field-row">
                        <div class="field">
                            <label>Masa Mula <span class="required">*</span></label>
                            <input type="time" id="bk-mula" name="masa_mula" min="08:00" max="17:00" required>
                        </div>
                        <div class="field">
                            <label>Masa Tamat <span class="required">*</span></label>
                            <input type="time" id="bk-tamat" name="masa_tamat" min="08:00" max="17:00" required>
                        </div>
                    </div>
                </div>
                <div class="form-footer" style="margin-top:0.5rem;">
                    <button type="submit" id="bk-submit-btn" class="btn-submit">Sahkan Tempahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<footer>
    <div><strong>Jabatan Hutan Sarawak</strong> &nbsp;|&nbsp; Bangunan Baitul Makmur II, Medan Raya, Petra Jaya, 93050 Kuching, Sarawak</div>
    <div>© <?php echo date("Y"); ?> Jabatan Hutan Sarawak. Hak Cipta Terpelihara.</div>
</footer>

{{-- Login modal --}}
@include('booking._login-modal')

<script>
// ── modal open/close ────────────────────────────────────────────────────────

function openModal(id) {
    document.getElementById(id).classList.add('active');
}

function closeModal(id) {
    const overlay = document.getElementById(id);
    const modal   = overlay.querySelector('.modal');
    modal.style.transform = 'translateY(10px) scale(0.97)';
    modal.style.opacity   = '0';
    setTimeout(() => {
        overlay.classList.remove('active');
        modal.style.transform = '';
        modal.style.opacity   = '';
    }, 220);
}

function closeEvent()     { closeModal('eventModal'); }
function closeBookModal() {
    closeModal('bookModal');
    // clear banners when closing
    setBkError(null);
    setBkSuccess(null);
}

// ── book slot (click on grid cell) ─────────────────────────────────────────

function openBookSlot(date, time) {
    const wrap    = document.querySelector('.bk-grid-wrap');
    const isAuth  = wrap?.dataset.auth === '1';
    const bilikId = wrap?.dataset.bilik;

    if (!bilikId) return;
    if (!isAuth) { window.location = '/booking/login'; return; }

    document.getElementById('bk-tarikh').value = date;
    document.getElementById('bk-mula').value   = time;

    const [h] = time.split(':');
    const endH = String(Math.min(parseInt(h) + 1, 17)).padStart(2, '0');
    document.getElementById('bk-tamat').value = endH + ':00';

    setBkError(null);
    setBkSuccess(null);
    openModal('bookModal');
}

// ── event detail modal ──────────────────────────────────────────────────────

function showEvent(tajuk, nama, bahagian, phone, mula, tamat, tarikh, remarks, bookingId, cancelToken, isOwn) {
    document.getElementById('ev-tajuk').textContent    = tajuk;
    document.getElementById('ev-nama').textContent     = nama;
    document.getElementById('ev-bahagian').textContent = bahagian;
    document.getElementById('ev-phone').textContent    = phone;
    document.getElementById('ev-masa').textContent     = mula + ' – ' + tamat;
    document.getElementById('ev-tarikh').textContent   = tarikh;
    document.getElementById('ev-remarks').textContent  = remarks || '-';

    const wrap = document.getElementById('ev-cancel-wrap');
    const form = document.getElementById('ev-cancel-form');
    if (isOwn) {
        form.action = '/booking/cancel/' + cancelToken;
        wrap.style.display = 'block';
    } else {
        wrap.style.display = 'none';
    }

    openModal('eventModal');
}

// ── banner helpers ──────────────────────────────────────────────────────────

function setBkError(msg) {
    const el = document.getElementById('bk-error');
    if (!msg) { el.style.display = 'none'; el.innerHTML = ''; return; }
    el.innerHTML = msg;
    el.style.display = 'block';
    el.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

function setBkSuccess(msg) {
    const el = document.getElementById('bk-success');
    if (!msg) { el.style.display = 'none'; el.textContent = ''; return; }
    el.textContent = msg;
    el.style.display = 'block';
}

// ── AJAX form submit ────────────────────────────────────────────────────────

document.getElementById('bk-form').addEventListener('submit', async function(e) {
    e.preventDefault();

    const btn = document.getElementById('bk-submit-btn');
    btn.disabled    = true;
    btn.textContent = 'Proses...';
    setBkError(null);
    setBkSuccess(null);

    const formData = new FormData(this);

    try {
        const res = await fetch('{{ route("booking.book.store") }}', {
            method:  'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body:    formData,
        });

        const data = await res.json();

        if (res.ok && data.success) {
            // show success briefly, then reload calendar to the booked week
            setBkSuccess(data.message ?? 'Tempahan berjaya!');
            setTimeout(() => {
                window.location = data.redirect ?? window.location.href;
            }, 1200);
            return; // keep button disabled during redirect
        }

        // validation errors (422) or business-logic error (e.g. 409 conflict)
        if (data.errors) {
            const items = Object.values(data.errors).flat();
            setBkError('<ul>' + items.map(m => `<li>${m}</li>`).join('') + '</ul>');
        } else if (data.message) {
            setBkError(data.message);
        } else {
            setBkError('Ralat tidak diketahui. Sila cuba lagi.');
        }

    } catch (err) {
        setBkError('Gagal berhubung dengan pelayan. Sila semak sambungan anda.');
    }

    btn.disabled    = false;
    btn.textContent = 'Sahkan Tempahan';
});

function closeLoginModal() { closeModal('loginModal'); }

// replace all "Log Masuk untuk Tempah" redirects with modal
function openBookSlot(date, time) {
    const wrap    = document.querySelector('.bk-grid-wrap');
    const isAuth  = wrap?.dataset.auth === '1';
    const bilikId = wrap?.dataset.bilik;

    if (!bilikId) return;
    if (!isAuth) { openModal('loginModal'); return; } // ← open modal instead of redirect

    document.getElementById('bk-tarikh').value = date;
    document.getElementById('bk-mula').value   = time;
    const [h] = time.split(':');
    const endH = String(Math.min(parseInt(h) + 1, 17)).padStart(2, '0');
    document.getElementById('bk-tamat').value = endH + ':00';

    setBkError(null);
    setBkSuccess(null);
    openModal('bookModal');
}

// AJAX login form
document.getElementById('login-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('login-btn');
    btn.disabled = true;
    btn.textContent = 'Memproses…';

    const errorEl = document.getElementById('login-error');
    errorEl.style.display = 'none';

    const formData = new FormData(this);

    try {
        const res  = await fetch('{{ route("booking.login.post") }}', {
            method:  'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body:    formData,
        });
        const data = await res.json();

        if (res.ok && data.success) {
            window.location.reload();
            return;
        }

        errorEl.textContent  = data.message ?? 'Ralat tidak diketahui.';
        errorEl.style.display = 'block';
    } catch {
        errorEl.textContent  = 'Gagal berhubung dengan pelayan.';
        errorEl.style.display = 'block';
    }

    btn.disabled    = false;
    btn.textContent = 'Log Masuk';
});
</script>

</body>
</html>