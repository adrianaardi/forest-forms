<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Kata Laluan — Sistem Tempahan</title>
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
<x-navbar :breadcrumbs="[['label' => 'Tempah Bilik Mesyuarat', 'url' => '/booking/calendar'], ['label' => 'Reset Kata Laluan']]" />
    <div class="pg-body" style="max-width:420px;">
    <div class="form-card">
        <div class="form-card-header">
            <h2>Reset Kata Laluan</h2>
            <p>Masukkan kata laluan baharu anda.</p>
        </div>
<form method="POST" action="{{ route('booking.password.store') }}">
                @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="form-section">
                @if(session('error'))
                    <div style="background:#fdf0f0; border:1px solid #f5c1c1; color:#a32d2d; padding:0.75rem 1rem; border-radius:8px; margin-bottom:1rem; font-size:13px;">
                        {{ session('error') }}
                    </div>
                @endif
                @if($errors->any())
                    <div style="background:#fdf0f0; border:1px solid #f5c1c1; color:#a32d2d; padding:0.75rem 1rem; border-radius:8px; margin-bottom:1rem; font-size:13px;">
                        <ul style="margin:0; padding-left:1.2rem;">
                            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                        </ul>
                    </div>
                @endif
                <div class="field">
                    <label>Emel</label>
                    <input type="email" name="email" value="{{ $email }}" readonly
                        style="background:#f5f5f5; color:#999;">
                </div>
                <div class="field">
                    <label>Kata Laluan Baharu</label>
                    <input type="password" name="password" placeholder="Minimum 8 aksara" required>
                </div>
                <div class="field">
                    <label>Sahkan Kata Laluan</label>
                    <input type="password" name="password_confirmation" placeholder="Taip semula" required>
                </div>
            </div>
            <div class="form-footer">
                <span></span>
                <button type="submit" class="btn-submit">Reset Kata Laluan</button>
            </div>
        </form>
    </div>
</div>

<footer>
    <div class="footer-content">
        <strong>Seksyen Pengurusan Dan Transformasi Digital</strong> 
        <span class="divider">|</span> 
        Tingkat 15, Bangunan Baitul Makmur II, Medan Raya, Petra Jaya, 93050 Kuching, Sarawak
    </div>
    
    <div class="footer-right">
        <span>© <?php echo date("Y"); ?> Jabatan Hutan Sarawak. Hak Cipta Terpelihara.</span>
        
        @guest('web')
            @guest('booking_user')      
                <a href="/login" class="footer-login-link" title="Login">
                    <svg class="user-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <circle cx="12" cy="10" r="3" />
                        <path d="M7 18c0-2.5 2-4.5 5-4.5s5 2 5 4.5" />
                    </svg>
                </a>
            @endguest
        @endguest
    </div>
</footer></body>
</html>