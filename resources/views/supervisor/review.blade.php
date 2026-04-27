<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semakan Permohonan — Jabatan Hutan Sarawak</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link rel="icon" href="{{ asset('images/logo-icon.png')}}">

</head>
<body>

<header>
    <div class="logo"></div>
    <div>
        <h1>Jabatan Hutan Sarawak</h1>
        <p>Forest Department Sarawak — Semakan Permohonan Muat Naik</p>
    </div>
</header>

<div class="pg-body">
    <div class="form-card">
        <div class="form-card-header">
            <h2>Semakan Permohonan Muat Naik Portal</h2>
            <p>Sila semak butiran permohonan dan berikan kelulusan anda.</p>
        </div>

        <div class="form-section">
            <div class="section-label">Maklumat Pemohon</div>
            <div class="field-row">
                <div class="field">
                    <label>No. Tiket</label>
                    <input type="text" value="{{ $permohonan->no_tiket }}" disabled>
                </div>
                <div class="field">
                    <label>Tarikh Hantar</label>
                    <input type="text" value="{{ \Carbon\Carbon::parse($permohonan->created_at)->format('d/m/Y H:i') }}" disabled>
                </div>
            </div>
            <div class="field-row">
                <div class="field">
                    <label>Nama</label>
                    <input type="text" value="{{ $permohonan->nama }}" disabled>
                </div>
                <div class="field">
                    <label>Jawatan</label>
                    <input type="text" value="{{ $permohonan->jawatan ?? '-' }}" disabled>
                </div>
            </div>
            <div class="field-row">
                <div class="field">
                    <label>Bahagian / Unit</label>
                    <input type="text" value="{{ $permohonan->bahagian_nama }}" disabled>
                </div>
                <div class="field">
                    <label>Email</label>
                    <input type="text" value="{{ $permohonan->telefon_email ?? '-' }}" disabled>
                </div>
            </div>
        </div>

        <div class="form-section">
            <div class="section-label">Maklumat Bahan</div>
            <div class="field">
                <label>Tajuk Maklumat</label>
                <input type="text" value="{{ $permohonan->tajuk_maklumat }}" disabled>
            </div>
            <div class="field">
                <label>Isi Kandungan</label>
                <textarea rows="3" disabled>{{ $permohonan->isi_kandungan ?? '-' }}</textarea>
            </div>
            <div class="field-row">
                <div class="field">
                    <label>Jenis Kandungan</label>
                    <input type="text" value="{{ $permohonan->jenis_kandungan }}" disabled>
                </div>
                <div class="field">
                    <label>Jenis Pengemaskinian</label>
                    <input type="text" value="{{ $permohonan->jenis_pengemaskinian }}" disabled>
                </div>
            </div>
            <div class="field-row">
                <div class="field">
                    <label>Tarikh Mula Paparan</label>
                    <input type="text" value="{{ $permohonan->tarikh_mula ? \Carbon\Carbon::parse($permohonan->tarikh_mula)->format('d/m/Y') : '-' }}" disabled>
                </div>
                <div class="field">
                    <label>Tarikh Akhir Paparan</label>
                    <input type="text" value="{{ $permohonan->tarikh_akhir ? \Carbon\Carbon::parse($permohonan->tarikh_akhir)->format('d/m/Y') : '-' }}" disabled>
                </div>
            </div>
            @if($permohonan->fail_paths && count($permohonan->fail_paths) > 0)
            <div class="field">
                <label>Fail Dilampirkan</label>
                @foreach($permohonan->fail_paths as $path)
                    <div style="margin-bottom: 4px;">
                        <a href="{{ asset('storage/' . $path) }}" target="_blank" style="font-size:13px; color:#185fa5;">
                            {{ basename($path) }}
                        </a>
                    </div>
                @endforeach
            </div>
            @endif
        </div>

        <div class="form-section">
            <div class="section-label">Keputusan Semakan</div>

            @if($permohonan->status === 'Diluluskan')
                <div style="background:#eaf3de; border:1px solid #c0dd97; color:#3b6d11; padding:0.75rem 1rem; border-radius:8px; font-size:13px;">
                    Permohonan ini telah pun diluluskan.
                    @if($permohonan->catatan_semakan)
                        <div style="margin-top:0.5rem; padding-top:0.5rem; border-top:1px solid #c0dd97;">
                            <strong style="font-size:11px;">CATATAN:</strong> {{ $permohonan->catatan_semakan }}
                        </div>
                    @endif
                </div>
            @else
                <form method="POST" action="{{ route('supervisor.approve', $permohonan->token) }}">
                    @csrf
                    <input type="hidden" name="status_override" id="status_override" value="Diluluskan">
                    <div class="field">
                        <label>Catatan <span class="required">*</span></label>
                        <textarea name="catatan_semakan" rows="3" placeholder="Tambah atau kemaskini catatan..." required>{{ $permohonan->catatan_semakan }}</textarea>
                    </div>
                    <div class="modal-actions" style="margin-top:0.75rem;">
                        <button type="submit" class="btn-lulus" onclick="document.getElementById('status_override').value='Diluluskan'">Luluskan</button>
                        <button type="submit" class="btn-tolak" onclick="document.getElementById('status_override').value='Dalam Semakan'">Dalam Semakan</button>
                    </div>
                </form>
            @endif
        </div>

    </div>
</div>

<footer>
    <div><strong>Jabatan Hutan Sarawak</strong> &nbsp;|&nbsp; Wisma Sumber Alam, Petra Jaya, 93660 Kuching, Sarawak</div>
    <div>© 2025 Jabatan Hutan Sarawak. Hak Cipta Terpelihara.</div>
</footer>

</body>
</html>