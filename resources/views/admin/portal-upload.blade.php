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

<x-navbar />

<div class="dashboard-body">

    @if(session('success'))
        <div style="background:#eaf3de; border:1px solid #c0dd97; color:#3b6d11; padding:0.75rem 1rem; border-radius:8px; margin-bottom:1.25rem; font-size:13px;">
            {{ session('success') }}
        </div>
    @endif

    {{-- Stats --}}
    <div class="stats-grid" style="margin-bottom:1.5rem;">
        <div class="stat-card sc-blue">
            <h2>{{ $stats['total'] }}</h2>
            <p>Jumlah Permohonan</p>
        </div>
        <div class="stat-card sc-orange">
            <h2>{{ $stats['pending'] }}</h2>
            <p>Menunggu Kelulusan</p>
        </div>
        <div class="stat-card" style="background:#4a7a8a; color:#fff; border-radius:10px; padding:1rem 1.25rem; text-align:center;">
            <h2 style="font-size:32px; font-weight:500; margin-bottom:4px;">{{ $stats['semakan'] }}</h2>
            <p style="font-size:12px; opacity:0.85;">Sedang Disemak</p>
        </div>
    </div>

    <p class="section-heading">Senarai Permohonan Muat Naik Portal</p>

    {{-- Filters --}}
    <form method="GET" action="/admin/portal-upload" style="margin-bottom:0.75rem;">
        <div class="toolbar">
            <input type="text" name="search" placeholder="Cari nama atau tajuk..." value="{{ request('search') }}">
            <select name="status">
                <option value="">-- Semua Status --</option>
                <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                <option value="Dalam Semakan" {{ request('status') == 'Dalam Semakan' ? 'selected' : '' }}>Dalam Semakan</option>
                <option value="Diluluskan" {{ request('status') == 'Diluluskan' ? 'selected' : '' }}>Diluluskan</option>
            </select>
            <select name="bahagian">
                <option value="">-- Semua Bahagian --</option>
                @foreach($bahagianList as $b)
                    <option value="{{ $b->nama_bahagian }}" {{ request('bahagian') == $b->nama_bahagian ? 'selected' : '' }}>
                        {{ $b->nama_bahagian }}
                    </option>
                @endforeach
            </select>
            <button type="submit" style="padding:7px 16px; background:#1a4731; color:#fff; border:none; border-radius:6px; font-size:13px; cursor:pointer;">Tapis</button>
            <a href="/admin/portal-upload" style="padding:7px 16px; background:#f0f0f0; color:#444; border-radius:6px; font-size:13px; text-decoration:none;">Set Semula</a>
        </div>
    </form>

    {{-- Action buttons --}}
    <div style="display:flex; gap:8px; margin-bottom:1rem; flex-wrap:wrap;">
        <button type="button" id="resendBtn" onclick="submitResend()" disabled
            style="padding:7px 16px; background:#e8f3fb; color:#185fa5; border:1px solid #b5d4f4; border-radius:6px; font-size:13px; transition:background 0.15s;">
            📨 Hantar Semula
        </button>
        <button type="button" class="btn-delete" id="deleteBtn" onclick="submitDelete()" disabled>
            🗑 Padam
        </button>
    </div>

    <form id="deleteForm" method="POST" action="{{ route('admin.portal-upload.delete') }}">
        @csrf
        <div id="deleteInputs"></div>
    </form>

    <form id="resendForm" method="POST" action="{{ route('admin.portal-upload.resend') }}">
        @csrf
        <div id="resendInputs"></div>
    </form>

    <table class="data-table">
        <tr>
            <th style="width:36px;"><input type="checkbox" id="checkAll" onclick="toggleAll(this)"></th>
            <th>No. Tiket</th>
            <th>Nama</th>
            <th>Bahagian</th>
            <th>Tajuk</th>
            <th>Tarikh Hantar</th>
            <th>Terakhir Dihantar</th>
            <th>Status</th>
            <th>Tindakan</th>
        </tr>
        @forelse($requests as $item)
        <tr>
            <td><input type="checkbox" class="row-check" value="{{ $item->id }}" data-status="{{ $item->status }}" onchange="updateButtons()"></td>
            <td style="font-size:11px; color:#666;">{{ $item->no_tiket }}</td>
            <td>{{ $item->nama }}</td>
            <td>{{ $item->bahagian_nama ?? '-' }}</td>
            <td class="td-truncate">{{ $item->tajuk_maklumat }}</td>
            <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</td>
            <td style="font-size:12px; color:#777;">
                @if($item->last_resent_at)
                    {{ \Carbon\Carbon::parse($item->last_resent_at)->diffForHumans() }}
                @else
                    -
                @endif
            </td>
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
                    '{{ addslashes($item->bahagian_nama ?? '-') }}',
                    '{{ addslashes($item->telefon_email ?? '-') }}',
                    '{{ addslashes($item->tajuk_maklumat) }}',
                    '{{ addslashes($item->isi_kandungan ?? '-') }}',
                    '{{ addslashes($item->jenis_kandungan) }}',
                    '{{ addslashes($item->kandungan_lain ?? '-') }}',
                    '{{ addslashes($item->jenis_pengemaskinian) }}',
                    '{{ addslashes($item->pengemaskinian_lain ?? '-') }}',
                    '{{ addslashes($item->catatan_semakan ?? '') }}',
                    '{{ $item->tarikh_mula ? \Carbon\Carbon::parse($item->tarikh_mula)->format('d/m/Y') : '-' }}',
                    '{{ $item->tarikh_akhir ? \Carbon\Carbon::parse($item->tarikh_akhir)->format('d/m/Y') : '-' }}',
                    {{ json_encode($item->status) }},
                    {{ json_encode($item->fail_paths ?? []) }}
                )">Lihat</button>
            </td>
        </tr>
        @empty
        <tr><td colspan="9" style="text-align:center; color:#999; padding:1.5rem;">Tiada rekod ditemui.</td></tr>
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
                <div id="d-status" style="margin-bottom:0.75rem;"></div>
                <div id="d-catatan-wrap" style="display:none;">
                    <label style="font-size:11px; color:#777; display:block; margin-bottom:4px;">Catatan Penyelia</label>
                    <div id="d-catatan" style="background:#f9fafb; border:1px solid #dde8e1; border-radius:8px; padding:0.75rem 1rem; font-size:13px; color:#333; line-height:1.6;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
var currentId = null;

function openModal(id, nama, jawatan, bahagian, telefon, tajuk, isi, jenis, klain, pengemaskinian, plain, catatan, mula, akhir, status, failPaths) {
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

    var catatanWrap = document.getElementById('d-catatan-wrap');
    if (catatan && catatan.trim().length > 0) {
        document.getElementById('d-catatan').textContent = catatan;
        catatanWrap.style.display = 'block';
    } else {
        catatanWrap.style.display = 'none';
    }

    document.getElementById('modalOverlay').classList.add('active');
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
    updateButtons();
}

function updateButtons() {
    var checked = document.querySelectorAll('.row-check:checked');
    var allChecks = document.querySelectorAll('.row-check');
    document.getElementById('deleteBtn').disabled = checked.length === 0;
    document.getElementById('checkAll').checked = checked.length === allChecks.length && allChecks.length > 0;

    var canResend = false;
    checked.forEach(function(cb) {
        if (cb.dataset.status === 'Dalam Semakan' || cb.dataset.status === 'Pending') canResend = true;
    });
    document.getElementById('resendBtn').disabled = !canResend;}

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

function submitResend() {
    if (!confirm('Hantar semula emel kepada penyelia yang dipilih?')) return;
    var container = document.getElementById('resendInputs');
    container.innerHTML = '';
    document.querySelectorAll('.row-check:checked').forEach(function(cb) {
        if (cb.dataset.status === 'Dalam Semakan' || cb.dataset.status === 'Pending') {
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids[]';
            input.value = cb.value;
            container.appendChild(input);
        }
    });
    document.getElementById('resendForm').submit();
}
</script>

</body>
</html>