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
</head>
<body>

<x-header />

<x-navbar :breadcrumbs="[['label' => 'Lupa Password']]" />

<div class="pg-body" style="max-width:500px;">
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

            <div class="form-footer">
                <a href="{{ route('login') }}" style="font-size: 14px; white-space: nowrap;" class="back-btn">
                    Kembali ke log masuk
                </a>

                <button type="submit" class="btn-submit" style="margin: 0;">
                    Hantar Pautan
                </button>
            </div>

        </form>

    </div>
</div>

<x-footer />

</body>
</html>