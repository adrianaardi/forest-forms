<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya — Sistem Tempahan</title>
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

<div class="pg-body" style="max-width:560px;">

    @if(session('success'))
        <div style="background:#eaf3de; border:1px solid #c0dd97; color:#3b6d11; padding:0.75rem 1rem; border-radius:8px; margin-bottom:1rem; font-size:13px;">
            {{ session('success') }}
        </div>
    @endif

    {{-- Profile info --}}
    <div class="form-card" style="margin-bottom:1.5rem;">
        <div class="form-card-header">
            <h2>Profil Saya</h2>
            <p>Kemaskini maklumat akaun anda.</p>
        </div>
        <form method="POST" action="{{ route('booking.user.profile.update') }}">
            @csrf
            <div class="form-section">
                @if($errors->has('name') || $errors->has('email') || $errors->has('bahagian'))
                    <div style="background:#fdf0f0; border:1px solid #f5c1c1; color:#a32d2d; padding:0.75rem 1rem; border-radius:8px; margin-bottom:1rem; font-size:13px;">
                        <ul style="margin:0; padding-left:1.2rem;">
                            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                        </ul>
                    </div>
                @endif
                <div class="field">
                    <label>Nama Penuh <span class="required">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
                </div>
                <div class="field">
                    <label>Emel <span class="required">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
                </div>
                <div class="field">
                    <label>Bahagian / Unit</label>
                    <input type="text" name="bahagian" value="{{ old('bahagian', $user->bahagian) }}" placeholder="Cth: Bahagian ICT">
                </div>
            </div>
            <div class="form-footer">
                <a href="/booking/calendar" class="btn-back">← Kembali</a>
                <button type="submit" class="btn-submit">Simpan</button>
            </div>
        </form>
    </div>

    {{-- Password --}}
    <div class="form-card">
        <div class="form-card-header">
            <h2>Tukar Kata Laluan</h2>
            <p>Kemaskini kata laluan akaun anda.</p>
        </div>
        <form method="POST" action="{{ route('booking.user.profile.password') }}">
            @csrf
            <div class="form-section">
                @error('current_password')
                    <div style="background:#fdf0f0; border:1px solid #f5c1c1; color:#a32d2d; padding:0.75rem 1rem; border-radius:8px; margin-bottom:1rem; font-size:13px;">
                        {{ $message }}
                    </div>
                @enderror
                <div class="field">
                    <label>Kata Laluan Semasa</label>
                    <input type="password" name="current_password" placeholder="••••••••" required>
                </div>
                <div class="field">
                    <label>Kata Laluan Baru</label>
                    <input type="password" name="password" placeholder="Minimum 8 aksara" required>
                    @error('password')
                        <div style="color:#a32d2d; font-size:12px; margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>
                <div class="field">
                    <label>Sahkan Kata Laluan Baru</label>
                    <input type="password" name="password_confirmation" placeholder="Taip semula" required>
                </div>
            </div>
            <div class="form-footer">
                <span></span>
                <button type="submit" class="btn-submit">Kemaskini Kata Laluan</button>
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