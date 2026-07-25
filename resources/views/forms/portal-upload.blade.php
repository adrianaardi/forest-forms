<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borang Permohonan Muat Naik Portal - Jabatan Hutan Sarawak</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link rel="icon" href="{{ asset('images/logo-icon.png') }}">
</head>
<body>

<x-header />

<x-navbar :breadcrumbs="[['label' => 'Pengurusan Laman Web']]" />

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

    <div class="form-card">
        <div class="form-card-header">
            <h2>Pengurusan Muat Naik ke Laman Web</h2>
            <p>Sila isi semua maklumat yang diperlukan dengan tepat.</p>
        </div>

        <form method="POST" action="/forms/portal-upload" enctype="multipart/form-data">
            @csrf

            <div class="form-section">
                <div class="section-label">Bahagian A - Maklumat Pemohon</div>

                <div class="field-row">
                    <div class="field">
                        <label>Nama <span class="required">*</span></label>
                        <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Nama penuh" required>
                    </div>

                    <div class="field">
                        <label>Jawatan <span class="required">*</span></label>
                        <input type="text" name="jawatan" value="{{ old('jawatan') }}" placeholder="Jawatan anda" required>
                    </div>
                </div>

                <div class="field-row">
                    <div class="field">
                        <label>Bahagian / Unit <span class="required">*</span></label>
                        <select name="bahagian_id" required>
                            <option value="">-- Pilih bahagian --</option>
                            @foreach($bahagian as $b)
                                <option value="{{ $b->id }}" {{ old('bahagian_id') == $b->id ? 'selected' : '' }}>
                                    {{ $b->nama_bahagian }}
                                </option>
                            @endforeach
                        </select>
                        @error('bahagian_id')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="field">
                        <label>Email <span class="required">*</span></label>
                        <input type="email" name="telefon_email" value="{{ old('telefon_email') }}" placeholder="Email anda" required>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="section-label">Bahagian B - Maklumat Bahan</div>

                <div class="field">
                    <label>Tajuk Maklumat <span class="required">*</span></label>
                    <input type="text" name="tajuk_maklumat" value="{{ old('tajuk_maklumat') }}" placeholder="Tajuk kandungan" required>
                </div>

                <div class="field">
                    <label>Isi Kandungan</label>
                    <textarea name="isi_kandungan" rows="4" placeholder="Huraikan kandungan yang ingin dimuat naik...">{{ old('isi_kandungan') }}</textarea>
                </div>

                <div class="field-row">
                    <div class="field">
                        <label>Jenis Kandungan <span class="required">*</span></label>
                        <select name="jenis_kandungan" id="kategori" onchange="toggleKategoriLain()" required>
                            <option value="">-- Pilih jenis --</option>
                            <option {{ old('jenis_kandungan') == 'Pengumuman' ? 'selected' : '' }}>Pengumuman</option>
                            <option {{ old('jenis_kandungan') == 'Foto' ? 'selected' : '' }}>Foto</option>
                            <option {{ old('jenis_kandungan') == 'Event' ? 'selected' : '' }}>Event</option>
                            <option {{ old('jenis_kandungan') == 'Tender' ? 'selected' : '' }}>Tender</option>
                            <option {{ old('jenis_kandungan') == 'Banner/Poster' ? 'selected' : '' }}>Banner/Poster</option>
                            <option {{ old('jenis_kandungan') == 'Jawatan Kosong' ? 'selected' : '' }}>Jawatan Kosong</option>
                            <option value="lain" {{ old('jenis_kandungan') == 'lain' ? 'selected' : '' }}>Lain-lain</option>
                        </select>
                        <div class="lain-box" id="kategori_lain_box">
                            <input type="text" name="kandungan_lain" value="{{ old('kandungan_lain') }}" placeholder="Sila nyatakan">
                        </div>
                    </div>

                    <div class="field">
                        <label>Jenis Pengemaskinian <span class="required">*</span></label>
                        <select name="jenis_pengemaskinian" id="jenis" onchange="toggleJenisLain()" required>
                            <option value="">-- Pilih jenis --</option>
                            <option {{ old('jenis_pengemaskinian') == 'Maklumat Baru' ? 'selected' : '' }}>Maklumat Baru</option>
                            <option {{ old('jenis_pengemaskinian') == 'Pembetulan' ? 'selected' : '' }}>Pembetulan</option>
                            <option value="lain" {{ old('jenis_pengemaskinian') == 'lain' ? 'selected' : '' }}>Lain-lain</option>
                        </select>
                        <div class="lain-box" id="jenis_lain_box">
                            <input type="text" name="pengemaskinian_lain" value="{{ old('pengemaskinian_lain') }}" placeholder="Sila nyatakan">
                        </div>
                    </div>
                </div>

                <div class="field">
                    <label>Upload Bahan <span class="help-text-muted">(Maks 5 fail, video tidak dibenarkan.)</span></label>
                    <input type="file" id="fileInput" name="fail[]" multiple accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip">
                    @error('fail')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                    @error('fail.*')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field-row">
                    <div class="field">
                        <label>Tarikh Mula Paparan</label>
                        <input type="date" name="tarikh_mula" value="{{ old('tarikh_mula') }}">
                    </div>

                    <div class="field">
                        <label>Tarikh Akhir Paparan</label>
                        <input type="date" name="tarikh_akhir" value="{{ old('tarikh_akhir') }}">
                    </div>
                </div>
            </div>

            <div class="form-footer">
                <a href="/" class="btn-back">← Kembali</a>
                <button type="submit" class="btn-submit">Hantar Permohonan</button>
            </div>
        </form>
    </div>
</div>

<x-footer />

<script>
function toggleKategoriLain() {
    var v = document.getElementById('kategori').value;
    document.getElementById('kategori_lain_box').classList.toggle('is-visible', v === 'lain');
}

function toggleJenisLain() {
    var v = document.getElementById('jenis').value;
    document.getElementById('jenis_lain_box').classList.toggle('is-visible', v === 'lain');
}

document.addEventListener('DOMContentLoaded', function () {
    toggleKategoriLain();
    toggleJenisLain();
});
</script>

</body>
</html>
