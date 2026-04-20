<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Jabatan Hutan Sarawak — Portal Perkhidmatan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500&display=swap"/>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
</head>
<body>

<header>
    <div class="logo"></div>
    <div>
        <h1>Jabatan Hutan Sarawak</h1>
        <p>Forest Department Sarawak — Sistem Perkhidmatan Dalaman</p>
    </div>
</header>

<nav>
    <a href="/" class="{{ request()->is('/') ? 'active' : '' }}">Laman Utama</a>

    @auth
        <a href="/admin/dashboard" class="{{ request()->is('admin/dashboard') ? 'active' : '' }}">Dashboard</a>
        
        <form method="POST" action="{{ route('logout') }}" style="margin-left:auto; display:flex; align-items:center;">
            @csrf
            <button type="submit" style="background:none; border:none; cursor:pointer; font-size:13px; color:rgba(255,255,255,0.7); padding:0;">
                Log Keluar
            </button>
        </form>
    @endauth

    @guest
        <a href="/login" style="margin-left: auto;">Admin</a>
    @endguest
</nav>

<div class="hero">
    <h2>Selamat Datang ke Portal Perkhidmatan</h2>
    <p>Portal ini menyediakan perkhidmatan dalaman untuk kakitangan Jabatan Hutan Sarawak. Sila pilih perkhidmatan yang diperlukan di bawah.</p>
</div>

<div class="section">
    @if(session('success'))
        <div style="background:#eaf3de; border:1px solid #c0dd97; color:#3b6d11; padding:0.75rem 1rem; border-radius:8px; margin-bottom:1rem; font-size:14px;">
            {{ session('success') }}
        </div>
    @endif

    <p class="section-title">Perkhidmatan Yang Ditawarkan</p>
    <div class="cards">
        <a href="/forms/ict-aduan" class="card">
            <div class="card-icon icon-ict">💻</div>
            <h3>Borang Aduan ICT</h3>
            <p>Laporkan kerosakan atau masalah berkaitan peralatan ICT dan digital di pejabat anda.</p>
            <span class="card-link">Hantar aduan →</span>
        </a>
        <a href="/forms/portal-upload" class="card">
            <div class="card-icon icon-upload">📂</div>
            <h3>Borang Permohonan Muat Naik Portal</h3>
            <p>Hantar permohonan untuk memuat naik kandungan baharu ke portal rasmi jabatan.</p>
            <span class="card-link">Hantar permohonan →</span>
        </a>
        <a href="/semak-tiket" class="card">
            <div class="card-icon icon-track">🔍</div>
            <h3>Semak Status Tiket</h3>
            <p>Semak status aduan atau permohonan anda menggunakan nombor tiket.</p>
            <span class="card-link">Semak sekarang →</span>
        </a>
    </div>
</div>

<footer>
    <div><strong>Jabatan Hutan Sarawak</strong> &nbsp;|&nbsp; Wisma Sumber Alam, Petra Jaya, 93660 Kuching, Sarawak</div>
    <div>© 2025 Jabatan Hutan Sarawak. Hak Cipta Terpelihara.</div>
</footer>

</body>
</html>