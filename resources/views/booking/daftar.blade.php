<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — Sistem Tempahan</title>
    <link rel="icon" href="{{ asset('images/logo-icon.png')}}">
    <link rel="stylesheet" href="{{ asset('style.css') }}">
</head>
<body>
<x-header />
<x-navbar :breadcrumbs="[['label' => 'Tempah Bilik Mesyuarat', 'url' => '/booking/calendar'], ['label' => 'Daftar Akaun']]" />
<div class="pg-body" style="max-width:600px;">
    <div class="form-card">
        <div class="form-card-header">
            <h2>Daftar Akaun</h2>
            <p>Pendaftaran memerlukan kelulusan admin sebelum anda boleh membuat tempahan.</p>
        </div>
        <form method="POST" action="{{ route('booking.daftar.post') }}">
            @csrf
            <div class="form-section">
                @if($errors->any())
                    <div class="form-error-box">
                        <ul class="form-error-list">
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
                <a href="/booking/calendar" class="btn-back">← Kembali ke Kalendar</a>
                <button type="submit" class="btn-submit">Daftar</button>
            </div>
        </form>
    </div>
</div>

<x-footer />
</body>
</html>