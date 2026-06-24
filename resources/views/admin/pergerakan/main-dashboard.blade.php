<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Utama Pentadbir - Pergerakan Pegawai</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans+Flex:opsz,wght@6..144,1..1000&family=Lora:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link rel="icon" href="{{ asset('images/logo-icon.png')}}">
</head>
<body>

<header>
        <div class="logo"></div>
    <div>
        <a href="/" style="color: white; text-decoration: none;"><h1>Jabatan Hutan Sarawak</h1></a>
        <p> Hub Aplikasi Perkhidmatan Atas Talian</p>
    </div>
</header>

<x-navbar :breadcrumbs="[['label' => 'Pergerakan Pegawai', 'url' => route('admin.pergerakan.index')], ['label' => 'Panel Pentadbir Utama']]" />

<div class="dashboard-body">

    <div style="margin-bottom: 1rem;">
        <a href="/admin/dashboard" class="btn-back">← Kembali ke Dashboard</a>
    </div>

    <p class="section-heading">Sistem Pergerakan Pegawai (Super Admin)</p>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="admin-grid">
        <div class="form-card">
            <div class="form-card-header">
                <h2>🏢 Urus Bahagian Jabatan</h2>
            </div>
            <div class="form-section">
            <form action="{{ route('admin.pergerakan.bahagian.store') }}" method="POST">
                @csrf
                <div class="field">
                    <label>Nama Bahagian Baru</label>
                    <input type="text" name="nama" placeholder="Cth: Bahagian ICT" required>
                </div>
                <button type="submit" class="btn-submit" style="background:#475569;">Daftar Bahagian Baru</button>
            </form>
            </div>

            <div class="data-table-container">
                <table class="data-table">
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
                                <td class="action-btns">
                                    <button type="button" class="btn-edit" onclick="openEditBahagian({{ $bahagian->id }}, '{{ $bahagian->nama }}')">Edit</button>
                                    <form action="{{ route('admin.pergerakan.bahagian.destroy', $bahagian->id) }}" method="POST" onsubmit="return confirm('Padam bahagian ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-delete">Padam</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" style="text-align:center; color:#999;">Tiada bahagian berdaftar.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-header">
                <h2>👥 Tambah Akaun Sub-Admin Bahagian</h2>
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

            <div class="data-table-container">
                <table class="data-table">
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
                                <td><span style="color: #194169; font-weight: 500;">{{ $sub->bahagian->nama ?? 'Tiada Bahagian' }}</span></td>
                                <td>{{ $sub->email }}</td>
                                <td class="action-btns">
                                    <button type="button" class="btn-edit" onclick="openEditSubAdmin({{ $sub->id }}, '{{ $sub->name }}', '{{ $sub->email }}', '{{ $sub->bahagian_id }}')">Edit</button>
                                    <form action="{{ route('admin.pergerakan.subadmin.destroy', $sub->id) }}" method="POST" onsubmit="return confirm('Padam akaun ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-delete">Padam</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" style="text-align:center; color:#999;">Tiada sub-admin berdaftar.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Edit Bahagian Modal -->
<div class="modal-overlay" id="modalEditBahagian">
    <div class="modal">
        <div class="modal-header">
            <h2>📝 Kemaskini Bahagian</h2>
            <button class="modal-close" onclick="closeModal('modalEditBahagian')">&times;</button>
        </div>
        <div class="modal-body">
            <form id="formEditBahagian" method="POST">
                @csrf @method('PUT')
                <div class="field">
                    <label>Nama Bahagian</label>
                    <input type="text" name="nama" id="edit_bahagian_nama" required>
                </div>
                <div class="modal-actions">
                    <button type="submit" class="btn-submit">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Sub-Admin Modal -->
<div class="modal-overlay" id="modalEditSubAdmin">
    <div class="modal">
        <div class="modal-header">
            <h2>📝 Kemaskini Akaun Sub-Admin</h2>
            <button class="modal-close" onclick="closeModal('modalEditSubAdmin')">&times;</button>
        </div>
        <div class="modal-body">
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
                <div class="modal-actions">
                    <button type="submit" class="btn-submit">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<footer>
    <div><strong>Seksyen Pengurusan Dan Transformasi Digital</strong> &nbsp;|&nbsp; Tingkat 15, Bangunan Baitul Makmur II, Medan Raya, Petra Jaya, 93050 Kuching, Sarawak</div>
    <div>© <?php echo date("Y"); ?> Jabatan Hutan Sarawak. Hak Cipta Terpelihara.</div>
</footer>

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
        if (event.target.classList.contains('modal-overlay')) {
            event.target.classList.remove('active');
        }
    }

    // Optional: Close modal on ESC key
    document.addEventListener('keydown', function(event) {
        if (event.key === "Escape") {
            const actives = document.querySelectorAll('.modal-overlay.active');
            actives.forEach(m => m.classList.remove('active'));
        }
    });
</script>

</body>
</html>