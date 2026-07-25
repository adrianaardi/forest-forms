<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pergerakan Pegawai</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link rel="icon" href="{{ asset('images/logo-icon.png') }}">
</head>
<body>
<x-header />

<x-navbar :breadcrumbs="[['label' => 'Paparan Pergerakan', 'url' => route('display.pergerakan')]]" />

<div class="pg-body">
    <div class="form-card">
        <div class="form-card-header">
            <h2>Pergerakan Pegawai</h2>
            <p>Senarai Kehadiran</p>
        </div>

        <div class="form-section">
            <form method="GET" action="{{ route('display.pergerakan') }}">
                <div class="field-row">
                    <div class="field">
                        <label>Cari Nama Pegawai</label>
                        <input type="text" name="search" value="{{ old('search', $search) }}" placeholder="Cari nama pegawai...">
                    </div>
                    <div class="field">
                        <label>Pilih Bahagian</label>
                        <select name="bahagian_id">
                            @foreach($bahagianList as $bahagian)
                                <option value="{{ $bahagian->id }}" {{ (string) $selectedBahagianId === (string) $bahagian->id || (!$selectedBahagianId && $loop->first) ? 'selected' : '' }}>{{ $bahagian->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-footer">
                    <a href="{{ route('display.pergerakan') }}" class="btn-back">Reset</a>
                    <button type="submit" class="btn-submit">Tapis</button>
                </div>
            </form>
            <div class="table-meta table-meta-right">
                <a href="{{ route('display.full-display', ['bahagian_id' => $selectedBahagianId, 'search' => $search]) }}" target="_blank">Paparan Penuh</a>
            </div>
        </div>

        <div class="form-section">
            <div class="section-label">Senarai Pegawai</div>
            <div class="table-wrap">
                <table class="app-table">
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
                                <td>{{ $pegawai->seksyen_unit}}</td>
                                <td>
                                    <span class="badge {{ $pegawai->is_hadir ? 'badge-done' : 'badge-pending' }}">{{ $pegawai->is_hadir ? 'Hadir' : 'Tidak Hadir' }}</span>
                                </td>
                                <td>{{$pegawai->remarks ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="table-empty">Tiada rekod pegawai dipaparkan untuk pilihan semasa.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="form-footer table-pagination">
                {{ $pegawaiList->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<x-footer />
</body>
</html>
