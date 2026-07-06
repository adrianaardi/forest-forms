<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil — Admin</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Google+Sans+Flex:opsz,wght@6..144,1..1000&family=Lora:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet"><link rel="stylesheet" href="{{ asset('style.css') }}">    <link rel="icon" href="{{ asset('images/logo-icon.png')}}">
</head>
<body>

<header>
    <div class="logo"></div>
    <div>
<a href="/" style="color: white; text-decoration: none;"><h1>Jabatan Hutan Sarawak</h1></a>
        <p> Hub Aplikasi Perkhidmatan Atas Talian</p>
    </div>
</header>

<x-navbar :breadcrumbs="[['label' => 'Profil']]" />
<div class="pg-body" style="width: 80%;">

    @if(session('success'))
        <div style="background:#eaf3de; border:1px solid #c0dd97; color:#3b6d11; padding:0.75rem 1rem; border-radius:8px; margin-bottom:1rem; font-size:13px;">
            {{ session('success') }}
        </div>
    @endif

    <div class="form-card">
        <div class="form-card-header">
            <h2>Profile Saya</h2>
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
    <div><strong>Seksyen Pengurusan Dan Transformasi Digital</strong> &nbsp;|&nbsp; Tingkat 15, Bangunan Baitul Makmur II, Medan Raya, Petra Jaya, 93050 Kuching, Sarawak</div>
    <div>© <?php echo date("Y"); ?> Jabatan Hutan Sarawak. Hak Cipta Terpelihara.    </div>
    @guest('web')
            @guest('booking_user')      
                <a href="/login" style="display:flex; align-items:center; gap:4px; color:#f7f4f4; text-decoration:none;">
                    <svg class="user-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" width="20" height="20">
                        <circle cx="12" cy="12" r="10" />
                        <circle cx="12" cy="10" r="3" />
                        <path d="M7 18c0-2.5 2-4.5 5-4.5s5 2 5 4.5" />
                    </svg>
                </a>
            @endguest
        @endguest
</div>
</footer>
</body>
</html>