<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pergerakan Pegawai</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans+Flex:opsz,wght@6..144,1..1000&family=Lora:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; background: linear-gradient(135deg, #0f172a 0%, #1e293b 45%, #111827 100%); color: #eff6ff; font-family: 'Google Sans Flex', Arial, sans-serif; overflow: hidden; }
        .wrap { display: grid; grid-template-columns: 320px 1fr; height: 100vh; }
        .left { border-right: 1px solid rgba(148,163,184,0.18); background: rgba(15,23,42,0.92); padding: 18px; display: flex; flex-direction: column; gap: 14px; }
        .panel { background: rgba(30,41,59,0.92); border: 1px solid rgba(148,163,184,0.18); border-radius: 16px; padding: 12px; box-shadow: 0 12px 26px rgba(15,23,42,0.35); }
        .title { font-size: 18px; font-weight: 700; margin-bottom: 6px; }
        .muted { color: #bfdbfe; font-size: 12px; }
        .item { margin-top: 8px; padding: 8px 10px; border-radius: 12px; background: rgba(15,23,42,0.75); border: 1px solid rgba(148,163,184,0.16); }
        .item strong { display: block; font-size: 13px; color: #fff; }
        .item span { display: block; color: #bfdbfe; font-size: 12px; margin-top: 2px; }
        .main { padding: 18px; display: flex; flex-direction: column; gap: 14px; }
        .topbar { display: flex; justify-content: space-between; align-items: center; gap: 12px; }
        .chip { background: rgba(56, 189, 248, 0.12); color: #e0f2fe; border: 1px solid rgba(56,189,248,0.25); border-radius: 999px; padding: 6px 10px; font-size: 12px; }
        .grid { display: flex; flex-direction: column; gap: 14px; flex: 1; }
        .table-shell { background: rgba(15,23,42,0.95); border: 1px solid rgba(148,163,184,0.18); border-radius: 18px; overflow: hidden; box-shadow: 0 12px 26px rgba(15,23,42,0.35); }
        .table-shell table { width: 100%; border-collapse: collapse; font-size: 13px; }
        .table-shell th, .table-shell td { padding: 10px 12px; text-align: left; border-bottom: 1px solid rgba(148,163,184,0.12); }
        .table-shell th { background: rgba(30,41,59,0.98); color: #cbd5e1; text-transform: uppercase; letter-spacing: 0.06em; font-size: 10px; }
        .table-shell td { color: #eff6ff; }
        th { color: #cbd5e1; text-transform: uppercase; letter-spacing: 0.06em; font-size: 10px; }
        td { color: #eff6ff; }
        .badge { display: inline-flex; padding: 4px 8px; border-radius: 999px; font-size: 11px; font-weight: 700; }
        .badge.hadir { background: rgba(34,197,94,0.16); color: #bbf7d0; border: 1px solid rgba(74,222,128,0.25); }
        .badge.tidak { background: rgba(248,113,113,0.18); color: #fecaca; border: 1px solid rgba(248,113,113,0.25); }
        .ticker { position: fixed; left: 0; right: 0; bottom: 0; background: linear-gradient(90deg, #111827 0%, #0f172a 100%); border-top: 2px solid #38bdf8; padding: 10px 12px; overflow: hidden; color: #e0f2fe; font-size: 13px; }
        .ticker span { display: inline-block; white-space: nowrap; animation: roll 24s linear infinite; }
        .ticker span:hover { animation-play-state: paused; }
        @keyframes roll { from { transform: translateX(110%); } to { transform: translateX(-110%); } }
        @media (max-width: 1100px) { .wrap { grid-template-columns: 1fr; } .left { border-right: none; border-bottom: 1px solid rgba(148,163,184,0.18); } .grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
<div class="wrap">
    <aside class="left">
        <div class="panel">
            <div class="title">📅 Aktiviti Harian</div>
            <div class="muted">Senarai aktiviti yang sedang berjalan.</div>
            @forelse($aktivitiList as $aktiviti)
                <div class="item">
                    <strong>{{ $aktiviti->nama_aktiviti }}</strong>
                    <span>{{ $aktiviti->biodata ?? 'Seksyen/Unit' }} · {{ \Carbon\Carbon::parse($aktiviti->tarikh)->format('d/m/Y') }}</span>
                </div>
            @empty
                <div class="item">
                    <strong>Tiada aktiviti</strong>
                    <span>Tiada aktiviti didaftarkan untuk paparan penuh ini.</span>
                </div>
            @endforelse
        </div>
    </aside>

    <main class="main">
        <div class="topbar">
            <div>
                <div style="font-size: 28px; font-weight: 800; line-height: 1.1;">Pergerakan Pegawai</div>
                <div class="muted" style="margin-top: 4px;">Automatik · 8 pegawai setiap skrin · status hadir/tidak hadir</div>
            </div>
            <div class="chip">{{ now()->translatedFormat('d F Y, H:i') }}</div>
        </div>

        @php $sectionLabel = $pegawaiList->first()?->bahagian->nama ?? 'Semua Seksyen'; @endphp
        <div class="panel" style="padding: 12px 14px; display:flex; align-items:center; justify-content:space-between; gap:12px;">
            <div>
                <div style="font-size: 12px; text-transform: uppercase; letter-spacing: .08em; color: #7dd3fc;">Bahagian Dipaparkan</div>
                <div style="font-size: 22px; font-weight: 800; color: #fff;">{{ $sectionLabel }}</div>
            </div>
            <div class="chip">{{ $pegawaiList->count() }} pegawai</div>
        </div>

        <div class="table-shell">
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Gred</th>
                        <th>Seksyen</th>
                        <th>Kehadiran</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pegawaiList as $pegawai)
                        <tr>
                            <td>{{ $pegawai->nama }}</td>
                            <td>{{ $pegawai->gred }}</td>
                            <td>{{ $pegawai->bahagian->nama ?? '-' }}</td>
                            <td><span class="badge {{ $pegawai->is_hadir ? 'hadir' : 'tidak' }}">{{ $pegawai->is_hadir ? 'Hadir' : 'Tidak Hadir' }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>
</div>

<div class="ticker"><span>{{ $newsTicker ?: 'Tiada pengumuman pada masa ini.' }}</span></div>

<script>
    (function () {
        const rows = Array.from(document.querySelectorAll('.table-shell tbody tr'));
        const rowsPerPage = 8;
        let currentPage = 0;
        
        if (!rows.length) return;

        function showPage(pageIndex) {
            const start = pageIndex * rowsPerPage;
            const end = start + rowsPerPage;

            rows.forEach((row, idx) => {
                if (idx >= start && idx < end) {
                    row.style.display = ''; // Shows row
                } else {
                    row.style.display = 'none'; // Hides row
                }
            });
        }

        // Show the first 8 rows immediately on load
        showPage(currentPage);

        // Calculate total pages needed
        const totalPages = Math.ceil(rows.length / rowsPerPage);

        // Cycle pages every 5 seconds if there's more than one page
        if (totalPages > 1) {
            setInterval(function () {
                currentPage = (currentPage + 1) % totalPages;
                showPage(currentPage);
            }, 5000);
        }
    })();
</script>
</body>
</html>