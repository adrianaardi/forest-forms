<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalendar — {{ $bilik?->nama_bilik ?? 'Tempahan' }}</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <style>
        .bk-wrap { display: flex; height: calc(100vh - 180px); overflow: hidden; }

        /* sidebar */
        .bk-sidebar { width: 200px; flex-shrink: 0; background: #f9fafb; border-right: 1px solid #e0e0e0; overflow-y: auto; padding: 0.75rem 0; }
        .bk-sidebar-section { padding: 0 0.75rem; margin-bottom: 0.75rem; }
        .bk-sidebar-label { font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.07em; color: #999; margin-bottom: 0.4rem; display: block; }
        .bk-room-link { display: block; padding: 5px 8px; border-radius: 6px; font-size: 12px; text-decoration: none; color: #444; transition: background 0.12s; margin-bottom: 1px; }
        .bk-room-link span { font-size: 10px; color: #999; display: block; }
        .bk-room-link:hover { background: #eaf3de; color: #1a4731; }
        .bk-room-link.active { background: #1a4731; color: #fff; }
        .bk-room-link.active span { color: rgba(255,255,255,0.65); }

        /* main */
        .bk-main { flex: 1; display: flex; flex-direction: column; overflow: hidden; }
        .bk-toolbar { display: flex; align-items: center; gap: 0.6rem; padding: 0.75rem 1rem; border-bottom: 1px solid #e0e0e0; background: #fff; flex-shrink: 0; }
        .bk-toolbar-title { font-size: 14px; font-weight: 500; flex: 1; }
        .bk-btn { padding: 4px 12px; border: 1px solid #ddd; border-radius: 6px; background: #fff; font-size: 12px; cursor: pointer; text-decoration: none; color: #333; }
        .bk-btn:hover { background: #f5f5f5; }
        .bk-btn-today { border-color: #1a4731; color: #1a4731; }

        /* grid */
        .bk-grid-wrap { flex: 1; overflow-y: auto; }
        .bk-grid { display: grid; grid-template-columns: 48px repeat(7, 1fr); min-width: 600px; }
        .bk-col-header { text-align: center; padding: 6px 2px; border-bottom: 2px solid #e0e0e0; border-right: 1px solid #eee; font-size: 11px; background: #fff; position: sticky; top: 0; z-index: 2; }
        .bk-col-header .dname { color: #888; font-weight: 400; }
        .bk-col-header .dnum { font-size: 17px; font-weight: 500; width: 30px; height: 30px; line-height: 30px; border-radius: 50%; margin: 2px auto 0; }
        .bk-col-header .dnum.today { background: #1a4731; color: #fff; }
        .bk-time-gutter { font-size: 10px; color: #bbb; text-align: right; padding: 2px 6px 0 0; height: 48px; border-right: 1px solid #e0e0e0; position: relative; }
        .bk-cell { border-right: 1px solid #eee; border-bottom: 1px solid #f5f5f5; height: 48px; position: relative; cursor: pointer; }
        .bk-cell:hover { background: #f0f9f4; }
        .bk-cell.half-hour { border-bottom: 1px dashed #f0f0f0; }
        .bk-event { position: absolute; left: 2px; right: 2px; border-radius: 4px; padding: 2px 4px; font-size: 10px; overflow: hidden; cursor: pointer; z-index: 1; background: #1a4731; color: #fff; line-height: 1.3; box-shadow: 0 1px 3px rgba(0,0,0,0.15); transition: opacity 0.1s; }
        .bk-event:hover { opacity: 0.85; }
    </style>
</head>
<body>
<header>
    <div class="logo">🌿</div>
    <div>
        <h1>Jabatan Hutan Sarawak</h1>
        <p>Sistem Tempahan Bilik Mesyuarat</p>
    </div>
</header>
<x-navbar />

@php
    $today    = \Carbon\Carbon::today();
    $prevWeek = $weekStart->copy()->subWeek()->toDateString();
    $nextWeek = $weekStart->copy()->addWeek()->toDateString();
    $thisWeek = $today->copy()->startOfWeek(\Carbon\Carbon::MONDAY)->toDateString();
    $days     = collect(range(0, 6))->map(fn($i) => $weekStart->copy()->addDays($i));
    $hours    = range(8, 16);
@endphp

<div class="bk-wrap">

    {{-- Sidebar --}}
    <div class="bk-sidebar">
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
                    <a href="/booking/login" class="btn-submit" style="text-decoration:none; font-size:12px; padding:6px 14px; background:#777;">Log Masuk untuk Tempah</a>
                @endauth
            @endif
        </div>

        <div class="cal-grid">
            {{-- empty cells before first day --}}
            @for($i = 0; $i < $startOfMonth->dayOfWeek; $i++)
                <div class="cal-day empty"></div>
            @endfor

            @for($d = 1; $d <= $endOfMonth->day; $d++)
                @php
                    $date       = \Carbon\Carbon::createFromDate($year, $month, $d);
                    $dateStr    = $date->toDateString();
                    $isPast     = $date->lt($today);
                    $isToday    = $date->isToday();
                    $dayBookings = $bookings[$dateStr] ?? collect();

                    // calculate how booked the day is
                    $bookedMinutes = 0;
                    foreach ($dayBookings as $b) {
                        $start = \Carbon\Carbon::parse($b->masa_mula);
                        $end   = \Carbon\Carbon::parse($b->masa_tamat);
                        $bookedMinutes += $start->diffInMinutes($end);
                    }
                    $totalMinutes = $totalSlots * 60;
                    $ratio = $totalMinutes > 0 ? $bookedMinutes / $totalMinutes : 0;

                    if ($isPast) $dayClass = 'past';
                    elseif ($ratio >= 1) $dayClass = 'full';
                    elseif ($ratio > 0) $dayClass = 'partial';
                    else $dayClass = 'available';
                @endphp

                <div class="cal-day {{ $dayClass }} {{ $isToday ? 'today' : '' }}"
                    @if(!$isPast) onclick="showSlots('{{ $dateStr }}', {{ $dayBookings->toJson() }})" style="cursor:pointer;" @endif>
                    <div class="day-num">{{ $d }}</div>
                    @if($dayBookings->count() > 0 && !$isPast)
                        <div style="font-size:10px; margin-top:2px; color:#555;">{{ $dayBookings->count() }} tempahan</div>
                    @endif
                </div>
            @endfor
        </div>

        <div class="cal-legend">
            <span><span class="dot" style="background:#eaf3de;"></span> Tersedia</span>
            <span><span class="dot" style="background:#fef9e7;"></span> Sebahagian</span>
            <span><span class="dot" style="background:#fdf0f0;"></span> Penuh</span>
            <span><span class="dot" style="background:#f5f5f5;"></span> Lepas</span>
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

function openBookSlot(date, bilikId, time) {
    if (!bilikId) return;
    var isAuth = document.querySelector('.bk-grid-wrap').dataset.auth === '1';
    if (isAuth) {
        window.location = '/booking/book/' + bilikId + '?tarikh=' + date + '&masa_mula=' + time;
    } else {
        window.location = '/booking/login';
    }
}
</script>

</body>
</html>