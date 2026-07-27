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
<x-header />
<x-navbar :breadcrumbs="[['label' => 'Tempahan Bilik', 'url' => '/booking/admin/dashboard'], ['label' => 'Urus Pengguna']]" />
<div class="pg-body">
    <div class="card-stack">

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Add user --}}
    <div class="form-card">
        <div class="form-card-header">
            <h2>Tambah Pengguna</h2>
            <p>Pengguna yang ditambah oleh admin akan terus diluluskan.</p>
        </div>
        <form method="POST" action="{{ route('booking.admin.users.store') }}">
            @csrf
            <div class="form-section">
                @if($errors->any())
                    <div class="form-error-box alert alert-error">
                        <ul class="form-error-list">
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
                        <select name="bahagian">
                            <option value="">-- Pilih Bahagian --</option>
                            <option value="Pejabat Direktorat" {{ old('bahagian') == 'Pejabat Direktorat' ? 'selected' : '' }}>Pejabat Direktorat</option>
                            <option value="Bahagian Perancangan dan Pengurusan Hutan" {{ old('bahagian') == 'Bahagian Perancangan dan Pengurusan Hutan' ? 'selected' : '' }}>Bahagian Perancangan dan Pengurusan Hutan</option>
                            <option value="Bahagian Pelesenan" {{ old('bahagian') == 'Bahagian Pelesenan' ? 'selected' : '' }}>Bahagian Pelesenan</option>
                            <option value="Bahagian Penyelidikan dan Pembangunan" {{ old('bahagian') == 'Bahagian Penyelidikan dan Pembangunan' ? 'selected' : '' }}>Bahagian Penyelidikan dan Pembangunan</option>
                            <option value="Bahagian Hasil dan Pengurusan Data" {{ old('bahagian') == 'Bahagian Hasil dan Pengurusan Data' ? 'selected' : '' }}>Bahagian Hasil dan Pengurusan Data</option>
                            <option value="Bahagian Hal Ehwal Antarabangsa" {{ old('bahagian') == 'Bahagian Hal Ehwal Antarabangsa' ? 'selected' : '' }}>Bahagian Hal Ehwal Antarabangsa</option>
                            <option value="Bahagian Teknologi Hutan dan Geospatial" {{ old('bahagian') == 'Bahagian Teknologi Hutan dan Geospatial' ? 'selected' : '' }}>Bahagian Teknologi Hutan dan Geospatial</option>
                            <option value="Bahagian Restorasi dan Hutan Industri" {{ old('bahagian') == 'Bahagian Restorasi dan Hutan Industri' ? 'selected' : '' }}>Bahagian Restorasi dan Hutan Industri</option>
                            <option value="Bahagian Pewartaan dan Konservasi" {{ old('bahagian') == 'Bahagian Pewartaan dan Konservasi' ? 'selected' : '' }}>Bahagian Pewartaan dan Konservasi</option>
                            <option value="Bahagian Perhutanan Sosial" {{ old('bahagian') == 'Bahagian Perhutanan Sosial' ? 'selected' : '' }}>Bahagian Perhutanan Sosial</option>
                            <option value="Bahagian Pencegahan dan Penguatkuasaan" {{ old('bahagian') == 'Bahagian Pencegahan dan Penguatkuasaan' ? 'selected' : '' }}>Bahagian Pencegahan dan Penguatkuasaan</option>
                            <option value="Bahagian Khidmat Pengurusan" {{ old('bahagian') == 'Bahagian Khidmat Pengurusan' ? 'selected' : '' }}>Bahagian Khidmat Pengurusan</option>
                            <option value="Bahagian Pembangunan Projek" {{ old('bahagian') == 'Bahagian Pembangunan Projek' ? 'selected' : '' }}>Bahagian Pembangunan Projek</option>
                            <option value="Unit Integriti dan Audit Dalaman" {{ old('bahagian') == 'Unit Integriti dan Audit Dalaman' ? 'selected' : '' }}>Unit Integriti dan Audit Dalaman</option>
                            <option value="Unit Korporat dan Permodenan Perkhidmatan" {{ old('bahagian') == 'Unit Korporat dan Permodenan Perkhidmatan' ? 'selected' : '' }}>Unit Korporat dan Permodenan Perkhidmatan</option>
                            <option value="Unit Pengurusan Geopark" {{ old('bahagian') == 'Unit Pengurusan Geopark' ? 'selected' : '' }}>Unit Pengurusan Geopark</option>
                            <option value="Unit Perancangan Strategik" {{ old('bahagian') == 'Unit Perancangan Strategik' ? 'selected' : '' }}>Unit Perancangan Strategik</option>
                            <option value="Unit Perundangan dan Khidmat Nasihat" {{ old('bahagian') == 'Unit Perundangan dan Khidmat Nasihat' ? 'selected' : '' }}>Unit Perundangan dan Khidmat Nasihat</option>
                            <option value="Pejabat Wilayah" {{ old('bahagian') == 'Pejabat Wilayah' ? 'selected' : '' }}>Pejabat Wilayah</option>
                        </select>
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
    <div class="form-card">
        <div class="form-card-header">
            <h2>Senarai Pengguna</h2>
            <p>Tapis mengikut status dan urus pendaftaran pengguna.</p>
        </div>
        <div class="form-section">
            <form method="GET" action="/booking/admin/users">
                <div class="filter-toolbar">
                    <div class="field">
                        <label for="status">Status</label>
                        <select id="status" name="status">
                            <option value="">-- Semua Status --</option>
                            <option value="pending"  {{ request('status') == 'pending'  ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Diluluskan</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>
                    <div class="table-actions">
                        <button type="submit" class="table-btn table-btn-info">Tapis</button>
                        <a href="/booking/admin/users" class="table-btn table-btn-neutral">Set Semula</a>
                    </div>
                </div>
            </form>
        </div>

        <div class="form-section">
            <div class="table-wrap">
                <table class="app-table">
                    <thead>
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
                    </thead>
                    <tbody>
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
                                <span class="badge badge-rejected">Ditolak</span>
                            @endif
                        </td>
                        <td>
                            <div class="table-actions">
                                @if($user->status === 'pending')
                                    <form method="POST" action="{{ route('booking.admin.users.status', $user->id) }}">
                                        @csrf
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" class="table-btn table-btn-success">Lulus</button>
                                    </form>
                                    <form method="POST" action="{{ route('booking.admin.users.status', $user->id) }}">
                                        @csrf
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="table-btn table-btn-warning">Tolak</button>
                                    </form>
                                @endif
                                @if($user->status === 'approved')
                                    <button type="button" class="table-btn table-btn-info"
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
                                    <button type="submit" class="table-btn table-btn-danger">Padam</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="table-empty">Tiada pengguna.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="table-pagination">{{ $users->links() }}</div>
        </div>
    </div>
    </div>
</div>

<x-footer />

{{-- Edit modal --}}
<div class="ticket-modal-overlay" id="editModal">
    <div class="ticket-modal">
        <div class="ticket-modal-header">
            <h3>Edit Maklumat Pengguna</h3>
            <button class="ticket-modal-close" onclick="closeEditModal()">×</button>
        </div>
        <div class="ticket-modal-body">
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
                        <select id="edit-bahagian" name="bahagian">
                            <option value="">-- Pilih Bahagian --</option>
                            <option value="Pejabat Direktorat">Pejabat Direktorat</option>
                            <option value="Bahagian Perancangan dan Pengurusan Hutan">Bahagian Perancangan dan Pengurusan Hutan</option>
                            <option value="Bahagian Pelesenan">Bahagian Pelesenan</option>
                            <option value="Bahagian Penyelidikan dan Pembangunan">Bahagian Penyelidikan dan Pembangunan</option>
                            <option value="Bahagian Hasil dan Pengurusan Data">Bahagian Hasil dan Pengurusan Data</option>
                            <option value="Bahagian Hal Ehwal Antarabangsa">Bahagian Hal Ehwal Antarabangsa</option>
                            <option value="Bahagian Teknologi Hutan dan Geospatial">Bahagian Teknologi Hutan dan Geospatial</option>
                            <option value="Bahagian Restorasi dan Hutan Industri">Bahagian Restorasi dan Hutan Industri</option>
                            <option value="Bahagian Pewartaan dan Konservasi">Bahagian Pewartaan dan Konservasi</option>
                            <option value="Bahagian Perhutanan Sosial">Bahagian Perhutanan Sosial</option>
                            <option value="Bahagian Pencegahan dan Penguatkuasaan">Bahagian Pencegahan dan Penguatkuasaan</option>
                            <option value="Bahagian Khidmat Pengurusan">Bahagian Khidmat Pengurusan</option>
                            <option value="Bahagian Pembangunan Projek">Bahagian Pembangunan Projek</option>
                            <option value="Unit Integriti dan Audit Dalaman">Unit Integriti dan Audit Dalaman</option>
                            <option value="Unit Korporat dan Permodenan Perkhidmatan">Unit Korporat dan Permodenan Perkhidmatan</option>
                            <option value="Unit Pengurusan Geopark">Unit Pengurusan Geopark</option>
                            <option value="Unit Perancangan Strategik">Unit Perancangan Strategik</option>
                            <option value="Unit Perundangan dan Khidmat Nasihat">Unit Perundangan dan Khidmat Nasihat</option>
                            <option value="Pejabat Wilayah">Pejabat Wilayah</option>
                        </select>
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
                    <div class="form-footer-actions">
                    <button type="button" class="table-btn table-btn-danger left" onclick="submitPasswordReset()">
                        Set Semula Kata Laluan
                    </button>
                    <button type="button" class="btn-back" onclick="closeEditModal()">
                        Batal
                    </button>
                    <button type="submit" class="btn-submit">Simpan</button>
                    </div>
                </div>
                <p class="help-text-muted">Kata Laluan: 123456789.</p>
            </form>

            <form id="reset-password-form" method="POST" class="is-hidden">
                @csrf
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
    
    // Dynamically set the password reset action url
    document.getElementById('reset-password-form').action = '/booking/admin/users/' + id + '/reset-password';

    const overlay = document.getElementById('editModal');
    overlay.classList.add('active');
}

function submitPasswordReset() {
    if (confirm('Set semula kata laluan pengguna ini kepada "password123"?')) {
        document.getElementById('reset-password-form').submit();
    }
}

function closeEditModal() {
    const overlay = document.getElementById('editModal');
    overlay.classList.remove('active');
}
</script>

</body>
</html>