<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pergerakan Pegawai</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans+Flex:opsz,wght@6..144,1..1000&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            background: #080f1e;
            color: #e8f0fe;
            font-family: 'Google Sans Flex', 'Segoe UI', Arial, sans-serif;
            overflow: hidden;
        }

        .wrap {
            display: grid;
            grid-template-rows: auto 1fr auto;
            height: 100vh;
        }

        /* ── TOP BAR ── */
        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 22px 48px;
            background: #0c1526;
            border-bottom: 1.5px solid rgba(99,160,255,0.18);
            flex-shrink: 0;
        }

        .topbar-left .page-title {
            font-size: 42px;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: #fff;
            line-height: 1;
        }

        .topbar-left .page-sub {
            display: flex;
            align-items: center;
            gap: 18px;
            margin-top: 10px;
        }

        .stat-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 16px;
            border-radius: 999px;
            font-size: 15px;
            font-weight: 600;
        }

        .stat-pill::before {
            content: '';
            width: 9px;
            height: 9px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .stat-pill.hadir {
            background: rgba(34,197,94,0.12);
            color: #86efac;
            border: 1.5px solid rgba(74,222,128,0.25);
        }

        .stat-pill.hadir::before { background: #4ade80; }

        .stat-pill.tidak {
            background: rgba(248,113,113,0.12);
            color: #fca5a5;
            border: 1.5px solid rgba(248,113,113,0.25);
        }

        .stat-pill.tidak::before { background: #f87171; }

        .chip {
            background: rgba(56,139,255,0.12);
            border: 1.5px solid rgba(56,139,255,0.28);
            border-radius: 999px;
            padding: 10px 22px;
            font-size: 17px;
            font-weight: 600;
            color: #a8c8ff;
        }

        /* ── BODY GRID ── */
        .body {
            display: grid;
            grid-template-columns: 340px 1fr;
            overflow: hidden;
        }

        /* ── SIDEBAR ── */
        .sidebar {
            background: #0c1526;
            border-right: 1.5px solid rgba(99,160,255,0.15);
            padding: 28px 22px;
            display: flex;
            flex-direction: column;
            gap: 14px;
            overflow: hidden;
        }

        .sidebar-heading {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: #4d7ab5;
            font-weight: 600;
            margin-bottom: 2px;
        }

        .sidebar-title {
            font-size: 20px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 6px;
        }

        .act-item {
            background: rgba(12,21,38,0.85);
            border: 1px solid rgba(99,160,255,0.15);
            border-radius: 14px;
            padding: 14px 16px;
        }

        .act-item strong {
            display: block;
            font-size: 15px;
            font-weight: 600;
            color: #e8f0fe;
        }

        .act-item span {
            display: block;
            font-size: 13px;
            color: #6d9fd4;
            margin-top: 4px;
        }

        /* ── MAIN ── */
        .main {
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .section-strip {
            background: #0a1120;
            border-bottom: 1px solid rgba(99,160,255,0.14);
            padding: 18px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
        }

        .section-strip .section-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: #4d7ab5;
            font-weight: 600;
        }

        .section-strip .section-name {
            font-size: 26px;
            font-weight: 800;
            color: #fff;
            margin-top: 2px;
        }

        .count-chip {
            background: rgba(56,139,255,0.12);
            border: 1.5px solid rgba(56,139,255,0.25);
            border-radius: 999px;
            padding: 8px 20px;
            font-size: 15px;
            font-weight: 600;
            color: #a8c8ff;
        }

        /* ── TABLE ── */
        .table-wrap {
            flex: 1;
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead th {
            background: #0a1120;
            color: #4d7ab5;
            text-transform: uppercase;
            letter-spacing: 0.10em;
            font-size: 12px;
            font-weight: 600;
            padding: 16px 32px;
            border-bottom: 1.5px solid rgba(99,160,255,0.18);
            text-align: left;
        }

        tbody tr {
            border-bottom: 1px solid rgba(99,160,255,0.09);
        }

        tbody td {
            padding: 20px 32px;
            vertical-align: middle;
        }

        td.col-nama  { font-size: 19px; font-weight: 600; color: #e8f0fe; }
        td.col-gred  { font-size: 16px; color: #7da8d8; }
        td.col-seksyen { font-size: 16px; color: #7da8d8; }
        td.col-catatan { font-size: 15px; color: #6d9fd4; }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 8px 16px;
            border-radius: 999px;
            font-size: 14px;
            font-weight: 700;
        }

        .badge::before {
            content: '';
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            flex-shrink: 0;
        }

        .badge.hadir {
            background: rgba(34,197,94,0.13);
            color: #86efac;
            border: 1.5px solid rgba(74,222,128,0.28);
        }

        .badge.hadir::before { background: #4ade80; }

        .badge.tidak {
            background: rgba(248,113,113,0.13);
            color: #fca5a5;
            border: 1.5px solid rgba(248,113,113,0.28);
        }

        .badge.tidak::before { background: #f87171; }

        /* ── TICKER ── */
        .ticker-bar {
            height: 68px;
            background: #050c18;
            border-top: 2px solid #1c3a8a;
            display: flex;
            align-items: center;
            overflow: hidden;
            flex-shrink: 0;
        }

        .ticker-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 24px;
            border-right: 1.5px solid rgba(99,160,255,0.18);
            height: 100%;
            flex-shrink: 0;
        }

        .ticker-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: rgba(56,139,255,0.14);
            border: 1.5px solid rgba(56,139,255,0.28);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .ticker-icon img {
            width: 28px;
            height: 28px;
            object-fit: contain;
        }

        /* Placeholder tree icon shown until real logo is dropped in */
        .ticker-icon-placeholder {
            width: 26px;
            height: 26px;
            fill: #7da8d8;
        }

        .ticker-logo-text .org-name {
            font-size: 13px;
            font-weight: 700;
            color: #c0d8ff;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            line-height: 1;
        }

        .ticker-logo-text .org-dept {
            font-size: 11px;
            color: #4d7ab5;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            margin-top: 3px;
        }

        .ticker-sep {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: #1c3a8a;
            margin: 0 18px;
            flex-shrink: 0;
        }

        .ticker-scroll {
            flex: 1;
            overflow: hidden;
            height: 100%;
            display: flex;
            align-items: center;
        }

        .ticker-text {
            white-space: nowrap;
            font-size: 16px;
            color: #7da8d8;
            display: inline-block;
            animation: ticker-roll 30s linear infinite;
            padding-left: 60px;
        }

        .ticker-text:hover { animation-play-state: paused; }

        @keyframes ticker-roll {
            from { transform: translateX(100vw); }
            to   { transform: translateX(-100%); }
        }

        /* ── FULLSCREEN GATE ── */
        #fs-gate {
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: rgba(8,15,30,0.97);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            flex-direction: column;
            gap: 14px;
        }

        #fs-gate .gate-title {
            font-size: 34px;
            font-weight: 800;
            color: #e8f0fe;
        }

        #fs-gate .gate-sub {
            font-size: 15px;
            color: #6d9fd4;
        }

        @media (max-width: 1100px) {
            .body { grid-template-columns: 1fr; }
            .sidebar { display: none; }
        }
    </style>
</head>
<body>

<div class="wrap">

    {{-- ── TOP BAR ── --}}
    <header class="topbar">
        <div class="topbar-left">
            <div class="page-title">Pergerakan Pegawai</div>
            @php
                $jumlahHadir = $pegawaiList->where('is_hadir', true)->count();
                $jumlahTidak = $pegawaiList->where('is_hadir', false)->count();
            @endphp
            <div class="page-sub">
                <span class="stat-pill hadir">{{ $jumlahHadir }} pegawai hadir</span>
                <span class="stat-pill tidak">{{ $jumlahTidak }} pegawai tidak hadir</span>
            </div>
        </div>
        <div class="chip">{{ now()->translatedFormat('d F Y, H:i') }}</div>
    </header>

    {{-- ── BODY ── --}}
    <div class="body">

        {{-- Sidebar --}}
        <aside class="sidebar">
            <div>
                <div class="sidebar-heading">Aktiviti Harian</div>
                <div class="sidebar-title">Sedang Berjalan</div>
            </div>

            @forelse($aktivitiList as $aktiviti)
                <div class="act-item">
                    <strong>{{ $aktiviti->nama_aktiviti }}</strong>
                    <span>{{ $aktiviti->seksyen_unit }} &middot; {{ \Carbon\Carbon::parse($aktiviti->tarikh)->format('d/m/Y') }}</span>
                </div>
            @empty
                <div class="act-item">
                    <strong>Tiada aktiviti</strong>
                    <span>Tiada aktiviti didaftarkan untuk paparan ini.</span>
                </div>
            @endforelse
        </aside>

        {{-- Main content --}}
        <main class="main">

            @php $sectionLabel = $pegawaiList->first()?->bahagian->nama ?? 'Semua Seksyen'; @endphp

            <div class="section-strip">
                <div>
                    <div class="section-label">Bahagian Dipaparkan</div>
                    <div class="section-name">{{ $sectionLabel }}</div>
                </div>
                <div class="count-chip">{{ $pegawaiList->count() }} pegawai</div>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Gred</th>
                            <th>Seksyen</th>
                            <th>Kehadiran</th>
                            <th>Catatan (Ketidakhadiran)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pegawaiList as $pegawai)
                            <tr>
                                <td class="col-nama">{{ $pegawai->nama }}</td>
                                <td class="col-gred">{{ $pegawai->gred }}</td>
                                <td class="col-seksyen">{{ $pegawai->seksyen_unit }}</td>
                                <td>
                                    <span class="badge {{ $pegawai->is_hadir ? 'hadir' : 'tidak' }}">
                                        {{ $pegawai->is_hadir ? 'Hadir' : 'Tidak Hadir' }}
                                    </span>
                                </td>
                                <td class="col-catatan">{{ $pegawai->remarks ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </main>
    </div>

    {{-- ── TICKER BAR ── --}}
    <footer class="ticker-bar">

        <div class="ticker-logo">
            <div class="ticker-icon">
                    <img src="{{ asset('images/logo-icon.png') }}" alt="Logo Jabatan Hutan Sarawak">
            </div>
            <div class="ticker-logo-text">
                <div class="org-name">Jabatan Hutan</div>
                <div class="org-dept">Sarawak</div>
            </div>
        </div>

        <div class="ticker-sep"></div>

        <div class="ticker-scroll">
            <div class="ticker-text">{{ $newsTicker ?: 'Tiada pengumuman pada masa ini.' }}</div>
        </div>

    </footer>

</div>


{{-- ── FULLSCREEN GATE ── --}}
<div id="fs-gate">
    <div class="gate-title">Pergerakan Pegawai</div>
    <div class="gate-sub">Klik untuk mula paparan skrin penuh</div>
</div>


<script>
    // Pagination — show 8 rows at a time
    (function () {
        const rows       = Array.from(document.querySelectorAll('tbody tr'));
        const perPage    = 10;
        let   page       = 0;
        const totalPages = Math.ceil(rows.length / perPage);

        function show(p) {
            const start = p * perPage;
            rows.forEach((r, i) => r.style.display = (i >= start && i < start + perPage) ? '' : 'none');
        }

        show(page);

        if (totalPages > 1) {
            setInterval(function () {
                page = (page + 1) % totalPages;
                show(page);
            }, 5000);
        }
    }());

    // Fullscreen gate
    document.getElementById('fs-gate').addEventListener('click', function () {
        const el  = document.documentElement;
        const req = el.requestFullscreen || el.webkitRequestFullscreen || el.mozRequestFullScreen;
        if (req) req.call(el);
        this.remove();
    });
</script>

</body>
</html>
