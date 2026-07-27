<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalendar — {{ $bilik?->nama_bilik ?? 'Tempahan' }}</title>
    <link rel="icon" href="{{ asset('images/logo-icon.png')}}">
<link rel="stylesheet" href="{{ asset('style.css') }}">
<style>
        /* ── layout ── */
        .bk-wrap { display: flex; height: calc(100vh - 180px); overflow: hidden; }

        /* ── sidebar ── */
        .bk-sidebar {
            width: 210px; flex-shrink: 0;
            background: var(--bg-surface);
            border-right: 1px solid var(--border-color);
            overflow-y: auto; padding: 0.75rem 0;
            transition: width 0.25s ease;
        }
        .bk-sidebar-section { padding: 0 0.75rem; margin-bottom: 0.75rem; }
        .bk-sidebar-label {
            font-size: 10px; font-weight: 600; text-transform: uppercase;
            letter-spacing: 0.07em; color: var(--primary); margin-bottom: 0.4rem; display: block;
        }
        .bk-room-link {
            display: block; padding: 6px 10px; border-radius: 8px;
            font-size: 12px; text-decoration: none; color: var(--text-dark);
            transition: background 0.15s, color 0.15s, transform 0.1s;
            margin-bottom: 2px;
        }
        .bk-room-link span { font-size: 10px; color: var(--text-muted); display: block; margin-top: 1px; }
        .bk-room-link:hover { background: var(--bg-main); color: var(--primary); transform: translateX(2px); }
        .bk-room-link.active { background: var(--primary); color: #ffffff; }
        .bk-room-link.active span { color: rgba(255,255,255,0.6); }

        /* ── mini calendar ── */
        .mini-cal { padding: 0.75rem; border-bottom: 1px solid var(--border-color); margin-bottom: 0.75rem; }
        .mini-cal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem; }
        .mini-cal-header span { font-size: 12px; font-weight: 500; color: var(--text-dark); }
        .mini-cal-header a {
            font-size: 14px; color: var(--text-muted); text-decoration: none;
            padding: 2px 6px; border-radius: 4px;
            transition: background 0.15s, color 0.15s;
        }
        .mini-cal-header a:hover { background: var(--bg-main); color: var(--primary); }
        .mini-cal-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 2px; }
        .mini-cal-dow { font-size: 9px; color: var(--text-muted); text-align: center; padding: 2px 0; font-weight: 600; }
        .mini-cal-day {
            font-size: 10px; text-align: center; padding: 4px 2px;
            border-radius: 5px; line-height: 1.4;
            transition: transform 0.1s, opacity 0.15s;
        }
        .mini-cal-day:not(.empty):not(.past) { cursor: pointer; }
        .mini-cal-day:not(.empty):not(.past):hover { transform: scale(1.15); opacity: 0.85; }
        .mini-cal-day.empty { cursor: default; }
        .mini-cal-day.past { color: #d0d7de; cursor: default; }
        .mini-cal-day.available { background: #d1fae5; color: #065f46; }
        .mini-cal-day.partial { background: #fef3c7; color: #92400e; }
        .mini-cal-day.full { background: #ffe4e6; color: #9f1239; }
        .mini-cal-day.today-dot { outline: 2px solid var(--primary); outline-offset: -1px; font-weight: 600; }
        .mini-cal-day.in-week { outline: 2px solid #7ec0c9; outline-offset: -1px; }
        .mini-cal-legend { display: flex; flex-wrap: wrap; gap: 5px; margin-top: 8px; }
        .mini-cal-legend span { font-size: 9px; display: flex; align-items: center; gap: 3px; color: var(--text-dark); }
        .mini-cal-legend .dot { width: 8px; height: 8px; border-radius: 2px; flex-shrink: 0; }
        .mini-cal-legend .dot.available { background: #d1fae5; border-radius: 50%; }
        .mini-cal-legend .dot.partial { background: #fef3c7; border-radius: 50%; }
        .mini-cal-legend .dot.full { background: #ffe4e6; border-radius: 50%; }

        /* ── guide ── */
        .bk-guide-text {
            font-size: 11px; color: var(--text-dark); line-height: 1.5;
            background: #eef7f2; border-left: 3px solid #9dc8b0;
            padding: 8px 10px; border-radius: 0 6px 6px 0;
        }
        .bk-wilayah-toggle {
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: none;
        border: none;
        cursor: pointer;
        padding: 4px 0;
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        color: var(--primary);
        margin-bottom: 0.3rem;
        transition: color 0.15s;
    }
    .bk-wilayah-toggle:hover { color: var(--primary-hover); }
    .bk-wilayah-toggle.open .bk-wilayah-arrow { transform: rotate(0deg); }
    .bk-wilayah-arrow {
        font-size: 11px;
        transition: transform 0.2s;
        color: var(--text-muted);
    }
    .bk-wilayah-rooms {
        padding-left: 4px;
        overflow: hidden;
        transition: max-height 0.25s ease;
    }
    .bk-wilayah-rooms.is-collapsed { display: none; }

        /* ── main ── */
        .bk-main { flex: 1; display: flex; flex-direction: column; overflow: hidden; }
        .bk-toolbar {
            display: flex; align-items: center; gap: 0.6rem;
            padding: 0.75rem 1rem; border-bottom: 1px solid var(--border-color);
            background: var(--bg-surface); flex-shrink: 0; flex-wrap: wrap;
        }
        .bk-toolbar-title { font-size: 13px; font-weight: 500; flex: 1; color: var(--text-dark); }
        .bk-btn {
            padding: 5px 12px; border: 1px solid var(--border-color); border-radius: 6px;
            background: var(--bg-main); font-size: 12px; cursor: pointer;
            text-decoration: none; color: var(--text-dark);
            transition: background 0.15s, border-color 0.15s;
        }
        .bk-btn:hover { background: #eef4f0; border-color: #c8d4cb; }
        .bk-btn-today { border-color: var(--primary); color: var(--primary); font-weight: 500; }
        .bk-btn-today:hover { background: #eaf3de; }
        .bk-btn-today.active {
            background: var(--primary); color: #ffffff; border-color: var(--primary);
        }
        .bk-btn-inline { display: flex; align-items: center; gap: 5px; }
        .bk-btn-inline-text { font-size: 12px; }
        .bk-toolbar-room-name { color: var(--primary); font-weight: 600; }
        .bk-toolbar-room-meta { color: var(--text-muted); font-size: 11px; }
        .bk-toolbar-action { text-decoration: none; font-size: 12px; padding: 6px 14px; }
        .bk-toolbar-login {
            font-size: 12px; padding: 6px 14px; background: var(--primary); color: #ffffff;
            border: none; border-radius: 6px; cursor: pointer;
        }
        .bk-toolbar-login:hover { background: var(--primary-hover); }
        .login-register-link { font-size: 13px; }
        .login-actions { display: flex; flex-direction: column; align-items: flex-end; gap: 4px; }
        .login-forgot-link { font-size: 11px; color: var(--text-muted); }
        .login-links { line-height: 1.5; }
        .login-admin-warning {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
            background: #faeeda;
            border-color: #f5d5a0;
            color: #854f0b;
        }
        .logout-inline-form { display: inline; margin-left: 0.5rem; }
        .logout-inline-btn {
            background: none;
            border: none;
            color: #854f0b;
            font-size: 13px;
            cursor: pointer;
            text-decoration: underline;
            padding: 0;
        }

        /* ── grid ── */
        .bk-grid-wrap { flex: 1; overflow-y: auto; scroll-behavior: smooth; }
        .bk-grid { display: grid; grid-template-columns: 48px repeat(7, 1fr); min-width: 600px; }
        .bk-col-header {
            text-align: center; padding: 8px 2px;
            border-bottom: 2px solid var(--border-color); border-right: 1px solid var(--border-color);
            font-size: 11px; background: var(--bg-main);
            position: sticky; top: 0; z-index: 2;
        }
        .bk-col-header .dname { color: var(--text-muted); font-weight: 500; font-size: 10px; text-transform: uppercase; letter-spacing: 0.05em; }
        .bk-col-header .dnum {
            font-size: 18px; font-weight: 500; width: 32px; height: 32px;
            line-height: 32px; border-radius: 50%; margin: 3px auto 0;
            transition: background 0.2s, color 0.2s;
        }
        .bk-col-header .dnum.today { background: var(--primary); color: #ffffff; }
        .bk-time-gutter {
            font-size: 10px; color: var(--text-muted); text-align: right;
            padding: 2px 8px 0 0; height: 48px; border-right: 1px solid var(--border-color);
        }
        .bk-cell {
            border-right: 1px solid var(--border-color); border-bottom: 1px solid var(--border-color);
            height: 48px; position: relative;
            transition: background 0.1s;
        }
        .bk-cell:not(.past-cell) { cursor: pointer; }
        .bk-cell:not(.past-cell):hover { background: #f0f9f4 !important; }
        .row-light { background: #f8faf9; }
        .row-dark { background: #fcfdfc; }
        .past-cell { background: #f7f7f7 !important; cursor: not-allowed; opacity: 0.5; }

        /* ── events ── */
        .bk-event {
            position: absolute; left: 2px; right: 2px; border-radius: 5px;
            padding: 3px 5px; font-size: 10px; overflow: hidden; cursor: pointer;
            z-index: 1; line-height: 1.3;
            box-shadow: var(--shadow-sm);
            transition: opacity 0.15s, transform 0.1s;
        }
        .bk-event:hover { opacity: 0.88; transform: scale(1.01); }
        .bk-event-title { font-weight: 700; font-size: 13px; }
        .bk-event-sub { font-weight: 400; font-size: 10px; opacity: .95; display: block; }

        .btn-secondary {
            padding: 8px 16px; font-size: 13px; border-radius: 6px;
            border: 1px solid var(--border-color); background: var(--bg-main); color: var(--text-dark); cursor: pointer;
            transition: background 0.15s;
        }
        .btn-secondary:hover { background: #e7ece9; }

        .cal-modal-lg { max-width: 520px; max-height: 80vh; display: flex; flex-direction: column; }
        .ev-remarks { color: var(--text-dark); }
        .ev-cancel-wrap {
            display: none;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #f0f0f0;
        }
        .ev-own-note { font-size: 12px; color: var(--text-muted); margin-bottom: 0.5rem; }
        .bk-room-pill {
            background: #f0f4f1;
            border: 1px solid #dde8e1;
            border-radius: 8px;
            padding: 0.6rem 0.9rem;
            margin-bottom: 0.75rem;
            font-size: 12px;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .bk-room-pill-icon { font-size: 15px; }
        .no-resize { resize: none; }
        .form-footer-tight { margin-top: 0.5rem; }
        .ws-subtitle {
            font-size: 11px;
            color: var(--text-muted);
            font-weight: 400;
            display: block;
            margin-top: 2px;
        }
        .ws-body-scroll { overflow-y: auto; }
        .ws-day-group { margin-bottom: 1rem; }
        .ws-day-label {
            font-size: 11px;
            font-weight: 600;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.4rem;
            padding-bottom: 0.3rem;
            border-bottom: 1px solid #eee;
        }
        .ws-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 7px 8px;
            border-radius: 7px;
            cursor: pointer;
            transition: background 0.15s;
            margin-bottom: 3px;
        }
        .ws-item:hover { background: #f0f9f4; }
        .ws-item-bar {
            width: 5px;
            height: 32px;
            border-radius: 3px;
            background: #7ec0c9;
            flex-shrink: 0;
        }
        .ws-item-main { flex: 1; min-width: 0; }
        .ws-item-title {
            font-size: 12px;
            font-weight: 500;
            color: var(--text-dark);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .ws-item-meta { font-size: 11px; color: #777; }
        .ws-item-arrow { font-size: 14px; color: #bbb; }
        .ws-empty {
            font-size: 12px;
            color: #888;
            text-align: center;
            padding: 1.5rem 0;
        }
        .ws-footer {
            padding: 0.75rem 1rem;
            border-top: 1px solid #f0f0f0;
            text-align: right;
        }
        .is-hidden { display: none !important; }

        .flash-msg {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 9999;
            padding: 0.75rem 1.25rem;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            box-shadow: var(--shadow-md);
            transition: opacity 0.5s, transform 0.5s;
            transform: translateY(0);
        }
        .flash-msg.success { background: #eaf3de; color: #27500a; border: 1px solid #c0dd97; }
        .flash-msg.info { background: #e6f1fb; color: #0c447c; border: 1px solid #b5d4f4; }

        .daftar-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.4);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
        .daftar-card {
            background: #ffffff;
            border-radius: 14px;
            padding: 2rem;
            max-width: 400px;
            width: 90%;
            text-align: center;
            box-shadow: var(--shadow-lg);
            animation: slideUp 0.3s ease;
        }
        .daftar-emoji { font-size: 42px; margin-bottom: 0.75rem; }
        .daftar-title { font-size: 15px; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-dark); }
        .daftar-text { font-size: 13px; color: var(--text-muted); line-height: 1.6; margin-bottom: 1.25rem; }
        .daftar-btn {
            background: var(--primary);
            color: #ffffff;
            border: none;
            padding: 10px 24px;
            border-radius: 8px;
            font-size: 13px;
            cursor: pointer;
            transition: background 0.15s;
        }
        .daftar-btn:hover { background: var(--primary-hover); }

        /* ── booking modal error/success banners ── */
        #bk-error, #bk-success {
            display: none;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            margin-bottom: 0.75rem;
            font-size: 13px;
            line-height: 1.5;
        }
        #bk-error   { background: #ffe1e1; border: 1px solid #f5c1c1; color: #a32d2d; }
        #bk-success { background: #eaf3de; border: 1px solid #c0dd97; color: #27500a; }
        #bk-error ul   { margin: 0; padding-left: 1.2rem; }
        #bk-submit-btn:disabled { opacity: 0.6; cursor: not-allowed; }

        @media (max-width: 700px) {
            .bk-sidebar { display: none; }
        }
    </style>
</head>
<body>

<x-header />
<x-navbar :breadcrumbs="[['label' => 'Tempah Bilik Mesyuarat', 'url' => '/booking/calendar'], ['label' => $bilik?->nama_bilik ?? 'Kalendar']]" />
        @if(session('daftar_success'))
<div id="daftar-modal" class="daftar-overlay">
    <div class="daftar-card">
        <div class="daftar-emoji">🎉</div>
        <h3 class="daftar-title">Pendaftaran Berjaya!</h3>
        <p class="daftar-text">
            Akaun anda telah didaftarkan. Sila tunggu kelulusan admin sebelum anda boleh membuat tempahan.
            Anda akan dihubungi melalui emel apabila akaun diluluskan.
        </p>
        <button onclick="document.getElementById('daftar-modal').remove()" class="daftar-btn">
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
    <div id="flash-msg" class="flash-msg alert {{ session('success') ? 'alert-success' : 'alert-info' }}">
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
    \Carbon\Carbon::setLocale('ms');
    $today         = \Carbon\Carbon::today();
    $prevWeek      = $weekStart->copy()->subWeek()->toDateString();
    $nextWeek      = $weekStart->copy()->addWeek()->toDateString();
    $thisWeek      = $today->copy()->startOfWeek(\Carbon\Carbon::MONDAY)->toDateString();
    $days          = collect(range(0, 6))->map(fn($i) => $weekStart->copy()->addDays($i));
    $hours         = range(8, 16);
    $totalSlotMins = 9 * 60;

    $miniMonth       = $weekStart->copy()->startOfMonth();
    $miniPrevMonth   = $miniMonth->copy()->subMonth()->day(15)->toDateString();
    $miniNextMonth   = $miniMonth->copy()->addMonth()->day(15)->toDateString();
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

    // ── Weekly booking summary popup data (RDD & Ibu Pejabat only) ──
    $summaryWilayah = ['RDD', 'Ibu Pejabat'];

    $summaryBilikIds = \App\Models\BookingBilik::whereHas('wilayah', function ($q) use ($summaryWilayah) {
            $q->whereIn('nama_wilayah', $summaryWilayah);
        })->pluck('id');

    $weekSummaryBookings = \App\Models\BookingRequest::whereIn('bilik_id', $summaryBilikIds)
        ->where('status', 'confirmed')
        ->whereBetween('tarikh', [$weekStart->toDateString(), $weekEnd->toDateString()])
        ->with('bilik', 'user')
        ->orderBy('tarikh')
        ->orderBy('masa_mula')
        ->get()
        ->groupBy('tarikh');

    $viewMode = !$bilik || request('view') === 'all';

    if ($viewMode) {
        $displayBookings = \App\Models\BookingRequest::whereIn('bilik_id', $summaryBilikIds)
            ->where('status', 'confirmed')
            ->whereBetween('tarikh', [$weekStart->toDateString(), $weekEnd->toDateString()])
            ->with('bilik', 'user')
            ->get();
    } else {
        $displayBookings = $bookings; // existing single-room collection
    }

    // greedy column assignment so overlapping bookings (any room) sit side-by-side
    function assignEventColumns($items) {
        usort($items, fn($a, $b) => $a['start'] <=> $b['start']);
        $colEnds = [];
        foreach ($items as &$item) {
            $placed = false;
            foreach ($colEnds as $ci => $end) {
                if ($item['start'] >= $end) { $colEnds[$ci] = $item['end']; $item['col'] = $ci; $placed = true; break; }
            }
            if (!$placed) { $colEnds[] = $item['end']; $item['col'] = count($colEnds) - 1; }
        }
        $total = count($colEnds);
        foreach ($items as &$item) { $item['cols'] = $total; }
        return $items;
    }

    // pre-compute columns per day
    $dayColumnData = [];
    foreach ($days as $day) {
        $ds = $day->toDateString();
        $items = $displayBookings->where('tarikh', $ds)->map(function ($b) {
            return [
                'start' => (int)substr($b->masa_mula, 0, 2) * 60 + (int)substr($b->masa_mula, 3, 2),
                'end'   => (int)substr($b->masa_tamat, 0, 2) * 60 + (int)substr($b->masa_tamat, 3, 2),
                'b'     => $b,
            ];
        })->values()->all();
        $dayColumnData[$ds] = assignEventColumns($items);
    }
@endphp

<div class="bk-wrap">

    {{-- Sidebar --}}
    <div class="bk-sidebar">

        @if($bilik)
        <div class="mini-cal">
            <div class="mini-cal-header">
                <a href="/booking/calendar?bilik={{ $bilik->id }}&week={{ $miniPrevMonth }}">‹</a>
                <span>{{ $miniMonth->translatedFormat('F Y') }}</span>
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
                <span><span class="dot available"></span>Tersedia</span>
                <span><span class="dot partial"></span>Sebahagian</span>
                <span><span class="dot full"></span>Penuh</span>
            </div>
        </div>
        @endif

        <div class="bk-sidebar-section">
            <div class="bk-guide-text">Sila pilih cawangan yang berkenaan sebelum membuat tempahan bilik.</div>
        </div>

    {{-- Room list grouped by wilayah --}}
    @foreach($bilikList as $wilayah => $rooms)
        @php
            $isActiveWilayah = $bilik && $rooms->contains('id', $bilik->id);
        @endphp
        <div class="bk-sidebar-section">
            <button
                class="bk-wilayah-toggle {{ $isActiveWilayah ? 'open' : '' }}"
                onclick="toggleWilayah(this)">
                <span>{{ $wilayah }}</span>
                <span class="bk-wilayah-arrow">{{ $isActiveWilayah ? '▾' : '›' }}</span>
            </button>
            <div class="bk-wilayah-rooms {{ $isActiveWilayah ? '' : 'is-collapsed' }}">
                @foreach($rooms as $room)
                    <a href="/booking/calendar?bilik={{ $room->id }}&week={{ $weekStart->toDateString() }}"
                    class="bk-room-link {{ $bilik && $bilik->id == $room->id ? 'active' : '' }}">
                        {{ $room->nama_bilik }}
                        @if($room->aras && $room->aras !== '-')
                            <span>{{ $room->aras }}{{ $room->wing && $room->wing !== '-' ? ', '.$room->wing : '' }}</span>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
    @endforeach

    </div>

    {{-- Main --}}
    <div class="bk-main">

        <div class="bk-toolbar">
            <a href="/booking/calendar?bilik={{ $bilik?->id }}&week={{ $prevWeek }}" class="bk-btn">‹</a>
            <a href="/booking/calendar?bilik={{ $bilik?->id }}&week={{ $nextWeek }}" class="bk-btn">›</a>
            @php
                $toggleViewUrl = '/booking/calendar?bilik='.$bilik?->id.'&week='.$weekStart->toDateString().($viewMode ? '' : '&view=all');
            @endphp
            <a href="{{ $toggleViewUrl }}" class="bk-btn bk-btn-inline {{ $viewMode ? 'bk-btn-today active' : '' }}" title="Lihat semua bilik (RDD & Ibu Pejabat)">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z"/><circle cx="12" cy="12" r="3"/>
                </svg>
            </a>
            <span class="bk-toolbar-title">
                {{ $weekStart->translatedFormat('j F') }} — {{ $weekEnd->translatedFormat('j F Y') }}
                @if($bilik)
                    <select class="bk-btn" 
                        onchange="if(this.value) window.location.href = '/booking/calendar?bilik=' + this.value + '&week={{ $weekStart->toDateString() }}';">
                    @foreach($bilikList as $wilayah => $rooms)
                        <optgroup label="{{ $wilayah }}">
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}" {{ $bilik->id == $room->id ? 'selected' : '' }}>
                                    {{ $room->nama_bilik }} ({{ $room->aras }}{{ $room->wing && $room->wing !== '-' ? ', '.$room->wing : '' }})
                                </option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
                @endif
            </span>
            @if($bilik)
                @auth('booking_user')
                    <a href="/booking/book" class="btn-submit bk-toolbar-action">+ Tempah</a>
                    @elseif ($viewMode)
                        <button onclick="openModal('loginModal')" class="bk-toolbar-login">Log Masuk</button>
                    @else
                        <button onclick="openModal('loginModal')" class="bk-toolbar-login">Log Masuk</button>
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
                        <div class="dname">{{ $day->translatedFormat('l') }}</div>
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
                            @if(!$isPast && !$viewMode)
                                onclick="openBookSlot('{{ $dateStr }}', '{{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:00')"
                            @elseif($viewMode)
                                onclick="handleViewModeClick()"
                            @endif>

                            @php
                                $dayItems = collect($dayColumnData[$dateStr] ?? [])->filter(fn($it) =>
                                    $it['b']->tarikh === $dateStr &&
                                    (int)substr($it['b']->masa_mula, 0, 2) <= $hour &&
                                    (int)substr($it['b']->masa_tamat, 0, 2) > $hour
                                );
                            @endphp
                            @foreach($dayItems as $it)
                                @php
                                    $b = $it['b'];
                                    $startsHere = (int)substr($b->masa_mula, 0, 2) === $hour;
                                    $mins = \Carbon\Carbon::parse($b->masa_mula)->diffInMinutes(\Carbon\Carbon::parse($b->masa_tamat));
                                    $h = ($mins / 60) * 48;
                                    $isOwn = Auth::guard('booking_user')->check() && Auth::guard('booking_user')->user()->id === $b->user_id;
                                    $cols = max($it['cols'], 1);
                                    $widthPct = 100 / $cols;
                                    $leftPct  = $it['col'] * $widthPct;
                                    $bg = $viewMode ? ($roomColorMap[$b->bilik_id] ?? '#1b4332') : ($isOwn ? '#7ec0c9' : '#1b4332');
                                @endphp
                                @if($startsHere)
                                    <div class="bk-event {{ $viewMode ? 'bk-event-nav' : '' }}"
                                        style="height:{{ max($h - 4, 14) }}px; left:calc({{ $leftPct }}% + 2px); width:calc({{ $widthPct }}% - 4px); background:{{ $bg }}; color:#fff;"
                                        @if($viewMode)
                                            onclick="event.stopPropagation(); window.location='/booking/calendar?bilik={{ $b->bilik_id }}&week={{ $weekStart->toDateString() }}'"
                                        @else
                                            onclick='event.stopPropagation(); showEvent(
                                                @json($b->tajuk_mesyuarat), @json($b->user->name), @json($b->user->bahagian ?? "-"),
                                                @json($b->user->phone ?? "-"), @json(substr($b->masa_mula,0,5)), @json(substr($b->masa_tamat,0,5)),
                                                @json($day->translatedFormat("j F Y")), @json($b->remarks ?? "-"), @json($b->id),
                                                @json($b->cancel_token), @json($isOwn)
                                            )'
                                        @endif
                                        title="{{ $viewMode ? ($b->bilik->nama_bilik ?? '').' — '.$b->tajuk_mesyuarat : $b->tajuk_mesyuarat.' — '.$b->user->name }}">
                                        <span class="bk-event-title">{{ Str::limit($b->tajuk_mesyuarat, 18) }}</span>
                                        @if($viewMode)
                                            <span class="bk-event-sub">{{ Str::limit($b->bilik->nama_bilik ?? '', 16) }}</span>
                                        @else
                                            <br>{{ substr($b->masa_mula,0,5) }}–{{ substr($b->masa_tamat,0,5) }}
                                        @endif
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
<div class="ticket-modal-overlay" id="eventModal">
    <div class="ticket-modal">
        <div class="ticket-modal-header">
            <h3 id="ev-tajuk"></h3>
            <button class="ticket-modal-close" onclick="closeEvent()">×</button>
        </div>
        <div class="ticket-modal-body">
            <div class="ticket-field-row">
                <div class="ticket-field"><label>Bilik</label><p>{{ $bilik?->nama_bilik ?? '-' }}</p></div>
                <div class="ticket-field"><label>Tarikh</label><p id="ev-tarikh"></p></div>
            </div>
            <div class="ticket-field-row">
                <div class="ticket-field"><label>Masa</label><p id="ev-masa"></p></div>
                <div class="ticket-field"><label>Pemohon</label><p id="ev-nama"></p></div>
            </div>

            <div class="ticket-field-row">
                <div class="ticket-field"><label>Bahagian</label><p id="ev-bahagian"></p></div>
                <div class="ticket-field"><label>No. Telefon</label><p id="ev-phone"></p></div>
            </div>
            <div class="ticket-field">
                <label>Catatan</label>
                <p id="ev-remarks" class="ev-remarks"></p>
            </div>
            <div id="ev-cancel-wrap" class="ev-cancel-wrap">
                <p class="ev-own-note">Ini adalah tempahan anda.</p>
                <form id="ev-cancel-form" method="POST">
                    @csrf
                    <button type="submit" class="btn-submit"
                        onclick="return confirm('Batalkan tempahan ini?')">
                        Batalkan Tempahan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Book modal --}}
<div class="ticket-modal-overlay" id="bookModal">
    <div class="ticket-modal">
        <div class="ticket-modal-header">
            <h3>Buat Tempahan</h3>
            <button class="ticket-modal-close" onclick="closeBookModal()">×</button>
        </div>
        <div class="ticket-modal-body">

            {{-- Error banner (shown on validation/conflict errors) --}}
            <div id="bk-error"></div>

            {{-- Success banner (briefly shown before page reloads) --}}
            <div id="bk-success"></div>

            <form id="bk-form">
                @csrf
                <input type="hidden" name="bilik_id" value="{{ $bilik?->id }}">
                <div class="form-section">
                    @if($bilik)
                    <div class="bk-room-pill">
                        <span class="bk-room-pill-icon">🌿</span>
                        <span><strong>{{ $bilik->nama_bilik }}</strong> — {{ $bilik->aras }}, {{ $bilik->wing }}</span>
                    </div>
                    @endif
                    <div class="field">
                        <label>Tajuk Mesyuarat <span class="required">*</span></label>
                        <input type="text" name="tajuk_mesyuarat" placeholder="Cth: Mesyuarat Jabatan Q2" required>
                    </div>
                    <div class="field">
                        <label>Catatan</label>
                        <textarea name="remarks" rows="2" class="no-resize"></textarea>
                    </div>
                    <div class="field">
                        <label>Tarikh <span class="required">*</span></label>
                        <input type="date" id="bk-tarikh" name="tarikh" min="{{ \Carbon\Carbon::today()->toDateString() }}" required>
                    </div>
                    <div class="field-row">
                        <div class="field">
                            <label>Masa Mula <span class="required">*</span></label>
                            <select id="bk-mula" name="masa_mula" required>
                                @for ($hour = 8; $hour <= 16; $hour++)
                                    @foreach (['00', '30'] as $minute)
                                        @php
                                            $time = sprintf('%02d:%s', $hour, $minute);
                                        @endphp
                                        <option value="{{ $time }}">{{ $time }}</option>
                                    @endforeach
                                @endfor
                            </select>
                        </div>

                        <div class="field">
                            <label>Masa Tamat <span class="required">*</span></label>
                            <select id="bk-tamat" name="masa_tamat" required>
                                @for ($hour = 8; $hour <= 17; $hour++)
                                    @foreach (['00', '30'] as $minute)
                                        @php
                                            $time = sprintf('%02d:%s', $hour, $minute);
                                        @endphp
                                        @if (!($hour == 17 && $minute == '30'))
                                            <option value="{{ $time }}">{{ $time }}</option>
                                        @endif
                                    @endforeach
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-footer form-footer-tight">
                    <button type="submit" id="bk-submit-btn" class="btn-submit">Sahkan Tempahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<x-footer />

{{-- Weekly Booking Summary popup (RDD & Ibu Pejabat) --}}
<div class="ticket-modal-overlay" id="weekSummaryModal">
    <div class="ticket-modal cal-modal-lg">
        <div class="ticket-modal-header">
            <h3>
                Ringkasan Tempahan Minggu Ini
                <span class="ws-subtitle">
                    {{ $weekStart->translatedFormat('d M') }} — {{ $weekEnd->translatedFormat('d M Y') }} · RDD &amp; Ibu Pejabat
                </span>
            </h3>
            <button class="ticket-modal-close" onclick="closeModal('weekSummaryModal')">×</button>
        </div>
        <div class="ticket-modal-body ws-body-scroll">
            @forelse($weekSummaryBookings as $tarikh => $dayBookings)
                @php $dCarbon = \Carbon\Carbon::parse($tarikh); @endphp
                <div class="ws-day-group">
                    <div class="ws-day-label">
                        {{ $dCarbon->translatedFormat('l, d F Y') }}
                    </div>
                    @foreach($dayBookings as $b)
                        <div class="ws-item"
                            onclick="window.location='/booking/calendar?bilik={{ $b->bilik_id }}&week={{ \Carbon\Carbon::parse($tarikh)->startOfWeek(\Carbon\Carbon::MONDAY)->toDateString() }}'">
                            <div class="ws-item-bar"></div>
                            <div class="ws-item-main">
                                <div class="ws-item-title">
                                    {{ $b->tajuk_mesyuarat }}
                                </div>
                                <div class="ws-item-meta">
                                    {{ $b->bilik->nama_bilik ?? '-' }} · {{ substr($b->masa_mula,0,5) }}–{{ substr($b->masa_tamat,0,5) }} · {{ $b->user->name ?? '-' }}
                                </div>
                            </div>
                            <span class="ws-item-arrow">›</span>
                        </div>
                    @endforeach
                </div>
            @empty
                <p class="ws-empty">
                    Tiada tempahan untuk minggu ini di wilayah RDD &amp; Ibu Pejabat.
                </p>
            @endforelse
        </div>
        <div class="form-footer ws-footer">
            <button class="btn-secondary" onclick="closeModal('weekSummaryModal')">Tutup</button>
        </div>
    </div>
</div>

{{-- Login modal --}}
@include('booking._login-modal')

<script>
// ── modal open/close ────────────────────────────────────────────────────────

function openModal(id) {
    document.getElementById(id).classList.add('active');
}

function closeModal(id) {
    const overlay = document.getElementById(id);
    const modal   = overlay.querySelector('.ticket-modal');
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

// ── Weekly booking summary: show only when entering the module from outside ──
document.addEventListener('DOMContentLoaded', function() {
    const hasDaftarModal = document.getElementById('daftar-modal'); // don't stack on top of registration success

    let cameFromOutsideModule = true;
    try {
        const ref = document.referrer ? new URL(document.referrer) : null;
        if (ref && ref.pathname === window.location.pathname) {
            cameFromOutsideModule = false; // referrer is also /booking/calendar → internal nav
        }
    } catch (e) {
        // malformed/empty referrer, treat as "from outside"
    }

    if (!cameFromOutsideModule) return; // skip popup, just clicking through rooms/weeks

    if (!hasDaftarModal) {
        setTimeout(() => openModal('weekSummaryModal'), 250);
    } else {
        const observer = new MutationObserver(() => {
            if (!document.getElementById('daftar-modal')) {
                observer.disconnect();
                setTimeout(() => openModal('weekSummaryModal'), 200);
            }
        });
        observer.observe(document.body, { childList: true });
    }
});

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
    errorEl.classList.add('is-hidden');
    errorEl.textContent = '';

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
        errorEl.classList.remove('is-hidden');
    } catch {
        errorEl.textContent  = 'Gagal berhubung dengan pelayan.';
        errorEl.classList.remove('is-hidden');
    }

    btn.disabled    = false;
    btn.textContent = 'Log Masuk';
});

function toggleWilayah(btn) {
    const rooms = btn.nextElementSibling;
    const arrow = btn.querySelector('.bk-wilayah-arrow');
    const isCollapsed = rooms.classList.contains('is-collapsed');

    if (isCollapsed) {
        rooms.classList.remove('is-collapsed');
        arrow.textContent = '▾';
        btn.classList.add('open');
    } else {
        rooms.classList.add('is-collapsed');
        arrow.textContent = '›';
        btn.classList.remove('open');
    }
}

function handleViewModeClick() {
    const wrap   = document.querySelector('.bk-grid-wrap');
    const isAuth = wrap?.dataset.auth === '1';

    if (!isAuth) {
        openModal('loginModal');
        return;
    }
    window.location.href = '/booking/book';
}
</script>

</body>
</html>