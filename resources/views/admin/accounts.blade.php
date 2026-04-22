<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Urus Akaun — Admin</title>
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

    @if($errors->has('delete'))
        <div style="background:#fdf0f0; border:1px solid #f5c1c1; color:#a32d2d; padding:0.75rem 1rem; border-radius:8px; margin-bottom:1rem; font-size:13px;">
            {{ $errors->first('delete') }}
        </div>
    @endif

    <div style="margin-bottom: 1rem;">
        <a href="/admin/profile" class="btn-back">← Kembali ke Profil</a>
    </div>

    {{-- Add account form --}}
    <div class="form-card" style="margin-bottom: 1.5rem;">
        <div class="form-card-header">
            <h2>Tambah Akaun Baharu</h2>
            <p>Cipta akaun admin baharu.</p>
        </div>
        <form method="POST" action="{{ route('admin.accounts.store') }}">
            @csrf
            <div class="form-section">
                <div class="field">
                    <label>Nama</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama penuh" required>
                    @error('name')
                        <div style="color:#a32d2d; font-size:12px; margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>
                <div class="field">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="email@jhs.gov.my" required>
                    @error('email')
                        <div style="color:#a32d2d; font-size:12px; margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>
                <div class="field-row">
                    <div class="field">
                        <label>Kata Laluan</label>
                        <input type="password" name="password" placeholder="Minimum 8 aksara" required>
                        @error('password')
                            <div style="color:#a32d2d; font-size:12px; margin-top:4px;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="field">
                        <label>Sahkan Kata Laluan</label>
                        <input type="password" name="password_confirmation" placeholder="Taip semula" required>
                    </div>
                </div>
            </div>
            <div class="form-footer">
                <span></span>
                <button type="submit" class="btn-submit">Tambah Akaun</button>
            </div>
        </form>
    </div>

    {{-- Accounts list --}}
    <div class="form-card">
        <div class="form-card-header">
            <h2>Senarai Akaun</h2>
            <p>Semua akaun admin yang berdaftar.</p>
        </div>
        <div class="form-section" style="padding: 0;">
            <table class="data-table" style="margin-bottom:0; border-radius:0; border:none;">
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Tarikh Daftar</th>
                    <th>Tindakan</th>
                </tr>
                @foreach($accounts as $account)
                <tr>
                    <td>
                        {{ $account->name }}
                        @if($account->id === Auth::id())
                            <span style="font-size:11px; color:#777;">(anda)</span>
                        @endif
                    </td>
                    <td>{{ $account->email }}</td>
                    <td>{{ \Carbon\Carbon::parse($account->created_at)->format('d/m/Y') }}</td>
                    <td>
                        @if($account->id !== Auth::id())
                            <form method="POST" action="{{ route('admin.accounts.destroy', $account->id) }}" onsubmit="return confirm('Padam akaun {{ $account->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete" style="padding:4px 12px; font-size:12px;">Padam</button>
                            </form>
                        @else
                            <span style="font-size:12px; color:#aaa;">—</span>
                        @endif
                    </td>
                </tr>
                @endforeach
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