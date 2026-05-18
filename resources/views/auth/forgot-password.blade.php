<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Kata Laluan — Jabatan Hutan Sarawak</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Google+Sans+Flex:opsz,wght@6..144,1..1000&family=Lora:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link rel="icon" href="{{ asset('images/logo-icon.png')}}">

    <style>
        /* 1. Main Page Header */
        header {
            background-color: #b07d7d !important; 
            color: white !important;
        }

        nav {
            background-color: #966666 !important; 
        }

        /* 2. Form Card Header - Now matches the button color */
        .form-card-header {
            background-color: #b07d7d !important; 
            border-bottom: none !important;
            color: white !important; /* Changed to white so text is readable on pink */
        }
        
        .form-card-header p {
            color: #fce4e6 !important; /* Light pink for the sub-text */
        }

        /* 3. The Submit Button */
        .btn-submit {
            background-color: #b07d7d !important;
            border: none !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
        }

        .btn-submit:hover {
            background-color: #8e6262 !important;
        }

        /* 4. Link inside the form */
        .form-footer a {
            color: #b07d7d !important;
            font-weight: 500 !important;
        }

        /* 5. The Page Footer (The bottom address bar) */
        footer {
            background-color: #b07d7d !important;
            color: white !important;
            padding: 20px 0 !important;
            border-top: 3px solid #966666 !important;
        }

        /* 6. Page Background */
        body {
            background-color: #f8f1f1 !important;
        }
    </style>
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
            <h2>Lupa Kata Laluan</h2>
            <p>Masukkan email anda untuk menerima pautan reset</p>
        </div>

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="form-section">

                {{-- SUCCESS MESSAGE --}}
                @if (session('status'))
                    <div style="background:#eaf3de; border:1px solid #c0dd97; color:#3b6d11; padding:0.75rem 1rem; border-radius:8px; margin-bottom:1rem; font-size:13px;">
                        {{ session('status') }}
                    </div>
                @endif

                {{-- ERROR MESSAGE --}}
                @if ($errors->any())
                    <div style="background:#ffe5e5; border:1px solid #ffb3b3; color:#a32d2d; padding:0.75rem 1rem; border-radius:8px; margin-bottom:1rem; font-size:13px;">
                        {{ $errors->first() }}
                    </div>
                @endif

                {{-- EMAIL FIELD --}}
                <div class="field">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="admin@jhs.gov.my" required autofocus>
                </div>

            </div>

            <div class="form-footer" style="display: flex; justify-content: space-between; align-items: center; width: 100%; gap: 1rem;">
                <a href="{{ route('login') }}" style="font-size: 14px; white-space: nowrap;">
                    Kembali ke log masuk
                </a>

                <button type="submit" class="btn-submit" style="margin: 0;">
                    Hantar Pautan
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