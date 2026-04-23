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

<x-navbar />

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
            <th>Wilayah</th>
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
            <td>{{ $item->wilayah ?? '-' }}</td>
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
                <button class="btn-view"
                    data-id="{{ $item->id }}"
                    data-nama="{{ $item->nama }}"
                    data-jawatan="{{ $item->jawatan ?? '-' }}"
                    data-bahagian="{{ $item->bahagian ?? '-' }}"
                    data-wilayah="{{ $item->wilayah ?? '-' }}"
                    data-telefon="{{ $item->telefon ?? '-' }}"
                    data-tarikh="{{ \Carbon\Carbon::parse($item->tarikh_aduan)->format('d/m/Y') }}"
                    data-masa="{{ $item->masa_aduan }}"
                    data-kategori="{{ $item->kategori_masalah }}"
                    data-lain="{{ $item->masalah_lain ?? '-' }}"
                    data-keterangan="{{ $item->keterangan_kerosakan ?? '-' }}"
                    data-status="{{ $item->status }}"
                    data-attachments='@json($item->attachments ? json_decode($item->attachments, true) : [])'
                    onclick="openModalFromButton(this)">
                    Lihat
                </button>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="8" style="text-align:center; color:#999; padding:1.5rem;">
                Tiada rekod ditemui.
            </td>
        </tr>
        @endforelse
    </table>

    <div class="pagination-wrap">
        {{ $complaints->links() }}
    </div>

</div>

<footer>
    <div><strong>Jabatan Hutan Sarawak</strong> | Wisma Sumber Alam, Petra Jaya, 93660 Kuching, Sarawak</div>
    <div>© 2025 Jabatan Hutan Sarawak. Hak Cipta Terpelihara.</div>
</footer>

<!-- MODAL -->
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
                    <div class="detail-field"><label>Wilayah</label><p id="d-wilayah"></p></div>
                </div>

                <div class="detail-row">
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

                <div class="detail-field" style="margin-top:0.5rem;">
                    <label>Attachment</label>
                    <div id="d-attachments">-</div>
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

<!-- STATUS FORM -->
<form id="statusForm" method="POST" style="display:none;">
    @csrf
    <input type="hidden" name="status" id="statusInput">
</form>

<script>
var currentId = null;

function openModalFromButton(btn) {

    currentId = btn.dataset.id;

    document.getElementById('d-nama').textContent = btn.dataset.nama;
    document.getElementById('d-jawatan').textContent = btn.dataset.jawatan;
    document.getElementById('d-bahagian').textContent = btn.dataset.bahagian;
    document.getElementById('d-wilayah').textContent = btn.dataset.wilayah;
    document.getElementById('d-telefon').textContent = btn.dataset.telefon;
    document.getElementById('d-tarikh').textContent = btn.dataset.tarikh;
    document.getElementById('d-masa').textContent = btn.dataset.masa;
    document.getElementById('d-kategori').textContent = btn.dataset.kategori;
    document.getElementById('d-lain').textContent = btn.dataset.lain;
    document.getElementById('d-keterangan').textContent = btn.dataset.keterangan;

    // SAFE attachments parsing
    let attachments = [];
    try {
        attachments = JSON.parse(btn.dataset.attachments || "[]");
    } catch (e) {
        attachments = [];
    }

    let attBox = document.getElementById('d-attachments');
    attBox.innerHTML = '';

    if (attachments.length > 0) {
        attachments.forEach(file => {
            attBox.innerHTML +=
                `<a href="/storage/${file}" target="_blank">📎 ${file.split('/').pop()}</a><br>`;
        });
    } else {
        attBox.innerHTML = 'Tiada lampiran';
    }

    const status = btn.dataset.status;

    const badges = {
        'Belum Selesai': '<span class="badge badge-pending">Belum Selesai</span>',
        'Dalam Tindakan': '<span class="badge badge-progress">Dalam Tindakan</span>',
        'Selesai': '<span class="badge badge-done">Selesai</span>'
    };

    document.getElementById('d-status').innerHTML = badges[status] || status;

    document.getElementById('modalOverlay').classList.add('active');
}

function closeModal() {
    document.getElementById('modalOverlay').classList.remove('active');
}

function closeOnOverlay(e) {
    if (e.target === document.getElementById('modalOverlay')) closeModal();
}
</script>

</body>
</html>