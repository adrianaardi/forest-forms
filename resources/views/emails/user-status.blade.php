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
        .detail-row { display: flex; gap: 10px; margin-bottom: 0.5rem; }
        .detail-label { font-size: 12px; color: #777; width: 160px; flex-shrink: 0; }
        .detail-value { font-size: 13px; color: #1a1a1a; }
        .status-lulus { display: inline-block; padding: 4px 14px; border-radius: 20px; font-size: 12px; font-weight: 600; background: #eaf3de; color: #27500a; }
        .status-semakan { display: inline-block; padding: 4px 14px; border-radius: 20px; font-size: 12px; font-weight: 600; background: #e6f1fb; color: #0c447c; }
        .remarks-box { background: #f9f9f9; border-left: 3px solid #1a4731; padding: 0.75rem 1rem; border-radius: 0 6px 6px 0; margin: 0.75rem 0; font-size: 13px; color: #333; }
        .btn { display: inline-block; margin-top: 1.25rem; padding: 10px 24px; background: #1a4731; color: #fff; text-decoration: none; border-radius: 8px; font-size: 13px; }
        .footer { background: #f9fafb; padding: 1rem 1.5rem; font-size: 11px; color: #999; border-top: 1px solid #eee; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Jabatan Hutan Sarawak</h1>
        <p>Sistem Perkhidmatan Dalaman — Kemaskini Status Permohonan</p>
    </div>
    <div class="body">
        <p>Salam hormat <strong>{{ $permohonan->nama }}</strong>,</p>

        @if($permohonan->status === 'Diluluskan')
            <p>Permohonan muat naik portal anda telah <strong>diluluskan</strong> oleh penyelia bahagian.</p>
        @else
            <p>Permohonan muat naik portal anda sedang <strong>dalam semakan</strong> oleh penyelia bahagian.</p>
        @endif

        <div class="detail-row">
            <span class="detail-label">No. Tiket</span>
            <span class="detail-value">
                <span style="user-select:all; -webkit-user-select:all; cursor:pointer;" title="Klik untuk pilih">{{ $permohonan->no_tiket }}</span>
                <span style="font-size:11px; color:#777;"> — klik untuk pilih</span>
            </span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Tajuk Maklumat</span>
            <span class="detail-value">{{ $permohonan->tajuk_maklumat }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Bahagian / Unit</span>
            <span class="detail-value">{{ $permohonan->bahagian_nama }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Status</span>
            <span class="detail-value">
                @if($permohonan->status === 'Diluluskan')
                    <span class="status-lulus">Diluluskan</span>
                @else
                    <span class="status-semakan">Dalam Semakan</span>
                @endif
            </span>
        </div>

        @if($permohonan->catatan_semakan)
        <div class="detail-row" style="margin-top:0.5rem;">
            <span class="detail-label">Catatan Penyelia</span>
            <span class="detail-value"></span>
        </div>
        <div class="remarks-box">{{ $permohonan->catatan_semakan }}</div>
        @endif

        <a href="{{ url('/semak-tiket') }}" class="btn">Semak Status Permohonan</a>
    </div>
    <div class="footer">
        Emel ini dihantar secara automatik oleh Sistem Perkhidmatan Dalaman Jabatan Hutan Sarawak. Sila jangan balas emel ini.
    </div>
</div>
</body>
</html>