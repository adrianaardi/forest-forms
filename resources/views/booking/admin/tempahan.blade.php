@extends('booking.layout')

@section('title', 'Urus Tempahan')

@section('content')

@if(session('success'))
    <div style="background:#eaf3de; border:1px solid #c0dd97; color:#3b6d11; padding:0.75rem 1rem; border-radius:8px; margin-bottom:1rem; font-size:13px;">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div style="background:#fdf0f0; border:1px solid #f5c1c1; color:#a32d2d; padding:0.75rem 1rem; border-radius:8px; margin-bottom:1rem; font-size:13px;">{{ session('error') }}</div>
@endif

<p class="section-heading">Senarai Tempahan</p>

<form method="GET" action="/booking/admin/tempahan">
    <div class="toolbar" style="margin-bottom:1rem;">
        <select name="status">
            <option value="">-- Semua Status --</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Diluluskan</option>
            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
        </select>
        <select name="bilik">
            <option value="">-- Semua Bilik --</option>
            @foreach($bilikList as $b)
                <option value="{{ $b->id }}" {{ request('bilik') == $b->id ? 'selected' : '' }}>{{ $b->nama_bilik }}</option>
            @endforeach
        </select>
        <button type="submit" style="padding:7px 16px; background:#1a4731; color:#fff; border:none; border-radius:6px; font-size:13px; cursor:pointer;">Tapis</button>
        <a href="/booking/admin/tempahan" style="padding:7px 16px; background:#f0f0f0; color:#444; border-radius:6px; font-size:13px; text-decoration:none;">Set Semula</a>
    </div>
</form>

<table class="data-table">
    <tr>
        <th>Pemohon</th>
        <th>Bilik</th>
        <th>Tarikh</th>
        <th>Masa</th>
        <th>Tujuan</th>
        <th>Status</th>
        <th>Tindakan</th>
    </tr>
    @forelse($tempahan as $b)
    <tr>
        <td>{{ $b->user->name }}<br><span style="font-size:11px; color:#777;">{{ $b->user->bahagian }}</span></td>
        <td>{{ $b->bilik->nama_bilik }}</td>
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
            @if($b->status === 'pending')
                <button class="btn-view" onclick="openApprove({{ $b->id }}, '{{ addslashes($b->user->name) }}', '{{ addslashes($b->bilik->nama_bilik) }}', '{{ \Carbon\Carbon::parse($b->tarikh)->format('d/m/Y') }}', '{{ substr($b->masa_mula,0,5) }} – {{ substr($b->masa_tamat,0,5) }}')">Semak</button>
            @else
                <span style="font-size:12px; color:#aaa;">—</span>
            @endif
        </td>
    </tr>
    @empty
    <tr><td colspan="7" style="text-align:center; color:#999; padding:1.5rem;">Tiada tempahan.</td></tr>
    @endforelse
</table>

<div class="pagination-wrap">{{ $tempahan->links() }}</div>

{{-- Approve modal --}}
<div class="modal-overlay" id="approveModal" onclick="closeApproveModal(event)">
    <div class="modal">
        <div class="modal-header">
            <h2>Semak Tempahan</h2>
            <button class="modal-close" onclick="document.getElementById('approveModal').classList.remove('active')">×</button>
        </div>
        <div class="modal-body">
            <div class="detail-group">
                <div class="detail-row">
                    <div class="detail-field"><label>Pemohon</label><p id="a-user"></p></div>
                    <div class="detail-field"><label>Bilik</label><p id="a-bilik"></p></div>
                </div>
                <div class="detail-row">
                    <div class="detail-field"><label>Tarikh</label><p id="a-tarikh"></p></div>
                    <div class="detail-field"><label>Masa</label><p id="a-masa"></p></div>
                </div>
            </div>
            <form id="approveForm" method="POST">
                @csrf
                <div class="field">
                    <label>Catatan (optional)</label>
                    <textarea name="catatan_admin" rows="2" placeholder="Tambah catatan jika perlu..."></textarea>
                </div>
                <div class="modal-actions" style="margin-top:0.75rem;">
                    <input type="hidden" name="status" id="approve-status">
                    <button type="submit" class="btn-lulus" onclick="document.getElementById('approve-status').value='approved'">Luluskan</button>
                    <button type="submit" class="btn-tolak" onclick="document.getElementById('approve-status').value='rejected'">Tolak</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openApprove(id, user, bilik, tarikh, masa) {
    document.getElementById('a-user').textContent = user;
    document.getElementById('a-bilik').textContent = bilik;
    document.getElementById('a-tarikh').textContent = tarikh;
    document.getElementById('a-masa').textContent = masa;
    document.getElementById('approveForm').action = '/booking/admin/tempahan/' + id + '/status';
    document.getElementById('approveModal').classList.add('active');
}
function closeApproveModal(e) {
    if (e.target === document.getElementById('approveModal')) document.getElementById('approveModal').classList.remove('active');
}
</script>

@endsection