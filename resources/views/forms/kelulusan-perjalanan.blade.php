<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelulusan Perjalanan Pegawai</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link rel="icon" href="{{ asset('images/logo-icon.png')}}">
</head>
<body>
<x-header />
<x-navbar :breadcrumbs="[['label' => 'Kelulusan Perjalanan Pegawai']]" />
<div class="pg-body">
    <div class="form-card">
        <div class="form-card-header">
            <h2>KELULUSAN PERJALANAN PEGAWAI JABATAN HUTAN SARAWAK</h2>
            <p>(Sila kemukakan borang ini selewat-lewatnya 3 hari bekerja sebelum perjalanan)</p>
        </div>

        @if(!$bookingUser)
            <div class="form-section">
                <div class="alert alert-error">
                    pengguna hendaklah log masuk untuk isi borang ini
                </div>
            </div>
        @else

        <form action="/forms/kelulusan-perjalanan" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-section">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if($errors->any())
                    <div class="form-error-box alert alert-error">
                        <ul class="form-error-list">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @error('auth')
                    <div class="field-error">{{ $message }}</div>
                @enderror

                @error('profile')
                    <div class="field-error">{{ $message }}</div>
                @enderror

                @error('signature')
                    <div class="field-error">{{ $message }}</div>
                @enderror

                <div class="field">
                    <label>Bahagian / Unit <span class="required">*</span></label>
                    <input type="text" value="{{ $bookingUser->bahagian }}" readonly class="is-readonly-field">
                </div>

                <div class="field-row">
                    <div class="field">
                        <label>Nama Pegawai <span class="required">*</span></label>
                        <input type="text" value="{{ $bookingUser->name }}" readonly class="is-readonly-field">
                    </div>
                    <div class="field">
                        <label>Jawatan dan Gred <span class="required">*</span></label>
                        <input type="text" value="{{ $bookingUser->jawatan }}" readonly class="is-readonly-field">
                    </div>
                </div>

                <div class="field">
                    <label>Pegawai Lain Yang Turut Serta &amp; Gred (Jika Ada)</label>
                    <div id="pegawai-turut-serta-list" class="repeatable-list">
                        @php
                            $pegawaiList = old('pegawai_turut_serta', ['']);
                        @endphp
                        @foreach($pegawaiList as $item)
                            <div class="repeatable-row">
                                <input type="text" name="pegawai_turut_serta[]" value="{{ $item }}" placeholder="Nama dan gred pegawai">
                                <button type="button" class="repeatable-btn repeatable-remove" aria-label="Buang">-</button>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" id="add-pegawai-btn" class="repeatable-btn repeatable-add">+ Tambah Pegawai</button>
                </div>

                <div class="field-row">
                    <div class="field">
                        <label>Destinasi Perjalanan <span class="required">*</span></label>
                        <input type="text" name="destinasi_perjalanan" value="{{ old('destinasi_perjalanan') }}" placeholder="Destinasi perjalanan" required>
                    </div>
                    <div class="field">
                        <label>Tarikh Perjalanan <span class="required">*</span></label>
                        <input type="date" name="tarikh_perjalanan" value="{{ old('tarikh_perjalanan') }}" required>
                    </div>
                </div>

                <div class="field">
                    <label>Jenis Permohonan <span class="required">*</span></label>

                    @error('jenis_permohonan')
                        <div class="field-error">{{ $message }}</div>
                    @enderror

                    @error('jenis_kenderaan')
                        <div class="field-error">{{ $message }}</div>
                    @enderror

                    @php
                        $selectedJenis = old('jenis_kenderaan');
                    @endphp

                    <table class="jenis-table" role="presentation">
                        <tbody>
                            <tr>
                                <td class="jenis-main-cell">
                                    <label class="jenis-main-label">
                                        <input type="radio" name="jenis_kenderaan" value="kenderaan_sendiri" {{ $selectedJenis === 'kenderaan_sendiri' ? 'checked' : '' }}>
                                        <span>Menggunakan Kenderaan Sendiri</span>
                                    </label>
                                </td>
                                <td class="jenis-reason-cell">
                                    <div class="sub-options {{ $selectedJenis === 'kenderaan_sendiri' ? '' : 'is-hidden' }}" id="sebab-kenderaan-sendiri">
                                        <p>Sebab Permohonan:</p>
                                        <div class="checkbox-option">
                                            <label><input type="checkbox" class="sebab-checkbox" data-group="kenderaan-sendiri" name="sebab_kenderaan_sendiri" value="Tiada kemudahan kenderaan rasmi jabatan" {{ old('sebab_kenderaan_sendiri') === 'Tiada kemudahan kenderaan rasmi jabatan' ? 'checked' : '' }}> Tiada kemudahan kenderaan rasmi jabatan</label>
                                        </div>
                                        <div class="checkbox-option">
                                            <label><input type="checkbox" class="sebab-checkbox" data-group="kenderaan-sendiri" name="sebab_kenderaan_sendiri" value="Tiada perkhidmatan terus kapal terbang/lain pengangkutan" {{ old('sebab_kenderaan_sendiri') === 'Tiada perkhidmatan terus kapal terbang/lain pengangkutan' ? 'checked' : '' }}> Tiada perkhidmatan terus kapal terbang/lain pengangkutan</label>
                                        </div>
                                        <div class="checkbox-option">
                                            <label><input type="checkbox" class="sebab-checkbox" data-group="kenderaan-sendiri" name="sebab_kenderaan_sendiri" value="Memohon tambang gantian (jarak melebihi 240km)" {{ old('sebab_kenderaan_sendiri') === 'Memohon tambang gantian (jarak melebihi 240km)' ? 'checked' : '' }}> Memohon tambang gantian (jarak melebihi 240km)</label>
                                        </div>
                                        <div class="checkbox-option">
                                            <label><input type="checkbox" class="sebab-checkbox" data-group="kenderaan-sendiri" data-lain="true" name="sebab_kenderaan_sendiri" value="Lain-lain" {{ old('sebab_kenderaan_sendiri') === 'Lain-lain' ? 'checked' : '' }}> Lain-lain:</label>
                                            <input type="text" name="sebab_kenderaan_sendiri_lain" id="sebab_kenderaan_sendiri_lain" class="nyatakan-input {{ old('sebab_kenderaan_sendiri') === 'Lain-lain' ? '' : 'is-hidden' }}" value="{{ old('sebab_kenderaan_sendiri_lain') }}" placeholder="Nyatakan sebab...">
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="jenis-main-cell">
                                    <label class="jenis-main-label">
                                        <input type="radio" name="jenis_kenderaan" value="penerbangan_selain_air_borneo" {{ $selectedJenis === 'penerbangan_selain_air_borneo' ? 'checked' : '' }}>
                                        <span>Penerbangan Selain Air Borneo</span>
                                        <small>(Sila kepilkan dokumen sokongan)</small>
                                    </label>
                                </td>
                                <td class="jenis-reason-cell">
                                    <div class="sub-options {{ $selectedJenis === 'penerbangan_selain_air_borneo' ? '' : 'is-hidden' }}" id="sebab-penerbangan-lain">
                                        <p>Sebab Permohonan:</p>
                                        <div class="checkbox-option">
                                            <label><input type="checkbox" class="sebab-checkbox" data-group="penerbangan-lain" name="sebab_penerbangan_lain" value="Tiada Tempat Duduk" {{ old('sebab_penerbangan_lain') === 'Tiada Tempat Duduk' ? 'checked' : '' }}> Tiada Tempat Duduk</label>
                                        </div>
                                        <div class="checkbox-option">
                                            <label><input type="checkbox" class="sebab-checkbox" data-group="penerbangan-lain" name="sebab_penerbangan_lain" value="Jadual Tidak Sesuai" {{ old('sebab_penerbangan_lain') === 'Jadual Tidak Sesuai' ? 'checked' : '' }}> Jadual Tidak Sesuai</label>
                                        </div>
                                        <div class="checkbox-option">
                                            <label><input type="checkbox" class="sebab-checkbox" data-group="penerbangan-lain" name="sebab_penerbangan_lain" value="Kecemasan" {{ old('sebab_penerbangan_lain') === 'Kecemasan' ? 'checked' : '' }}> Kecemasan</label>
                                        </div>
                                        <div class="checkbox-option">
                                            <label><input type="checkbox" class="sebab-checkbox" data-group="penerbangan-lain" name="sebab_penerbangan_lain" value="Destinasi Tidak Disediakan" {{ old('sebab_penerbangan_lain') === 'Destinasi Tidak Disediakan' ? 'checked' : '' }}> Destinasi Tidak Disediakan</label>
                                        </div>
                                        <div class="checkbox-option">
                                            <label><input type="checkbox" class="sebab-checkbox" data-group="penerbangan-lain" data-lain="true" name="sebab_penerbangan_lain" value="Lain-lain" {{ old('sebab_penerbangan_lain') === 'Lain-lain' ? 'checked' : '' }}> Lain-lain:</label>
                                            <input type="text" name="sebab_penerbangan_lain_lain" id="sebab_penerbangan_lain_lain" class="nyatakan-input {{ old('sebab_penerbangan_lain') === 'Lain-lain' ? '' : 'is-hidden' }}" value="{{ old('sebab_penerbangan_lain_lain') }}" placeholder="Nyatakan sebab...">
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="field-row">
                    <div class="field">
                        <label>Dokumen Sokongan (Jika Ada)</label>
                        <input type="file" name="dokumen_sokongan" accept=".pdf,.png,.jpg,.jpeg">
                    </div>
                    <div class="field">
                        <label>No. Telefon <span class="required">*</span></label>
                        <input type="text" value="{{ $bookingUser->phone }}" readonly class="is-readonly-field">
                    </div>
                </div>

                <div class="field">
                    <label>Emel <span class="required">*</span></label>
                    <input type="email" value="{{ $bookingUser->email }}" readonly class="is-readonly-field">
                </div>
            </div>

            <div class="form-footer">
                <span></span>
                <button type="submit" class="btn-submit">Hantar Permohonan</button>
            </div>
        </form>

        <div class="form-section">
            <label>Sijil Digital Pegawai</label>
            @if($bookingUser->signature)
                <div class="digital-cert-box">
                    <img src="{{ asset('storage/' . $bookingUser->signature) }}" alt="Sijil digital {{ $bookingUser->name }}" class="digital-cert-image">
                </div>
            @else
                <div class="alert alert-error">
                    Sijil digital tidak dijumpai. Sila kemas kini tandatangan digital di profil booking anda.
                </div>
            @endif
        </div>
        @endif
    </div>
</div>

<script>
(function () {
    const list = document.getElementById('pegawai-turut-serta-list');
    const addBtn = document.getElementById('add-pegawai-btn');

    if (!list || !addBtn) {
        return;
    }

    function bindRemove(btn) {
        btn.addEventListener('click', function () {
            const rows = list.querySelectorAll('.repeatable-row');
            if (rows.length === 1) {
                rows[0].querySelector('input').value = '';
                return;
            }
            btn.closest('.repeatable-row').remove();
        });
    }

    list.querySelectorAll('.repeatable-remove').forEach(bindRemove);

    addBtn.addEventListener('click', function () {
        const row = document.createElement('div');
        row.className = 'repeatable-row';
        row.innerHTML = '<input type="text" name="pegawai_turut_serta[]" placeholder="Nama dan gred pegawai"><button type="button" class="repeatable-btn repeatable-remove" aria-label="Buang">-</button>';
        list.appendChild(row);
        bindRemove(row.querySelector('.repeatable-remove'));
        row.querySelector('input').focus();
    });

    // --- 7. Jenis Permohonan: main radio choice (kenderaan sendiri OR penerbangan) ---
    const jenisRadios = document.querySelectorAll('input[name="jenis_kenderaan"]');
    const sebabKenderaan = document.getElementById('sebab-kenderaan-sendiri');
    const sebabPenerbangan = document.getElementById('sebab-penerbangan-lain');

    const nyatakanInputs = {
        'kenderaan-sendiri': document.getElementById('sebab_kenderaan_sendiri_lain'),
        'penerbangan-lain': document.getElementById('sebab_penerbangan_lain_lain'),
    };

    function toggleNyatakan(group) {
        const lainChecked = document.querySelector(
            '.sebab-checkbox[data-group="' + group + '"][data-lain="true"]'
        )?.checked;
        const input = nyatakanInputs[group];
        if (!input) return;
        if (lainChecked) {
            input.classList.remove('is-hidden');
        } else {
            input.classList.add('is-hidden');
            input.value = '';
        }
    }

    function clearGroup(group) {
        document.querySelectorAll('.sebab-checkbox[data-group="' + group + '"]').forEach(cb => {
            cb.checked = false;
        });
        toggleNyatakan(group);
    }

    function showRelevantSection(selected) {
        if (selected === 'kenderaan_sendiri') {
            sebabKenderaan.classList.remove('is-hidden');
            sebabPenerbangan.classList.add('is-hidden');
        } else if (selected === 'penerbangan_selain_air_borneo') {
            sebabPenerbangan.classList.remove('is-hidden');
            sebabKenderaan.classList.add('is-hidden');
        } else {
            sebabKenderaan.classList.add('is-hidden');
            sebabPenerbangan.classList.add('is-hidden');
        }
    }

    jenisRadios.forEach(radio => radio.addEventListener('change', function () {
        showRelevantSection(this.value);
        // switching the main choice clears the OTHER group so a stale
        // answer from the option you're leaving can never be submitted
        if (this.value === 'kenderaan_sendiri') {
            clearGroup('penerbangan-lain');
        } else if (this.value === 'penerbangan_selain_air_borneo') {
            clearGroup('kenderaan-sendiri');
        }
    }));

    // initial render (respects old() input on validation-error redisplay)
    showRelevantSection(document.querySelector('input[name="jenis_kenderaan"]:checked')?.value);
    toggleNyatakan('kenderaan-sendiri');
    toggleNyatakan('penerbangan-lain');

    // --- Sebab Permohonan: checkbox look, but single-select per group ---
    document.querySelectorAll('.sebab-checkbox').forEach(cb => {
        cb.addEventListener('change', function () {
            const group = this.dataset.group;
            if (this.checked) {
                // enforce "only one at a time" within this group
                document.querySelectorAll('.sebab-checkbox[data-group="' + group + '"]').forEach(other => {
                    if (other !== this) other.checked = false;
                });
            }
            toggleNyatakan(group);
        });
    });
})();
</script>

<x-footer />
</body>
</html>