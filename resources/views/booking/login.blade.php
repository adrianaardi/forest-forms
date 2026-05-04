@extends('booking.layout')

@section('title', 'Log Masuk — Sistem Tempahan')

@section('content')

<div style="max-width:420px; margin:0 auto;">
    <div class="form-card">
        <div class="form-card-header">
            <h2>Log Masuk</h2>
            <p>Sistem Tempahan Bilik Mesyuarat</p>
        </div>
        <form method="POST" action="/booking/login">
            @csrf
            <div class="form-section">
                @if(session('error'))
                    <div style="background:#fdf0f0; border:1px solid #f5c1c1; color:#a32d2d; padding:0.75rem 1rem; border-radius:8px; margin-bottom:1rem; font-size:13px;">
                        {{ session('error') }}
                    </div>
                @endif
                @if(session('success'))
                    <div style="background:#eaf3de; border:1px solid #c0dd97; color:#3b6d11; padding:0.75rem 1rem; border-radius:8px; margin-bottom:1rem; font-size:13px;">
                        {{ session('success') }}
                    </div>
                @endif
                <div class="field">
                    <label>Emel</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="emel@domain.com" required autofocus>
                </div>
                <div class="field">
                    <label>Kata Laluan</label>
                    <input type="password" name="password" placeholder="••••••••" required>
                </div>
            </div>
            <div class="form-footer">
                <a href="/booking/daftar" style="font-size:13px; color:#1a4731;">Belum ada akaun? Daftar</a>
                <button type="submit" class="btn-submit">Log Masuk</button>
            </div>
        </form>
    </div>
</div>

@endsection