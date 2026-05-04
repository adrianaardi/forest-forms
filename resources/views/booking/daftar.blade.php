@extends('booking.layout')

@section('title', 'Daftar — Sistem Tempahan')

@section('content')

<div style="max-width:480px; margin:0 auto;">
    <div class="form-card">
        <div class="form-card-header">
            <h2>Daftar Akaun</h2>
            <p>Pendaftaran memerlukan kelulusan admin sebelum anda boleh log masuk.</p>
        </div>
        <form method="POST" action="/booking/daftar">
            @csrf
            <div class="form-section">
                @if($errors->any())
                    <div style="background:#fdf0f0; border:1px solid #f5c1c1; color:#a32d2d; padding:0.75rem 1rem; border-radius:8px; margin-bottom:1rem; font-size:13px;">
                        <ul style="margin:0; padding-left:1.2rem;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="field">
                    <label>Nama Penuh <span class="required">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama penuh" required>
                </div>
                <div class="field">
                    <label>Emel <span class="required">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="emel@sarawak.gov.my" required>
                </div>
                <div class="field">
                    <label>Bahagian / Unit</label>
                    <input type="text" name="bahagian" value="{{ old('bahagian') }}" placeholder="Cth: Bahagian ICT">
                </div>
                <div class="field-row">
                    <div class="field">
                        <label>Kata Laluan <span class="required">*</span></label>
                        <input type="password" name="password" placeholder="Minimum 8 aksara" required>
                    </div>
                    <div class="field">
                        <label>Sahkan Kata Laluan <span class="required">*</span></label>
                        <input type="password" name="password_confirmation" placeholder="Taip semula" required>
                    </div>
                </div>
            </div>
            <div class="form-footer">
                <a href="/booking/login" style="font-size:13px; color:#666;">← Kembali ke Log Masuk</a>
                <button type="submit" class="btn-submit">Daftar</button>
            </div>
        </form>
    </div>
</div>

@endsection