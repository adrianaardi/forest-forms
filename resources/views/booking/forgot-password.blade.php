<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Laluan — Sistem Tempahan</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link rel="icon" href="{{ asset('images/logo-icon.png')}}">
</head>
<body>
<header>
    <div class="logo"></div>
    <div>
        <h1>Jabatan Hutan Sarawak</h1>
        <p>Hub Aplikasi Perkhidmatan Atas Talian</p>
    </div>
</header>
<x-navbar />

<div class="pg-body" style="max-width:420px;">
    <div class="form-card">
        <div class="form-card-header" style="background:#194169;">
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
                <a href="/booking/calendar" style="font-size:13px; color:#194169;">← Kembali</a>
                <button type="submit" class="btn-submit" style="background:#194169;">Hantar Pautan Reset</button>
            </div>
        </form>
    </div>
</div>

<footer>
    <div><strong>Jabatan Hutan Sarawak</strong> &nbsp;|&nbsp; Bangunan Baitul Makmur II, Medan Raya, Petra Jaya, 93050 Kuching, Sarawak</div>
    <div>© <?php echo date("Y"); ?> Jabatan Hutan Sarawak. Hak Cipta Terpelihara.</div>
</footer>
</body>
</html>