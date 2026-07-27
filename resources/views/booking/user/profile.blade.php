<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Saya — Sistem Tempahan</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Google+Sans+Flex:opsz,wght@6..144,1..1000&family=Lora:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet"><link rel="stylesheet" href="{{ asset('style.css') }}">        <link rel="icon" href="{{ asset('images/logo-icon.png')}}">

</head>
<body>
<x-header />
<x-navbar :breadcrumbs="[['label' => 'Tempah Bilik Mesyuarat', 'url' => '/booking/calendar'], ['label' => 'Profile Saya']]" />

<div class="pg-body" style="max-width:560px;">

    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom:1rem;">
            {{ session('success') }}
        </div>
    @endif

    {{-- Profile info --}}
    <div class="form-card" style="margin-bottom:1.5rem;">
        <div class="form-card-header">
            <h2>Profile Saya</h2>
            <p>Kemaskini maklumat akaun anda.</p>
        </div>
        <form method="POST" action="{{ route('booking.user.profile.update') }}">
            @csrf
            <div class="form-section">
                @if($errors->has('name') || $errors->has('email') || $errors->has('bahagian'))
                    <div class="form-error-box alert alert-error">
                        <ul class="form-error-list">
                            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                        </ul>
                    </div>
                @endif
                <div class="field">
                    <label>Nama Penuh <span class="required">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
                </div>
                <div class="field">
                    <label>Emel <span class="required">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
                </div>
                <div class="field">
                    <label>Bahagian / Unit</label>
                    <input type="text" name="bahagian" value="{{ old('bahagian', $user->bahagian) }}" placeholder="Cth: Bahagian ICT">
                </div>
                <div class="field">
                    <label>Wilayah <span class="required">*</span></label>
                    <select name="wilayah_id" required>
                        <option value="">-- Pilih Wilayah --</option>
                        @foreach($wilayahs as $w)
                            <option value="{{ $w->id }}" {{ old('wilayah_id', $user->wilayah_id) == $w->id ? 'selected' : '' }}>
                                {{ $w->nama_wilayah }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>No. Telefon</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="Cth: 0123456789">
                </div>
            </div>
            <div class="form-footer">
                <a href="/booking/calendar" class="btn-back">← Kembali</a>
                <button type="submit" class="btn-submit">Simpan</button>
            </div>
        </form>
    </div>

    {{-- Password --}}
    <div class="form-card">
        <div class="form-card-header">
            <h2>Tukar Kata Laluan</h2>
            <p>Kemaskini kata laluan akaun anda.</p>
        </div>
        <form method="POST" action="{{ route('booking.user.profile.password') }}">
            @csrf
            <div class="form-section">
                @error('current_password')
                    <div class="alert alert-error" style="margin-bottom:1rem;">
                        {{ $message }}
                    </div>
                @enderror
                <div class="field">
                    <label>Kata Laluan Semasa</label>
                    <input type="password" name="current_password" placeholder="••••••••" required>
                </div>
                <div class="field">
                    <label>Kata Laluan Baru</label>
                    <input type="password" name="password" placeholder="Minimum 8 aksara" required>
                    @error('password')
                        <div style="color:#a32d2d; font-size:12px; margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>
                <div class="field">
                    <label>Sahkan Kata Laluan Baru</label>
                    <input type="password" name="password_confirmation" placeholder="Taip semula" required>
                </div>
            </div>
            <div class="form-footer">
                <span></span>
                <button type="submit" class="btn-submit">Kemaskini Kata Laluan</button>
            </div>
        </form>
    </div>

</div>

<x-footer />

</body>
</html>