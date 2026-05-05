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
        .body p { font-size: 14px; color: #333; line-height: 1.6; margin-bottom: 0.75rem; }
        .detail-box { background: #f0f4f1; border: 1px solid #dde8e1; border-radius: 8px; padding: 1rem 1.25rem; margin: 1rem 0; }
        .detail-row { display: flex; gap: 10px; margin-bottom: 0.5rem; }
        .detail-label { font-size: 12px; color: #777; width: 140px; flex-shrink: 0; }
        .detail-value { font-size: 13px; color: #1a1a1a; font-weight: 500; }
        .cancel-box { background: #fdf0f0; border: 1px solid #f5c1c1; border-radius: 8px; padding: 1rem 1.25rem; margin-top: 1.5rem; }
        .cancel-box p { font-size: 13px; color: #a32d2d; margin: 0 0 0.75rem; }
        .btn-cancel { display: inline-block; padding: 10px 20px; background: #a32d2d; color: #fff; text-decoration: none; border-radius: 6px; font-size: 13px; }
        .footer { background: #f9fafb; padding: 1rem 1.5rem; font-size: 11px; color: #999; border-top: 1px solid #eee; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Jabatan Hutan Sarawak</h1>
        <p>Sistem Tempahan Bilik Mesyuarat — Pengesahan Tempahan</p>
    </div>
    <div class="body">
        <p>Salam hormat <strong>{{ $user->name }}</strong>,</p>
        <p>Tempahan bilik mesyuarat anda telah berjaya disahkan.</p>

        <div class="detail-box">
            <div class="detail-row">
                <span class="detail-label">Tajuk Mesyuarat</span>
                <span class="detail-value">{{ $booking->tajuk_mesyuarat }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Bilik</span>
                <span class="detail-value">{{ $bilik->nama_bilik }} — {{ $bilik->aras }}, {{ $bilik->wing }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Tarikh</span>
                <span class="detail-value">{{ \Carbon\Carbon::parse($booking->tarikh)->format('d/m/Y') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Masa</span>
                <span class="detail-value">{{ substr($booking->masa_mula, 0, 5) }} – {{ substr($booking->masa_tamat, 0, 5) }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Nama Pemohon</span>
                <span class="detail-value">{{ $user->name }} ({{ $user->bahagian ?? '-' }})</span>
            </div>
        </div>

        <div class="cancel-box">
            <p>Jika anda ingin membatalkan atau mengubah tempahan ini, sila batalkan dan buat tempahan baharu melalui sistem. Klik butang di bawah untuk membatalkan.</p>
            <a href="{{ $cancelUrl }}" class="btn-cancel">Batalkan Tempahan</a>
        </div>

        <p style="margin-top:1.5rem; font-size:12px; color:#999;">Jika anda tidak membuat tempahan ini, sila abaikan emel ini.</p>
    </div>
    <div class="footer">
        Emel ini dihantar secara automatik oleh Sistem Tempahan Bilik Mesyuarat Jabatan Hutan Sarawak. Sila jangan balas emel ini.
    </div>
</div>
</body>
</html>