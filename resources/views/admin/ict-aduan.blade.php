<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aduan ICT — Admin</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link rel="icon" href="{{ asset('images/logo-icon.png')}}">
</head>
<body>

<x-header />

<x-navbar :breadcrumbs="[['label' => 'Aduan ICT', 'url' => '/admin/ict-aduan'], ['label' => 'Senarai Aduan']]" />

<div class="dashboard-body">

    <p class="page-heading">Senarai Aduan ICT</p>

    <form method="GET" action="/admin/ict-aduan">
        <div class="listing-toolbar">
            <input type="text" name="search" placeholder="Cari nama, bahagian, kategori..." value="{{ request('search') }}">

            <select name="status">
                <option value="">-- Semua Status --</option>
                <option value="Belum Selesai" {{ request('status') == 'Belum Selesai' ? 'selected' : '' }}>Belum Selesai</option>
                <option value="Dalam Tindakan" {{ request('status') == 'Dalam Tindakan' ? 'selected' : '' }}>Dalam Tindakan</option>
                <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="Tindakan Pembekal SAINS/Luar" {{ request('status') == 'Tindakan Pembekal SAINS/Luar' ? 'selected' : '' }}>Tindakan Pembekal SAINS/Luar</option>
                <option value="Tangguh/KIV" {{ request('status') == 'Tangguh/KIV' ? 'selected' : '' }}>Tangguh / KIV</option>
            </select>

            @if(Auth::user()->role === 'admin')
                <select name="wilayah_id">
                        <option value="">-- Pilih Wilayah --</option>
                        @foreach($wilayahs as $wilayah)
                            <option
                                value="{{ $wilayah->id }}"
                                {{ ((string) request('wilayah_id', request('wilayah')) === (string) $wilayah->id || request('wilayah') === $wilayah->nama_wilayah) ? 'selected' : '' }}>
                                {{ $wilayah->nama_wilayah }}
                            </option>
                        @endforeach
                    </select>
            @endif

            <button type="submit" class="table-btn table-btn-info">Tapis</button>
            <a href="/admin/ict-aduan" class="table-btn table-btn-neutral">Set Semula</a>
            <button type="button" class="table-btn table-btn-danger" id="deleteBtn" onclick="submitDelete()" disabled>Padam</button>
        </div>
    </form>

    <form id="deleteForm" method="POST" action="{{ route('admin.ict-aduan.delete') }}">
        @csrf
        <div id="deleteInputs"></div>
    </form>

    <div class="table-card">
        <div class="table-wrap">
            <table class="app-table">
                <tr>
                    <th class="check-col"><input type="checkbox" id="checkAll" onclick="toggleAll(this)"></th>
                    <th>No. Rujukan</th>
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
                    <td>{{ $item->nama_wilayah ?? $item->wilayah ?? '-' }}</td>
                    <td>{{ $item->kategori_masalah }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tarikh_aduan)->format('d/m/Y') }}</td>
                    <td>
                        @if($item->status === 'Belum Selesai')
                            <span class="badge badge-pending">Belum Selesai</span>
                        @elseif($item->status === 'Dalam Tindakan')
                            <span class="badge badge-progress">Dalam Tindakan</span>
                        @elseif($item->status === 'Tindakan Pembekal SAINS/Luar')
                            <span class="badge badge-warning">Tindakan Pembekal SAINS/Luar</span>
                        @elseif($item->status === 'Tangguh/KIV')
                            <span class="badge badge-kiv">Tangguh / KIV</span>
                        @elseif($item->status === 'Selesai')
                            <span class="badge badge-done">Selesai</span>
                        @else
                            <span class="badge">{{ $item->status }}</span>
                        @endif
                    </td>
                    <td>
                        <div class="table-actions">
                            <button class="table-btn table-btn-info"
                                data-id="{{ $item->id }}"
                                data-nama="{{ $item->nama }}"
                                data-jawatan="{{ $item->jawatan ?? '-' }}"
                                data-bahagian="{{ $item->bahagian ?? '-' }}"
                                data-wilayah="{{ $item->nama_wilayah ?? $item->wilayah ?? '-' }}"
                                data-telefon="{{ $item->telefon ?? '-' }}"
                                data-emel="{{ $item->emel ?? '-' }}"
                                data-tarikh="{{ \Carbon\Carbon::parse($item->tarikh_aduan)->format('d/m/Y') }}"
                                data-masa="{{ $item->masa_aduan }}"
                                data-kategori="{{ $item->kategori_masalah }}"
                                data-lain="{{ $item->masalah_lain ?? '-' }}"
                                data-keterangan="{{ $item->keterangan_kerosakan ?? '-' }}"
                                data-status="{{ $item->status }}"
                                data-nama_syarikat="{{ $item->nama_syarikat ?? '' }}"
                                data-no_tel_syarikat="{{ $item->no_tel_syarikat ?? '' }}"
                                data-tarikh_tindakan="{{ $item->tarikh_tindakan ?? '' }}"
                                data-tarikh_selesai="{{ $item->tarikh_selesai ?? '' }}"
                                data-catatan_pembekal="{{ $item->catatan_pembekal ?? '' }}"
                                data-remarks="{{ $item->remarks ?? '' }}"
                                data-attachments='@json($item->attachments ? json_decode($item->attachments, true) : [])'
                                onclick="openModalFromButton(this)">
                                Lihat
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="table-empty">Tiada rekod ditemui.</td>
                </tr>
                @endforelse
            </table>
        </div>
    </div>

    <div class="table-pagination">
        {{ $complaints->links() }}
    </div>

</div>

<x-footer />

<!-- MODAL -->
<div class="ticket-modal-overlay" id="modalOverlay" onclick="closeOnOverlay(event)">
    <div class="ticket-modal">
        <div class="ticket-modal-header">
            <h3>Borang Aduan Baikpulih ICT / Digital</h3>
            <button class="ticket-modal-close" onclick="closeModal()">&times;</button>
        </div>

        <div class="ticket-modal-body">

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
                    <div class="detail-field"><label>Email</label><p id="d-emel"></p></div>
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

                <div class="detail-field detail-field-spaced">
                    <label>Keterangan Kerosakan</label>
                    <p id="d-keterangan" class="text-pre-wrap"></p>
                </div>

                <div class="detail-field detail-field-spaced">
                    <label>Attachment</label>
                    <div id="d-attachments" class="attachment-list">-</div>
                </div>
            </div>

            <div class="detail-group">
                <div class="detail-section-label">Bahagian C — Tindakan / Penyelesaian</div>

                <!-- CURRENT STATUS -->
                <div id="d-status" class="status-display"></div>

                <!-- FORM -->
                <form id="updateStatusForm" method="POST">
                    @csrf

                    <input type="hidden" name="id" id="complaintId">

                    <!-- STATUS -->
                    <div class="detail-row">
                        <div class="field full-width-field">
                            <label>Status Tindakan</label>
                            <select name="status" id="statusSelect" onchange="toggleSupplierSection()" required>
                                <option value="">-- Pilih Status --</option>
                                <option value="Dalam Tindakan">Dalam Tindakan</option>
                                <option value="Selesai">Selesai</option>
                                <option value="Tindakan Pembekal SAINS/Luar">Tindakan Pembekal SAINS/Luar</option>
                                <option value="Tangguh/KIV">Tangguh/KIV</option>
                            </select>
                        </div>
                    </div>

                    <!-- PREVIOUS REMARKS -->
                    <div class="detail-field">
                        <label>Catatan Sebelumnya</label>
                        <p id="d-remarks">-</p>
                    </div>

                    <!-- REMARKS -->
                    <div class="field full-width-field">
                        <label>Catatan / Remarks</label>
                        <textarea name="remarks" rows="3" placeholder="Masukkan catatan..."></textarea>
                    </div>

                    <!-- SUPPLIER SECTION -->
                    <div id="supplierSection" class="detail-group supplier-section is-hidden">

                        <div class="detail-section-label">Maklumat Pembekal</div>

                        <div class="field-row">
                            <div class="field">
                                <label>Nama Syarikat</label>
                                <input type="text" name="nama_syarikat">
                            </div>

                            <div class="field">
                                <label>No Telefon</label>
                                <input type="text" name="no_tel_syarikat">
                            </div>
                        </div>

                        <div class="field-row">
                            <div class="field">
                                <label>Tarikh Tindakan</label>
                                <input type="date" name="tarikh_tindakan">
                            </div>

                            <div class="field">
                                <label>Tarikh Selesai</label>
                                <input type="date" name="tarikh_selesai">
                            </div>
                        </div>

                        <div class="field">
                            <label>Catatan Pembekal</label>
                            <input type="text" name="catatan_pembekal">
                        </div>

                    </div>

                    <!-- SUBMIT -->
                    <div class="modal-actions">
                        <button type="submit" class="btn-submit">Simpan Status</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<!-- STATUS FORM -->
<form id="statusForm" method="POST" class="is-hidden">
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
    document.getElementById('d-emel').textContent = btn.dataset.emel;
    document.getElementById('d-tarikh').textContent = btn.dataset.tarikh;
    document.getElementById('d-masa').textContent = btn.dataset.masa;
    document.getElementById('d-kategori').textContent = btn.dataset.kategori;
    document.getElementById('d-lain').textContent = btn.dataset.lain;
    document.getElementById('d-keterangan').textContent = btn.dataset.keterangan;

    // SET FORM ACTION + ID
    document.getElementById('updateStatusForm').action = `/admin/ict-aduan/${currentId}/status`;
    document.getElementById('complaintId').value = currentId;

    document.querySelector('input[name="nama_syarikat"]').value = btn.dataset.nama_syarikat || '';
    document.querySelector('input[name="no_tel_syarikat"]').value = btn.dataset.no_tel_syarikat || '';
    document.querySelector('input[name="tarikh_tindakan"]').value = btn.dataset.tarikh_tindakan || '';
    document.querySelector('input[name="tarikh_selesai"]').value = btn.dataset.tarikh_selesai || '';
    document.querySelector('input[name="catatan_pembekal"]').value = btn.dataset.catatan_pembekal || '';
    document.querySelector('textarea[name="remarks"]').value = btn.dataset.remarks || '';
    document.getElementById('d-remarks').textContent = btn.dataset.remarks || '-';

    // reset form
    document.getElementById('statusSelect').value = '';
    document.getElementById('supplierSection').classList.add('is-hidden');

    if (btn.dataset.status === 'Tindakan Pembekal SAINS/Luar') {
        document.getElementById('supplierSection').classList.remove('is-hidden');
        document.getElementById('statusSelect').value = 'Tindakan Pembekal SAINS/Luar';
    }

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
                `<a class="attachment-item" href="/storage/${file}" target="_blank">📎 ${file.split('/').pop()}</a>`;
        });
    } else {
        attBox.innerHTML = '<span class="attachment-empty">Tiada lampiran</span>';
    }

    const status = btn.dataset.status;

    const badges = {
        'Belum Selesai': '<span class="badge badge-pending">Belum Selesai</span>',
        'Dalam Tindakan': '<span class="badge badge-progress">Dalam Tindakan</span>',
        'Tindakan Pembekal SAINS/Luar': '<span class="badge badge-warning">Tindakan Pembekal SAINS/Luar</span>',
        'Tangguh/KIV': '<span class="badge badge-kiv">Tangguh / KIV</span>',
        'Selesai': '<span class="badge badge-done">Selesai</span>'
    };

    document.getElementById('d-status').innerHTML = badges[status] || status;

    document.getElementById('modalOverlay').classList.add('active');
}

function toggleSupplierSection() {
    const status = document.getElementById('statusSelect').value;
    const section = document.getElementById('supplierSection');

    if (status === 'Tindakan Pembekal SAINS/Luar') {
        section.classList.remove('is-hidden');
    } else {
        section.classList.add('is-hidden');
    }
}

function closeModal() {
    document.getElementById('modalOverlay').classList.remove('active');
}

function closeOnOverlay(e) {
    if (e.target === document.getElementById('modalOverlay')) closeModal();
}

// 1. Handles the main header checkbox (Check/Uncheck all rows)
function toggleAll(masterCheckbox) {
    const rowCheckboxes = document.querySelectorAll('.row-check');

    rowCheckboxes.forEach(checkbox => {
        checkbox.checked = masterCheckbox.checked;
    });

    updateDelete();
}

// 2. Checks if any checkbox is active and updates the "Padam" button status
function updateDelete() {
    const rowCheckboxes = document.querySelectorAll('.row-check');
    const deleteBtn = document.getElementById('deleteBtn');
    const deleteInputsContainer = document.getElementById('deleteInputs');

    deleteInputsContainer.innerHTML = '';

    let anyChecked = false;

    rowCheckboxes.forEach(checkbox => {
        if (checkbox.checked) {
            anyChecked = true;

            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'ids[]';
            hiddenInput.value = checkbox.value;
            deleteInputsContainer.appendChild(hiddenInput);
        }
    });

    deleteBtn.disabled = !anyChecked;
}

// 3. Handles clicking the actual "Padam" button
function submitDelete() {
    if (confirm("Adakah anda pasti mahu memadam item yang dipilih?")) {
        document.getElementById('deleteForm').submit();
    }
}
</script>

</body>
</html>