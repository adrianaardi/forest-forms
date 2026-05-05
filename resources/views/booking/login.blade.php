<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Masuk — Sistem Tempahan</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
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

<div class="pg-body" style="max-width:420px;">
    <div class="form-card">
        <div class="form-card-header">
            <h2>Log Masuk</h2>
            <p>Sistem Tempahan Bilik Mesyuarat</p>
        </div>
        <form method="POST" action="{{ route('booking.login.post') }}">
            @csrf
            <div class="form-section">
                @if(session('error'))
                    <div style="background:#fdf0f0; border:1px solid #f5c1c1; color:#a32d2d; padding:0.75rem 1rem; border-radius:8px; margin-bottom:1rem; font-size:13px;">
                        {{ session('error') }}
                    </div>
                @endif
                @if(session('success'))
                    <div style="background:#eaf3de; border:1px solid #c0dd97; color:#3b6d11; padding:0.75rem 1rem; border-radius:8px; margin-bottom:1rem; font-size:13px;">
                        {{ session('success') }}
                    </div>
                @endif
                <div class="field">
                    <label>Emel</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="emel@domain.com" required autofocus>
                </div>
                <div class="field">
                    <label>Kata Laluan</label>
                    <input type="password" name="password" placeholder="••••••••" required>
                </div>
            </div>
            <div class="form-footer">
                <a href="{{ route('booking.daftar') }}" style="font-size:13px; color:#1a4731;">Belum ada akaun? Daftar</a>
                <button type="submit" class="btn-submit">Log Masuk</button>
            </div>
        </form>
    </div>
</div>

<footer>
    <div><strong>Jabatan Hutan Sarawak</strong> &nbsp;|&nbsp; Wisma Sumber Alam, Petra Jaya, 93660 Kuching, Sarawak</div>
    <div>© 2025 Jabatan Hutan Sarawak. Hak Cipta Terpelihara.</div>
</footer>
</body>
</html>