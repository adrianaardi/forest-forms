<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Kata Laluan — Jabatan Hutan Sarawak</title>

<link rel="stylesheet" href="{{ asset('style.css') }}">
    <link rel="icon" href="{{ asset('images/logo-icon.png')}}">
</head>
<body>

<x-header />

<x-navbar :breadcrumbs="[['label' => 'Reset Password']]" />


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
                    <div class="alert alert-error" style="margin-bottom:1rem;">
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

<x-footer />

</body>
</html>