<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borang Aduan ICT — Jabatan Hutan Sarawak</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
</head>
<body class="pg">

<header>
    <div class="logo"></div>
    <div>
        <h1>Jabatan Hutan Sarawak</h1>
        <p>Forest Department Sarawak — Sistem Perkhidmatan Dalaman</p>
    </div>
</header>

<nav>
    <a href="/" class="{{ request()->is('/') ? 'active' : '' }}">Laman Utama</a>

    @auth
        <a href="/admin/dashboard" class="{{ request()->is('admin/dashboard') ? 'active' : '' }}">Dashboard</a>
        
        <form method="POST" action="{{ route('logout') }}" style="margin-left:auto; display:flex; align-items:center;">
            @csrf
            <button type="submit" style="background:none; border:none; cursor:pointer; font-size:13px; color:rgba(255,255,255,0.7); padding:0;">
                Log Keluar
            </button>
        </form>
    @endauth

    @guest
        <a href="/login" style="margin-left: auto;">Admin</a>
    @endguest
</nav>

<div class="pg-body">

    @if($errors->any())
        <div style="background:#fdf0f0; border:1px solid #f5c1c1; color:#a32d2d; padding:0.75rem 1rem; border-radius:8px; margin-bottom:1rem; font-size:14px;">
            <ul style="margin:0; padding-left:1.2rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-card">
        <div class="form-card-header">
            <h2>Borang Aduan Baikpulih ICT / Digital</h2>
            <p>Sila isi semua maklumat yang diperlukan dengan tepat.</p>
        </div>

        <form method="POST" action="/forms/ict-aduan">
            @csrf

            <div class="form-section">
                <div class="section-label">Bahagian A — Maklumat Pengadu</div>
                <div class="field-row">
                    <div class="field">
                        <label>Nama <span style="color:#c0392b">*</span></label>
                        <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Nama penuh" required>
                    </div>
                    <div class="field">
                        <label>Jawatan</label>
                        <input type="text" name="jawatan" value="{{ old('jawatan') }}" placeholder="Jawatan anda">
                    </div>
                </div>
                <div class="field-row">
                    <div class="field">
                        <label>Bahagian / Unit</label>
                        <input type="text" name="bahagian" value="{{ old('bahagian') }}" placeholder="Cth: Bahagian ICT">
                    </div>
                    <div class="field">
                        <label>No Telefon</label>
                        <input type="text" name="telefon" value="{{ old('telefon') }}" placeholder="Cth: 082-XXXXXX">
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="section-label">Bahagian B — Maklumat Kerosakan</div>
                <div class="field">
                    <label>Kategori Masalah <span style="color:#c0392b">*</span></label>
                    <select name="kategori_masalah" id="kategori" onchange="toggleLain()" required>
                        <option value="">-- Pilih kategori --</option>
                        <option {{ old('kategori_masalah') == 'CPU' ? 'selected' : '' }}>CPU</option>
                        <option {{ old('kategori_masalah') == 'Monitor' ? 'selected' : '' }}>Monitor</option>
                        <option {{ old('kategori_masalah') == 'Printer' ? 'selected' : '' }}>Printer</option>
                        <option {{ old('kategori_masalah') == 'Scanner' ? 'selected' : '' }}>Scanner</option>
                        <option {{ old('kategori_masalah') == 'Perisian' ? 'selected' : '' }}>Perisian</option>
                        <option {{ old('kategori_masalah') == 'Internet' ? 'selected' : '' }}>Internet</option>
                        <option value="lain" {{ old('kategori_masalah') == 'lain' ? 'selected' : '' }}>Lain-lain</option>
                    </select>
                    <div class="lain-box" id="lain-box">
                        <input type="text" name="masalah_lain" value="{{ old('masalah_lain') }}" placeholder="Sila nyatakan masalah">
                    </div>
                </div>
                <div class="field">
                    <label>Keterangan Kerosakan</label>
                    <textarea name="keterangan_kerosakan" rows="4" placeholder="Huraikan masalah dengan lebih lanjut...">{{ old('keterangan_kerosakan') }}</textarea>
                </div>
            </div>

            <div class="form-footer">
                <a href="/" class="btn-back">← Kembali</a>
                <button type="submit" class="btn-submit">Hantar Aduan</button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleLain() {
    var v = document.getElementById('kategori').value;
    document.getElementById('lain-box').style.display = v === 'lain' ? 'block' : 'none';
}
</script>

</body>
</html>