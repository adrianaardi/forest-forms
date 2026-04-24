<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Urus Bahagian — Admin</title>
    <link rel="icon" href="{{ asset('images/logo-icon.png')}}">
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

    @if(session('success'))
        <div style="background:#eaf3de; border:1px solid #c0dd97; color:#3b6d11; padding:0.75rem 1rem; border-radius:8px; margin-bottom:1rem; font-size:13px;">
            {{ session('success') }}
        </div>
    @endif

    {{-- Add form --}}
    <div class="form-card" style="margin-bottom:1.5rem;">
        <div class="form-card-header">
            <h2>Tambah Bahagian / Unit</h2>
            <p>Daftarkan bahagian dan email penyelia untuk kelulusan permohonan.</p>
        </div>
        <form method="POST" action="{{ route('admin.bahagian.store') }}">
            @csrf
            <div class="form-section">
                <div class="field-row">
                    <div class="field">
                        <label>Nama Bahagian / Unit <span class="required">*</span></label>
                        <input type="text" name="nama_bahagian" value="{{ old('nama_bahagian') }}" placeholder="Cth: Bahagian ICT" required>
                        @error('nama_bahagian')
                            <div style="color:#a32d2d; font-size:12px; margin-top:4px;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label>Email Penyelia <span class="required">*</span></label>
                        <input type="email" name="email_supervisor" value="{{ old('email_supervisor') }}" placeholder="penyelia@sarawak.gov.my" required>
                        @error('email_supervisor')
                            <div style="color:#a32d2d; font-size:12px; margin-top:4px;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="form-footer">
                <span></span>
                <button type="submit" class="btn-submit">Tambah</button>
            </div>
        </form>
    </div>

    {{-- List --}}
    <div class="form-card">
        <div class="form-card-header">
            <h2>Senarai Bahagian</h2>
            <p>Bahagian yang telah didaftarkan.</p>
        </div>
        <div class="form-section" style="padding:0;">
            <table class="data-table" style="margin-bottom:0; border-radius:0; border:none;">
                <tr>
                    <th>Nama Bahagian / Unit</th>
                    <th>Email Penyelia</th>
                    <th>Tindakan</th>
                </tr>
                @forelse($bahagian as $item)
                <tr>
                    <td>{{ $item->nama_bahagian }}</td>
                    <td>{{ $item->email_supervisor }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.bahagian.destroy', $item->id) }}" onsubmit="return confirm('Padam bahagian ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete" style="padding:4px 12px; font-size:12px;">Padam</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="3" style="text-align:center; color:#999; padding:1.5rem;">Tiada bahagian didaftarkan.</td></tr>
                @endforelse
            </table>
        </div>
    </div>

</div>

<footer>
    <div><strong>Jabatan Hutan Sarawak</strong> &nbsp;|&nbsp; Wisma Sumber Alam, Petra Jaya, 93660 Kuching, Sarawak</div>
    <div>© 2025 Jabatan Hutan Sarawak. Hak Cipta Terpelihara.</div>
</footer>

</body>
</html>