<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: auto; background: #fff; border-radius: 10px; overflow: hidden; border: 1px solid #dde8e1; }
        .header { background: #1a4731; padding: 1.25rem 1.5rem; }
        .header h1 { color: #fff; font-size: 16px; margin: 0; }
        .header p { color: rgba(255,255,255,0.65); font-size: 12px; margin: 4px 0 0; }
        .body { padding: 1.5rem; }
        .body p { font-size: 14px; color: #333; line-height: 1.6; }
        .detail-row { display: flex; gap: 10px; margin-bottom: 0.5rem; }
        .detail-label { font-size: 12px; color: #777; width: 160px; flex-shrink: 0; }
        .detail-value { font-size: 13px; color: #1a1a1a; }
        .btn { display: inline-block; margin-top: 1.5rem; padding: 12px 28px; background: #1a4731; color: #fff; text-decoration: none; border-radius: 8px; font-size: 14px; }
        .footer { background: #f9fafb; padding: 1rem 1.5rem; font-size: 11px; color: #999; border-top: 1px solid #eee; }
    </style>
        <link rel="icon" href="{{ asset('images/logo-icon.png')}}">

</head>
<body>
<div class="container">
    <div class="header">
        <h1>Jabatan Hutan Sarawak</h1>
        <p>Sistem Perkhidmatan Dalaman — Permohonan Muat Naik Portal</p>
    </div>
    <div class="body">
        <p>Salam hormat,</p>
        <p>Terdapat permohonan muat naik portal baharu yang memerlukan kelulusan anda.</p>

        <div class="detail-row">
            <span class="detail-label">No. Tiket</span>
            <span class="detail-value">{{ $permohonan->no_tiket }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Nama Pemohon</span>
            <span class="detail-value">{{ $permohonan->nama }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Bahagian / Unit</span>
            <span class="detail-value">{{ $permohonan->bahagian_nama }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Tajuk Maklumat</span>
            <span class="detail-value">{{ $permohonan->tajuk_maklumat }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Jenis Kandungan</span>
            <span class="detail-value">{{ $permohonan->jenis_kandungan }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Tarikh Hantar</span>
            <span class="detail-value">{{ \Carbon\Carbon::parse($permohonan->created_at)->format('d/m/Y H:i') }}</span>
        </div>

        <a href="{{ $approvalUrl }}" class="btn">Semak & Luluskan Permohonan</a>

        <p style="margin-top:1.5rem; font-size:12px; color:#999;">Jika anda tidak dapat tekan butang di atas, salin pautan berikut ke pelayar anda:<br>{{ $approvalUrl }}</p>
    </div>
    <div class="footer">
        Emel ini dihantar secara automatik oleh Sistem Perkhidmatan Dalaman Jabatan Hutan Sarawak. Sila jangan balas emel ini.
    </div>
</div>
</body>
</html>