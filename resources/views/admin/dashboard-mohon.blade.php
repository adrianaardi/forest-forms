<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Portal Muat Naik</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link rel="icon" href="{{ asset('images/logo-icon.png')}}">
    <style>
        .dash-wrap {
            display: flex; flex-direction: column; gap: 1.25rem;
        }
        .dash-row {
            display: grid; gap: 1.25rem;
        }
        .dash-row-2 { grid-template-columns: 1fr 1fr; }
        .dash-row-1 { grid-template-columns: 1fr; }

        .dash-card {
            background:#f7f4f4; border: 1px solid #eee;
            border-radius: 14px; padding: 1.25rem;
        }
        .dash-card-title {
            font-size: 12px; font-weight: 600;
            text-transform: uppercase; letter-spacing: 0.05em;
            color: #999; margin-bottom: 1rem;
            display: flex; justify-content: space-between; align-items: center;
        }
        .dash-card-title span {
            font-size: 18px; font-weight: 600;
            color: #1a1a1a; text-transform: none; letter-spacing: 0;
        }
        .dash-total-badge {
            background: #eaf3de; color: #27500a;
            font-size: 11px; padding: 3px 10px;
            border-radius: 20px; font-weight: 500;
        }

        @media (max-width: 700px) {
            .dash-row-2 { grid-template-columns: 1fr; }
        }
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

    <div class="db-greeting" style="margin-bottom:1.5rem;">
        <div>
            <h2 class="db-greeting-title">Dashboard Portal Muat Naik 📊</h2>
            <p class="db-greeting-sub">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
        </div>
        <span class="dash-total-badge">{{ $total }} jumlah permohonan</span>
    </div>

    <div class="dash-wrap">

        {{-- Row 1: monthly + pengemaskinian --}}
        <div class="dash-row dash-row-2">

            <div class="dash-card">
                <div class="dash-card-title">
                    Permohonan Mengikut Bulan
                    <span>{{ $months->sum('count') }}</span>
                </div>
                <canvas id="monthChart" style="max-height:220px;"></canvas>
            </div>

            <div class="dash-card">
                <div class="dash-card-title">
                    Jenis Pengemaskinian
                    <span>{{ array_sum($pengemaskinian) }}</span>
                </div>
                <canvas id="pengemaskinianChart" style="max-height:220px;"></canvas>
            </div>

        </div>

        {{-- Row 2: by bahagian --}}
        <div class="dash-row dash-row-1">
            <div class="dash-card">
                <div class="dash-card-title">
                    Permohonan Mengikut Bahagian
                    <span>{{ $byBahagian->sum('count') }}</span>
                </div>
                <canvas id="bahagianChart" style="max-height:260px;"></canvas>
            </div>
        </div>

        {{-- Row 3: status + jenis kandungan --}}
        <div class="dash-row dash-row-2">

            <div class="dash-card">
                <div class="dash-card-title">Status Permohonan</div>
                <div style="display:flex; align-items:center; gap:1.5rem; flex-wrap:wrap;">
                    <canvas id="statusChart" style="max-height:180px; max-width:180px;"></canvas>
                    <div id="statusLegend" class="db-chart-legend" style="flex-direction:column; gap:8px;"></div>
                </div>
            </div>

            <div class="dash-card">
                <div class="dash-card-title">Jenis Kandungan</div>
                <div style="display:flex; align-items:center; gap:1.5rem; flex-wrap:wrap;">
                    <canvas id="kandunganChart" style="max-height:180px; max-width:180px;"></canvas>
                    <div id="kandunganLegend" class="db-chart-legend" style="flex-direction:column; gap:8px;"></div>
                </div>
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
const green  = '#2C3E50';
const teal   = '#2d7a55';
const sage   = '#7ec0c9';
const blue   = '#194169';
const sky    = '#4a90d9';
const amber  = '#f0a541';
const orange = '#c47a1e';
const rose   = '#e05c5c';
const slate  = '#8fa3b1';
const muted  = '#c5d5de';

const barDefaults = {
    borderRadius: 6,
    borderSkipped: false,
};

// ── monthly column chart ──
new Chart(document.getElementById('monthChart'), {
    type: 'bar',
    data: {
        labels: @json($months->pluck('label')),
        datasets: [{
            label: 'Permohonan',
            data:  @json($months->pluck('count')),
            backgroundColor: [sage, teal, green],
            ...barDefaults,
        }]
    },
    options: {
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f0f0f0' } },
            x: { grid: { display: false } }
        }
    }
});

// ── pengemaskinian column chart ──
new Chart(document.getElementById('pengemaskinianChart'), {
    type: 'bar',
    data: {
        labels: @json(array_keys($pengemaskinian)),
        datasets: [{
            label: 'Permohonan',
            data:  @json(array_values($pengemaskinian)),
            backgroundColor: [blue, sky, muted],
            ...barDefaults,
        }]
    },
    options: {
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f0f0f0' } },
            x: { grid: { display: false } }
        }
    }
});

// ── bahagian bar chart (horizontal) ──
new Chart(document.getElementById('bahagianChart'), {
    type: 'bar',
    data: {
        labels: @json($byBahagian->pluck('bahagian_nama')),
        datasets: [{
            label: 'Permohonan',
            data:  @json($byBahagian->pluck('count')),
            backgroundColor: green,
            borderRadius: 4,
            borderSkipped: false,
        }]
    },
    options: {
        indexAxis: 'y',
        plugins: { legend: { display: false } },
        scales: {
            x: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f0f0f0' } },
            y: { grid: { display: false }, ticks: { font: { size: 11 } } }
        }
    }
});

// ── status pie chart ──
function makePie(canvasId, legendId, labels, counts, colors) {
    new Chart(document.getElementById(canvasId), {
        type: 'doughnut',
        data: {
            labels,
            datasets: [{
                data: counts,
                backgroundColor: colors,
                borderWidth: 2,
                borderColor: '#fff',
                hoverOffset: 6,
            }]
        },
        options: {
            cutout: '58%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: { label: ctx => ` ${ctx.label}: ${ctx.parsed}` }
                }
            }
        }
    });

    const legend = document.getElementById(legendId);
    labels.forEach((label, i) => {
        const item = document.createElement('div');
        item.className = 'db-chart-legend-item';
        item.innerHTML = `<span class="db-chart-legend-dot" style="background:${colors[i]};"></span><span>${label} (${counts[i]})</span>`;
        legend.appendChild(item);
    });
}

makePie(
    'statusChart', 'statusLegend',
    @json(array_keys($status)),
    @json(array_values($status)),
    [amber, sky, sage]
);

makePie(
    'kandunganChart', 'kandunganLegend',
    @json(array_keys($kandungan)),
    @json(array_values($kandungan)),
    [green, teal, blue, amber, rose, slate]
);
</script>

</body>
</html>