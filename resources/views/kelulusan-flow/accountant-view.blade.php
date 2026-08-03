<!DOCTYPE html>
<html lang="ms">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Semakan Akauntan Kelulusan Perjalanan</title>
	<link rel="stylesheet" href="{{ asset('style.css') }}">
	<link rel="icon" href="{{ asset('images/logo-icon.png')}}">
</head>
<body>

<x-header />

<x-navbar :breadcrumbs="[['label' => 'Kelulusan Perjalanan', 'url' => route('kelulusan-flow.accountant-view')], ['label' => 'Senarai Akauntan']]" />

<div class="dashboard-body">
	<p class="page-heading">Senarai borang yang telah diluluskan HOD dan sedia untuk cetakan PDF.</p>

	<div class="table-card">
		<div class="table-wrap">
			<table class="app-table">
				<tr>
					<th>No. Rujukan</th>
					<th>Nama</th>
					<th>Bahagian</th>
					<th>Destinasi</th>
					<th>Tarikh Perjalanan</th>
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
						<td><span class="badge badge-done">Diluluskan</span></td>
						<td>
							<div class="table-actions">
								<button type="button" class="table-btn table-btn-info" onclick="openModal({{ $item->id }})">Lihat</button>
							</div>
						</td>
					</tr>
				@empty
					<tr>
						<td colspan="7" class="table-empty">Tiada borang diluluskan untuk cetakan.</td>
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
			<h3>Semakan Akauntan Borang Kelulusan Perjalanan</h3>
			<button class="ticket-modal-close" type="button" onclick="closeModal()">&times;</button>
		</div>

		<div class="ticket-modal-body">
			<div class="detail-group">
				<div class="detail-section-label">Maklumat Pemohon</div>
				<div class="detail-row">
					<div class="detail-field"><label>No. Rujukan</label><p id="a-no-tiket"></p></div>
					<div class="detail-field"><label>Tarikh Hantar</label><p id="a-created-at"></p></div>
				</div>
				<div class="detail-row">
					<div class="detail-field"><label>Nama</label><p id="a-nama"></p></div>
					<div class="detail-field"><label>Jawatan</label><p id="a-jawatan"></p></div>
				</div>
				<div class="detail-row">
					<div class="detail-field"><label>Bahagian</label><p id="a-bahagian"></p></div>
					<div class="detail-field"><label>No. Telefon</label><p id="a-telefon"></p></div>
				</div>
				<div class="detail-row">
					<div class="detail-field"><label>Emel</label><p id="a-emel"></p></div>
					<div class="detail-field"><label>Tarikh Perjalanan</label><p id="a-tarikh-perjalanan"></p></div>
				</div>
			</div>

			<div class="detail-group">
				<div class="detail-section-label">Butiran Perjalanan</div>
				<div class="detail-field detail-field-spaced">
					<label>Pegawai Turut Serta</label>
					<p id="a-pegawai" class="text-pre-wrap"></p>
				</div>
				<div class="detail-row">
					<div class="detail-field"><label>Destinasi</label><p id="a-destinasi"></p></div>
					<div class="detail-field"><label>Jenis Permohonan</label><p id="a-jenis"></p></div>
				</div>
				<div class="detail-field detail-field-spaced">
					<label>Lampiran</label>
					<div id="a-lampiran" class="attachment-list"></div>
				</div>
			</div>

			<div class="detail-group">
				<div class="detail-section-label">Jejak Kelulusan</div>
				<div class="detail-row">
					<div class="detail-field"><label>Status Penyelia</label><div id="a-supervisor-status" class="status-display"></div></div>
					<div class="detail-field"><label>Tarikh Penyelia</label><p id="a-supervisor-reviewed"></p></div>
				</div>
				<div class="detail-row">
					<div class="detail-field"><label>Status HOD</label><div id="a-hod-status" class="status-display"></div></div>
					<div class="detail-field"><label>Tarikh HOD</label><p id="a-hod-reviewed"></p></div>
				</div>
				<div class="detail-field detail-field-spaced">
					<label>Catatan HOD</label>
					<p id="a-hod-catatan" class="text-pre-wrap"></p>
				</div>
			</div>

			<div class="detail-group">
				<div class="detail-section-label">Sijil Digital</div>
				<div class="detail-row">
					<div class="detail-field"><label>Pemohon</label><div id="a-sign-user"></div></div>
					<div class="detail-field"><label>Penyelia</label><div id="a-sign-supervisor"></div></div>
				</div>
				<div class="detail-row">
					<div class="detail-field"><label>HOD</label><div id="a-sign-hod"></div></div>
				</div>
			</div>

			<div class="modal-actions">
				<button type="button" class="table-btn table-btn-success" onclick="printCurrentForm()">Cetak / Simpan PDF</button>
			</div>
		</div>
	</div>
</div>

<script>
const accountantData = @json($borangData);
let currentPrintItem = null;

function renderSignature(path, altText) {
	if (!path) {
		return '<div class="alert alert-error">Tiada sijil digital ditemui.</div>';
	}
	return '<div class="digital-cert-box"><img src="/storage/' + path + '" alt="' + altText + '" class="digital-cert-image"></div>';
}

function openModal(id) {
	const item = accountantData.find(row => row.id === id);
	if (!item) return;
	currentPrintItem = item;

	document.getElementById('a-no-tiket').textContent = item.no_tiket;
	document.getElementById('a-created-at').textContent = item.created_at;
	document.getElementById('a-nama').textContent = item.nama;
	document.getElementById('a-jawatan').textContent = item.jawatan || '-';
	document.getElementById('a-bahagian').textContent = item.bahagian || '-';
	document.getElementById('a-telefon').textContent = item.telefon || '-';
	document.getElementById('a-emel').textContent = item.emel || '-';
	document.getElementById('a-tarikh-perjalanan').textContent = item.tarikh_perjalanan;
	document.getElementById('a-pegawai').textContent = item.pegawai_turut_serta.length ? item.pegawai_turut_serta.join('\n') : '-';
	document.getElementById('a-destinasi').textContent = item.destinasi_perjalanan;
	document.getElementById('a-jenis').textContent = item.jenis_kenderaan;

	document.getElementById('a-supervisor-status').innerHTML = item.supervisor_status === 'Disokong'
		? '<span class="badge badge-done">Disokong</span>'
		: '<span class="badge badge-warning">Tidak disokong</span>';
	document.getElementById('a-supervisor-reviewed').textContent = item.supervisor_reviewed_at || '-';

	document.getElementById('a-hod-status').innerHTML = item.hod_status === 'Diluluskan'
		? '<span class="badge badge-done">Diluluskan</span>'
		: '<span class="badge badge-warning">Tidak diluluskan</span>';
	document.getElementById('a-hod-reviewed').textContent = item.hod_reviewed_at || '-';
	document.getElementById('a-hod-catatan').textContent = item.hod_catatan || '-';

	document.getElementById('a-sign-user').innerHTML = renderSignature(item.signature_path, 'Sijil digital pemohon');
	document.getElementById('a-sign-supervisor').innerHTML = renderSignature(item.supervisor_signature_path, 'Sijil digital penyelia');
	document.getElementById('a-sign-hod').innerHTML = renderSignature(item.hod_signature_path, 'Sijil digital HOD');

	const attachmentWrap = document.getElementById('a-lampiran');
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

	document.getElementById('modalOverlay').classList.add('active');
}

/**
 * Parses the stored jenis_kenderaan string (e.g. "Kenderaan Sendiri (Tiada
 * kemudahan kenderaan rasmi jabatan)" or "Penerbangan Selain Air Borneo
 * (Lain-lain: sebab tersendiri)") back into the a)/b) + i-iv/v checkbox
 * structure used on the official paper form.
 */
function parseJenisPermohonan(jenisStr) {
	const result = {
		kenderaanSendiri: false,
		penerbangan: false,
		reason: null,
		reasonLain: ''
	};
	if (!jenisStr) return result;

	const match = jenisStr.match(/^(.*?)\s*\(([^]*)\)\s*$/);
	const label = match ? match[1].trim() : jenisStr.trim();
	const reasonRaw = match ? match[2].trim() : '';

	if (label.startsWith('Kenderaan Sendiri')) {
		result.kenderaanSendiri = true;
	} else if (label.startsWith('Penerbangan')) {
		result.penerbangan = true;
	}

	if (reasonRaw.startsWith('Lain-lain:')) {
		result.reason = 'Lain-lain';
		result.reasonLain = reasonRaw.replace('Lain-lain:', '').trim();
	} else {
		result.reason = reasonRaw;
	}

	return result;
}

function box(checked) {
	return checked ? '&#9745;' : '&#9744;'; // ☑ / ☐
}

function printCurrentForm() {
	if (!currentPrintItem) return;

	const item = currentPrintItem;
	const win = window.open('', '_blank');
	if (!win) return;

	const jenis = parseJenisPermohonan(item.jenis_kenderaan);

	const kenderaanReasons = [
		'Tiada kemudahan kenderaan rasmi jabatan',
		'Tiada perkhidmatan terus kapal terbang/lain pengangkutan',
		'Memohon tambang gantian (jarak melebihi 240km)',
		'Lain-lain'
	];
	const penerbanganReasons = [
		'Tiada Tempat Duduk',
		'Jadual Tidak Sesuai',
		'Kecemasan',
		'Destinasi Tidak Disediakan',
		'Lain-lain'
	];

	const kenderaanRows = kenderaanReasons.map(function (reason, idx) {
		const checked = jenis.kenderaanSendiri && jenis.reason === reason;
		const label = reason === 'Lain-lain' && checked && jenis.reasonLain
			? 'Lain-lain: (nyatakan) ' + jenis.reasonLain
			: (reason === 'Lain-lain' ? 'Lain-lain: (nyatakan)' : reason);
		const numeral = ['i', 'ii', 'iii', 'iv'][idx];
		return '<div class="jenis-item"><span class="chk">' + box(checked) + '</span><span class="num">' + numeral + '.</span><span>' + label + '</span></div>';
	}).join('');

	const penerbanganRows = penerbanganReasons.map(function (reason, idx) {
		const checked = jenis.penerbangan && jenis.reason === reason;
		const label = reason === 'Lain-lain' && checked && jenis.reasonLain
			? 'Lain-lain: (nyatakan) ' + jenis.reasonLain
			: (reason === 'Lain-lain' ? 'Lain-lain: (nyatakan)' : reason);
		const numeral = ['i', 'ii', 'iii', 'iv', 'v'][idx];
		return '<div class="jenis-item"><span class="chk">' + box(checked) + '</span><span class="num">' + numeral + '.</span><span>' + label + '</span></div>';
	}).join('');

	// Pad the "pegawai turut serta" list out to the 6 numbered slots on the paper form.
	const pegawaiSlots = Array.from({ length: 6 }, function (_, i) {
		return item.pegawai_turut_serta[i] || '';
	});

	const supervisorDisokong = item.supervisor_status === 'Disokong';
	const hodDiluluskan = item.hod_status === 'Diluluskan';

	const sigUser = item.signature_path ? '/storage/' + item.signature_path : '';
	const sigSupervisor = item.supervisor_signature_path ? '/storage/' + item.supervisor_signature_path : '';
	const sigHod = item.hod_signature_path ? '/storage/' + item.hod_signature_path : '';

	win.document.write(`<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>${item.no_tiket}</title>
<style>
	@page { size: A4; margin: 16mm 14mm; }
	* { box-sizing: border-box; }
	body { font-family: 'Times New Roman', Times, serif; font-size: 12px; color: #000; margin: 0; padding: 0; }
	.ref-line { text-align: right; font-size: 11px; margin-bottom: 4px; }
	.form-title { text-align: center; font-weight: bold; font-size: 15px; text-transform: uppercase; margin: 6px 0 2px; }
	.form-subtitle { text-align: center; font-size: 11px; font-style: italic; margin-bottom: 12px; }
	.section-title { font-weight: bold; margin: 14px 0 6px; border-bottom: 1px solid #000; padding-bottom: 2px; }
	.field-line { display: flex; margin-bottom: 5px; }
	.field-num { width: 18px; flex-shrink: 0; }
	.field-label { width: 200px; flex-shrink: 0; }
	.field-colon { width: 14px; flex-shrink: 0; }
	.field-value { flex: 1; border-bottom: 1px dotted #444; min-height: 15px; }
	.pegawai-grid { display: grid; grid-template-columns: 1fr 1fr; column-gap: 24px; margin: 4px 0 8px 18px; }
	.pegawai-slot { display: flex; margin-bottom: 4px; }
	.pegawai-slot .num { width: 18px; }
	.pegawai-slot .value { flex: 1; border-bottom: 1px dotted #444; min-height: 14px; }
	.jenis-block { margin: 6px 0 10px; }
	.jenis-main { display: flex; align-items: baseline; gap: 6px; font-weight: bold; margin-bottom: 4px; }
	.jenis-note { font-weight: normal; font-style: italic; font-size: 10.5px; }
	.jenis-item { display: flex; gap: 6px; margin: 2px 0 2px 20px; }
	.jenis-item .num { width: 16px; }
	.chk { font-size: 13px; }
	.sign-block { display: flex; justify-content: space-between; margin-top: 14px; }
	.sign-col { width: 48%; }
	.sign-img { max-width: 200px; max-height: 70px; display: block; margin: 4px 0; }
	.sign-placeholder { border-bottom: 1px solid #000; height: 50px; margin: 4px 0; }
	.approval-line { display: flex; align-items: center; gap: 8px; margin: 4px 0; }
	.approval-line .chk { font-size: 14px; }
	.approval-note { font-size: 10.5px; }
	.catatan-box { border-bottom: 1px dotted #444; min-height: 16px; margin: 4px 0 4px 20px; }
	.notes { margin-top: 18px; font-size: 10px; }
	.notes p { margin: 2px 0; }
	.section-c-sign { text-align: center; margin-top: 20px; }
	.section-c-sign .line { border-bottom: 1px solid #000; width: 260px; margin: 30px auto 4px; }
	.section-c-sign .role { font-size: 10.5px; }
</style></head><body>

<div class="ref-line">RUJUKAN: ${item.no_tiket}</div>
<div class="form-title">Kelulusan Perjalanan Pegawai Jabatan Hutan Sarawak</div>
<div class="form-subtitle">(Sila kemukakan borang ini selewat-lewatnya 3 hari bekerja sebelum perjalanan)</div>

<div class="section-title">A. Dilengkapkan Oleh Pemohon</div>

<div class="field-line"><div class="field-num">1</div><div class="field-label">Bahagian/Unit/Seksyen</div><div class="field-colon">:</div><div class="field-value">${item.bahagian || ''}</div></div>
<div class="field-line"><div class="field-num">2</div><div class="field-label">Nama Pegawai</div><div class="field-colon">:</div><div class="field-value">${item.nama || ''}</div></div>
<div class="field-line"><div class="field-num">3</div><div class="field-label">Jawatan dan Gred</div><div class="field-colon">:</div><div class="field-value">${item.jawatan || ''}</div></div>

<div class="field-line"><div class="field-num">4</div><div class="field-label">Pegawai Lain Yang Turut Serta &amp; Gred</div><div class="field-colon">:</div><div class="field-value"></div></div>
<div class="pegawai-grid">
	${pegawaiSlots.map(function (v, i) {
		return '<div class="pegawai-slot"><span class="num">' + (i + 1) + '.</span><span class="value">' + v + '</span></div>';
	}).join('')}
</div>

<div class="field-line"><div class="field-num">5</div><div class="field-label">Destinasi Perjalanan</div><div class="field-colon">:</div><div class="field-value">${item.destinasi_perjalanan || ''}</div></div>
<div class="field-line"><div class="field-num">6</div><div class="field-label">Tarikh Perjalanan</div><div class="field-colon">:</div><div class="field-value">${item.tarikh_perjalanan || ''}</div></div>

<div class="field-line"><div class="field-num">7</div><div class="field-label">Jenis Permohonan (sila isi diruangan bertanda)</div></div>

<div class="jenis-block">
	<div class="jenis-main"><span class="chk">${box(jenis.kenderaanSendiri)}</span><span>a) Menggunakan Kenderaan Sendiri</span></div>
	${kenderaanRows}
</div>
<div class="jenis-block">
	<div class="jenis-main"><span class="chk">${box(jenis.penerbangan)}</span><span>b) Penerbangan Selain Air Borneo</span><span class="jenis-note">(sila kepilkan dokumen sokongan)</span></div>
	${penerbanganRows}
</div>

<div class="sign-block">
	<div class="sign-col">
		<div>Tarikh: ${item.created_at || ''}</div>
	</div>
	<div class="sign-col" style="text-align:center;">
		${sigUser ? '<img class="sign-img" src="' + sigUser + '">' : '<div class="sign-placeholder"></div>'}
		<div>Tandatangan Pemohon</div>
	</div>
</div>

<div class="section-title">B. Kegunaan Oleh Ketua Bahagian/Unit/Seksyen</div>
<div class="approval-line"><span class="chk">${box(supervisorDisokong)}</span><span>Disokong</span></div>
<div class="approval-line"><span class="chk">${box(item.supervisor_status === 'Tidak disokong')}</span><span>Tidak Disokong</span></div>

<div class="sign-block">
	<div class="sign-col">
		<div>Tarikh: ${item.supervisor_reviewed_at || ''}</div>
	</div>
	<div class="sign-col" style="text-align:center;">
		${sigSupervisor ? '<img class="sign-img" src="' + sigSupervisor + '">' : '<div class="sign-placeholder"></div>'}
		<div>(Tandatangan)</div>
		<div>Nama: ${item.supervisor_name || ''}</div>
		<div>Jawatan: ${item.supervisor_jawatan || ''}</div>
	</div>
</div>

<div class="section-title">C. Kegunaan Kelulusan</div>
<div class="approval-line"><span class="chk">${box(hodDiluluskan)}</span><span>Diluluskan</span></div>
<div class="approval-line"><span class="chk">${box(item.hod_status === 'Tidak diluluskan')}</span><span>Tidak Diluluskan (catatan)</span></div>
<div class="catatan-box">${!hodDiluluskan ? (item.hod_catatan || '') : ''}</div>

<div class="section-c-sign">
	${sigHod ? '<img class="sign-img" src="' + sigHod + '" style="margin:0 auto;">' : '<div class="line"></div>'}
	<div class="line"></div>
	<div>Tandatangan &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Tarikh: ${item.hod_reviewed_at || ''}</div>
	<div class="role">Pengarah/Timbalan Pengarah/Bahagian/Unit Pegawai Wilayah</div>
	<div>${item.hod_name || ''}${item.hod_jawatan ? ' - ' + item.hod_jawatan : ''}</div>
</div>

<div class="notes">
	<div><strong>Nota:</strong></div>
	<p>* bagi pegawai yang sepatutnya menggunakan satu kenderaan (car pool) tetapi hendak menggunakan kenderaan sendiri.</p>
	<p>* bagi pegawai yang menggunakan kenderaan sendiri (melebihi 240km) tertakluk arahan/kawalan dalaman semasa jabatan/pekeliling.</p>
	<p>* *Potong mana yang tidak berkenaan sekiranya ketiadaan Pengarah/telah diberi penurunan kuasa.</p>
</div>

</body></html>`);

	win.document.close();
	win.focus();
	win.print();
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