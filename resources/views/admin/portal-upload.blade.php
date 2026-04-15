<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permohonan Muat Naik — Admin</title>
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
        
        <form method="POST" action="{{ route('logout') }}" style="margin-left:auto; display:flex; align-items:center;">
            @csrf
            <button type="submit" style="background:none; border:none; cursor:pointer; font-size:13px; color:rgba(255,255,255,0.7); padding:0;">
                Log Keluar
            </button>
        </form>
    @endauth

    @guest
        <a href="/login" style="margin-left: auto;">Admin</a>
    @endguest
</nav>

<div class="dashboard-body">

    <div style="margin-bottom: 1rem;">
        <a href="/admin/dashboard" class="btn-back">← Kembali ke Dashboard</a>
    </div>

    <p class="section-heading">Senarai Permohonan Muat Naik Portal</p>

    <form method="GET" action="/admin/portal-upload">
        <div class="toolbar">
            <input type="text" name="search" placeholder="Cari nama, tajuk, jenis kandungan..." value="{{ request('search') }}">
            <select name="status">
                <option value="">-- Semua Status --</option>
                <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                <option value="Dalam Semakan" {{ request('status') == 'Dalam Semakan' ? 'selected' : '' }}>Dalam Semakan</option>
                <option value="Diluluskan" {{ request('status') == 'Diluluskan' ? 'selected' : '' }}>Diluluskan</option>
            </select>
            <button type="submit">Tapis</button>
            <a href="/admin/portal-upload" class="btn-reset">Set Semula</a>
            <button type="button" class="btn-delete" id="deleteBtn" onclick="submitDelete()" disabled>Padam</button>
        </div>
    </form>

    <form id="deleteForm" method="POST" action="{{ route('admin.portal-upload.delete') }}">
        @csrf
        <div id="deleteInputs"></div>
    </form>

    <table class="data-table">
        <tr>
            <th style="width:36px;"><input type="checkbox" id="checkAll" onclick="toggleAll(this)"></th>
            <th>Nama</th>
            <th>Tajuk</th>
            <th>Jenis Kandungan</th>
            <th>Pengemaskinian</th>
            <th>Tarikh Hantar</th>
            <th>Status</th>
            <th>Tindakan</th>
        </tr>
        @forelse($requests as $item)
        <tr>
            <td><input type="checkbox" class="row-check" value="{{ $item->id }}" onchange="updateDelete()"></td>
            <td>{{ $item->nama }}</td>
            <td class="td-truncate">{{ $item->tajuk_maklumat }}</td>
            <td>{{ $item->jenis_kandungan }}</td>
            <td>{{ $item->jenis_pengemaskinian }}</td>
            <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</td>
            <td>
                @if($item->status === 'Pending')
                    <span class="badge badge-pending">Pending</span>
                @elseif($item->status === 'Dalam Semakan')
                    <span class="badge badge-progress">Dalam Semakan</span>
                @else
                    <span class="badge badge-done">Diluluskan</span>
                @endif
            </td>
            <td>
                <button class="btn-view" onclick="openModal(
                    {{ $item->id }},
                    '{{ addslashes($item->nama) }}',
                    '{{ addslashes($item->jawatan ?? '-') }}',
                    '{{ addslashes($item->bahagian ?? '-') }}',
                    '{{ addslashes($item->telefon_email ?? '-') }}',
                    '{{ addslashes($item->tajuk_maklumat) }}',
                    '{{ addslashes($item->isi_kandungan ?? '-') }}',
                    '{{ addslashes($item->jenis_kandungan) }}',
                    '{{ addslashes($item->kandungan_lain ?? '-') }}',
                    '{{ addslashes($item->jenis_pengemaskinian) }}',
                    '{{ addslashes($item->pengemaskinian_lain ?? '-') }}',
                    '{{ $item->tarikh_mula ? \Carbon\Carbon::parse($item->tarikh_mula)->format('d/m/Y') : '-' }}',
                    '{{ $item->tarikh_akhir ? \Carbon\Carbon::parse($item->tarikh_akhir)->format('d/m/Y') : '-' }}',
                    {{ json_encode($item->status) }},
                    {{ json_encode($item->fail_path ? asset('storage/' . $item->fail_path) : '') }}                 )">Lihat</button>
            </td>
        </tr>
        @empty
        <tr><td colspan="8" style="text-align:center; color:#999; padding:1.5rem;">Tiada rekod ditemui.</td></tr>
        @endforelse
    </table>

    <div class="pagination-wrap">
        {{ $requests->links() }}
    </div>

</div>

<footer>
    <div><strong>Jabatan Hutan Sarawak</strong> &nbsp;|&nbsp; Wisma Sumber Alam, Petra Jaya, 93660 Kuching, Sarawak</div>
    <div>© 2025 Jabatan Hutan Sarawak. Hak Cipta Terpelihara.</div>
</footer>

<div class="modal-overlay" id="modalOverlay" onclick="closeOnOverlay(event)">
    <div class="modal">
        <div class="modal-header">
            <h2>Borang Permohonan Muat Naik Portal</h2>
            <button class="modal-close" onclick="closeModal()">×</button>
        </div>
        <div class="modal-body">
            <div class="detail-group">
                <div class="detail-section-label">Bahagian A — Maklumat Pemohon</div>
                <div class="detail-row">
                    <div class="detail-field"><label>Nama</label><p id="d-nama"></p></div>
                    <div class="detail-field"><label>Jawatan</label><p id="d-jawatan"></p></div>
                </div>
                <div class="detail-row">
                    <div class="detail-field"><label>Bahagian / Unit</label><p id="d-bahagian"></p></div>
                    <div class="detail-field"><label>No Telefon / Email</label><p id="d-telefon"></p></div>
                </div>
            </div>
            <div class="detail-group">
                <div class="detail-section-label">Bahagian B — Maklumat Bahan</div>
                <div class="detail-field" style="margin-bottom:0.6rem;">
                    <label>Tajuk Maklumat</label><p id="d-tajuk"></p>
                </div>
                <div class="detail-field" style="margin-bottom:0.6rem;">
                    <label>Isi Kandungan</label>
                    <p id="d-isi" style="white-space:pre-wrap;"></p>
                </div>
                <div class="detail-row">
                    <div class="detail-field"><label>Jenis Kandungan</label><p id="d-jenis"></p></div>
                    <div class="detail-field"><label>Kandungan Lain-lain</label><p id="d-klain"></p></div>
                </div>
                <div class="detail-row">
                    <div class="detail-field"><label>Jenis Pengemaskinian</label><p id="d-pengemaskinian"></p></div>
                    <div class="detail-field"><label>Pengemaskinian Lain-lain</label><p id="d-plain"></p></div>
                </div>
                <div class="detail-row">
                    <div class="detail-field"><label>Tarikh Mula Paparan</label><p id="d-mula"></p></div>
                    <div class="detail-field"><label>Tarikh Akhir Paparan</label><p id="d-akhir"></p></div>
                </div>
            </div>
            <div class="detail-group">
                <div class="detail-section-label">Bahagian C — Lampiran (Fail/Media)</div>
                <div id="file-preview-container" style="margin-top:10px; border:1px dashed #ccc; padding:10px; border-radius:8px; text-align:center;">
                    </div>
            </div>
            <div class="detail-group">
                <div class="detail-section-label">Status</div>
                <div id="d-status" style="margin-bottom:1rem;"></div>
                <div class="modal-actions" id="d-actions"></div>
            </div>
        </div>
    </div>
</div>

<form id="statusForm" method="POST" style="display:none;">
    @csrf
    <input type="hidden" name="status" id="statusInput">
</form>

<script>
var currentId = null;

function openModal(id, nama, jawatan, bahagian, telefon, tajuk, isi, jenis, klain, pengemaskinian, plain, mula, akhir, status, fileUrl) {
    currentId = id;
    document.getElementById('d-nama').textContent = nama;
    document.getElementById('d-jawatan').textContent = jawatan;
    document.getElementById('d-bahagian').textContent = bahagian;
    document.getElementById('d-telefon').textContent = telefon;
    document.getElementById('d-tajuk').textContent = tajuk;
    document.getElementById('d-isi').textContent = isi;
    document.getElementById('d-jenis').textContent = jenis;
    document.getElementById('d-klain').textContent = klain;
    document.getElementById('d-pengemaskinian').textContent = pengemaskinian;
    document.getElementById('d-plain').textContent = plain;
    document.getElementById('d-mula').textContent = mula;
    document.getElementById('d-akhir').textContent = akhir;

    const container = document.getElementById('file-preview-container');
        if (container) {
            container.innerHTML = ''; // Clear old preview

            // Check if fileUrl actually exists and isn't just a blank string
            if (fileUrl && fileUrl.trim().length > 10) { 
                // Get extension and make it lowercase to be safe
                const ext = fileUrl.split('.').pop().toLowerCase().split(/\?|#/)[0];

                // 1. IMAGES
                if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext)) {
                    container.innerHTML = `<img src="${fileUrl}" style="max-width:100%; border-radius:5px; border: 1px solid #ddd;">`;
                } 
                // 2. VIDEOS
                else if (['mp4', 'webm', 'mov'].includes(ext)) {
                    container.innerHTML = `<video controls style="width:100%; max-height:300px;"><source src="${fileUrl}"></video>`;
                }
                // 3. PDF
                else if (ext === 'pdf') {
                    container.innerHTML = `
                        <div style="width:100%;">
                            <iframe src="${fileUrl}" style="width:100%; height:450px; border:1px solid #ddd;"></iframe>
                            <div style="margin-top:10px;">
                                <a href="${fileUrl}" target="_blank" class="btn-view" style="text-decoration:none; display:inline-block;">
                                    ↗️ Buka PDF (Tab Baru)
                                </a>
                            </div>
                        </div>`;
                } 
                // 4. DOCS/OTHER
                else {
                    container.innerHTML = `
                        <div style="padding:20px; background:#f4f4f4; border-radius:8px;">
                            <p>Fail <strong>.${ext.toUpperCase()}</strong> telah dimuat naik.</p>
                            <a href="${fileUrl}" download class="btn-view" style="text-decoration:none; display:inline-block;">
                                Muat Turun Fail
                            </a>
                        </div>`;
                }
            } else {
                // This only shows if the link is truly empty
                container.innerHTML = '<p style="color:#999; padding: 20px;">Tiada lampiran disediakan.</p>';
            }
        }

    var badges = {
        'Pending':       '<span class="badge badge-pending">Pending</span>',
        'Dalam Semakan': '<span class="badge badge-progress">Dalam Semakan</span>',
        'Diluluskan':    '<span class="badge badge-done">Diluluskan</span>'
    };
    document.getElementById('d-status').innerHTML = badges[status] || status;

    var actions = document.getElementById('d-actions');
    actions.innerHTML = '';
    if (status !== 'Diluluskan') {
        actions.innerHTML =
            '<button class="btn-lulus" onclick="updateStatus(\'Diluluskan\')">Luluskan</button>' +
            '<button class="btn-tolak" onclick="updateStatus(\'Pending\')">Tolak</button>';
    }

    document.getElementById('modalOverlay').classList.add('active');

    if (status === 'Pending') {
        updateStatus('Dalam Semakan', false);
    }
}

function updateStatus(newStatus, reload = true) {
    var form = document.getElementById('statusForm');
    form.action = '/admin/portal-upload/' + currentId + '/status';
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