<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Tempahan — {{ $bilik->nama_bilik }}</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
</head>
<body>
<header>
    <div class="logo">🌿</div>
    <div>
        <h1>Jabatan Hutan Sarawak</h1>
        <p>Sistem Tempahan Bilik Mesyuarat</p>
    </div>
</header>
<x-navbar />

<div class="pg-body" style="max-width:560px;">
    <div class="form-card">
        <div class="form-card-header">
            <h2>Buat Tempahan</h2>
            <p>{{ $bilik->nama_bilik }} — {{ $bilik->aras }}, {{ $bilik->wing }}</p>
        </div>
        <form method="POST" action="{{ route('booking.book.store', $bilik->id) }}">
            @csrf

            <div class="form-section">
                <div class="section-label">Maklumat Mesyuarat</div>

                @if(session('error'))
                    <div style="background:#fdf0f0; border:1px solid #f5c1c1; color:#a32d2d; padding:0.75rem 1rem; border-radius:8px; margin-bottom:1rem; font-size:13px;">
                        {{ session('error') }}
                    </div>
                @endif
                @if($errors->any())
                    <div style="background:#fdf0f0; border:1px solid #f5c1c1; color:#a32d2d; padding:0.75rem 1rem; border-radius:8px; margin-bottom:1rem; font-size:13px;">
                        <ul style="margin:0; padding-left:1.2rem;">
                            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                <div class="field">
                    <label>Tajuk Mesyuarat <span class="required">*</span></label>
                    <input type="text" name="tajuk_mesyuarat" value="{{ old('tajuk_mesyuarat') }}" placeholder="Cth: Mesyuarat Jabatan Q2" required>
                </div>
                <div class="field">
                    <label>Tarikh <span class="required">*</span></label>
                    <input type="date" name="tarikh" value="{{ old('tarikh', $tarikh) }}"
                        min="{{ \Carbon\Carbon::today()->toDateString() }}" required>
                </div>
                <div class="field-row">
                    <div class="field">
                        <label>Masa Mula <span class="required">*</span></label>
                        <input type="time" name="masa_mula" value="{{ old('masa_mula', request('masa_mula', '08:00')) }}" min="08:00" max="17:00" required>
                    </div>
                    <div class="field">
                        <label>Masa Tamat <span class="required">*</span></label>
                        <input type="time" name="masa_tamat" value="{{ old('masa_tamat', '09:00') }}" min="08:00" max="17:00" required>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="section-label">Pengesahan Identiti</div>
                <p style="font-size:12px; color:#777; margin-bottom:0.75rem;">Masukkan emel dan kata laluan akaun anda untuk mengesahkan tempahan.</p>
                <div class="field">
                    <label>Emel <span class="required">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $user?->email) }}" placeholder="emel@domain.com" required>
                </div>
                <div class="field">
                    <label>Kata Laluan <span class="required">*</span></label>
                    <input type="password" name="password" placeholder="••••••••" required>
                </div>
            </div>

            <div class="form-footer">
                <a href="/booking/calendar?bilik={{ $bilik->id }}" class="btn-back">← Kembali</a>
                <button type="submit" class="btn-submit">Sahkan Tempahan</button>
            </div>
        </form>
    </div>
</div>

<footer>
    <div><strong>Jabatan Hutan Sarawak</strong> &nbsp;|&nbsp; Wisma Sumber Alam, Petra Jaya, 93660 Kuching, Sarawak</div>
    <div>© 2025 Jabatan Hutan Sarawak. Hak Cipta Terpelihara.</div>
</footer>
</body>
</html>