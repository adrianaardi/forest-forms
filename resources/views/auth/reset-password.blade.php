<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Kata Laluan — Jabatan Hutan Sarawak</title>

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
        <h1>Jabatan Hutan Sarawak</h1>
        <p>Hub Aplikasi Perkhidmatan Atas Talian</p>
    </div>
</header>

<nav>
    <a href="/">Hub Aplikasi</a>
    <a href="/login" class="active" style="margin-left: auto;">Admin</a>
</nav>

<div class="pg-body">
    <div class="form-card">

        <div class="form-card-header">
            <h2>Reset Kata Laluan</h2>
            <p>Masukkan kata laluan baharu anda</p>
        </div>
<form method="POST" action="{{ route('password.store') }}">
    @csrf
    <input type="hidden" name="token" value="{{ request()->route('token') }}">            <div class="form-section">

                {{-- ERROR MESSAGE --}}
                @if ($errors->any())
                    <div style="background:#ffe5e5; border:1px solid #ffb3b3; color:#a32d2d; padding:0.75rem 1rem; border-radius:8px; margin-bottom:1rem; font-size:13px;">
                        {{ $errors->first() }}
                    </div>
                @endif

                {{-- EMAIL --}}
                <div class="field">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ request()->email }}" required>
                </div>

                {{-- PASSWORD --}}
                <div class="field">
                    <label for="password">Kata Laluan Baharu</label>
                    <input type="password" id="password" name="password" required>
                </div>

                {{-- CONFIRM PASSWORD --}}
                <div class="field">
                    <label for="password_confirmation">Sahkan Kata Laluan</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required>
                </div>

            </div>

            <div class="form-footer" style="display: flex; justify-content: space-between; align-items: center; width: 100%; gap: 1rem;">
                <a href="{{ route('login') }}" style="font-size: 14px; white-space: nowrap;">
                    Kembali ke log masuk
                </a>
                <button type="submit" class="btn-submit" style="margin: 0;">
                    Reset Kata Laluan
                </button>
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