<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semak Status Tiket — Jabatan Hutan Sarawak</title>
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


<div class="pg-body">
    <div class="form-card">
        <div class="form-card-header">
            <h2>Semak Status Tiket</h2>
            <p>Masukkan nombor tiket anda untuk menyemak status permohonan.</p>
        </div>

        <div class="form-section">
            <form method="POST" action="/semak-tiket">
                @csrf
                <div class="field">
                    <label>Nombor Tiket</label>
                    <input type="text" name="no_tiket" value="{{ old('no_tiket', $tiket ?? '') }}" placeholder="Cth: JHS/ICT/A/2026(1)" required>
                </div>
                <div style="margin-top: 0.75rem;">
                    <button type="submit" class="btn-submit">Semak</button>
                </div>
            </form>
        </div>

        @if(isset($tiket))
            @if($result)
                <div class="form-section">
                    <div class="section-label">Keputusan</div>

                    <div class="field-row">
                        <div class="field">
                            <label>No. Tiket</label>
                            <input type="text" value="{{ $result->no_tiket }}" disabled>
                        </div>
                        <div class="field">
                            <label>Nama</label>
                            <input type="text" value="{{ $result->nama }}" disabled>
                        </div>
                    </div>

                    @if($type === 'ict')
                        <div class="field-row">
                            <div class="field">
                                <label>Kategori Masalah</label>
                                <input type="text" value="{{ $result->kategori_masalah }}" disabled>
                            </div>
                            <div class="field">
                                <label>Tarikh Aduan</label>
                                <input type="text" value="{{ \Carbon\Carbon::parse($result->tarikh_aduan)->format('d/m/Y') }}" disabled>
                            </div>
                        </div>
                    @else
                        <div class="field-row">
                            <div class="field">
                                <label>Tajuk Maklumat</label>
                                <input type="text" value="{{ $result->tajuk_maklumat }}" disabled>
                            </div>
                            <div class="field">
                                <label>Tarikh Hantar</label>
                                <input type="text" value="{{ \Carbon\Carbon::parse($result->created_at)->format('d/m/Y') }}" disabled>
                            </div>
                        </div>
                    @endif

                    <div class="field">
                        <label>Status</label>
                        <div style="margin-top: 4px;">
                            @php $status = $result->status; @endphp
                            @if(in_array($status, ['Belum Selesai', 'Pending']))
                                <span class="badge badge-pending">{{ $status }}</span>
                            @elseif(in_array($status, ['Dalam Tindakan', 'Dalam Semakan']))
                                <span class="badge badge-progress">{{ $status }}</span>
                            @else
                                <span class="badge badge-done">{{ $status }}</span>
                            @endif
                        </div>
                    </div>

                    @if($type === 'mnb' && $result->catatan_semakan)
                    <div class="field" style="margin-top:0.75rem;">
                        <label>Catatan Penyelia</label>
                        <div style="background:#f9fafb; border:1px solid #dde8e1; border-radius:8px; padding:0.75rem 1rem; font-size:13px; color:#333; line-height:1.6; margin-top:4px;">
                            {{ $result->catatan_semakan }}
                        </div>
                    </div>
                    @endif
                </div>
            @else
                <div class="form-section">
                    <p style="color:#a32d2d; font-size:13px;">Tiket <strong>{{ $tiket }}</strong> tidak dijumpai. Sila semak semula nombor tiket anda.</p>
                </div>
            @endif
        @endif

        <div class="form-footer">
            <a href="/" class="btn-back">← Kembali</a>
        </div>
    </div>
</div>

<footer>
    <div><strong>Jabatan Hutan Sarawak</strong> &nbsp;|&nbsp; Wisma Sumber Alam, Petra Jaya, 93660 Kuching, Sarawak</div>
    <div>© 2025 Jabatan Hutan Sarawak. Hak Cipta Terpelihara.</div>
</footer>

</body>
</html>