<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Urus Pengguna — Tempahan</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Google+Sans+Flex:opsz,wght@6..144,1..1000&family=Lora:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet"><link rel="stylesheet" href="{{ asset('style.css') }}">    <link rel="icon" href="{{ asset('images/logo-icon.png')}}">
</head>
<body>
<header>
    <div class="logo"></div>
    <div>
        <h1>Jabatan Hutan Sarawak</h1>
        <p>Hub Aplikasi Perkhidmatan Atas Talian</p>
    </div>
</header>
<x-navbar :breadcrumbs="[['label' => 'Tempahan Bilik', 'url' => '/booking/admin/dashboard'], ['label' => 'Urus Pengguna']]" />
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
                        <label>No. Telefon</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Cth: 0123456789">
                    </div>
                </div>
                <div class="field-row">
                    <div class="field">
                        <label>Wilayah</label>
                        <select name="wilayah_id" required>
                            <option value="">-- Pilih Wilayah --</option>
                            @foreach($wilayahs as $w)
                                <option value="{{ $w->id }}" {{ old('wilayah_id') == $w->id ? 'selected' : '' }}>
                                    {{ $w->nama_wilayah }}
                                </option>
                            @endforeach
                        </select>
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
            <button type="submit" style="padding:7px 16px; background:#194169; color:#fff; border:none; border-radius:6px; font-size:13px; cursor:pointer;">Tapis</button>
            <a href="/booking/admin/users" class="btn-reset">Set Semula</a>
        </div>
    </form>

    <table class="data-table">
        <tr>
            <th>Nama</th>
            <th>Emel</th>
            <th>Bahagian</th>
            <th>Wilayah</th>
            <th>No. Telefon</th>
            <th>Tarikh Daftar</th>
            <th>Status</th>
            <th>Tindakan</th>
        </tr>
        @forelse($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->bahagian ?? '-' }}</td>
            <td>{{ $user->wilayah?->nama_wilayah ?? '-' }}</td>
            <td>{{ $user->phone ?? '-' }}</td>
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
                @if($user->status === 'approved')
                    <button class="btn-view" style="padding:4px 12px; font-size:12px;"
                        onclick="openEditModal(
                            {{ $user->id }},
                            '{{ addslashes($user->name) }}',
                            '{{ addslashes($user->email) }}',
                            '{{ addslashes($user->bahagian ?? '') }}',
                            '{{ addslashes($user->phone ?? '') }}',
                            '{{ $user->wilayah_id ?? '' }}'
                        )">Edit
                    </button>
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
        <tr><td colspan="7" style="text-align:center; color:#999; padding:1.5rem;">Tiada pengguna.</td></tr>
        @endforelse
    </table>

    <div class="pagination-wrap">{{ $users->links() }}</div>

</div>

<footer>
    <div><strong>Jabatan Hutan Sarawak</strong> &nbsp;|&nbsp; Bangunan Baitul Makmur II, Medan Raya, Petra Jaya, 93050 Kuching, Sarawak</div>
    <div>© <?php echo date("Y"); ?> Jabatan Hutan Sarawak. Hak Cipta Terpelihara.</div>
</footer>

{{-- Edit modal --}}
<div class="modal-overlay" id="editModal">
    <div class="modal" style="max-width:480px;">
        <div class="modal-header">
            <h2 style="font-size:14px;">Edit Maklumat Pengguna</h2>
            <button class="modal-close" onclick="closeEditModal()">×</button>
        </div>
        <div class="modal-body">
            <form id="edit-form" method="POST">
                @csrf
                <div class="form-section">
                    <div class="field">
                        <label>Nama <span class="required">*</span></label>
                        <input type="text" id="edit-name" name="name" required>
                    </div>
                    <div class="field">
                        <label>Emel <span class="required">*</span></label>
                        <input type="email" id="edit-email" name="email" required>
                    </div>
                    <div class="field">
                        <label>Bahagian</label>
                        <input type="text" id="edit-bahagian" name="bahagian" placeholder="Cth: Bahagian ICT">
                    </div>
                    <div class="field">
                        <label>Wilayah</label>
                        <select id="edit-wilayah" name="wilayah_id">
                            <option value="">-- Pilih Wilayah --</option>
                            @foreach($wilayahs as $w)
                                <option value="{{ $w->id }}">{{ $w->nama_wilayah }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label>No. Telefon</label>
                        <input type="text" id="edit-phone" name="phone" placeholder="Cth: 0123456789">
                    </div>
                </div>
                <div class="form-footer">
                    <button type="button" onclick="closeEditModal()"
                        style="padding:8px 16px; font-size:13px; border-radius:6px; border:1px solid #ddd; background:#f5f5f5; color:#555; cursor:pointer;">
                        Batal
                    </button>
                    <button type="submit" class="btn-submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openEditModal(id, name, email, bahagian, phone, wilayahId) {
    document.getElementById('edit-name').value     = name;
    document.getElementById('edit-email').value    = email;
    document.getElementById('edit-bahagian').value = bahagian;
    document.getElementById('edit-phone').value    = phone;
    document.getElementById('edit-wilayah').value  = wilayahId;
    document.getElementById('edit-form').action    = '/booking/admin/users/' + id + '/edit';

    const overlay = document.getElementById('editModal');
    overlay.classList.add('active');
    const modal = overlay.querySelector('.modal');
    modal.style.transform = '';
    modal.style.opacity   = '';
}

function closeEditModal() {
    const overlay = document.getElementById('editModal');
    const modal   = overlay.querySelector('.modal');
    modal.style.transform = 'translateY(10px) scale(0.97)';
    modal.style.opacity   = '0';
    setTimeout(() => {
        overlay.classList.remove('active');
        modal.style.transform = '';
        modal.style.opacity   = '';
    }, 220);
}
</script>

<style>
.modal-overlay {
    display: none; position: fixed; inset: 0;
    background: rgba(0,0,0,0.4);
    justify-content: center; align-items: center;
    z-index: 999;
}
.modal-overlay.active { display: flex; }
.modal-overlay .modal {
    transform: translateY(20px) scale(0.97);
    opacity: 0;
    transition: transform 0.25s ease, opacity 0.25s ease;
}
.modal-overlay.active .modal {
    transform: translateY(0) scale(1);
    opacity: 1;
}
</style>

</body>
</html>