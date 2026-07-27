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

<x-header />

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
                    <label>No. Rujukan</label>
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
                <div class="attachment-list">
                    @foreach($permohonan->fail_paths as $path)
                        <a href="{{ asset('storage/' . $path) }}" target="_blank" class="attachment-item">
                            {{ basename($path) }}
                        </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <div class="form-section">
            <div class="section-label">Keputusan Semakan</div>

            @if($permohonan->status === 'Diluluskan')
                <div class="alert alert-success">
                    Permohonan ini telah pun diluluskan.
                    @if($permohonan->catatan_semakan)
                        <div class="detail-group">
                            <div class="detail-field">
                                <label>Catatan</label>
                                <p class="text-pre-wrap">{{ $permohonan->catatan_semakan }}</p>
                            </div>
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
                    <div class="modal-actions">
                        <button type="submit" class="table-btn table-btn-success" onclick="document.getElementById('status_override').value='Diluluskan'">Luluskan</button>
                        <button type="submit" class="table-btn table-btn-warning" onclick="document.getElementById('status_override').value='Dalam Semakan'">Dalam Semakan</button>
                    </div>
                </form>
            @endif
        </div>

    </div>
</div>

<x-footer />
</body>
</html>