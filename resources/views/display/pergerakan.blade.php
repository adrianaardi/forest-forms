<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pergerakan Pegawai</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans+Flex:opsz,wght@6..144,1..1000&family=Lora:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link rel="icon" href="{{ asset('images/logo-icon.png') }}">
</head>
<body>
<header>
    <div class="logo"></div>
    <div>
        <h1>Jabatan Hutan Sarawak</h1>
        <p>Hub Aplikasi Perkhidmatan Atas Talian</p>
    </div>
</header>

<x-navbar :breadcrumbs="[['label' => 'Paparan Pergerakan', 'url' => route('display.pergerakan')]]" />

<div class="dashboard-body">
    <div class="section-heading" style="margin-bottom: 1rem;">
        <span>Pergerakan Pegawai · Senarai Kehadiran</span>
        <a href="{{ route('display.full-display', ['bahagian_id' => $selectedBahagianId, 'search' => $search]) }}" target="_blank">Paparan Penuh</a>
    </div>

    <div class="card" style="padding: 1rem; margin-bottom: 1rem;">
        <form method="GET" action="{{ route('display.pergerakan') }}" class="toolbar">
            <input type="text" name="search" value="{{ old('search', $search) }}" placeholder="Cari nama pegawai...">
            <select name="bahagian_id">
                @foreach($bahagianList as $bahagian)
                    <option value="{{ $bahagian->id }}" {{ (string) $selectedBahagianId === (string) $bahagian->id || (!$selectedBahagianId && $loop->first) ? 'selected' : '' }}>{{ $bahagian->nama }}</option>
                @endforeach
            </select>
            <button type="submit">Tapis</button>
            <a href="{{ route('display.pergerakan') }}" class="btn-reset">Reset</a>
        </form>
    </div>

    <div class="card" style="padding: 0; overflow: hidden;">
        <div style="background:#194169; color:#fff; padding: 0.9rem 1rem; font-size: 14px; font-weight: 600;">Senarai Pegawai</div>
        <div style="overflow-x: auto;">
            <table class="data-table" style="margin-bottom: 0;">
                <thead>
                    <tr>
                        <th>Nama Pegawai</th>
                        <th>Gred</th>
                        <th>Seksyen</th>
                        <th>Status Kehadiran</th>
                        <th>Catatan (Ketidakhadiran)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pegawaiList as $pegawai)
                        <tr>
                            <td><strong>{{ $pegawai->nama }}</strong></td>
                            <td>{{ $pegawai->gred }}</td>
                            <td>{{ $pegawai->bahagian->nama ?? '-' }}</td>
                            <td>
                                <span class="badge {{ $pegawai->is_hadir ? 'badge-done' : 'badge-pending' }}">{{ $pegawai->is_hadir ? 'Hadir' : 'Tidak Hadir' }}</span>
                            </td>
                            <td>{{$pegawai->remarks ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center; color:#777;">Tiada rekod pegawai dipaparkan untuk pilihan semasa.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination-wrap" style="display:flex; justify-content:center; padding:0.75rem 0; background:#194169; color:#fff;">
            {{ $pegawaiList->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<footer>
    <div><strong>Jabatan Hutan Sarawak</strong> | Wisma Sumber Alam, Petra Jaya, 93660 Kuching, Sarawak</div>
    <div>© {{ date('Y') }} Jabatan Hutan Sarawak. Hak Cipta Terpelihara.</div>
</footer>

<style>
    @keyframes marquee { from { transform: translateX(100%); } to { transform: translateX(-100%); } }
</style>
</body>
</html>
