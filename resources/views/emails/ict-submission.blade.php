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
        .ticket-box { background: #f0f4f1; border: 1px solid #dde8e1; border-radius: 8px; padding: 1rem 1.25rem; margin: 1rem 0; text-align: center; }
        .ticket-box p { margin: 0; font-size: 12px; color: #777; }
        .ticket-box h2 { margin: 4px 0 0; font-size: 20px; color: #1a4731; letter-spacing: 1px; }
        .detail-row { display: flex; gap: 10px; margin-bottom: 0.5rem; }
        .detail-label { font-size: 12px; color: #777; width: 160px; flex-shrink: 0; }
        .detail-value { font-size: 13px; color: #1a1a1a; }
        .btn { display: inline-block; margin-top: 1.25rem; padding: 10px 24px; background: #1a4731; color: #fff; text-decoration: none; border-radius: 8px; font-size: 13px; }
        .footer { background: #f9fafb; padding: 1rem 1.5rem; font-size: 11px; color: #999; border-top: 1px solid #eee; }
    </style>
        <link rel="icon" href="{{ asset('images/logo-icon.png')}}">

</head>
<body>
<div class="container">
    <div class="header">
        <h1>Jabatan Hutan Sarawak</h1>
        <p>Sistem Perkhidmatan Dalaman — Pengesahan Aduan</p>
    </div>
    <div class="body">
        <p>Salam hormat <strong>{{ $aduan->nama }}</strong>,</p>
        <p>Aduan ICT anda telah berjaya dihantar dan sedang menunggu tindakan pihak teknikal.</p>

        <div class="ticket-box">
            <p>Nombor Tiket Anda</p>
            <h2 id="tiket-no" onclick="this.focus(); document.execCommand('selectAll')" 
                style="cursor:pointer; user-select:all; -webkit-user-select:all;"
                title="Klik untuk pilih">{{ $aduan->no_tiket }}</h2>
            <p style="font-size:11px; color:#777; margin-top:4px;">Klik nombor tiket untuk memilih, kemudian Ctrl+C untuk salin.</p>
        </div>

        <div class="detail-row">
            <span class="detail-label">Nama Pengadu</span>
            <span class="detail-value">{{ $aduan->nama }}</span>
        </div>

        <div class="detail-row">
            <span class="detail-label">Bahagian / Unit</span>
            <span class="detail-value">{{ $aduan->bahagian }}</span>
        </div>

        <div class="detail-row">
            <span class="detail-label">Wilayah</span>
            <span class="detail-value">{{ $aduan->wilayah }}</span>
        </div>

        <div class="detail-row">
            <span class="detail-label">Kategori Masalah</span>
            <span class="detail-value">{{ $aduan->kategori_masalah }}</span>
        </div>

        <div class="detail-row">
            <span class="detail-label">Tarikh Hantar</span>
            <span class="detail-value">
                {{ \Carbon\Carbon::parse($aduan->created_at)->format('d/m/Y H:i') }}
            </span>
        </div>

        <div class="detail-row">
            <span class="detail-label">Status Semasa</span>
            <span class="detail-value">
                @if($aduan->status == 'Selesai')
                    <span style="color:#27500a; font-weight:bold;">Selesai</span>
                @elseif($aduan->status == 'Dalam Tindakan')
                    <span style="color:#0c447c; font-weight:bold;">Dalam Tindakan</span>
                @else
                    <span style="color:#777; font-weight:bold;">Belum Selesai</span>
                @endif
            </span>
        </div>

        <p style="margin-top:1rem;">Simpan nombor tiket ini untuk menyemak status aduan anda.</p>

        <a href="{{ url('/semak-tiket') }}" class="btn">Semak Status Aduan</a>
    </div>
    <div class="footer">
        Emel ini dihantar secara automatik oleh Sistem Perkhidmatan Dalaman Jabatan Hutan Sarawak. Sila jangan balas emel ini.
    </div>
</div>
</body>
</html>