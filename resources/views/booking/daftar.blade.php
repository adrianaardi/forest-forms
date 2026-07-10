<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — Sistem Tempahan</title>
    <link rel="icon" href="{{ asset('images/logo-icon.png')}}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans+Flex:opsz,wght@6..144,1..1000&family=Lora:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('style.css') }}">
</head>
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
                    <select name="bahagian">
                        <option value="">-- Pilih Bahagian / Unit --</option>
                        <option value="Pejabat Direktorat" {{ old('bahagian') == 'Pejabat Direktorat' ? 'selected' : '' }}>Pejabat Direktorat</option>
                        <option value="Bahagian Perancangan dan Pengurusan Hutan" {{ old('bahagian') == 'Bahagian Perancangan dan Pengurusan Hutan' ? 'selected' : '' }}>Bahagian Perancangan dan Pengurusan Hutan</option>
                        <option value="Bahagian Pelesenan" {{ old('bahagian') == 'Bahagian Pelesenan' ? 'selected' : '' }}>Bahagian Pelesenan</option>
                        <option value="Bahagian Penyelidikan dan Pembangunan" {{ old('bahagian') == 'Bahagian Penyelidikan dan Pembangunan' ? 'selected' : '' }}>Bahagian Penyelidikan dan Pembangunan</option>
                        <option value="Bahagian Hasil dan Pengurusan Data" {{ old('bahagian') == 'Bahagian Hasil dan Pengurusan Data' ? 'selected' : '' }}>Bahagian Hasil dan Pengurusan Data</option>
                        <option value="Bahagian Hal Ehwal Antarabangsa" {{ old('bahagian') == 'Bahagian Hal Ehwal Antarabangsa' ? 'selected' : '' }}>Bahagian Hal Ehwal Antarabangsa</option>
                        <option value="Bahagian Teknologi Hutan dan Geospatial" {{ old('bahagian') == 'Bahagian Teknologi Hutan dan Geospatial' ? 'selected' : '' }}>Bahagian Teknologi Hutan dan Geospatial</option>
                        <option value="Bahagian Restorasi dan Hutan Industri" {{ old('bahagian') == 'Bahagian Restorasi dan Hutan Industri' ? 'selected' : '' }}>Bahagian Restorasi dan Hutan Industri</option>
                        <option value="Bahagian Pewartaan dan Konservasi" {{ old('bahagian') == 'Bahagian Pewartaan dan Konservasi' ? 'selected' : '' }}>Bahagian Pewartaan dan Konservasi</option>
                        <option value="Bahagian Perhutanan Sosial" {{ old('bahagian') == 'Bahagian Perhutanan Sosial' ? 'selected' : '' }}>Bahagian Perhutanan Sosial</option>
                        <option value="Bahagian Pencegahan dan Penguatkuasaan" {{ old('bahagian') == 'Bahagian Pencegahan dan Penguatkuasaan' ? 'selected' : '' }}>Bahagian Pencegahan dan Penguatkuasaan</option>
                        <option value="Bahagian Khidmat Pengurusan" {{ old('bahagian') == 'Bahagian Khidmat Pengurusan' ? 'selected' : '' }}>Bahagian Khidmat Pengurusan</option>
                        <option value="Bahagian Pembangunan Projek" {{ old('bahagian') == 'Bahagian Pembangunan Projek' ? 'selected' : '' }}>Bahagian Pembangunan Projek</option>
                        <option value="Unit Integriti dan Audit Dalaman" {{ old('bahagian') == 'Unit Integriti dan Audit Dalaman' ? 'selected' : '' }}>Unit Integriti dan Audit Dalaman</option>
                        <option value="Unit Korporat dan Permodenan Perkhidmatan" {{ old('bahagian') == 'Unit Korporat dan Permodenan Perkhidmatan' ? 'selected' : '' }}>Unit Korporat dan Permodenan Perkhidmatan</option>
                        <option value="Unit Pengurusan Geopark" {{ old('bahagian') == 'Unit Pengurusan Geopark' ? 'selected' : '' }}>Unit Pengurusan Geopark</option>
                        <option value="Unit Perancangan Strategik" {{ old('bahagian') == 'Unit Perancangan Strategik' ? 'selected' : '' }}>Unit Perancangan Strategik</option>
                        <option value="Unit Perundangan dan Khidmat Nasihat" {{ old('bahagian') == 'Unit Perundangan dan Khidmat Nasihat' ? 'selected' : '' }}>Unit Perundangan dan Khidmat Nasihat</option>
                        <option value="Pejabat Wilayah" {{ old('bahagian') == 'Pejabat Wilayah' ? 'selected' : '' }}>Pejabat Wilayah</option>
                    </select>
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
    <div class="footer-content">
        <strong>Seksyen Pengurusan Dan Transformasi Digital</strong> 
        <span class="divider">|</span> 
        Tingkat 15, Bangunan Baitul Makmur II, Medan Raya, Petra Jaya, 93050 Kuching, Sarawak
    </div>
    
    <div class="footer-right">
        <span>© <?php echo date("Y"); ?> Jabatan Hutan Sarawak. Hak Cipta Terpelihara.</span>
        
        @guest('web')
            @guest('booking_user')      
                <a href="/login" class="footer-login-link" title="Login">
                    <svg class="user-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <circle cx="12" cy="10" r="3" />
                        <path d="M7 18c0-2.5 2-4.5 5-4.5s5 2 5 4.5" />
                    </svg>
                </a>
            @endguest
        @endguest
    </div>
</footer>
</body>
</html>