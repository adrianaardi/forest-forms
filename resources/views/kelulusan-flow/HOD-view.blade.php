<!DOCTYPE html>
<html lang="ms">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Semakan HOD Kelulusan Perjalanan</title>
	<link rel="stylesheet" href="{{ asset('style.css') }}">
	<link rel="icon" href="{{ asset('images/logo-icon.png')}}">
</head>
<body>

<x-header />

<x-navbar :breadcrumbs="[['label' => 'Kelulusan Perjalanan', 'url' => route('kelulusan-flow.hod-view')], ['label' => 'Senarai Semakan HOD']]" />

<div class="dashboard-body">
	@if(session('success'))
		<div class="alert alert-success">{{ session('success') }}</div>
	@endif

	@error('signature')
		<div class="alert alert-error">{{ $message }}</div>
	@enderror

	<p class="page-heading">Senarai borang yang telah disokong oleh penyelia untuk semakan HOD.</p>

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
							@if($item->status === 'Diluluskan')
								<span class="badge badge-done">Diluluskan</span>
							@elseif($item->status === 'Tidak diluluskan')
								<span class="badge badge-warning">Tidak diluluskan</span>
							@else
								<span class="badge badge-pending">Menunggu HOD</span>
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
						<td colspan="8" class="table-empty">Tiada borang untuk semakan HOD.</td>
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
			<h3>Semakan HOD Borang Kelulusan Perjalanan</h3>
			<button class="ticket-modal-close" type="button" onclick="closeModal()">&times;</button>
		</div>

		<div class="ticket-modal-body">
			<div class="detail-group">
				<div class="detail-section-label">Maklumat Pemohon</div>
				<div class="detail-row">
					<div class="detail-field"><label>No. Rujukan</label><p id="h-no-tiket"></p></div>
					<div class="detail-field"><label>Tarikh Hantar</label><p id="h-tarikh-hantar"></p></div>
				</div>
				<div class="detail-row">
					<div class="detail-field"><label>Nama Pegawai</label><p id="h-nama"></p></div>
					<div class="detail-field"><label>Jawatan dan Gred</label><p id="h-jawatan"></p></div>
				</div>
				<div class="detail-row">
					<div class="detail-field"><label>Bahagian / Unit</label><p id="h-bahagian"></p></div>
					<div class="detail-field"><label>No. Telefon</label><p id="h-telefon"></p></div>
				</div>
				<div class="detail-row">
					<div class="detail-field"><label>Emel</label><p id="h-emel"></p></div>
					<div class="detail-field"><label>Tarikh Perjalanan</label><p id="h-tarikh-perjalanan"></p></div>
				</div>
			</div>

			<div class="detail-group">
				<div class="detail-section-label">Butiran Perjalanan</div>
				<div class="detail-field detail-field-spaced">
					<label>Pegawai Lain Yang Turut Serta</label>
					<p id="h-pegawai" class="text-pre-wrap"></p>
				</div>
				<div class="detail-row">
					<div class="detail-field"><label>Destinasi</label><p id="h-destinasi"></p></div>
					<div class="detail-field"><label>Jenis Permohonan</label><p id="h-jenis"></p></div>
				</div>
				<div class="detail-field detail-field-spaced">
					<label>Lampiran</label>
					<div id="h-lampiran" class="attachment-list"></div>
				</div>
				<div class="detail-field detail-field-spaced">
					<label>Sijil Digital Pemohon</label>
					<div id="h-user-signature"></div>
				</div>
			</div>

			<div class="detail-group">
				<div class="detail-section-label">Semakan Penyelia</div>
				<div class="detail-row">
					<div class="detail-field"><label>Status Penyelia</label><div id="h-supervisor-status" class="status-display"></div></div>
					<div class="detail-field"><label>Tarikh Semakan Penyelia</label><p id="h-supervisor-reviewed-at">-</p></div>
				</div>
				<div class="detail-field detail-field-spaced">
					<label>Sijil Digital Penyelia</label>
					<div id="h-supervisor-signature"></div>
				</div>
			</div>

			<div class="detail-group">
				<div class="detail-section-label">Semakan HOD</div>
				<div class="detail-row">
					<div class="detail-field"><label>Status HOD</label><div id="h-status" class="status-display"></div></div>
					<div class="detail-field"><label>Tarikh Semakan HOD</label><p id="h-reviewed-at">-</p></div>
				</div>
				<div class="detail-field detail-field-spaced">
					<label>Catatan HOD</label>
					<p id="h-catatan" class="text-pre-wrap">-</p>
				</div>
				<div id="hodDecisionWrap" class="detail-group">
					<form id="hodDecisionForm" method="POST">
						@csrf
						<input type="hidden" name="keputusan" id="hod-keputusan">
						<div class="field">
							<label>Catatan <span class="required">*</span></label>
							<textarea name="catatan" id="hod-catatan" rows="3" placeholder="Nyatakan catatan keputusan..." required></textarea>
						</div>
						<div class="modal-actions">
							<button type="submit" class="table-btn table-btn-success" onclick="setHodDecision('Diluluskan')">Diluluskan</button>
							<button type="submit" class="table-btn table-btn-danger" onclick="setHodDecision('Tidak diluluskan')">Tidak diluluskan</button>
						</div>
					</form>
				</div>
			</div>

			<div class="detail-group">
				<div class="detail-section-label">Sijil HOD Semasa</div>
				@if($currentUserSignature)
					<div class="digital-cert-box">
						<img src="{{ asset('storage/' . $currentUserSignature) }}" alt="Sijil digital {{ $currentUserName }}" class="digital-cert-image">
					</div>
				@else
					<div class="alert alert-error">Sijil digital HOD tidak dijumpai.</div>
				@endif
			</div>
		</div>
	</div>
</div>

<script>
const hodBorangData = @json($borangData);

function openModal(id) {
	const item = hodBorangData.find(row => row.id === id);
	if (!item) return;

	document.getElementById('h-no-tiket').textContent = item.no_tiket;
	document.getElementById('h-tarikh-hantar').textContent = item.created_at;
	document.getElementById('h-nama').textContent = item.nama;
	document.getElementById('h-jawatan').textContent = item.jawatan || '-';
	document.getElementById('h-bahagian').textContent = item.bahagian || '-';
	document.getElementById('h-telefon').textContent = item.telefon || '-';
	document.getElementById('h-emel').textContent = item.emel || '-';
	document.getElementById('h-tarikh-perjalanan').textContent = item.tarikh_perjalanan;
	document.getElementById('h-pegawai').textContent = item.pegawai_turut_serta.length ? item.pegawai_turut_serta.join('\n') : '-';
	document.getElementById('h-destinasi').textContent = item.destinasi_perjalanan;
	document.getElementById('h-jenis').textContent = item.jenis_kenderaan;
	document.getElementById('h-supervisor-reviewed-at').textContent = item.supervisor_reviewed_at;
	document.getElementById('h-reviewed-at').textContent = item.hod_reviewed_at;

	const supervisorStatus = item.supervisor_status || 'Disokong';
	document.getElementById('h-supervisor-status').innerHTML = supervisorStatus === 'Disokong'
		? '<span class="badge badge-done">Disokong</span>'
		: '<span class="badge badge-warning">Tidak disokong</span>';

	const finalStatus = item.hod_status || item.status || 'Menunggu HOD';
	document.getElementById('h-status').innerHTML = finalStatus === 'Diluluskan'
		? '<span class="badge badge-done">Diluluskan</span>'
		: finalStatus === 'Tidak diluluskan'
			? '<span class="badge badge-warning">Tidak diluluskan</span>'
			: '<span class="badge badge-pending">Menunggu HOD</span>';

	document.getElementById('h-catatan').textContent = item.hod_catatan || '-';

	const supervisorSig = document.getElementById('h-supervisor-signature');
	supervisorSig.innerHTML = item.supervisor_signature_path
		? '<div class="digital-cert-box"><img src="/storage/' + item.supervisor_signature_path + '" alt="Sijil digital penyelia" class="digital-cert-image"></div>'
		: '<div class="alert alert-error">Tiada sijil digital penyelia ditemui.</div>';

	const userSig = document.getElementById('h-user-signature');
	userSig.innerHTML = item.signature_path
		? '<div class="digital-cert-box"><img src="/storage/' + item.signature_path + '" alt="Sijil digital pemohon" class="digital-cert-image"></div>'
		: '<div class="alert alert-error">Tiada sijil digital pemohon ditemui.</div>';

	const attachmentWrap = document.getElementById('h-lampiran');
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

	const form = document.getElementById('hodDecisionForm');
	form.action = '/kelulusan-flow/hod-view/' + item.id;
	document.getElementById('hod-keputusan').value = '';
	document.getElementById('hod-catatan').value = item.hod_catatan || '';

	document.getElementById('hodDecisionWrap').style.display = finalStatus === 'Menunggu HOD' ? 'block' : 'none';

	document.getElementById('modalOverlay').classList.add('active');
}

function setHodDecision(value) {
	document.getElementById('hod-keputusan').value = value;
}

function closeModal() {
	document.getElementById('modalOverlay').classList.remove('active');
}

function closeOnOverlay(event) {
	if (event.target === document.getElementById('modalOverlay')) {
		closeModal();
	}
}
</script>

</body>
</html>
