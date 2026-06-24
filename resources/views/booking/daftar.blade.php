<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — Sistem Tempahan</title>
        <link rel="icon" href="{{ asset('images/logo-icon.png')}}">

<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Google+Sans+Flex:opsz,wght@6..144,1..1000&family=Lora:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet"><link rel="stylesheet" href="{{ asset('style.css') }}"></head>
<body>
<header>
    <div class="logo"></div>
    <div>
<a href="/" style="color: white; text-decoration: none;"><h1>Jabatan Hutan Sarawak</h1></a>
        <p> Hub Aplikasi Perkhidmatan Atas Talian</p>
    </div>
</header>
<x-navbar :breadcrumbs="[['label' => 'Tempah Bilik Mesyuarat', 'url' => '/booking/calendar'], ['label' => 'Daftar Akaun']]" />
<div class="pg-body" style="max-width:500px;">
    <div class="form-card">
        <div class="form-card-header" style="background:#194169;">
            <h2>Daftar Akaun</h2>
            <p>Pendaftaran memerlukan kelulusan admin sebelum anda boleh membuat tempahan.</p>
        </div>
        <form method="POST" action="{{ route('booking.daftar.post') }}">
            @csrf
            <div class="form-section">
                @if($errors->any())
                    <div style="background:#fdf0f0; border:1px solid #f5c1c1; color:#a32d2d; padding:0.75rem 1rem; border-radius:8px; margin-bottom:1rem; font-size:13px;">
                        <ul style="margin:0; padding-left:1.2rem;">
                            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
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
                <div class="field">
                <label>Wilayah <span class="required">*</span></label>
                <select name="wilayah_id" required>
                    <option value="">-- Pilih Wilayah --</option>
                    @foreach($wilayahs as $w)
                        <option value="{{ $w->id }}" {{ old('wilayah_id') == $w->id ? 'selected' : '' }}>
                            {{ $w->nama_wilayah }}
                        </option>
                    @endforeach
                </select>
            </div>
                <div class="field">
                    <label>No. Telefon</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Cth: 0123456789">
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
                <a href="/booking/calendar">← Kembali ke Kalendar</a>
                <button type="submit" class="btn-submit" style="background:#194169;">Daftar</button>
            </div>
        </form>
    </div>
</div>

<footer>
    <div><strong>Seksyen Pengurusan Dan Transformasi Digital</strong> &nbsp;|&nbsp; Tingkat 15, Bangunan Baitul Makmur II, Medan Raya, Petra Jaya, 93050 Kuching, Sarawak</div>
    <div>© <?php echo date("Y"); ?> Jabatan Hutan Sarawak. Hak Cipta Terpelihara.</div>
</footer></body>
</html>