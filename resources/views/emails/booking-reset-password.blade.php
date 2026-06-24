<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: auto; background: #fff; border-radius: 10px; overflow: hidden; border: 1px solid #dde8e1; }
        .header { background: #194169; padding: 1.25rem 1.5rem; }
        .header h1 { color: #fff; font-size: 16px; margin: 0; }
        .header p { color: rgba(255,255,255,0.65); font-size: 12px; margin: 4px 0 0; }
        .body { padding: 1.5rem; }
        .body p { font-size: 14px; color: #333; line-height: 1.6; margin-bottom: 0.75rem; }
        .btn { display: inline-block; padding: 12px 24px; background: #194169; color: #fff; text-decoration: none; border-radius: 6px; font-size: 14px; margin: 1rem 0; }
        .note { font-size: 12px; color: #999; margin-top: 1rem; }
        .footer { background: #f9fafb; padding: 1rem 1.5rem; font-size: 11px; color: #999; border-top: 1px solid #eee; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
<a href="/" style="color: white; text-decoration: none;"><h1>Jabatan Hutan Sarawak</h1></a>
        <p>Sistem Tempahan Bilik Mesyuarat — Reset Kata Laluan</p>
    </div>
    <div class="body">
        <p>Salam hormat <strong>{{ $user->name }}</strong>,</p>
        <p>Kami menerima permintaan untuk menetapkan semula kata laluan akaun anda. Klik butang di bawah untuk meneruskan.</p>
        <a href="{{ $resetUrl }}" class="btn">Reset Kata Laluan</a>
        <p class="note">Pautan ini akan tamat tempoh dalam 60 minit. Jika anda tidak membuat permintaan ini, sila abaikan emel ini.</p>
    </div>
    <div class="footer">
        Emel ini dihantar secara automatik. Sila jangan balas emel ini.
    </div>
</div>
</body>
</html>