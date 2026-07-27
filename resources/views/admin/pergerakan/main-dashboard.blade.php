<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Utama Pentadbir - Pergerakan Pegawai</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link rel="icon" href="{{ asset('images/logo-icon.png')}}">
</head>
<body>

<x-header />
<x-navbar :breadcrumbs="[['label' => 'Pergerakan Pegawai', 'url' => route('admin.pergerakan.index')], ['label' => 'Panel Pentadbir Utama']]" />

<div class="pg-body">

    <div class="form-card">
        <div class="form-card-header">
            <h2>Sistem Pergerakan Pegawai (Super Admin)</h2>
            <p>Urus bahagian dan akaun sub-admin</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="cards">
        <div class="form-card">
            <div class="form-card-header">
                <h2>Urus Bahagian Jabatan</h2>
            </div>
            <div class="form-section">
            <form action="{{ route('admin.pergerakan.bahagian.store') }}" method="POST">
                @csrf
                <div class="field">
                    <label>Nama Bahagian Baru</label>
                    <input type="text" name="nama" placeholder="Cth: Bahagian ICT" required>
                </div>
                <button type="submit" class="btn-submit">Daftar Bahagian Baru</button>
            </form>
            </div>

            <div class="form-section">
                <div class="table-wrap">
                <table class="app-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Bahagian</th>
                            <th>Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bahagianList as $bahagian)
                            <tr>
                                <td>{{ $bahagian->id }}</td>
                                <td><strong>{{ $bahagian->nama }}</strong></td>
                                <td>
                                    <div class="table-actions">
                                    <button type="button" class="btn-back table-btn" onclick="openEditBahagian({{ $bahagian->id }}, '{{ $bahagian->nama }}')">Edit</button>
                                    <form action="{{ route('admin.pergerakan.bahagian.destroy', $bahagian->id) }}" method="POST" onsubmit="return confirm('Padam bahagian ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-submit table-btn">Padam</button>
                                    </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="table-empty">Tiada bahagian berdaftar.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-header">
                <h2>Tambah Akaun Sub-Admin Bahagian</h2>
            </div>
            <div class="form-section">
            <form action="{{ route('admin.pergerakan.subadmin.store') }}" method="POST">
                @csrf
                <div class="field">
                    <label>Nama Penuh Sub-Admin</label>
                    <input type="text" name="name" placeholder="Nama pegawai pengurus" required>
                </div>
                <div class="field">
                    <label>Email Rasmi (ID Log Masuk)</label>
                    <input type="email" name="email" placeholder="username@sarawak.gov.my" required>
                </div>
                <div class="field">
                    <label>Bahagian Bertanggungjawab</label>
                    <select name="bahagian_id" required>
                        <option value="">-- Pilih Bahagian Terhad --</option>
                        @foreach($bahagianList as $bahagian)
                            <option value="{{ $bahagian->id }}">{{ $bahagian->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>Kata Laluan Sementara</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit" class="btn-submit">Daftar Sub-Admin</button>
            </form>
            </div>

            <div class="form-section">
                <div class="table-wrap">
                <table class="app-table">
                    <thead>
                        <tr>
                            <th>Nama Pengurus</th>
                            <th>Bahagian Terhad</th>
                            <th>Emel Akses</th>
                            <th>Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subadmins as $sub)
                            <tr>
                                <td><strong>{{ $sub->name }}</strong></td>
                                <td><span>{{ $sub->bahagian->nama ?? 'Tiada Bahagian' }}</span></td>
                                <td>{{ $sub->email }}</td>
                                <td>
                                    <div class="table-actions">
                                    <button type="button" class="btn-back table-btn" onclick="openEditSubAdmin({{ $sub->id }}, '{{ $sub->name }}', '{{ $sub->email }}', '{{ $sub->bahagian_id }}')">Edit</button>
                                    <form action="{{ route('admin.pergerakan.subadmin.destroy', $sub->id) }}" method="POST" onsubmit="return confirm('Padam akaun ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-submit table-btn">Padam</button>
                                    </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="table-empty">Tiada sub-admin berdaftar.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Bahagian Modal -->
<div class="ticket-modal-overlay" id="modalEditBahagian">
    <div class="ticket-modal">
        <div class="ticket-modal-header">
            <h3>Kemaskini Bahagian</h3>
            <button class="ticket-modal-close" onclick="closeModal('modalEditBahagian')">&times;</button>
        </div>
        <div class="ticket-modal-body">
            <form id="formEditBahagian" method="POST">
                @csrf @method('PUT')
                <div class="field">
                    <label>Nama Bahagian</label>
                    <input type="text" name="nama" id="edit_bahagian_nama" required>
                </div>
                <div class="form-footer">
                    <span></span>
                    <button type="submit" class="btn-submit">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Sub-Admin Modal -->
<div class="ticket-modal-overlay" id="modalEditSubAdmin">
    <div class="ticket-modal">
        <div class="ticket-modal-header">
            <h3>Kemaskini Akaun Sub-Admin</h3>
            <button class="ticket-modal-close" onclick="closeModal('modalEditSubAdmin')">&times;</button>
        </div>
        <div class="ticket-modal-body">
            <form id="formEditSubAdmin" method="POST">
                @csrf @method('PUT')
                <div class="field">
                    <label>Nama Penuh</label>
                    <input type="text" name="name" id="edit_sub_name" required>
                </div>
                <div class="field">
                    <label>Email Rasmi</label>
                    <input type="email" name="email" id="edit_sub_email" required>
                </div>
                <div class="field">
                    <label>Bahagian Bertanggungjawab</label>
                    <select name="bahagian_id" id="edit_sub_bahagian_id" required>
                        @foreach($bahagianList as $bahagian)
                            <option value="{{ $bahagian->id }}">{{ $bahagian->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>Kata Laluan Baru (Biarkan kosong jika tiada perubahan)</label>
                    <input type="password" name="password">
                </div>
                <div class="form-footer">
                    <span></span>
                    <button type="submit" class="btn-submit">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<x-footer />

<script>
    function openEditBahagian(id, nama) {
        const modal = document.getElementById('modalEditBahagian');
        const form = document.getElementById('formEditBahagian');
        
        // Set action URL dynamically
        form.action = `/admin/pergerakan-pegawai/bahagian/${id}`;
        
        // Set current values
        document.getElementById('edit_bahagian_nama').value = nama;
        
        modal.classList.add('active');
    }

    function openEditSubAdmin(id, name, email, bahagianId) {
        const modal = document.getElementById('modalEditSubAdmin');
        const form = document.getElementById('formEditSubAdmin');
        
        // Set action URL dynamically
        form.action = `/admin/pergerakan-pegawai/subadmin/${id}`;
        
        // Set current values
        document.getElementById('edit_sub_name').value = name;
        document.getElementById('edit_sub_email').value = email;
        document.getElementById('edit_sub_bahagian_id').value = bahagianId;
        
        modal.classList.add('active');
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('active');
    }

    // Close modal when clicking on overlay
    window.onclick = function(event) {
        if (event.target.classList.contains('ticket-modal-overlay')) {
            event.target.classList.remove('active');
        }
    }

    // Optional: Close modal on ESC key
    document.addEventListener('keydown', function(event) {
        if (event.key === "Escape") {
            const actives = document.querySelectorAll('.ticket-modal-overlay.active');
            actives.forEach(m => m.classList.remove('active'));
        }
    });
</script>

</body>
</html>