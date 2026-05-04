@extends('booking.layout')

@section('title', 'Urus Pengguna')

@section('content')

@if(session('success'))
    <div style="background:#eaf3de; border:1px solid #c0dd97; color:#3b6d11; padding:0.75rem 1rem; border-radius:8px; margin-bottom:1rem; font-size:13px;">{{ session('success') }}</div>
@endif

{{-- Add user --}}
<div class="form-card" style="margin-bottom:1.5rem;">
    <div class="form-card-header">
        <h2>Tambah Pengguna</h2>
        <p>Pengguna yang ditambah oleh admin akan terus diluluskan.</p>
    </div>
    <form method="POST" action="{{ route('booking.admin.users.store') }}">
        @csrf
        <div class="form-section">
            <div class="field-row">
                <div class="field">
                    <label>Nama <span class="required">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama penuh" required>
                    @error('name')<div style="color:#a32d2d; font-size:12px; margin-top:4px;">{{ $message }}</div>@enderror
                </div>
                <div class="field">
                    <label>Emel <span class="required">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="emel@domain.com" required>
                    @error('email')<div style="color:#a32d2d; font-size:12px; margin-top:4px;">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="field-row">
                <div class="field">
                    <label>Bahagian</label>
                    <input type="text" name="bahagian" value="{{ old('bahagian') }}" placeholder="Cth: Bahagian ICT">
                </div>
                <div class="field">
                    <label>Kata Laluan <span class="required">*</span></label>
                    <input type="password" name="password" placeholder="Minimum 8 aksara" required>
                    @error('password')<div style="color:#a32d2d; font-size:12px; margin-top:4px;">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>
        <div class="form-footer">
            <span></span>
            <button type="submit" class="btn-submit">Tambah</button>
        </div>
    </form>
</div>

{{-- User list --}}
<form method="GET" action="/booking/admin/users">
    <div class="toolbar" style="margin-bottom:1rem;">
        <select name="status">
            <option value="">-- Semua Status --</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Diluluskan</option>
            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
        </select>
        <button type="submit" style="padding:7px 16px; background:#1a4731; color:#fff; border:none; border-radius:6px; font-size:13px; cursor:pointer;">Tapis</button>
        <a href="/booking/admin/users" style="padding:7px 16px; background:#f0f0f0; color:#444; border-radius:6px; font-size:13px; text-decoration:none;">Set Semula</a>
    </div>
</form>

<table class="data-table">
    <tr>
        <th>Nama</th>
        <th>Emel</th>
        <th>Bahagian</th>
        <th>Status</th>
        <th>Tindakan</th>
    </tr>
    @forelse($users as $user)
    <tr>
        <td>{{ $user->name }}</td>
        <td>{{ $user->email }}</td>
        <td>{{ $user->bahagian ?? '-' }}</td>
        <td>
            @if($user->status === 'pending')
                <span class="badge badge-pending">Pending</span>
            @elseif($user->status === 'approved')
                <span class="badge badge-done">Diluluskan</span>
            @else
                <span class="badge" style="background:#fdf0f0; color:#a32d2d;">Ditolak</span>
            @endif
        </td>
        <td style="display:flex; gap:6px; flex-wrap:wrap;">
            @if($user->status === 'pending')
                <form method="POST" action="{{ route('booking.admin.users.status', $user->id) }}">
                    @csrf
                    <input type="hidden" name="status" value="approved">
                    <button type="submit" class="btn-lulus" style="padding:4px 12px; font-size:12px;">Lulus</button>
                </form>
                <form method="POST" action="{{ route('booking.admin.users.status', $user->id) }}">
                    @csrf
                    <input type="hidden" name="status" value="rejected">
                    <button type="submit" class="btn-tolak" style="padding:4px 12px; font-size:12px;">Tolak</button>
                </form>
            @endif
            <form method="POST" action="{{ route('booking.admin.users.delete', $user->id) }}" onsubmit="return confirm('Padam pengguna ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-delete" style="padding:4px 12px; font-size:12px;">Padam</button>
            </form>
        </td>
    </tr>
    @empty
    <tr><td colspan="5" style="text-align:center; color:#999; padding:1.5rem;">Tiada pengguna.</td></tr>
    @endforelse
</table>

<div class="pagination-wrap">{{ $users->links() }}</div>

@endsection