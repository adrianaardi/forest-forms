@extends('booking.layout')

@section('title', 'Kalendar — ' . $bilik->nama_bilik)

@section('content')

@php
    $prevMonth = $month == 1 ? 12 : $month - 1;
    $prevYear  = $month == 1 ? $year - 1 : $year;
    $nextMonth = $month == 12 ? 1 : $month + 1;
    $nextYear  = $month == 12 ? $year + 1 : $year;
    $today     = \Carbon\Carbon::today();
    $workStart = 8;
    $workEnd   = 17;
    $totalSlots = $workEnd - $workStart;
@endphp

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem; flex-wrap:wrap; gap:8px;">
    <div>
        <h2 style="font-size:16px; font-weight:500; margin:0;">{{ $bilik->nama_bilik }}</h2>
        <p style="font-size:12px; color:#777; margin:2px 0 0;">{{ $bilik->aras }} — {{ $bilik->lokasi }}</p>
    </div>
    <a href="/booking/tempah/{{ $bilik->id }}" class="btn-submit" style="text-decoration:none; font-size:13px;">+ Buat Tempahan</a>
</div>

<div class="form-card">
    <div style="padding:1rem 1.25rem; display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #f0f0f0;">
        <a href="/booking/calendar/{{ $bilik->id }}?month={{ $prevMonth }}&year={{ $prevYear }}" style="text-decoration:none; color:#1a4731; font-size:13px;">← Sebelum</a>
        <strong style="font-size:14px;">{{ $startOfMonth->translatedFormat('F Y') }}</strong>
        <a href="/booking/calendar/{{ $bilik->id }}?month={{ $nextMonth }}&year={{ $nextYear }}" style="text-decoration:none; color:#1a4731; font-size:13px;">Seterus →</a>
    </div>

    <div style="padding:1rem 1.25rem;">
        <div class="cal-header">
            @foreach(['Ahd','Isn','Sel','Rab','Kha','Jum','Sab'] as $day)
                <span>{{ $day }}</span>
            @endforeach
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

{{-- Slot modal --}}
<div class="modal-overlay" id="slotModal" onclick="closeSlotModal(event)">
    <div class="modal">
        <div class="modal-header">
            <h2 id="slot-date-title">Tempahan</h2>
            <button class="modal-close" onclick="document.getElementById('slotModal').classList.remove('active')">×</button>
        </div>
        <div class="modal-body">
            <div id="slot-list"></div>
            <div style="margin-top:1rem;">
                <a id="slot-book-link" href="#" class="btn-submit" style="text-decoration:none; display:inline-block; font-size:13px;">+ Buat Tempahan Pada Tarikh Ini</a>
            </div>
        </div>
    </div>
</div>

<script>
function showSlots(date, bookings) {
    var modal = document.getElementById('slotModal');
    var title = document.getElementById('slot-date-title');
    var list  = document.getElementById('slot-list');
    var link  = document.getElementById('slot-book-link');

    title.textContent = 'Tempahan — ' + date;
    link.href = '/booking/tempah/{{ $bilik->id }}?tarikh=' + date;
    list.innerHTML = '';

    if (bookings.length === 0) {
        list.innerHTML = '<p style="color:#999; font-size:13px; text-align:center; padding:1rem 0;">Tiada tempahan pada tarikh ini.</p>';
    } else {
        bookings.forEach(function(b) {
            var div = document.createElement('div');
            div.className = 'slot-item';
            div.innerHTML = '<span class="slot-time">' + b.masa_mula.substring(0,5) + ' – ' + b.masa_tamat.substring(0,5) + '</span>' +
                            '<span class="slot-user">👤 ' + b.user.name + ' (' + (b.user.bahagian || '-') + ')</span>';
            list.appendChild(div);
        });
    }

    modal.classList.add('active');
}

function closeSlotModal(e) {
    if (e.target === document.getElementById('slotModal')) {
        document.getElementById('slotModal').classList.remove('active');
    }
}
</script>

@endsection