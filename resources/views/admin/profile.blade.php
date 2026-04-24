<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil — Admin</title>
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

<x-navbar />

<div class="pg-body" style="width: 80%;">

    @if(session('success'))
        <div style="background:#eaf3de; border:1px solid #c0dd97; color:#3b6d11; padding:0.75rem 1rem; border-radius:8px; margin-bottom:1rem; font-size:13px;">
            {{ session('success') }}
        </div>
    @endif

    <div class="form-card">
        <div class="form-card-header">
            <h2>Profil Saya</h2>
            <p>Kemaskini kata laluan akaun anda.</p>
        </div>

        <form method="POST" action="{{ route('admin.profile.password') }}">
            @csrf
            <div class="form-section">
                <div class="section-label">Maklumat Akaun</div>
                <div class="field">
                    <label>Nama</label>
                    <input type="text" value="{{ Auth::user()->name }}" disabled>
                </div>
                <div class="field">
                    <label>Email</label>
                    <input type="text" value="{{ Auth::user()->email }}" disabled>
                </div>
            </div>

            <div class="form-section">
                <div class="section-label">Tukar Kata Laluan</div>
                <div class="field">
                    <label>Kata Laluan Semasa</label>
                    <input type="password" name="current_password" placeholder="••••••••" required>
                    @error('current_password')
                        <div style="color:#a32d2d; font-size:12px; margin-top:4px;">{{ $message }}</div>
                    @enderror
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
                    <input type="password" name="password_confirmation" placeholder="Taip semula kata laluan baru" required>
                </div>
            </div>

            <div class="form-footer">
                @if(Auth::user()->name === 'Admin')
                    <a href="/admin/accounts" style="font-size:13px; color:#185fa5;">Urus Akaun →</a>
                @else
                    <span></span>
                @endif
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