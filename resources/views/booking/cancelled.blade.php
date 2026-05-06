<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tempahan Dibatalkan</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
        <link rel="icon" href="{{ asset('images/logo-icon.png')}}">

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

<div class="pg-body" style="max-width:500px;">
    <div class="form-card">
        <div class="form-card-header">
            <h2>{{ $alreadyCancelled ? 'Tempahan Sudah Dibatalkan' : 'Tempahan Berjaya Dibatalkan' }}</h2>
            <p>{{ $alreadyCancelled ? 'Tempahan ini telah pun dibatalkan sebelum ini.' : 'Tempahan anda telah berjaya dibatalkan.' }}</p>
        </div>
        <div class="form-section" style="text-align:center; padding:2rem;">
            <div style="font-size:40px; margin-bottom:1rem;">
                {{ $alreadyCancelled ? '⚠️' : '✅' }}
            </div>
            <div style="background:#f9fafb; border:1px solid #dde8e1; border-radius:8px; padding:1rem; text-align:left; font-size:13px; color:#333; max-width:320px; margin:0 auto;">
                <div style="margin-bottom:6px;"><strong>Tajuk:</strong> {{ $booking->tajuk_mesyuarat }}</div>
                <div style="margin-bottom:6px;"><strong>Bilik:</strong> {{ $booking->bilik->nama_bilik }}</div>
                <div style="margin-bottom:6px;"><strong>Tarikh:</strong> {{ \Carbon\Carbon::parse($booking->tarikh)->format('d/m/Y') }}</div>
                <div><strong>Masa:</strong> {{ substr($booking->masa_mula,0,5) }} – {{ substr($booking->masa_tamat,0,5) }}</div>
            </div>
            @if(!$alreadyCancelled)
                <p style="font-size:12px; color:#777; margin-top:1rem;">Jika anda ingin membuat tempahan baharu, sila kembali ke kalendar.</p>
            @endif
        </div>
        <div class="form-footer">
            <span></span>
            <a href="/booking/calendar?bilik={{ $booking->bilik_id }}" class="btn-submit" style="text-decoration:none;">Lihat Kalendar</a>
        </div>
    </div>
</div>

<footer>
    <div><strong>Jabatan Hutan Sarawak</strong> &nbsp;|&nbsp; Wisma Sumber Alam, Petra Jaya, 93660 Kuching, Sarawak</div>
    <div>© 2025 Jabatan Hutan Sarawak. Hak Cipta Terpelihara.</div>
</footer>
</body>
</html>