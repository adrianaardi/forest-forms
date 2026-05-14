<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard — Tempahan</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans+Flex:opsz,wght@6..144,1..1000&family=Source+Serif+4:ital,opsz,wght@0,8..60,537;1,8..60,537&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('style.css') }}">    <style>
    <link rel="icon" href="{{ asset('images/logo-icon.png')}}">
    <style>
        .dash-wrap  { display:flex; flex-direction:column; gap:1.25rem; }
        .dash-row   { display:grid; gap:1.25rem; }
        .dash-row-2 { grid-template-columns:1fr 1fr; }
        .dash-row-3 { grid-template-columns:1fr 1fr 1fr; }
        .dash-card  { background:#fff; border:1px solid #eee; border-radius:14px; padding:1.25rem; }
        .dash-card-title {
            font-size:11px; font-weight:600; text-transform:uppercase;
            letter-spacing:0.05em; color:#aaa; margin-bottom:1rem;
            display:flex; justify-content:space-between; align-items:center;
        }
        .dash-card-title strong { font-size:22px; font-weight:600; color:#1a1a1a; text-transform:none; letter-spacing:0; }

        /* room pills */
        .room-pill {
            display:inline-flex; align-items:center; gap:5px;
            font-size:11px; padding:4px 10px; border-radius:20px;
            margin:3px; font-weight:500;
        }
        .room-pill-free    { background:#e6f7d1; color:#190a50; }
        .room-pill-partial { background:#e7e8fe; color:#0d0b85; }
        .room-pill-full    { background:#ffe0e0; color:#a32d2d; }
        .room-pill-dot { width:6px; height:6px; border-radius:50%; flex-shrink:0; }

        @media(max-width:700px) { .dash-row-2, .dash-row-3 { grid-template-columns:1fr; } }
    </style>
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

    {{-- date + alert --}}
    <div class="db-greeting" style="margin-bottom:1.5rem;">
        <div>
            <h2 class="db-greeting-title">Dashboard Tempahan 📅</h2>
            <p class="db-greeting-sub">{{ $today->translatedFormat('l, d F Y') }}</p>
        </div>
        <a href="/booking/admin/users?status=pending"
           class="db-alert {{ $stats['pending_users'] > 0 ? 'db-alert-active' : 'db-alert-clear' }}">
            @if($stats['pending_users'] > 0)
                ⚠ {{ $stats['pending_users'] }} pengguna menunggu kelulusan
            @else
                ✓ Tiada pengguna menunggu kelulusan
            @endif
        </a>
    </div>

    <div class="dash-wrap">

        {{-- Row 1: weekly chart + room availability --}}
        <div class="dash-row dash-row-2">

            {{-- weekly chart --}}
            <div class="dash-card">
                <div class="dash-card-title">
                    Tempahan Mingguan
                    <strong>{{ $weeklyData->sum('count') }}</strong>
                </div>
                <canvas id="weeklyChart" style="max-height:200px;"></canvas>
            </div>

            {{-- room availability --}}
            <div class="dash-card">
                <div class="dash-card-title">
                    Ketersediaan Bilik Hari Ini
                    <strong>{{ $allBilik->count() }} bilik</strong>
                </div>

                <div style="display:flex; gap:1rem; margin-bottom:1rem; flex-wrap:wrap;">
                    <div style="text-align:center; flex:1;">
                        <div style="font-size:24px; font-weight:600; color:#27500a;">{{ $bilikFree->count() }}</div>
                        <div style="font-size:11px; color:#aaa;">Kosong</div>
                    </div>
                    <div style="text-align:center; flex:1;">
                        <div style="font-size:24px; font-weight:600; color:#0d0b85;">{{ $bilikPartial->count() }}</div>
                        <div style="font-size:11px; color:#aaa;">Sebahagian</div>
                    </div>
                    <div style="text-align:center; flex:1;">
                        <div style="font-size:24px; font-weight:600; color:#a32d2d;">{{ $bilikFull->count() }}</div>
                        <div style="font-size:11px; color:#aaa;">Penuh</div>
                    </div>
                </div>

                <div style="border-top:1px solid #f5f5f5; padding-top:0.75rem;">
                    @foreach($bilikFree as $r)
                        <span class="room-pill room-pill-free">
                            <span class="room-pill-dot" style="background:#27500a;"></span>
                            {{ $r['nama_bilik'] }}
                        </span>
                    @endforeach
                    @foreach($bilikPartial as $r)
                        <span class="room-pill room-pill-partial">
                            <span class="room-pill-dot" style="background:#0d0b85;"></span>
                            {{ $r['nama_bilik'] }}
                        </span>
                    @endforeach
                    @foreach($bilikFull as $r)
                        <span class="room-pill room-pill-full">
                            <span class="room-pill-dot" style="background:#a32d2d;"></span>
                            {{ $r['nama_bilik'] }}
                        </span>
                    @endforeach
                </div>
            </div>

        </div>

        {{-- Row 2: recent bookings table --}}
        <div class="dash-row">
            <div class="dash-card">
                <div class="dash-card-title" style="margin-bottom:0.75rem;">
                    5 Tempahan Terkini
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
                    <tr>
                        <td colspan="5" style="text-align:center; color:#bbb; padding:1.5rem; font-size:13px;">
                            Tiada tempahan lagi.
                        </td>
                    </tr>
                    @endforelse
                </table>
            </div>
        </div>

    </div>
</div>

<footer>
    <div><strong>Jabatan Hutan Sarawak</strong> &nbsp;|&nbsp; Bangunan Baitul Makmur II, Medan Raya, Petra Jaya, 93050 Kuching, Sarawak</div>
    <div>© <?php echo date("Y"); ?> Jabatan Hutan Sarawak. Hak Cipta Terpelihara.</div>
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
new Chart(document.getElementById('weeklyChart'), {
    type: 'bar',
    data: {
        labels:   @json($weeklyData->pluck('label')),
        datasets: [{
            label:           'Tempahan',
            data:            @json($weeklyData->pluck('count')),
            backgroundColor: @json($weeklyData->map(fn($d) => $d['count'] > 0 ? '#2C3E50' : '#eaf3de')),
            borderRadius:    6,
            borderSkipped:   false,
        }]
    },
    options: {
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: { label: ctx => ` ${ctx.parsed.y} tempahan` }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1 },
                grid: { color: '#f5f5f5' }
            },
            x: { grid: { display: false } }
        }
    }
});
</script>

</body>
</html>