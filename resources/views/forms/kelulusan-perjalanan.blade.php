<!DOCTYPE html>
<html lang="ms">
<head>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelulusan Perjalanan Pegawai</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link rel="icon" href="{{ asset('images/logo-icon.png')}}">
</head>
<body>
<x-header />
<x-navbar :breadcrumbs="[['label' => 'Kelulusan Perjalanan Pegawai']]" />
<div class="pg-body">
    <div class="form-card">
        <div class="form-card-header">
            <h2>Kelulusan Perjalanan Pegawai Jabatan Hutan Sarawak</h2>
            <p>Permohonan kelulusan perjalanan rasmi bagi pegawai Jabatan Hutan Sarawak. Sila lengkapkan maklumat di bawah.</p>
        </div>
        <form action="/forms/kelulusan-perjalanan" method="POST">
            @csrf
            <div class="form-section">
                
                <div class="field">
                    <label>Nama Pegawai <span class="required">*</span></label>
                    <input type="text" name="nama_pegawai" value="{{ old('nama_pegawai') }}" placeholder="Nama penuh pegawai" required>
                </div>
                <div class="field-row">
                    <div class="field">
                        <label>Jawatan <span class="required">*</span></label>
                        <input type="text" name="jawatan" value="{{ old('jawatan') }}" placeholder="Jawatan pegawai" required>
                    </div>
                    <div class="field">
                        <label>Bahagian<span class="required">*</span></label>
                        <select name="bahagian" value="{{ old('bahagian') }}" required>
                            <option value="">Pilih Bahagian</option>
                            <option value="Bahagian A">Bahagian A</option>
                            <option value="Bahagian B">Bahagian B</option>
                            <option value="Bahagian C">Bahagian C</option>
                        </select>
                    </div>
                </div>
                <div class="field-row">
                    <div class="field">
                        <label>Tarikh Perjalanan <span class="required">*</span></label>
                        <input type="date" name="tarikh_perjalanan" value="{{ old('tarikh_perjalanan') }}" required>
                    </div>
                    <div class="field">
                        <label>Destinasi <span class="required">*</span></label>
                        <input type="text" name="destinasi" value="{{ old('destinasi') }}" placeholder="Destinasi perjalanan" required>
                    </div>
                </div>
                <div class="field">
                    <label>Jenis Perjalanan <span class="required">*</span></label>
                    <select name="jenis_perjalanan" value="{{ old('jenis_perjalanan') }}" required>
                        <option value="">Pilih Jenis Perjalanan</option>
                        <option value="Kenderaan Sendiri">Kenderaan Sendiri</option>
                        <option value="Air Borneo">Air Borneo</option>
                    </select>
                </div>
                
                <div class="field-row">
                    <div class="field">

                </div>
            </div>
        </form>
    </div>
</div>
<x-footer />
</body>

</html>
