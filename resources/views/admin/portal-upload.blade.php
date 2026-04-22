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
    <div class="logo">🌿</div>
    <div>
        <h1>Jabatan Hutan Sarawak</h1>
        <p>Forest Department Sarawak — Sistem Perkhidmatan Dalaman</p>
    </div>
</header>

<x-navbar />

<div class="dashboard-body">

    <p class="section-heading">Senarai Permohonan Muat Naik Portal</p>

    <form method="GET" action="/admin/portal-upload">
        <div class="toolbar">
            <input type="text" name="search" placeholder="Cari nama, tajuk, bahagian..." value="{{ request('search') }}">
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
            <th>No. Tiket</th>
            <th>Nama</th>
            <th>Bahagian</th>
            <th>Tajuk</th>
            <th>Tarikh Hantar</th>
            <th>Status</th>
            <th>Tindakan</th>
        </tr>
        @forelse($requests as $item)
        <tr>
            <td><input type="checkbox" class="row-check" value="{{ $item->id }}" onchange="updateDelete()"></td>
            <td style="font-size:11px; color:#666;">{{ $item->no_tiket }}</td>
            <td>{{ $item->nama }}</td>
            <td>{{ $item->bahagian_nama ?? '-' }}</td>
            <td class="td-truncate">{{ $item->tajuk_maklumat }}</td>
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
            <td style="display:flex; gap:6px; flex-wrap:wrap;">
                <button class="btn-view" onclick="openModal(
                    {{ $item->id }},
                    '{{ addslashes($item->nama) }}',
                    '{{ addslashes($item->jawatan ?? '-') }}',
                    '{{ addslashes($item->bahagian_nama ?? '-') }}',
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
                    {{ json_encode($item->fail_paths ?? []) }}
                )">Lihat</button>

                @if($item->status === 'Dalam Semakan')
                    <form method="POST" action="{{ route('admin.portal-upload.resend', $item->id) }}" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn-view" style="background:#faeeda; color:#854f0b; border-color:#f5d5a0;" onclick="return confirm('Hantar semula emel kepada penyelia?')">Hantar Semula</button>
                    </form>
                @endif
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
                <div class="detail-section-label">Lampiran</div>
                <div id="file-preview-container" style="margin-top:8px;"></div>
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

function openModal(id, nama, jawatan, bahagian, telefon, tajuk, isi, jenis, klain, pengemaskinian, plain, mula, akhir, status, failPaths) {
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

    // handle multiple files
    var container = document.getElementById('file-preview-container');
    container.innerHTML = '';
    if (failPaths && failPaths.length > 0) {
        failPaths.forEach(function(path) {
            var url = '/storage/' + path;
            var ext = path.split('.').pop().toLowerCase();
            var wrapper = document.createElement('div');
            wrapper.style.marginBottom = '8px';

            if (['jpg','jpeg','png','gif','webp'].includes(ext)) {
                wrapper.innerHTML = '<img src="' + url + '" style="max-width:100%; border-radius:5px; border:1px solid #ddd;">';
            } else if (ext === 'pdf') {
                wrapper.innerHTML = '<a href="' + url + '" target="_blank" class="btn-view" style="display:inline-block; text-decoration:none;">↗ Buka PDF</a>';
            } else {
                var name = path.split('/').pop();
                wrapper.innerHTML = '<a href="' + url + '" download class="btn-view" style="display:inline-block; text-decoration:none;">⬇ ' + name + '</a>';
            }
            container.appendChild(wrapper);
        });
    } else {
        container.innerHTML = '<p style="color:#999; font-size:13px;">Tiada lampiran.</p>';
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