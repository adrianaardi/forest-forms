<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Urus Pengguna — Tempahan</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
        <link rel="icon" href="{{ asset('images/logo-icon.png')}}">

</head>
<body>
<header>
    <div class="logo"></div>
    <div>
        <h1>Jabatan Hutan Sarawak</h1>
        <p>Sistem Tempahan Bilik Mesyuarat — Admin</p>
    </div>
</header>
<x-navbar />

<div class="dashboard-body">

    @if(session('success'))
        <div style="background:#eaf3de; border:1px solid #c0dd97; color:#3b6d11; padding:0.75rem 1rem; border-radius:8px; margin-bottom:1rem; font-size:13px;">
            {{ session('success') }}
        </div>
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
                @if($errors->any())
                    <div style="background:#fdf0f0; border:1px solid #f5c1c1; color:#a32d2d; padding:0.75rem 1rem; border-radius:8px; margin-bottom:1rem; font-size:13px;">
                        <ul style="margin:0; padding-left:1.2rem;">
                            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                        </ul>
                    </div>
                @endif
                <div class="field-row">
                    <div class="field">
                        <label>Nama <span class="required">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama penuh" required>
                    </div>
                    <div class="field">
                        <label>Emel <span class="required">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="emel@domain.com" required>
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
    <p class="section-heading">Senarai Pengguna</p>

    <form method="GET" action="/booking/admin/users">
        <div class="toolbar" style="margin-bottom:1rem;">
            <select name="status">
                <option value="">-- Semua Status --</option>
                <option value="pending"  {{ request('status') == 'pending'  ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Diluluskan</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
            </select>
            <button type="submit" style="padding:7px 16px; background:#1a4731; color:#fff; border:none; border-radius:6px; font-size:13px; cursor:pointer;">Tapis</button>
            <a href="/booking/admin/users" class="btn-reset">Set Semula</a>
        </div>
    </form>

    <table class="data-table">
        <tr>
            <th>Nama</th>
            <th>Emel</th>
            <th>Bahagian</th>
            <th>Tarikh Daftar</th>
            <th>Status</th>
            <th>Tindakan</th>
        </tr>
        @forelse($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->bahagian ?? '-' }}</td>
            <td>{{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') }}</td>
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
                <form method="POST" action="{{ route('booking.admin.users.delete', $user->id) }}"
                    onsubmit="return confirm('Padam pengguna {{ addslashes($user->name) }}?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-delete" style="padding:4px 12px; font-size:12px;">Padam</button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="6" style="text-align:center; color:#999; padding:1.5rem;">Tiada pengguna.</td></tr>
        @endforelse
    </table>

    <div class="pagination-wrap">{{ $users->links() }}</div>

</div>

<footer>
    <div><strong>Jabatan Hutan Sarawak</strong> &nbsp;|&nbsp; Wisma Sumber Alam, Petra Jaya, 93660 Kuching, Sarawak</div>
    <div>© 2025 Jabatan Hutan Sarawak. Hak Cipta Terpelihara.</div>
</footer>
</body>
</html>