<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Aduan ICT</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans+Flex:opsz,wght@6..144,1..1000&family=Source+Serif+4:ital,opsz,wght@0,8..60,537;1,8..60,537&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('style.css') }}">    <link rel="icon" href="{{ asset('images/logo-icon.png')}}">
    <style>
        /* 🌿 EMERALD LUXURY PALETTE */
        :root {
            --emerald: #284139;
            --wasabi: #808976;
            --khaki: #F8D794;
            --earth: #B86830;
            --noir: #111A19;
        }

        .dash-wrap {
            display: flex; flex-direction: column; gap: 1.25rem;
        }
        .dash-row {
            display: grid; gap: 1.25rem;
        }
        .dash-row-2 { grid-template-columns: 1fr 1fr; }
        .dash-row-1 { grid-template-columns: 1fr; }

        .dash-card {
            background:#f7f4f4; border: 1px solid rgba(40,65,57,0.1);
            border-radius: 14px; padding: 1.25rem;
            box-shadow: 0 4px 15px rgba(17,26,25,0.05);
        }
        .dash-card-title {
            font-size: 12px; font-weight: 600;
            text-transform: uppercase; letter-spacing: 0.05em;
            color: var(--wasabi); margin-bottom: 1rem;
            display: flex; justify-content: space-between; align-items: center;
        }
        .dash-card-title span {
            font-size: 18px; font-weight: 600;
            color: var(--noir); text-transform: none; letter-spacing: 0;
        }
        .dash-total-badge {
            background: var(--khaki); color: var(--emerald);
            font-size: 11px; padding: 4px 12px;
            border-radius: 20px; font-weight: 700;
        }

        /* Legend Styling */
        .db-chart-legend { display: flex; flex-direction: column; gap: 8px; }
        .db-chart-legend-item { display: flex; align-items: center; gap: 8px; font-size: 12px; color: var(--noir); }
        .db-chart-legend-dot { width: 10px; height: 10px; border-radius: 50%; display: inline-block; }

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

<div class="dashboard-body" style="padding: 1.5rem;">

    <div class="db-greeting" style="margin-bottom:1.5rem; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2 class="db-greeting-title" style="margin:0; color: var(--emerald);">Dashboard Aduan ICT 📊</h2>
            <p class="db-greeting-sub" style="margin:5px 0 0; color: var(--wasabi);">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
        </div>
        <span class="dash-total-badge">{{ $total }} JUMLAH ADUAN</span>
    </div>

    <div class="dash-wrap">

        {{-- Row 1: monthly + kategori --}}
        <div class="dash-row dash-row-2">
            <div class="dash-card">
                <div class="dash-card-title">
                    Aduan Mengikut Bulan
                    <span>{{ $months->sum('count') }}</span>
                </div>
                <canvas id="monthChart" style="max-height:220px;"></canvas>
            </div>

            <div class="dash-card">
                <div class="dash-card-title">
                    Kategori Masalah
                    <span>{{ array_sum($kategori) }}</span>
                </div>
                <canvas id="kategoriChart" style="max-height:220px;"></canvas>
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

        {{-- Row 3: status + wilayah --}}
        <div class="dash-row dash-row-2">
            <div class="dash-card">
                <div class="dash-card-title">Status Aduan</div>
                <div style="display:flex; align-items:center; gap:1.5rem; flex-wrap:wrap;">
                    <canvas id="statusChart" style="max-height:180px; max-width:180px;"></canvas>
                    <div id="statusLegend" class="db-chart-legend"></div>
                </div>
            </div>

            <div class="dash-card">
                <div class="dash-card-title">Aduan Mengikut Wilayah</div>
                <div style="display:flex; align-items:center; gap:1.5rem; flex-wrap:wrap;">
                    <canvas id="wilayahChart" style="max-height:180px; max-width:180px;"></canvas>
                    <div id="wilayahLegend" class="db-chart-legend"></div>
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
/* Colors from image_566d1e.jpg */
const emerald = '#284139';
const wasabi  = '#808976';
const khaki   = '#F8D794';
const earth   = '#B86830';
const noir    = '#111A19';

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
            label: 'Aduan', /* Fixes "undefined" tooltip */
            data:  @json($months->pluck('count')),
            backgroundColor: emerald,
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

// ── kategori chart ──
new Chart(document.getElementById('kategoriChart'), {
    type: 'bar',
    data: {
        labels: @json(array_keys($kategori)),
        datasets: [{
            label: 'Bilangan', /* Fixes "undefined" tooltip */
            data: @json(array_values($kategori)),
            backgroundColor: [wasabi, earth, khaki, emerald],
            ...barDefaults,
        }]
    },
    options: {
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1 } },
            x: { grid: { display: false } }
        }
    }
});

// ── bahagian bar chart (horizontal) ──
new Chart(document.getElementById('bahagianChart'), {
    type: 'bar',
    data: {
        labels: @json($byBahagian->pluck('bahagian')),
        datasets: [{
            label: 'Aduan', /* Fixes "undefined" tooltip */
            data:  @json($byBahagian->pluck('count')),
            backgroundColor: wasabi,
            ...barDefaults,
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

// ── pie chart helper ──
function makePie(canvasId, legendId, labels, counts, colors) {
    new Chart(document.getElementById(canvasId), {
        type: 'doughnut',
        data: {
            labels,
            datasets: [{
                label: 'Jumlah', /* Fixes "undefined" tooltip */
                data: counts,
                backgroundColor: colors,
                borderWidth: 2,
                borderColor: '#fff',
            }]
        },
        options: {
            cutout: '65%',
            plugins: {
                legend: { display: false },
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
    [emerald, khaki, wasabi]
);

makePie(
    'wilayahChart', 'wilayahLegend',
    @json(array_keys($wilayah)),
    @json(array_values($wilayah)),
    [emerald, wasabi, earth, khaki]
);
</script>
</body>
</html>