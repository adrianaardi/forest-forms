<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Laluan — Sistem Tempahan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Google+Sans+Flex:opsz,wght@6..144,1..1000&family=Lora:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('style.css') }}">

    <link rel="icon" href="{{ asset('images/logo-icon.png')}}">
</head>
<body>
<header>
        <div class="logo"></div>
    <div>
        <a href="/" style="color: white; text-decoration: none;"><h1>Jabatan Hutan Sarawak</h1></a>
        <p> Hub Aplikasi Perkhidmatan Atas Talian</p>
    </div>
</header>
<x-navbar :breadcrumbs="[['label' => 'Tempah Bilik Mesyuarat', 'url' => '/booking/calendar'], ['label' => 'Lupa Kata Laluan']]" />    <div class="pg-body" style="max-width:420px;">
    <div class="form-card">
        <div class="form-card-header">
            <h2>Lupa Kata Laluan</h2>
            <p>Masukkan emel anda dan kami akan hantar pautan reset.</p>
        </div>
<form method="POST" action="{{ route('booking.password.email') }}">            @csrf
            <div class="form-section">
                @if(session('success'))
                    <div style="background:#eaf3de; border:1px solid #c0dd97; color:#3b6d11; padding:0.75rem 1rem; border-radius:8px; margin-bottom:1rem; font-size:13px;">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div style="background:#fdf0f0; border:1px solid #f5c1c1; color:#a32d2d; padding:0.75rem 1rem; border-radius:8px; margin-bottom:1rem; font-size:13px;">
                        {{ session('error') }}
                    </div>
                @endif
                <div class="field">
                    <label>Emel</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="emel@domain.com" required autofocus>
                </div>
            </div>
            <div class="form-footer">
                <a href="/booking/calendar">← Kembali</a>
                <button type="submit" class="btn-submit">Hantar Pautan Reset</button>
            </div>
        </form>
    </div>
</div>

<footer>
    <div><strong>Seksyen Pengurusan Dan Transformasi Digital</strong> &nbsp;|&nbsp; Tingkat 15, Bangunan Baitul Makmur II, Medan Raya, Petra Jaya, 93050 Kuching, Sarawak</div>
    <div>© <?php echo date("Y"); ?> Jabatan Hutan Sarawak. Hak Cipta Terpelihara.</div>
</footer></body>
</html>