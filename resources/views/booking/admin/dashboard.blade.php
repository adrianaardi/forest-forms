@extends('booking.layout')

@section('title', 'Admin Dashboard — Tempahan')

@section('content')

<div class="stats-grid" style="margin-bottom:1.5rem;">
    <div class="stat-card sc-blue">
        <h2>{{ $stats['total_tempahan'] }}</h2>
        <p>Jumlah Tempahan</p>
    </div>
    <div class="stat-card sc-orange">
        <h2>{{ $stats['pending'] }}</h2>
        <p>Tempahan Pending</p>
    </div>
    <div class="stat-card sc-green">
        <h2>{{ $stats['approved'] }}</h2>
        <p>Tempahan Diluluskan</p>
    </div>
</div>

<p class="section-heading">Tempahan Terkini</p>

<table class="data-table">
    <tr>
        <th>Pemohon</th>
        <th>Bilik</th>
        <th>Tarikh</th>
        <th>Masa</th>
        <th>Status</th>
    </tr>
    @forelse($recentBookings as $b)
    <tr>
        <td>{{ $b->user->name }}<br><span style="font-size:11px; color:#777;">{{ $b->user->bahagian }}</span></td>
        <td>{{ $b->bilik->nama_bilik }}</td>
        <td>{{ \Carbon\Carbon::parse($b->tarikh)->format('d/m/Y') }}</td>
        <td style="font-size:12px;">{{ substr($b->masa_mula,0,5) }} – {{ substr($b->masa_tamat,0,5) }}</td>
        <td>
            @if($b->status === 'pending')
                <span class="badge badge-pending">Pending</span>
            @elseif($b->status === 'approved')
                <span class="badge badge-done">Diluluskan</span>
            @else
                <span class="badge" style="background:#fdf0f0; color:#a32d2d;">Ditolak</span>
            @endif
        </td>
    </tr>
    @empty
    <tr><td colspan="5" style="text-align:center; color:#999; padding:1.5rem;">Tiada tempahan.</td></tr>
    @endforelse
</table>

<div style="margin-top:1rem; font-size:13px;">
    <a href="/booking/admin/tempahan" style="color:#1a4731;">Lihat semua tempahan →</a>
</div>

@endsection
