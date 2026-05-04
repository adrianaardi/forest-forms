@extends('booking.layout')

@section('title', 'Tempahan Saya')

@section('content')

@if(session('success'))
    <div style="background:#eaf3de; border:1px solid #c0dd97; color:#3b6d11; padding:0.75rem 1rem; border-radius:8px; margin-bottom:1rem; font-size:13px;">
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div style="background:#fdf0f0; border:1px solid #f5c1c1; color:#a32d2d; padding:0.75rem 1rem; border-radius:8px; margin-bottom:1rem; font-size:13px;">
        {{ session('error') }}
    </div>
@endif

<p class="section-heading">Tempahan Saya</p>

<table class="data-table">
    <tr>
        <th>Bilik</th>
        <th>Tarikh</th>
        <th>Masa</th>
        <th>Tujuan</th>
        <th>Status</th>
        <th>Tindakan</th>
    </tr>
    @forelse($bookings as $b)
    <tr>
        <td>{{ $b->bilik->nama_bilik }}<br><span style="font-size:11px; color:#777;">{{ $b->bilik->aras }}</span></td>
        <td>{{ \Carbon\Carbon::parse($b->tarikh)->format('d/m/Y') }}</td>
        <td style="font-size:12px;">{{ substr($b->masa_mula,0,5) }} – {{ substr($b->masa_tamat,0,5) }}</td>
        <td class="td-truncate">{{ $b->tujuan }}</td>
        <td>
            @if($b->status === 'pending')
                <span class="badge badge-pending">Pending</span>
            @elseif($b->status === 'approved')
                <span class="badge badge-done">Diluluskan</span>
            @else
                <span class="badge" style="background:#fdf0f0; color:#a32d2d;">Ditolak</span>
            @endif
        </td>
        <td>
            @if($b->status === 'pending' || ($b->status === 'approved' && !\Carbon\Carbon::parse($b->tarikh)->isPast()))
                <form method="POST" action="/booking/batal/{{ $b->id }}" onsubmit="return confirm('Batalkan tempahan ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-delete" style="padding:4px 12px; font-size:12px;">Batal</button>
                </form>
            @else
                <span style="font-size:12px; color:#aaa;">—</span>
            @endif
        </td>
    </tr>
    @empty
    <tr><td colspan="6" style="text-align:center; color:#999; padding:1.5rem;">Tiada tempahan.</td></tr>
    @endforelse
</table>

@endsection