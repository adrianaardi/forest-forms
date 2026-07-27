<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Urus Akaun — Admin</title>
<link rel="stylesheet" href="{{ asset('style.css') }}"> 
   <link rel="icon" href="{{ asset('images/logo-icon.png')}}">
</head>
<body>

<x-header />


<x-navbar :breadcrumbs="[['label' => 'Aduan ICT', 'url' => '/admin/ict-aduan'], ['label' => 'Urus Akaun']]" />
<div class="pg-body">

    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom:1rem;">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->has('delete'))
        <div class="alert alert-error" style="margin-bottom:1rem;">
            {{ $errors->first('delete') }}
        </div>
    @endif

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
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="aduan.wilayahname@sarawak.gov.my" required>
                    @error('email')
                        <div style="color:#a32d2d; font-size:12px; margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>
                <div class="field">
                    <label>Wilayah</label>
                    <select name="wilayah_id" required>
                        <option value="">-- Pilih Wilayah --</option>
                        @foreach($wilayahs as $wilayah)
                            <option value="{{ $wilayah->id }}">
                                {{ $wilayah->nama_wilayah }}
                            </option>
                        @endforeach
                    </select>
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
            <table class="app-table" style="margin-bottom:0; border-radius:0; border:none;">
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Wilayah  </th>
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
                    <td>
                        {{ $account->wilayah->nama_wilayah ?? '-' }}
                    </td>
                    <td>{{ \Carbon\Carbon::parse($account->created_at)->format('d/m/Y') }}</td>
                    <td>
                        <div class="table-actions">
                            <button
                                type="button"
                                class="table-btn table-btn-info"
                                onclick="openEditAccount(this)"
                                data-id="{{ $account->id }}"
                                data-name="{{ $account->name }}"
                                data-email="{{ $account->email }}"
                                data-wilayah-id="{{ $account->wilayah_id }}">
                                Edit
                            </button>

                            @if($account->id !== Auth::id())
                                <form method="POST" action="{{ route('admin.accounts.destroy', $account->id) }}" onsubmit="return confirm('Padam akaun {{ $account->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="table-btn table-btn-danger">Padam</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>

</div>

<x-footer />

<div class="ticket-modal-overlay" id="editAccountModal" onclick="closeEditAccountOnOverlay(event)">
    <div class="ticket-modal">
        <div class="ticket-modal-header">
            <h3>Kemaskini Akaun</h3>
            <button type="button" class="ticket-modal-close" onclick="closeEditAccount()">&times;</button>
        </div>

        <div class="ticket-modal-body">
            <form id="editAccountForm" method="POST">
                @csrf
                @method('PUT')

                <div class="field">
                    <label>Nama</label>
                    <input type="text" name="name" id="editAccountName" required>
                </div>

                <div class="field">
                    <label>Email</label>
                    <input type="email" name="email" id="editAccountEmail" required>
                </div>

                <div class="field">
                    <label>Wilayah</label>
                    <select name="wilayah_id" id="editAccountWilayah" required>
                        <option value="">-- Pilih Wilayah --</option>
                        @foreach($wilayahs as $wilayah)
                            <option value="{{ $wilayah->id }}">{{ $wilayah->nama_wilayah }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="field-row">
                    <div class="field">
                        <label>Kata Laluan Baharu (Opsyenal)</label>
                        <input type="password" name="password" placeholder="Biarkan kosong jika tiada perubahan">
                    </div>
                    <div class="field">
                        <label>Sahkan Kata Laluan</label>
                        <input type="password" name="password_confirmation" placeholder="Taip semula jika ubah kata laluan">
                    </div>
                </div>

                <div class="form-footer">
                    <button type="button" class="btn-back" onclick="closeEditAccount()">Batal</button>
                    <button type="submit" class="btn-submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openEditAccount(button) {
    const id = button.dataset.id;
    const name = button.dataset.name || '';
    const email = button.dataset.email || '';
    const wilayahId = button.dataset.wilayahId || '';

    document.getElementById('editAccountForm').action = `/admin/accounts/${id}`;
    document.getElementById('editAccountName').value = name;
    document.getElementById('editAccountEmail').value = email;
    document.getElementById('editAccountWilayah').value = wilayahId;

    document.querySelector('#editAccountForm input[name="password"]').value = '';
    document.querySelector('#editAccountForm input[name="password_confirmation"]').value = '';

    document.getElementById('editAccountModal').classList.add('active');
}

function closeEditAccount() {
    document.getElementById('editAccountModal').classList.remove('active');
}

function closeEditAccountOnOverlay(event) {
    if (event.target === document.getElementById('editAccountModal')) {
        closeEditAccount();
    }
}
</script>

</body>
</html>