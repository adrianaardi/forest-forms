@extends('booking.layout')

@section('title', 'Tempahan Bilik — Jabatan Hutan Sarawak')

@section('content')

@if(session('success'))
    <div style="background:#eaf3de; border:1px solid #c0dd97; color:#3b6d11; padding:0.75rem 1rem; border-radius:8px; margin-bottom:1rem; font-size:13px;">
        {{ session('success') }}
    </div>
@endif

<div style="margin-bottom:1.25rem;">
    <h2 style="font-size:17px; font-weight:500; margin-bottom:0.25rem;">Bilik Mesyuarat</h2>
    <p style="font-size:13px; color:#666;">Pilih bilik untuk melihat kalendar ketersediaan dan membuat tempahan.</p>
</div>

@foreach($bilik as $aras => $rooms)
    <div class="aras-title">{{ $aras }}</div>
    <div class="bilik-grid">
        @foreach($rooms as $room)
            @auth('booking_user')
                <a href="/booking/calendar/{{ $room->id }}" class="bilik-card">
            @else
                <div class="bilik-card" style="cursor:default;" onclick="window.location='/booking/login'">
            @endauth
                <h3>{{ $room->nama_bilik }}</h3>
                <p>{{ $room->lokasi }}</p>
                @if($room->kapasiti)
                    <p style="margin-top:4px;">👥 {{ $room->kapasiti }} orang</p>
                @endif
            @auth('booking_user')
                </a>
            @else
                </div>
            @endauth
        @endforeach
    </div>
@endforeach

@guest('booking_user')
    <div style="background:#f0f4f1; border:1px solid #dde8e1; border-radius:10px; padding:1.25rem; margin-top:1.5rem; text-align:center; font-size:13px; color:#555;">
        Sila <a href="/booking/login" style="color:#1a4731; font-weight:500;">log masuk</a> atau <a href="/booking/daftar" style="color:#1a4731; font-weight:500;">daftar</a> untuk membuat tempahan.
    </div>
@endguest

@endsection