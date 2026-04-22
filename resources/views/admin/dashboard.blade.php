<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard — Jabatan Hutan Sarawak</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
</head>
<body>

<header>
    <div class="logo"></div>
    <div>
        <h1>Jabatan Hutan Sarawak</h1>
        <p>Forest Department Sarawak — Sistem Perkhidmatan Dalaman</p>
    </div>
</header>

<x-navbar />

<div class="dashboard-body">

    <div class="stats-grid">
        <div class="stat-card sc-blue">
            <h2>{{ $stats['total_ict'] }}</h2>
            <p>Jumlah Aduan ICT</p>
        </div>
        <div class="stat-card sc-orange">
            <h2>{{ $stats['pending_upload'] }}</h2>
            <p>Permohonan Muat Naik Menunggu</p>
        </div>
        <div class="stat-card sc-green">
            <h2>{{ $stats['selesai_ict'] }}</h2>
            <p>Aduan ICT Selesai</p>
        </div>
    </div>

    <div class="section-heading">
        <span>Aduan ICT Terkini</span>
        <a href="/admin/ict-aduan">Lihat semua →</a>
    </div>
    <table class="data-table">
        <tr>
            <th>No. Tiket</th>
            <th>Nama</th>
            <th>Kategori</th>
            <th>Tarikh</th>
            <th>Status</th>
        </tr>
        @forelse($recentIct as $item)
        <tr>
            <td>{{ $item->no_tiket }}</td>
            <td>{{ $item->nama }}</td>
            <td>{{ $item->kategori_masalah }}</td>
            <td>{{ \Carbon\Carbon::parse($item->tarikh_aduan)->format('d/m/Y') }}</td>
            <td>
                @if($item->status === 'Belum Selesai')
                    <span class="badge badge-pending">Belum Selesai</span>
                @elseif($item->status === 'Dalam Tindakan')
                    <span class="badge badge-progress">Dalam Tindakan</span>
                @else
                    <span class="badge badge-done">Selesai</span>
                @endif
            </td>
        </tr>
        @empty
        <tr><td colspan="5" style="text-align:center; color:#999; padding:1.5rem;">Tiada aduan.</td></tr>
        @endforelse
    </table>

    <div class="section-heading">
        <span>Permohonan Muat Naik Terkini</span>
        <a href="/admin/portal-upload">Lihat semua →</a>
    </div>
    <table class="data-table">
        <tr>
            <th>No. Tiket</th>
            <th>Nama</th>
            <th>Tajuk</th>
            <th>Tarikh</th>
            <th>Status</th>
        </tr>
        @forelse($recentUpload as $item)
        <tr>
            <td>{{ $item->no_tiket }}</td>
            <td>{{ $item->nama }}</td>
            <td>{{ $item->tajuk_maklumat }}</td>
            <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</td>
            <td>
                @if($item->status === 'Pending')
                    <span class="badge badge-pending">Pending</span>
                @elseif($item->status === 'Dalam Semakan')
                    <span class="badge badge-progress">Dalam Semakan</span>
                @else
                    <span class="badge badge-done">Diluluskan</span>
                @endif
            </td>
        </tr>
        @empty
        <tr><td colspan="5" style="text-align:center; color:#999; padding:1.5rem;">Tiada permohonan.</td></tr>
        @endforelse
    </table>

</div>

<footer>
    <div><strong>Jabatan Hutan Sarawak</strong> &nbsp;|&nbsp; Wisma Sumber Alam, Petra Jaya, 93660 Kuching, Sarawak</div>
    <div>© 2025 Jabatan Hutan Sarawak. Hak Cipta Terpelihara.</div>
</footer>

</body>
</html>