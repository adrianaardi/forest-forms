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

        /* SAME AS SUBMISSION */
        .ticket-box { 
            background: #f0f4f1; 
            border: 1px solid #dde8e1; 
            border-radius: 8px; 
            padding: 1rem 1.25rem; 
            margin: 1rem 0; 
            text-align: center; 
        }
        .ticket-box p { margin: 0; font-size: 12px; color: #777; }
        .ticket-box h2 { margin: 4px 0 0; font-size: 20px; color: #1a4731; letter-spacing: 1px; }

        .detail-row { display: flex; gap: 10px; margin-bottom: 0.5rem; }
        .detail-label { font-size: 12px; color: #777; width: 160px; flex-shrink: 0; }
        .detail-value { font-size: 13px; color: #1a1a1a; }

        .status-lulus { display: inline-block; padding: 4px 14px; border-radius: 20px; font-size: 12px; font-weight: 600; background: #eaf3de; color: #27500a; }
        .status-semakan { display: inline-block; padding: 4px 14px; border-radius: 20px; font-size: 12px; font-weight: 600; background: #e6f1fb; color: #0c447c; }

        .remarks-box { 
            background: #f9f9f9; 
            border-left: 3px solid #1a4731; 
            padding: 0.75rem 1rem; 
            border-radius: 0 6px 6px 0; 
            margin: 0.75rem 0; 
            font-size: 13px; 
            color: #333; 
        }

        .btn { display: inline-block; margin-top: 1.25rem; padding: 10px 24px; background: #1a4731; color: #fff; text-decoration: none; border-radius: 8px; font-size: 13px; }

        .footer { background: #f9fafb; padding: 1rem 1.5rem; font-size: 11px; color: #999; border-top: 1px solid #eee; }
    </style>
</head>

<body>
<div class="container">

    <div class="header">
        <h1>Jabatan Hutan Sarawak</h1>
        <p>Sistem Perkhidmatan Dalaman — Kemaskini Status Aduan</p>
    </div>

    <div class="body">

        <p>Salam hormat <strong>{{ $aduan->nama }}</strong>,</p>

        @if($aduan->status === 'Belum Selesai')
            <p>Aduan ICT anda telah diterima dan sedang menunggu tindakan pihak pentadbir.</p>
        @elseif($aduan->status === 'Dalam Tindakan')
            <p>Aduan ICT anda sedang <strong>dalam tindakan</strong> oleh pihak teknikal.</p>
        @elseif($aduan->status === 'Tindakan Pembekal SAINS/Luar')
            <p>Aduan ICT anda telah dirujuk kepada <strong>pembekal luar (SAINS / vendor)</strong>.</p>
        @elseif($aduan->status === 'Tangguh/KIV')
            <p>Aduan ICT anda telah <strong>ditangguhkan (KIV)</strong>.</p>
        @elseif($aduan->status === 'Selesai')
            <p>Aduan ICT anda telah <strong>selesai</strong>. Terima kasih atas makluman anda.</p>
        @endif

        <!-- SAME STYLE AS SUBMISSION -->
        <div class="ticket-box">
            <p>Nombor Tiket Anda</p>
            <h2 style="cursor:pointer; user-select:all;">{{ $aduan->no_tiket }}</h2>
            <p style="font-size:11px; color:#777; margin-top:4px;">Klik nombor tiket untuk memilih</p>
        </div>

        <div class="detail-row">
            <span class="detail-label">Nama Pengadu</span>
            <span class="detail-value">{{ $aduan->nama }}</span>
        </div>

        <div class="detail-row">
            <span class="detail-label">Bahagian / Unit</span>
            <span class="detail-value">{{ $aduan->bahagian ?? '-' }}</span>
        </div>

        <div class="detail-row">
            <span class="detail-label">Wilayah</span>
            <span class="detail-value">{{ $aduan->wilayah ?? '-' }}</span>
        </div>

        <div class="detail-row">
            <span class="detail-label">Kategori Masalah</span>
            <span class="detail-value">{{ $aduan->kategori_masalah }}</span>
        </div>

        <div class="detail-row">
            <span class="detail-label">Status Semasa</span>
            <span class="detail-value">
                @if($aduan->status === 'Selesai')
                    <span class="status-lulus">Selesai</span>
                @else
                    <span class="status-semakan">{{ $aduan->status }}</span>
                @endif
            </span>
        </div>

        @if($aduan->remarks)
            <div class="detail-row" style="margin-top:0.5rem;">
                <span class="detail-label">Catatan Tindakan</span>
            </div>
            <div class="remarks-box">
                {{ $aduan->remarks }}
            </div>
        @endif

        <p style="margin-top:1rem;">Anda boleh menyemak status terkini menggunakan nombor tiket ini.</p>

        <a href="{{ url('/semak-tiket') }}" class="btn">Semak Status Aduan</a>

    </div>

    <div class="footer">
        Emel ini dihantar secara automatik oleh Sistem Perkhidmatan Dalaman Jabatan Hutan Sarawak. Sila jangan balas emel ini.
    </div>

</div>
</body>
</html>