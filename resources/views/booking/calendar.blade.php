<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalendar — {{ $bilik?->nama_bilik ?? 'Tempahan' }}</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <style>
        .bk-wrap { display: flex; height: calc(100vh - 180px); overflow: hidden; }

        /* ── sidebar ── */
        .bk-sidebar { width: 210px; flex-shrink: 0; background: #f9fafb; border-right: 1px solid #e0e0e0; overflow-y: auto; padding: 0.75rem 0; }
        .bk-sidebar-section { padding: 0 0.75rem; margin-bottom: 0.75rem; }
        .bk-sidebar-label { font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.07em; color: #999; margin-bottom: 0.4rem; display: block; }
        .bk-room-link { display: block; padding: 5px 8px; border-radius: 6px; font-size: 12px; text-decoration: none; color: #444; transition: background 0.12s; margin-bottom: 1px; }
        .bk-room-link span { font-size: 10px; color: #999; display: block; }
        .bk-room-link:hover { background: #eaf3de; color: #1a4731; }
        .bk-room-link.active { background: #1a4731; color: #fff; }
        .bk-room-link.active span { color: rgba(255,255,255,0.65); }

        /* ── mini calendar ── */
        .mini-cal { padding: 0 0.75rem 0.75rem; border-bottom: 1px solid #e0e0e0; margin-bottom: 0.75rem; }
        .mini-cal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem; }
        .mini-cal-header span { font-size: 12px; font-weight: 500; color: #333; }
        .mini-cal-header a { font-size: 13px; color: #777; text-decoration: none; padding: 0 4px; }
        .mini-cal-header a:hover { color: #1a4731; }
        .mini-cal-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 1px; }
        .mini-cal-dow { font-size: 9px; color: #aaa; text-align: center; padding: 2px 0; font-weight: 600; }
        .mini-cal-day { font-size: 10px; text-align: center; padding: 3px 1px; border-radius: 4px; cursor: pointer; line-height: 1.4; transition: background 0.1s; }
        .mini-cal-day.empty { background: none; cursor: default; }
        .mini-cal-day.past { color: #ccc; cursor: default; }
        .mini-cal-day.available { background: #eaf3de; color: #27500a; }
        .mini-cal-day.partial { background: #fef9e7; color: #854f0b; }
        .mini-cal-day.full { background: #fdf0f0; color: #a32d2d; }
        .mini-cal-day.nobook { background: #f5f5f5; color: #bbb; }
        .mini-cal-day.today-dot { outline: 2px solid #1a4731; outline-offset: -1px; }
        .mini-cal-day.in-week { outline: 2px solid #7ec99a; outline-offset: -1px; }
        .mini-cal-legend { display: flex; flex-wrap: wrap; gap: 4px; margin-top: 6px; }
        .mini-cal-legend span { font-size: 9px; display: flex; align-items: center; gap: 3px; color: #666; }
        .mini-cal-legend .dot { width: 8px; height: 8px; border-radius: 2px; flex-shrink: 0; }

        /* ── main ── */
        .bk-main { flex: 1; display: flex; flex-direction: column; overflow: hidden; }
        .bk-toolbar { display: flex; align-items: center; gap: 0.6rem; padding: 0.75rem 1rem; border-bottom: 1px solid #e0e0e0; background: #fff; flex-shrink: 0; flex-wrap: wrap; }
        .bk-toolbar-title { font-size: 14px; font-weight: 500; flex: 1; }
        .bk-btn { padding: 4px 12px; border: 1px solid #ddd; border-radius: 6px; background: #fff; font-size: 12px; cursor: pointer; text-decoration: none; color: #333; }
        .bk-btn:hover { background: #f5f5f5; }
        .bk-btn-today { border-color: #1a4731; color: #1a4731; }

        /* ── grid ── */
        .bk-grid-wrap { flex: 1; overflow-y: auto; }
        .bk-grid { display: grid; grid-template-columns: 48px repeat(7, 1fr); min-width: 600px; }
        .bk-col-header { text-align: center; padding: 6px 2px; border-bottom: 2px solid #e0e0e0; border-right: 1px solid #eee; font-size: 11px; background: #fff; position: sticky; top: 0; z-index: 2; }
        .bk-col-header .dname { color: #888; font-weight: 400; }
        .bk-col-header .dnum { font-size: 17px; font-weight: 500; width: 30px; height: 30px; line-height: 30px; border-radius: 50%; margin: 2px auto 0; }
        .bk-col-header .dnum.today { background: #1a4731; color: #fff; }
        .bk-time-gutter { font-size: 10px; color: #bbb; text-align: right; padding: 2px 6px 0 0; height: 48px; border-right: 1px solid #e0e0e0; }
        .bk-cell { border-right: 1px solid #eee; border-bottom: 1px solid #f5f5f5; height: 48px; position: relative; cursor: pointer; }
        .bk-cell:hover { background: #f0f9f4; }
        .bk-event { position: absolute; left: 2px; right: 2px; border-radius: 4px; padding: 2px 4px; font-size: 10px; overflow: hidden; cursor: pointer; z-index: 1; background: #1a4731; color: #fff; line-height: 1.3; box-shadow: 0 1px 3px rgba(0,0,0,0.15); transition: opacity 0.1s; }
        .bk-event:hover { opacity: 0.85; }

        .bk-guide-text {
            font-size: 11px;
            color: #666;
            line-height: 1.4;
            background: #f3f7f2;
            border-left: 3px solid #1a4731;
            padding: 8px 10px;
            border-radius: 6px;
        }

        @media (max-width: 700px) {
            .bk-sidebar { display: none; }
        }
    </style>
</head>
<body>

<header>
    <div class="logo"></div>
    <div>
        <h1>Jabatan Hutan Sarawak</h1>
        <p>Sistem Tempahan Bilik Mesyuarat</p>
    </div>
</header>
<x-navbar />

@php
    $today      = \Carbon\Carbon::today();
    $prevWeek   = $weekStart->copy()->subWeek()->toDateString();
    $nextWeek   = $weekStart->copy()->addWeek()->toDateString();
    $thisWeek   = $today->copy()->startOfWeek(\Carbon\Carbon::MONDAY)->toDateString();
    $days       = collect(range(0, 6))->map(fn($i) => $weekStart->copy()->addDays($i));
    $hours      = range(8, 16);
    $totalSlotMins = 9 * 60; // 8am-5pm = 9 hours

    // mini calendar — same month as week start
    $miniMonth      = $weekStart->copy()->startOfMonth();
    $miniPrevMonth  = $miniMonth->copy()->subMonth()->toDateString();
    $miniNextMonth  = $miniMonth->copy()->addMonth()->toDateString();
    $miniDaysInMonth = $miniMonth->daysInMonth;
    $miniFirstDow   = $miniMonth->dayOfWeek; // 0=Sun

    // build day booking summary for mini calendar
    $miniBookingSummary = [];
    if ($bilik) {
        $monthBookings = \App\Models\BookingRequest::where('bilik_id', $bilik->id)
            ->where('status', 'confirmed')
            ->whereYear('tarikh', $miniMonth->year)
            ->whereMonth('tarikh', $miniMonth->month)
            ->get();

        for ($d = 1; $d <= $miniDaysInMonth; $d++) {
            $ds = $miniMonth->copy()->setDay($d)->toDateString();
            $dayBk = $monthBookings->where('tarikh', $ds);
            $bookedMins = 0;
            foreach ($dayBk as $bk) {
                $bookedMins += \Carbon\Carbon::parse($bk->masa_mula)->diffInMinutes(\Carbon\Carbon::parse($bk->masa_tamat));
            }
            $ratio = $bookedMins / $totalSlotMins;
            if ($ratio >= 1) $miniBookingSummary[$ds] = 'full';
            elseif ($ratio > 0) $miniBookingSummary[$ds] = 'partial';
            else $miniBookingSummary[$ds] = 'available';
        }
    }
@endphp

<div class="bk-wrap">

    {{-- Sidebar --}}
    <div class="bk-sidebar">

        {{-- Mini Calendar --}}
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
                        $ds       = $miniMonth->copy()->setDay($d)->toDateString();
                        $isPast   = \Carbon\Carbon::parse($ds)->lt($today);
                        $isToday  = $ds === $today->toDateString();
                        $inWeek   = $ds >= $weekStart->toDateString() && $ds <= $weekEnd->toDateString();
                        $status   = $miniBookingSummary[$ds] ?? 'available';
                        $cls      = $isPast ? 'past' : $status;
                    @endphp
                    <div class="mini-cal-day {{ $cls }} {{ $isToday ? 'today-dot' : '' }} {{ $inWeek && !$isPast ? 'in-week' : '' }}"
                        @if(!$isPast)
                            onclick="window.location='/booking/calendar?bilik={{ $bilik->id }}&week={{ $ds }}'"
                            title="{{ $ds }}"
                        @endif>
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

        {{-- Guide text --}}
        <div class="bk-sidebar-section">
            <div class="bk-guide-text">
                Sila pilih bilik mesyuarat di bawah untuk membuat tempahan.
            </div>
        </div>

        {{-- Room list --}}
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

        {{-- Toolbar --}}
        <div class="bk-toolbar">
            <a href="/booking/calendar?bilik={{ $bilik?->id }}&week={{ $prevWeek }}" class="bk-btn">‹</a>
            <a href="/booking/calendar?bilik={{ $bilik?->id }}&week={{ $nextWeek }}" class="bk-btn">›</a>
            <a href="/booking/calendar?bilik={{ $bilik?->id }}&week={{ $thisWeek }}" class="bk-btn bk-btn-today">Hari Ini</a>
            <span class="bk-toolbar-title">
                {{ $weekStart->translatedFormat('d M') }} — {{ $weekEnd->translatedFormat('d M Y') }}
                @if($bilik)
                    &nbsp;·&nbsp;
                    <span style="color:#1a4731; font-weight:500;">{{ $bilik->nama_bilik }}</span>
                    <span style="color:#999; font-size:12px;"> — {{ $bilik->aras }}, {{ $bilik->wing }}</span>
                @endif
            </span>
            @if($bilik)
                @auth('booking_user')
                    <a href="/booking/book/{{ $bilik->id }}" class="btn-submit" style="text-decoration:none; font-size:12px; padding:6px 14px;">+ Tempah</a>
                @else
                    <a href="/booking/login" style="text-decoration:none; font-size:12px; padding:6px 14px; background:#777; color:#fff; border-radius:6px;">Log Masuk untuk Tempah</a>
                @endauth
            @endif
        </div>

        {{-- Grid --}}
        <div class="bk-grid-wrap"
             data-auth="{{ Auth::guard('booking_user')->check() ? '1' : '0' }}"
             data-bilik="{{ $bilik?->id }}">
            <div class="bk-grid">

                {{-- Header --}}
                <div class="bk-col-header"></div>
                @foreach($days as $day)
                    <div class="bk-col-header">
                        <div class="dname">{{ $day->translatedFormat('D') }}</div>
                        <div class="dnum {{ $day->isToday() ? 'today' : '' }}">{{ $day->format('j') }}</div>
                    </div>
                @endforeach

                {{-- Time rows --}}
                @foreach($hours as $hour)
                    <div class="bk-time-gutter">{{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:00</div>
                    @foreach($days as $day)
                        @php
                            $dateStr     = $day->toDateString();
                            $dayBookings = $bookings->filter(fn($b) =>
                                $b->tarikh === $dateStr &&
                                (int)substr($b->masa_mula, 0, 2) <= $hour &&
                                (int)substr($b->masa_tamat, 0, 2) > $hour
                            );
                        @endphp
                        <div class="bk-cell"
                            onclick="openBookSlot('{{ $dateStr }}', '{{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:00')">
                            @foreach($dayBookings as $b)
                                @php
                                    $startsHere = (int)substr($b->masa_mula, 0, 2) === $hour;
                                    $mins = \Carbon\Carbon::parse($b->masa_mula)->diffInMinutes(\Carbon\Carbon::parse($b->masa_tamat));
                                    $h = ($mins / 60) * 48;
                                @endphp
                                @if($startsHere)
                                    <div class="bk-event"
                                        style="height:{{ max($h - 4, 14) }}px;"
                                        onclick="event.stopPropagation(); showEvent(
                                            '{{ addslashes($b->tajuk_mesyuarat) }}',
                                            '{{ addslashes($b->user->name) }}',
                                            '{{ addslashes($b->user->bahagian ?? '-') }}',
                                            '{{ substr($b->masa_mula,0,5) }}',
                                            '{{ substr($b->masa_tamat,0,5) }}',
                                            '{{ $day->translatedFormat('d F Y') }}'
                                        )"
                                        title="{{ $b->tajuk_mesyuarat }} — {{ $b->user->name }}">
                                        <strong>{{ Str::limit($b->tajuk_mesyuarat, 22) }}</strong><br>
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
<div class="modal-overlay" id="eventModal" onclick="if(event.target===this)closeEvent()">
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
                <div class="detail-field" style="margin-top:0.5rem;">
                    <label>Bahagian</label><p id="ev-bahagian"></p>
                </div>
            </div>
        </div>
    </div>
</div>

<footer>
    <div><strong>Jabatan Hutan Sarawak</strong> &nbsp;|&nbsp; Wisma Sumber Alam, Petra Jaya, 93660 Kuching, Sarawak</div>
    <div>© 2025 Jabatan Hutan Sarawak. Hak Cipta Terpelihara.</div>
</footer>

<script>
function showEvent(tajuk, nama, bahagian, mula, tamat, tarikh) {
    document.getElementById('ev-tajuk').textContent    = tajuk;
    document.getElementById('ev-nama').textContent     = nama;
    document.getElementById('ev-bahagian').textContent = bahagian;
    document.getElementById('ev-masa').textContent     = mula + ' – ' + tamat;
    document.getElementById('ev-tarikh').textContent   = tarikh;
    document.getElementById('eventModal').classList.add('active');
}

function closeEvent() {
    document.getElementById('eventModal').classList.remove('active');
}

function openBookSlot(date, time) {
    var wrap    = document.querySelector('.bk-grid-wrap');
    var isAuth  = wrap.dataset.auth === '1';
    var bilikId = wrap.dataset.bilik;
    if (!bilikId) return;
    if (isAuth) {
        window.location = '/booking/book/' + bilikId + '?tarikh=' + date + '&masa_mula=' + time;
    } else {
        window.location = '/booking/login';
    }
}
</script>

</body>
</html>