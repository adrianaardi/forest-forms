<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelulusan Berjaya — Jabatan Hutan Sarawak</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link rel="icon" href="{{ asset('images/logo-icon.png')}}">

</head>
<body>

<header>
    <div class="logo"></div>
    <div>
        <h1>Jabatan Hutan Sarawak</h1>
        <p>Forest Department Sarawak — Sistem Perkhidmatan Dalaman</p>
    </div>
</header>

<div class="pg-body">
    <div class="form-card">
        <div class="form-card-header">
            <h2>Kelulusan Berjaya</h2>
            <p>Tindakan anda telah direkodkan.</p>
        </div>
        <div class="form-section" style="text-align:center; padding: 2rem;">
            <div style="font-size:40px; margin-bottom:1rem;">✅</div>
            <p style="font-size:15px; font-weight:500; color:#1a4731; margin-bottom:0.5rem;">
                Permohonan {{ $permohonan->no_tiket }} telah diluluskan.
            </p>
            <p style="font-size:13px; color:#666;">
                Pemohon akan dimaklumkan melalui emel sekiranya mereka menyertakan alamat emel semasa menghantar permohonan.
            </p>
        </div>
    </div>
</div>

<footer>
    <div><strong>Jabatan Hutan Sarawak</strong> &nbsp;|&nbsp; Wisma Sumber Alam, Petra Jaya, 93660 Kuching, Sarawak</div>
    <div>© 2025 Jabatan Hutan Sarawak. Hak Cipta Terpelihara.</div>
</footer>

</body>
</html>