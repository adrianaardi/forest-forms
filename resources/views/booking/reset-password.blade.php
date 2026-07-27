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
<x-header />
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
                    <div class="alert alert-error" style="margin-bottom:1rem;">
                        {{ session('error') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="form-error-box alert alert-error">
                        <ul class="form-error-list">
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

<x-footer />
</body>
</html>