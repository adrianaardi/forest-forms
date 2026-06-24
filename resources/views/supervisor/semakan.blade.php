<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dalam Semakan — Jabatan Hutan Sarawak</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Google+Sans+Flex:opsz,wght@6..144,1..1000&family=Lora:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet"><link rel="stylesheet" href="{{ asset('style.css') }}"></head>
<body>

<header>
    <div class="logo"></div>
    <div>
<a href="/" style="color: white; text-decoration: none;"><h1>Jabatan Hutan Sarawak</h1></a>
        <p> Hub Aplikasi Perkhidmatan Atas Talian</p>
    </div>
</header>

<div class="pg-body">
    <div class="form-card">
        <div class="form-card-header">
            <h2>Permohonan Dalam Semakan</h2>
            <p>Maklum balas anda telah direkodkan.</p>
        </div>
        <div class="form-section" style="text-align:center; padding:2rem;">
            <div style="font-size:40px; margin-bottom:1rem;">🔍</div>
            <p style="font-size:15px; font-weight:500; color:#185fa5; margin-bottom:0.5rem;">
                Permohonan {{ $permohonan->no_tiket }} sedang dalam semakan.
            </p>
            <p style="font-size:13px; color:#666; margin-bottom:1rem;">
                Pemohon telah dimaklumkan melalui emel sekiranya mereka menyertakan alamat emel yang betul.
            </p>
            @if($permohonan->catatan_semakan)
            <div style="background:#f9fafb; border:1px solid #dde8e1; border-radius:8px; padding:0.75rem 1rem; font-size:13px; color:#333; text-align:left; max-width:400px; margin:0 auto;">
                <strong style="font-size:11px; color:#777; display:block; margin-bottom:4px;">CATATAN ANDA</strong>
                {{ $permohonan->catatan_semakan }}
            </div>
            @endif
        </div>
        <div class="form-footer">
            <span></span>
            <a href="{{ route('supervisor.view', $permohonan->token) }}" class="btn-submit" style="text-decoration:none;">Kembali ke Permohonan</a>
        </div>
    </div>
</div>

<footer>
    <div><strong>Seksyen Pengurusan Dan Transformasi Digital</strong> &nbsp;|&nbsp; Tingkat 15, Bangunan Baitul Makmur II, Medan Raya, Petra Jaya, 93050 Kuching, Sarawak</div>
    <div>© <?php echo date("Y"); ?> Jabatan Hutan Sarawak. Hak Cipta Terpelihara.</div>
</footer>
</body>
</html>