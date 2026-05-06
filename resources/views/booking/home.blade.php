<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tempahan Bilik — JHS</title>
            <link rel="icon" href="{{ asset('images/logo-icon.png')}}">

    <link rel="stylesheet" href="{{ asset('style.css') }}">
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

<div class="pg-body">

    @if(session('success'))
        <div style="background:#eaf3de; border:1px solid #c0dd97; color:#3b6d11; padding:0.75rem 1rem; border-radius:8px; margin-bottom:1rem; font-size:13px;">
            {{ session('success') }}
        </div>
    @endif

    <div style="margin-bottom:1.25rem;">
        <h2 style="font-size:17px; font-weight:500; margin-bottom:0.25rem;">Bilik Mesyuarat</h2>
        <p style="font-size:13px; color:#666;">Pilih bilik untuk melihat kalendar dan membuat tempahan.</p>
    </div>

    @foreach($bilik as $aras => $rooms)
        <div class="aras-title">{{ $aras }}</div>
        <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(160px,1fr)); gap:10px; margin-bottom:1rem;">
            @foreach($rooms as $room)
                <a href="/booking/calendar?bilik={{ $room->id }}" class="card">
                    <h3>{{ $room->nama_bilik }}</h3>
                    <p>{{ $room->wing }}</p>
                    <span class="card-link" style="margin-top:0.5rem; display:block;">Lihat kalendar →</span>
                </a>
            @endforeach
        </div>
    @endforeach

    @guest('booking_user')
        @guest('web')
            <div style="background:#f0f4f1; border:1px solid #dde8e1; border-radius:10px; padding:1.25rem; margin-top:1.5rem; text-align:center; font-size:13px; color:#555;">
                Sila <a href="/booking/login" style="color:#1a4731; font-weight:500;">log masuk</a> atau
                <a href="/booking/daftar" style="color:#1a4731; font-weight:500;">daftar</a> untuk membuat tempahan.
            </div>
        @endguest
    @endguest

</div>

<footer>
    <div><strong>Jabatan Hutan Sarawak</strong> &nbsp;|&nbsp; Wisma Sumber Alam, Petra Jaya, 93660 Kuching, Sarawak</div>
    <div>© 2025 Jabatan Hutan Sarawak. Hak Cipta Terpelihara.</div>
</footer>
</body>
</html>