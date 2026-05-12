<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard — Tempahan</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link rel="icon" href="{{ asset('images/logo-icon.png')}}">
</head>
<body>
<header>
    <div class="logo"></div>
    <div>
        <h1>Jabatan Hutan Sarawak</h1>
        <p>Hub Aplikasi Perkhidmatan Atas Talian</p>
    </div>
</header>
<x-navbar />

<div class="dashboard-body">

    {{-- greeting --}}
    <div class="db-greeting">
        <div>
            <h2 class="db-greeting-title">Selamat Datang, Admin 👋</h2>
            <p class="db-greeting-sub">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
        </div>
        <a href="/booking/admin/users?status=pending" class="db-alert {{ $stats['pending_users'] > 0 ? 'db-alert-active' : 'db-alert-clear' }}">
            @if($stats['pending_users'] > 0)
                ⚠ {{ $stats['pending_users'] }} pengguna menunggu kelulusan
            @else
                ✓ Tiada pengguna menunggu kelulusan
            @endif
        </a>
    </div>

    {{-- stats --}}
    <div class="db-stats">
        <div class="db-stat db-stat-green">
            <div class="db-stat-icon">📅</div>
            <div>
                <div class="db-stat-num">{{ $stats['total'] }}</div>
                <div class="db-stat-label">Jumlah Tempahan Aktif</div>
            </div>
        </div>
        <div class="db-stat db-stat-blue">
            <div class="db-stat-icon">🕐</div>
            <div>
                <div class="db-stat-num">{{ $stats['today'] }}</div>
                <div class="db-stat-label">Tempahan Hari Ini</div>
            </div>
        </div>
        <div class="db-stat db-stat-teal">
            <div class="db-stat-icon">📆</div>
            <div>
                <div class="db-stat-num">{{ $stats['this_week'] }}</div>
                <div class="db-stat-label">Tempahan Minggu Ini</div>
            </div>
        </div>
        <div class="db-stat db-stat-slate">
            <div class="db-stat-icon">👤</div>
            <div>
                <div class="db-stat-num">{{ $stats['total_users'] }}</div>
                <div class="db-stat-label">Pengguna Aktif</div>
            </div>
        </div>
    </div>

    {{-- today's bookings --}}
    <div class="db-section">
        <div class="db-section-header">
            <h3 class="db-section-title">Tempahan Hari Ini</h3>
            <span class="db-badge">{{ $todayBookings->count() }}</span>
        </div>
        @if($todayBookings->isEmpty())
            <div class="db-empty">Tiada tempahan hari ini.</div>
        @else
            <div class="db-today-list">
                @foreach($todayBookings as $b)
                <div class="db-today-item">
                    <div class="db-today-time">
                        {{ substr($b->masa_mula,0,5) }}<br>
                        <span>{{ substr($b->masa_tamat,0,5) }}</span>
                    </div>
                    <div class="db-today-bar"></div>
                    <div class="db-today-info">
                        <div class="db-today-title">{{ $b->tajuk_mesyuarat }}</div>
                        <div class="db-today-meta">
                            {{ $b->bilik->nama_bilik }} · {{ $b->user->name }}
                            @if($b->user->bahagian) · {{ $b->user->bahagian }} @endif
                        </div>
                        @if($b->remarks)
                            <div class="db-today-remarks">{{ $b->remarks }}</div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- upcoming bookings --}}
    <div class="db-section">
        <div class="db-section-header">
            <h3 class="db-section-title">Tempahan Akan Datang</h3>
            <a href="/booking/calendar" class="db-link">Lihat kalendar →</a>
        </div>
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
                <td>
                    {{ $b->user->name }}
                    <br><span style="font-size:11px; color:#999;">{{ $b->user->bahagian ?? '-' }}</span>
                </td>
                <td>
                    {{ $b->bilik->nama_bilik }}
                    <br><span style="font-size:11px; color:#999;">{{ $b->bilik->aras }}</span>
                </td>
                <td>{{ \Carbon\Carbon::parse($b->tarikh)->translatedFormat('d M Y') }}</td>
                <td style="font-size:12px; white-space:nowrap;">{{ substr($b->masa_mula,0,5) }} – {{ substr($b->masa_tamat,0,5) }}</td>
                <td class="td-truncate">{{ $b->tajuk_mesyuarat }}</td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align:center; color:#bbb; padding:1.5rem; font-size:13px;">Tiada tempahan akan datang.</td></tr>
            @endforelse
        </table>
    </div>

</div>

<footer>
    <div><strong>Jabatan Hutan Sarawak</strong> &nbsp;|&nbsp; Bangunan Baitul Makmur II, Medan Raya, Petra Jaya, 93050 Kuching, Sarawak</div>
    <div>© <?php echo date("Y"); ?> Jabatan Hutan Sarawak. Hak Cipta Terpelihara.</div>
</footer>
</body>
</html>