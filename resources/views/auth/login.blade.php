<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — Jabatan Hutan Sarawak</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Google+Sans+Flex:opsz,wght@6..144,1..1000&family=Lora:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet"><link rel="stylesheet" href="{{ asset('style.css') }}">
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

<x-navbar :breadcrumbs="[['label' => 'Log Masuk Admin']]" />

<div class="pg-body">
    <div class="form-card">
        <div class="form-card-header">
            <h2>Log Masuk Admin</h2>
            <p>Hanya untuk kakitangan yang diberi kuasa. testing testing</p>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-section">
                @if(session('status'))
                    <div style="background:#eaf3de; border:1px solid #c0dd97; color:#3b6d11; padding:0.75rem 1rem; border-radius:8px; margin-bottom:1rem; font-size:13px;">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="field">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="admin@jhs.gov.my" required autofocus>
                    @error('email')
                        <div style="color:#a32d2d; font-size:12px; margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field">
                    <label for="password">Kata Laluan</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" required>
                    @error('password')
                        <div style="color:#a32d2d; font-size:12px; margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-footer">
                <a href="{{ route('password.request') }}">Lupa kata laluan?</a>
                <button type="submit" class="btn-submit">Log Masuk</button>
            </div>
        </form>
    </div>
</div>

<footer>
    <div><strong>Seksyen Pengurusan Dan Transformasi Digital</strong> &nbsp;|&nbsp; Tingkat 15, Bangunan Baitul Makmur II, Medan Raya, Petra Jaya, 93050 Kuching, Sarawak</div>
    <div>© <?php echo date("Y"); ?> Jabatan Hutan Sarawak. Hak Cipta Terpelihara.</div>
</footer>
</body>
</html>