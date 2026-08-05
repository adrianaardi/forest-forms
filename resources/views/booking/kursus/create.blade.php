<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kursus — JHS</title>
    <link rel="icon" href="{{ asset('images/logo-icon.png')}}">
    <link rel="stylesheet" href="{{ asset('style.css') }}">
</head>
<body>
<x-header />
<x-navbar :breadcrumbs="[['label' => 'Katalog Kursus', 'url' => route('booking.kursus.index')], ['label' => 'Tambah Kursus']]" />

<div class="pg-body">
    @if($errors->any())
        <div class="form-error-box">
            <ul class="form-error-list">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('booking.kursus.store') }}" class="form-card">
        @csrf

        <div class="form-card-header">
            <h2>Tambah Kursus Baharu</h2>
            <p>Hanya pengguna tempahan yang mempunyai kebenaran boleh menambah kursus ke dalam katalog.</p>
        </div>

        <div class="form-section">
            <div class="section-label">Maklumat Kursus</div>

            <div class="field">
                <label for="tajuk">Tajuk Kursus <span class="required-mark">*</span></label>
                <input id="tajuk" type="text" name="tajuk" value="{{ old('tajuk') }}" required>
            </div>

            <div class="field-row">
                <div class="field">
                    <label for="penganjur">Penganjur <span class="required-mark">*</span></label>
                    <input id="penganjur" type="text" name="penganjur" value="{{ old('penganjur') }}" required>
                </div>
                <div class="field">
                    <label for="lokasi">Lokasi <span class="required-mark">*</span></label>
                    <input id="lokasi" type="text" name="lokasi" value="{{ old('lokasi') }}" required>
                </div>
            </div>

            <div class="field-row">
                <div class="field">
                    <label for="tarikh_mula">Tarikh Mula <span class="required-mark">*</span></label>
                    <input id="tarikh_mula" type="date" name="tarikh_mula" value="{{ old('tarikh_mula') }}" required>
                </div>
                <div class="field">
                    <label for="tarikh_tamat">Tarikh Tamat <span class="required-mark">*</span></label>
                    <input id="tarikh_tamat" type="date" name="tarikh_tamat" value="{{ old('tarikh_tamat') }}" required>
                </div>
            </div>

            <div class="field-row">
                <div class="field">
                    <label for="jumlah_tempat">Jumlah Tempat <span class="required-mark">*</span></label>
                    <input id="jumlah_tempat" type="number" min="1" name="jumlah_tempat" value="{{ old('jumlah_tempat') }}" required>
                </div>
                <div class="field">
                    <label for="yuran">Yuran (RM)</label>
                    <input id="yuran" type="number" min="0" step="0.01" name="yuran" value="{{ old('yuran') }}" placeholder="Contoh: 450.00">
                </div>
            </div>

            <div class="field">
                <label for="is_dalam_sarawak">Kategori Lokasi <span class="required-mark">*</span></label>
                <select id="is_dalam_sarawak" name="is_dalam_sarawak" required>
                    <option value="1" {{ old('is_dalam_sarawak', '1') === '1' ? 'selected' : '' }}>Di Dalam Sarawak</option>
                    <option value="0" {{ old('is_dalam_sarawak') === '0' ? 'selected' : '' }}>Di Luar Sarawak</option>
                </select>
            </div>

            <div class="field">
                <label for="ringkasan">Ringkasan Kursus <span class="required-mark">*</span></label>
                <textarea id="ringkasan" name="ringkasan" required>{{ old('ringkasan') }}</textarea>
            </div>
        </div>

        <div class="form-footer">
            <a href="{{ route('booking.kursus.index') }}" class="btn-back">← Kembali</a>
            <button type="submit" class="btn-submit">Simpan Kursus</button>
        </div>
    </form>
</div>

<x-footer />
</body>
</html>