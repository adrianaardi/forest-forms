<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard — Tempahan</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
</head>
<body>
<header>
    <div class="logo">🌿</div>
    <div>
        <h1>Jabatan Hutan Sarawak</h1>
        <p>Sistem Tempahan Bilik Mesyuarat — Admin</p>
    </div>
</header>
<x-navbar />

<div class="dashboard-body">

    <div class="stats-grid" style="margin-bottom:1.5rem;">
        <div class="stat-card sc-blue">
            <h2>{{ $stats['total'] }}</h2>
            <p>Jumlah Tempahan Aktif</p>
        </div>
        <div class="stat-card sc-green">
            <h2>{{ $stats['today'] }}</h2>
            <p>Tempahan Hari Ini</p>
        </div>
        <div class="stat-card sc-orange">
            <h2>{{ $stats['pending_users'] }}</h2>
            <p>Pengguna Menunggu Kelulusan</p>
        </div>
    </div>

    <p class="section-heading">Tempahan Akan Datang</p>

    <table class="data-table">
        <tr>
            <th>Pemohon</th>
            <th>Bilik</th>
            <th>Tarikh</th>
            <th>Masa</th>
            <th>Tajuk</th>
        </tr>
        @forelse($recentBookings as $b)
        <tr>
            <td>{{ $b->user->name }}<br><span style="font-size:11px; color:#777;">{{ $b->user->bahagian ?? '-' }}</span></td>
            <td>{{ $b->bilik->nama_bilik }}<br><span style="font-size:11px; color:#777;">{{ $b->bilik->aras }}</span></td>
            <td>{{ \Carbon\Carbon::parse($b->tarikh)->format('d/m/Y') }}</td>
            <td style="font-size:12px;">{{ substr($b->masa_mula,0,5) }} – {{ substr($b->masa_tamat,0,5) }}</td>
            <td class="td-truncate">{{ $b->tajuk_mesyuarat }}</td>
        </tr>
        @empty
        <tr><td colspan="5" style="text-align:center; color:#999; padding:1.5rem;">Tiada tempahan akan datang.</td></tr>
        @endforelse
    </table>

    @if($stats['pending_users'] > 0)
        <div style="margin-top:1rem; font-size:13px;">
            <a href="/booking/admin/users?status=pending" style="color:#b35c00; font-weight:500;">
                ⚠ {{ $stats['pending_users'] }} pengguna menunggu kelulusan →
            </a>
        </div>
    @endif

</div>

<footer>
    <div><strong>Jabatan Hutan Sarawak</strong> &nbsp;|&nbsp; Wisma Sumber Alam, Petra Jaya, 93660 Kuching, Sarawak</div>
    <div>© 2025 Jabatan Hutan Sarawak. Hak Cipta Terpelihara.</div>
</footer>
</body>
</html>