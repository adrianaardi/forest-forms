<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aduan ICT — Admin</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
</head>
<body>

<header>
    <div class="logo"></div>
    <div>
        <h1>Jabatan Hutan Sarawak</h1>
        <p>Forest Department Sarawak — Sistem Perkhidmatan Dalaman</p>
    </div>
</header>

<nav>
    <a href="/" class="{{ request()->is('/') ? 'active' : '' }}">Laman Utama</a>

    @auth
        <a href="/admin/dashboard" class="{{ request()->is('admin/dashboard') ? 'active' : '' }}">Dashboard</a>
        <a href="/admin/ict-aduan" class="{{ request()->is('admin/ict-aduan') ? 'active' : '' }}">Aduan ICT</a>
        <a href="/admin/portal-upload" class="{{ request()->is('admin/portal-upload') ? 'active' : '' }}">Muat Naik</a>

        <a href="/admin/profile" class="{{ request()->is('admin/profile', 'admin/accounts') ? 'active' : '' }}" style="margin-left:auto;">
            👤 {{ Auth::user()->name }}
        </a>

        <form method="POST" action="{{ route('logout') }}" style="display:flex; align-items:center; margin-left:1rem;">
            @csrf
            <button type="submit" style="background:none; border:none; cursor:pointer; font-size:13px; color:rgba(255,255,255,0.7); padding:0;">
                Log Keluar
            </button>
        </form>
    @endauth

    @guest
        <a href="/login" style="margin-left:auto;">Admin</a>
    @endguest
</nav>

<div class="dashboard-body">

    <div style="margin-bottom: 1rem;">
        <a href="/admin/dashboard" class="btn-back">← Kembali ke Dashboard</a>
    </div>

    <p class="section-heading">Senarai Aduan ICT</p>

    <form method="GET" action="/admin/ict-aduan">
        <div class="toolbar">
            <input type="text" name="search" placeholder="Cari nama, bahagian, kategori..." value="{{ request('search') }}">
            <select name="status">
                <option value="">-- Semua Status --</option>
                <option value="Belum Selesai" {{ request('status') == 'Belum Selesai' ? 'selected' : '' }}>Belum Selesai</option>
                <option value="Dalam Tindakan" {{ request('status') == 'Dalam Tindakan' ? 'selected' : '' }}>Dalam Tindakan</option>
                <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
            <button type="submit">Tapis</button>
            <a href="/admin/ict-aduan" class="btn-reset">Set Semula</a>
            <button type="button" class="btn-delete" id="deleteBtn" onclick="submitDelete()" disabled>Padam</button>
        </div>
    </form>

    <form id="deleteForm" method="POST" action="{{ route('admin.ict-aduan.delete') }}">
        @csrf
        <div id="deleteInputs"></div>
    </form>

    <table class="data-table">
        <tr>
            <th style="width:36px;"><input type="checkbox" id="checkAll" onclick="toggleAll(this)"></th>
            <th>No. Tiket</th>
            <th>Nama</th>
            <th>Bahagian</th>
            <th>Kategori</th>
            <th>Tarikh</th>
            <th>Status</th>
            <th>Tindakan</th>
        </tr>
        @forelse($complaints as $item)
        <tr>
            <td><input type="checkbox" class="row-check" value="{{ $item->id }}" onchange="updateDelete()"></td>
            <td>{{ $item->no_tiket }}</td>
            <td>{{ $item->nama }}</td>
            <td>{{ $item->bahagian ?? '-' }}</td>
            <td>{{ $item->kategori_masalah }}</td>
            <td>{{ \Carbon\Carbon::parse($item->tarikh_aduan)->format('d/m/Y') }}</td>
            <td>
                @if($item->status === 'Belum Selesai')
                    <span class="badge badge-pending">Belum Selesai</span>
                @elseif($item->status === 'Dalam Tindakan')
                    <span class="badge badge-progress">Dalam Tindakan</span>
                @else
                    <span class="badge badge-done">Selesai</span>
                @endif
            </td>
            <td>
                <button class="btn-view" onclick="openModal(
                    {{ $item->id }},
                    '{{ addslashes($item->nama) }}',
                    '{{ addslashes($item->jawatan ?? '-') }}',
                    '{{ addslashes($item->bahagian ?? '-') }}',
                    '{{ addslashes($item->telefon ?? '-') }}',
                    '{{ \Carbon\Carbon::parse($item->tarikh_aduan)->format('d/m/Y') }}',
                    '{{ $item->masa_aduan }}',
                    '{{ addslashes($item->kategori_masalah) }}',
                    '{{ addslashes($item->masalah_lain ?? '-') }}',
                    '{{ addslashes($item->keterangan_kerosakan ?? '-') }}',
                    '{{ $item->status }}'
                )">Lihat</button>
            </td>
        </tr>
        @empty
        <tr><td colspan="7" style="text-align:center; color:#999; padding:1.5rem;">Tiada rekod ditemui.</td></tr>
        @endforelse
    </table>

    <div class="pagination-wrap">
        {{ $complaints->links() }}
    </div>

</div>

<footer>
    <div><strong>Jabatan Hutan Sarawak</strong> &nbsp;|&nbsp; Wisma Sumber Alam, Petra Jaya, 93660 Kuching, Sarawak</div>
    <div>© 2025 Jabatan Hutan Sarawak. Hak Cipta Terpelihara.</div>
</footer>

<div class="modal-overlay" id="modalOverlay" onclick="closeOnOverlay(event)">
    <div class="modal">
        <div class="modal-header">
            <h2>Borang Aduan Baikpulih ICT / Digital</h2>
            <button class="modal-close" onclick="closeModal()">×</button>
        </div>
        <div class="modal-body">
            <div class="detail-group">
                <div class="detail-section-label">Bahagian A — Maklumat Pengadu</div>
                <div class="detail-row">
                    <div class="detail-field"><label>Nama</label><p id="d-nama"></p></div>
                    <div class="detail-field"><label>Jawatan</label><p id="d-jawatan"></p></div>
                </div>
                <div class="detail-row">
                    <div class="detail-field"><label>Bahagian / Unit</label><p id="d-bahagian"></p></div>
                    <div class="detail-field"><label>No Telefon</label><p id="d-telefon"></p></div>
                </div>
                <div class="detail-row">
                    <div class="detail-field"><label>Tarikh Aduan</label><p id="d-tarikh"></p></div>
                    <div class="detail-field"><label>Masa Aduan</label><p id="d-masa"></p></div>
                </div>
            </div>
            <div class="detail-group">
                <div class="detail-section-label">Bahagian B — Maklumat Kerosakan</div>
                <div class="detail-row">
                    <div class="detail-field"><label>Kategori Masalah</label><p id="d-kategori"></p></div>
                    <div class="detail-field"><label>Masalah Lain-lain</label><p id="d-lain"></p></div>
                </div>
                <div class="detail-field" style="margin-top:0.5rem;">
                    <label>Keterangan Kerosakan</label>
                    <p id="d-keterangan" style="white-space:pre-wrap;"></p>
                </div>
            </div>
            <div class="detail-group">
                <div class="detail-section-label">Status</div>
                <div id="d-status" style="margin-bottom: 1rem;"></div>
                <div class="modal-actions" id="d-actions"></div>
            </div>
        </div>
    </div>
</div>

<!-- hidden status update forms -->
<form id="statusForm" method="POST" style="display:none;">
    @csrf
    <input type="hidden" name="status" id="statusInput">
</form>

<script>
var currentId = null;

function openModal(id, nama, jawatan, bahagian, telefon, tarikh, masa, kategori, lain, keterangan, status) {
    currentId = id;
    document.getElementById('d-nama').textContent = nama;
    document.getElementById('d-jawatan').textContent = jawatan;
    document.getElementById('d-bahagian').textContent = bahagian;
    document.getElementById('d-telefon').textContent = telefon;
    document.getElementById('d-tarikh').textContent = tarikh;
    document.getElementById('d-masa').textContent = masa;
    document.getElementById('d-kategori').textContent = kategori;
    document.getElementById('d-lain').textContent = lain;
    document.getElementById('d-keterangan').textContent = keterangan;

    var badges = {
        'Belum Selesai': '<span class="badge badge-pending">Belum Selesai</span>',
        'Dalam Tindakan': '<span class="badge badge-progress">Dalam Tindakan</span>',
        'Selesai': '<span class="badge badge-done">Selesai</span>'
    };
    document.getElementById('d-status').innerHTML = badges[status] || status;

    var actions = document.getElementById('d-actions');
    actions.innerHTML = '';
    if (status !== 'Selesai') {
        actions.innerHTML =
            '<button class="btn-lulus" onclick="updateStatus(\'Selesai\')">Luluskan</button>' +
            '<button class="btn-tolak" onclick="updateStatus(\'Belum Selesai\')">Tolak</button>';
    }

    document.getElementById('modalOverlay').classList.add('active');

    // auto change to Dalam Tindakan if Belum Selesai
    if (status === 'Belum Selesai') {
        updateStatus('Dalam Tindakan', false);
    }
}

function updateStatus(newStatus, reload = true) {
    var form = document.getElementById('statusForm');
    form.action = '/admin/ict-aduan/' + currentId + '/status';
    document.getElementById('statusInput').value = newStatus;
    if (reload) {
        form.submit();
    } else {
        fetch(form.action, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: '_token={{ csrf_token() }}&status=' + newStatus
        });
    }
}

function closeModal() {
    var modal = document.getElementById('modalOverlay');
    var box = modal.querySelector('.modal');
    box.style.transform = 'translateY(16px)';
    box.style.opacity = '0';
    setTimeout(function() {
        modal.classList.remove('active');
        box.style.transform = '';
        box.style.opacity = '';
    }, 200);
}

function closeOnOverlay(e) {
    if (e.target === document.getElementById('modalOverlay')) closeModal();
}

function toggleAll(source) {
    document.querySelectorAll('.row-check').forEach(function(cb) {
        cb.checked = source.checked;
    });
    updateDelete();
}

function updateDelete() {
    var checked = document.querySelectorAll('.row-check:checked').length;
    document.getElementById('deleteBtn').disabled = checked === 0;
    var allChecks = document.querySelectorAll('.row-check');
    document.getElementById('checkAll').checked = checked === allChecks.length && allChecks.length > 0;
}

function submitDelete() {
    if (!confirm('Padam rekod yang dipilih?')) return;
    var container = document.getElementById('deleteInputs');
    container.innerHTML = '';
    document.querySelectorAll('.row-check:checked').forEach(function(cb) {
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'ids[]';
        input.value = cb.value;
        container.appendChild(input);
    });
    document.getElementById('deleteForm').submit();
}
</script>

</body>
</html>