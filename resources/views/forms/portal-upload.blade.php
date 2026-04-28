<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borang Permohonan Muat Naik Portal — Jabatan Hutan Sarawak</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link rel="icon" href="{{ asset('images/logo-icon.png')}}">

</head>
<body>

<header>
    <div class="logo"></div>
    <div>
        <h1>Jabatan Hutan Sarawak</h1>
        <p>Forest Department Sarawak — Sistem Perkhidmatan Dalaman</p>
    </div>
</header>

<x-navbar />

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
            <h2>Borang Permohonan Muat Naik Portal</h2>
            <p>Sila isi semua maklumat yang diperlukan dengan tepat.</p>
        </div>

        <form method="POST" action="/forms/portal-upload" enctype="multipart/form-data">
            @csrf

            <div class="form-section">
                <div class="section-label">Bahagian A — Maklumat Pemohon</div>
                <div class="field-row">
                    <div class="field">
                        <label>Nama <span class="required">*</span></label>
                        <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Nama penuh" required>
                    </div>
                    <div class="field">
                        <label>Jawatan <span class="required">*</span></label></label>
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
                            <div style="color:#a32d2d; font-size:12px; margin-top:4px;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label>Email <span class="required">*</span></label></label>
                        <input type="text" name="telefon_email" value="{{ old('telefon_email') }}" placeholder="Email anda" required>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="section-label">Bahagian B — Maklumat Bahan</div>

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
                    <label>Upload Bahan <span style="font-size:11px; color:#777;">(Maks 5 fail, video tidak dibenarkan.)</span></label>
                    <input type="file" id="fileInput" name="fail[]" multiple accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip">
                    @error('fail')
                        <div style="color:#a32d2d; font-size:12px; margin-top:4px;">{{ $message }}</div>
                    @enderror
                    @error('fail.*')
                        <div style="color:#a32d2d; font-size:12px; margin-top:4px;">{{ $message }}</div>
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