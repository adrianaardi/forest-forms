<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dalam Semakan — Jabatan Hutan Sarawak</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link rel="icon" href="{{ asset('images/logo-icon.png')}}">
</head>
<body>

<x-header />

<div class="pg-body">
    <div class="form-card">
        <div class="form-card-header">
            <h2>Permohonan Dalam Semakan</h2>
            <p>Maklum balas anda telah direkodkan.</p>
        </div>
        <div class="form-section">
            <div class="alert alert-info">
                Permohonan {{ $permohonan->no_tiket }} sedang dalam semakan.
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