<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diluluskan — Jabatan Hutan Sarawak</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link rel="icon" href="{{ asset('images/logo-icon.png')}}">
</head>
<body>

<x-header />

<div class="pg-body">
    <div class="form-card">
        <div class="form-card-header">
            <h2>Permohonan Diluluskan</h2>
            <p>Kelulusan anda telah direkodkan.</p>
        </div>
        <div class="form-section">
            <div class="alert alert-success">
                Permohonan {{ $permohonan->no_tiket }} telah diluluskan.
            </div>
            <p class="help-text-muted">
                Pemohon telah dimaklumkan melalui emel sekiranya mereka menyertakan alamat emel yang betul.
            </p>
            @if($permohonan->catatan_semakan)
                <div class="detail-group">
                    <div class="detail-field">
                        <label>Catatan Anda</label>
                        <p class="text-pre-wrap">{{ $permohonan->catatan_semakan }}</p>
                    </div>
                </div>
            @endif
        </div>
        <div class="form-footer">
            <span></span>
            <a href="{{ route('supervisor.view', $permohonan->token) }}" class="btn-submit">Kembali ke Permohonan</a>
        </div>
    </div>
</div>

<x-footer />
</body>
</html>