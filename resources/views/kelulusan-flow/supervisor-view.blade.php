<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semakan Kelulusan Perjalanan</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link rel="icon" href="{{ asset('images/logo-icon.png')}}">
</head>
<body>

<x-header />

<x-navbar :breadcrumbs="[['label' => 'Kelulusan Perjalanan', 'url' => route('kelulusan-flow.supervisor-view')], ['label' => 'Senarai Permohonan']]" />

<div class="dashboard-body">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @error('signature')
        <div class="alert alert-error">{{ $message }}</div>
    @enderror

    <p class="page-heading">Senarai borang kelulusan perjalanan yang dihantar untuk semakan.</p>

    <div class="table-card">
        <div class="table-wrap">
            <table class="app-table">
                <tr>
                    <th>No. Rujukan</th>
                    <th>Nama</th>
                    <th>Bahagian</th>
                    <th>Destinasi</th>
                    <th>Tarikh Perjalanan</th>
                    <th>Tarikh Hantar</th>
                    <th>Status</th>
                    <th>Tindakan</th>
                </tr>

                @forelse($borang as $item)
                    <tr>
                        <td class="table-meta">{{ $item->no_tiket }}</td>
                        <td>{{ $item->nama }}</td>
                        <td>{{ $item->bahagian }}</td>
                        <td>{{ $item->destinasi_perjalanan }}</td>
                        <td class="table-meta">{{ \Carbon\Carbon::parse($item->tarikh_perjalanan)->format('d/m/Y') }}</td>
                        <td class="table-meta">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</td>
                        <td>
                            @if($item->status === 'Disokong')
                                <span class="badge badge-done">Disokong</span>
                            @elseif($item->status === 'Menunggu HOD')
                                <span class="badge badge-progress">Menunggu HOD</span>
                            @elseif($item->status === 'Tidak disokong')
                                <span class="badge badge-warning">Tidak disokong</span>
                            @else
                                <span class="badge badge-pending">Pending</span>
                            @endif
                        </td>
                        <td>
                            <div class="table-actions">
                                <button type="button" class="table-btn table-btn-info" onclick="openModal({{ $item->id }})">Lihat</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="table-empty">Tiada borang dihantar.</td>
                    </tr>
                @endforelse
            </table>
        </div>
    </div>
</div>

<x-footer />

<div class="ticket-modal-overlay" id="modalOverlay" onclick="closeOnOverlay(event)">
    <div class="ticket-modal ticket-modal-wide">
        <div class="ticket-modal-header">
            <h3>Semakan Borang Kelulusan Perjalanan</h3>
            <button class="ticket-modal-close" type="button" onclick="closeModal()">&times;</button>
        </div>

        <div class="ticket-modal-body">
            <div class="detail-group">
                <div class="detail-section-label">Maklumat Pemohon</div>
                <div class="detail-row">
                    <div class="detail-field"><label>No. Rujukan</label><p id="d-no-tiket"></p></div>
                    <div class="detail-field"><label>Tarikh Hantar</label><p id="d-tarikh-hantar"></p></div>
                </div>
                <div class="detail-row">
                    <div class="detail-field"><label>Nama Pegawai</label><p id="d-nama"></p></div>
                    <div class="detail-field"><label>Jawatan dan Gred</label><p id="d-jawatan"></p></div>
                </div>
                <div class="detail-row">
                    <div class="detail-field"><label>Bahagian / Unit</label><p id="d-bahagian"></p></div>
                    <div class="detail-field"><label>No. Telefon</label><p id="d-telefon"></p></div>
                </div>
                <div class="detail-row">
                    <div class="detail-field"><label>Emel</label><p id="d-emel"></p></div>
                    <div class="detail-field"><label>Tarikh Perjalanan</label><p id="d-tarikh-perjalanan"></p></div>
                </div>
            </div>

            <div class="detail-group">
                <div class="detail-section-label">Butiran Perjalanan</div>
                <div class="detail-field detail-field-spaced">
                    <label>Pegawai Lain Yang Turut Serta</label>
                    <p id="d-pegawai" class="text-pre-wrap"></p>
                </div>
                <div class="detail-row">
                    <div class="detail-field"><label>Destinasi</label><p id="d-destinasi"></p></div>
                    <div class="detail-field"><label>Jenis Permohonan</label><p id="d-jenis"></p></div>
                </div>
                <div class="detail-field detail-field-spaced">
                    <label>Lampiran</label>
                    <div id="d-lampiran" class="attachment-list"></div>
                </div>
                <div class="detail-field detail-field-spaced">
                    <label>Sijil Digital Pemohon</label>
                    <div id="d-user-signature"></div>
                </div>
            </div>

            <div class="detail-group">
                <div class="detail-section-label">Sijil Penyelia Semasa</div>
                @if(isset($currentUserSignature) && $currentUserSignature)
                    <div class="digital-cert-box">
                        <img src="{{ asset('storage/' . $currentUserSignature) }}" alt="Sijil digital {{ $currentUserName ?? '' }}" class="digital-cert-image">
                    </div>
                @else
                    <div class="alert alert-error">Sijil digital penyelia tidak dijumpai.</div>
                @endif
            </div>

            <div id="decisionWrap" class="detail-group">
                <form id="decisionForm" method="POST">
                    @csrf
                    <input type="hidden" name="keputusan" id="keputusan">
                    <div class="modal-actions">
                        <button type="submit" class="table-btn table-btn-success" onclick="setDecision('Disokong')">Disokong</button>
                        <button type="submit" class="table-btn table-btn-danger" onclick="setDecision('Tidak disokong')">Tidak disokong</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
const borangData = @json($borangData);

function setText(id, text) {
    const el = document.getElementById(id);
    if (el) el.textContent = text ?? '-';
}

function setHTML(id, html) {
    const el = document.getElementById(id);
    if (el) el.innerHTML = html;
}

function openModal(id) {
    const item = borangData.find(row => row.id === id);
    if (!item) return;

    setText('d-no-tiket', item.no_tiket);
    setText('d-tarikh-hantar', item.created_at);
    setText('d-nama', item.nama);
    setText('d-jawatan', item.jawatan || '-');
    setText('d-bahagian', item.bahagian || '-');
    setText('d-telefon', item.telefon || '-');
    setText('d-emel', item.emel || '-');
    setText('d-tarikh-perjalanan', item.tarikh_perjalanan);
    setText('d-pegawai', item.pegawai_turut_serta && item.pegawai_turut_serta.length ? item.pegawai_turut_serta.join('\n') : '-');
    setText('d-destinasi', item.destinasi_perjalanan);
    setText('d-jenis', item.jenis_kenderaan);

    setHTML('d-user-signature', item.signature_path
        ? '<div class="digital-cert-box"><img src="/storage/' + item.signature_path + '" alt="Sijil digital pemohon" class="digital-cert-image"></div>'
        : '<div class="alert alert-error">Tiada sijil digital pemohon ditemui.</div>');

    const attachmentWrap = document.getElementById('d-lampiran');
    if (attachmentWrap) {
        attachmentWrap.innerHTML = '';
        if (item.attachments && item.attachments.length) {
            item.attachments.forEach(function (path) {
                const ext = path.split('.').pop().toLowerCase();
                const url = '/storage/' + path;
                if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext)) {
                    attachmentWrap.innerHTML += '<a href="' + url + '" target="_blank"><img class="attachment-image" src="' + url + '" alt="Lampiran"></a>';
                } else {
                    attachmentWrap.innerHTML += '<a class="attachment-item table-btn table-btn-info" href="' + url + '" target="_blank">' + path.split('/').pop() + '</a>';
                }
            });
        } else {
            attachmentWrap.innerHTML = '<span class="attachment-empty">Tiada lampiran.</span>';
        }
    }

    const form = document.getElementById('decisionForm');
    if (form) {
        form.action = '/kelulusan-flow/supervisor-view/' + item.id;
    }
    
    const keputusanInput = document.getElementById('keputusan');
    if (keputusanInput) {
        keputusanInput.value = '';
    }

    const statusLabel = item.hod_status || item.status || 'Pending';
    const supervisorStatus = item.supervisor_status || 'Pending';

    const decisionWrap = document.getElementById('decisionWrap');
    if (decisionWrap) {
        decisionWrap.style.display = (statusLabel === 'Pending' || supervisorStatus === 'Pending') ? 'block' : 'none';
    }

    const modalOverlay = document.getElementById('modalOverlay');
    if (modalOverlay) {
        modalOverlay.classList.add('active');
    }
}

function setDecision(value) {
    const input = document.getElementById('keputusan');
    if (input) input.value = value;
}

function closeModal() {
    const modalOverlay = document.getElementById('modalOverlay');
    if (modalOverlay) modalOverlay.classList.remove('active');
}

function closeOnOverlay(event) {
    if (event.target === document.getElementById('modalOverlay')) {
        closeModal();
    }
}
</script>

</body>
</html>