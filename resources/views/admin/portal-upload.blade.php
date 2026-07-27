<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permohonan Muat Naik — Admin</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link rel="icon" href="{{ asset('images/logo-icon.png')}}">
</head>
<body>

<x-header />

<x-navbar :breadcrumbs="[['label' => 'Portal Muat Naik', 'url' => '/admin/portal-upload'], ['label' => 'Senarai Permohonan']]" />

<div class="dashboard-body">

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <p class="page-heading">Senarai permohonan yang telah dihantar.</p>

    {{-- Filters --}}
    <form method="GET" action="/admin/portal-upload">
        <div class="listing-toolbar">
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

            <button type="submit" class="table-btn table-btn-info">Tapis</button>
            <a href="/admin/portal-upload" class="table-btn table-btn-neutral">Set Semula</a>
        </div>
    </form>

    {{-- Action buttons --}}
    <div class="table-actions">
        <button type="button" class="table-btn table-btn-info" id="resendBtn" onclick="submitResend()" disabled>📨 Hantar Semula</button>
        <button type="button" class="table-btn table-btn-danger" id="deleteBtn" onclick="submitDelete()" disabled>🗑 Padam</button>
    </div>

    <form id="deleteForm" method="POST" action="{{ route('admin.portal-upload.delete') }}">
        @csrf
        <div id="deleteInputs"></div>
    </form>
    <form id="resendForm" method="POST" action="{{ route('admin.portal-upload.resend') }}">
        @csrf
        <div id="resendInputs"></div>
    </form>

    <div class="table-card">
        <div class="table-wrap">
            <table class="app-table">
                <tr>
                    <th class="check-col"><input type="checkbox" id="checkAll" onclick="toggleAll(this)"></th>
                    <th>No. Rujukan</th>
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
                    <td class="table-meta">{{ $item->no_tiket }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->bahagian_nama ?? '-' }}</td>
                    <td>{{ $item->tajuk_maklumat }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</td>
                    <td class="table-meta">
                        {{ $item->last_resent_at ? \Carbon\Carbon::parse($item->last_resent_at)->diffForHumans() : '-' }}
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
                        <div class="table-actions">
                            <button class="table-btn table-btn-info" onclick="openModal(
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
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="table-empty">Tiada rekod ditemui.</td></tr>
                @endforelse
            </table>
        </div>
    </div>

    <div class="table-pagination">{{ $requests->links() }}</div>

</div>

<x-footer />

{{-- Detail Modal --}}
<div class="ticket-modal-overlay" id="modalOverlay" onclick="closeOnOverlay(event)">
    <div class="ticket-modal">
        <div class="ticket-modal-header">
            <h3>Borang Permohonan Muat Naik Portal</h3>
            <button class="ticket-modal-close" onclick="closeModal()">&times;</button>
        </div>
        <div class="ticket-modal-body">

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

                <div class="detail-field detail-field-spaced">
                    <label>Tajuk Maklumat</label><p id="d-tajuk"></p>
                </div>

                <div class="detail-field detail-field-spaced">
                    <label>Isi Kandungan</label>
                    <p id="d-isi" class="text-pre-wrap"></p>
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
                <div id="file-preview-container" class="attachment-list"></div>
            </div>

            <div class="detail-group">
                <div class="detail-section-label">Status</div>
                <div id="d-status" class="status-display"></div>

                <div id="d-catatan-wrap" class="detail-group is-hidden">
                    <div class="detail-field">
                        <label>Catatan Penyelia</label>
                        <p id="d-catatan" class="text-pre-wrap"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
var currentId = null;

function openModal(id, nama, jawatan, bahagian, telefon, tajuk, isi, jenis, klain, pengemaskinian, plain, catatan, mula, akhir, status, failPaths) {
    currentId = id;
    document.getElementById('d-nama').textContent          = nama;
    document.getElementById('d-jawatan').textContent       = jawatan;
    document.getElementById('d-bahagian').textContent      = bahagian;
    document.getElementById('d-telefon').textContent       = telefon;
    document.getElementById('d-tajuk').textContent         = tajuk;
    document.getElementById('d-isi').textContent           = isi;
    document.getElementById('d-jenis').textContent         = jenis;
    document.getElementById('d-klain').textContent         = klain;
    document.getElementById('d-pengemaskinian').textContent = pengemaskinian;
    document.getElementById('d-plain').textContent         = plain;
    document.getElementById('d-mula').textContent          = mula;
    document.getElementById('d-akhir').textContent         = akhir;

    var container = document.getElementById('file-preview-container');
    container.innerHTML = '';
    if (failPaths && failPaths.length > 0) {
        failPaths.forEach(function(path) {
            var url = '/storage/' + path;
            var ext = path.split('.').pop().toLowerCase();
            if (['jpg','jpeg','png','gif','webp'].includes(ext)) {
                container.innerHTML += '<img class="attachment-image" src="' + url + '">';
            } else if (ext === 'pdf') {
                container.innerHTML += '<a class="attachment-item table-btn table-btn-info" href="' + url + '" target="_blank">↗ Buka PDF</a>';
            } else {
                var name = path.split('/').pop();
                container.innerHTML += '<a class="attachment-item table-btn table-btn-info" href="' + url + '" download>⬇ ' + name + '</a>';
            }
        });
    } else {
        container.innerHTML = '<span class="attachment-empty">Tiada lampiran.</span>';
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
        catatanWrap.classList.remove('is-hidden');
    } else {
        catatanWrap.classList.add('is-hidden');
    }

    document.getElementById('modalOverlay').classList.add('active');
}

function closeModal() {
    document.getElementById('modalOverlay').classList.remove('active');
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
    var checked   = document.querySelectorAll('.row-check:checked');
    var allChecks = document.querySelectorAll('.row-check');

    var deleteBtn = document.getElementById('deleteBtn');
    var resendBtn = document.getElementById('resendBtn');

    deleteBtn.disabled = checked.length === 0;

    document.getElementById('checkAll').checked = checked.length === allChecks.length && allChecks.length > 0;

    var canResend = false;
    checked.forEach(function(cb) {
        if (cb.dataset.status === 'Dalam Semakan' || cb.dataset.status === 'Pending') canResend = true;
    });
    resendBtn.disabled = !canResend;
}

function submitDelete() {
    if (!confirm('Padam rekod yang dipilih?')) return;
    var container = document.getElementById('deleteInputs');
    container.innerHTML = '';
    document.querySelectorAll('.row-check:checked').forEach(function(cb) {
        var input = document.createElement('input');
        input.type  = 'hidden';
        input.name  = 'ids[]';
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
            input.type  = 'hidden';
            input.name  = 'ids[]';
            input.value = cb.value;
            container.appendChild(input);
        }
    });
    document.getElementById('resendForm').submit();
}
</script>

</body>
</html>