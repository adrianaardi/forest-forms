<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Urus Pengguna — Tempahan</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link rel="icon" href="{{ asset('images/logo-icon.png')}}">
</head>
<body>
<x-header />
<x-navbar :breadcrumbs="[['label' => 'Tempahan Bilik', 'url' => '/booking/admin/dashboard'], ['label' => 'Urus Pengguna']]" />

<div class="pg-body">
    <div class="card-stack">

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Search & Add existing user --}}
        <div class="form-card">
            <div class="form-card-header">
                <h2>Tambah Kebenaran Tempahan</h2>
                <p>Cari pengguna sedia ada mengikut emel dan berikan kebenaran tempahan terus.</p>
            </div>
            <div class="form-section">
                <div class="search-add-wrap" style="position: relative; max-width: 420px;">
                    <input
                        type="text"
                        id="userSearchInput"
                        class="form-input"
                        placeholder="Taip emel pengguna..."
                        autocomplete="off"
                    >
                    <div id="userSearchResults" class="search-dropdown" style="display:none;"></div>
                </div>
            </div>
        </div>

        {{-- Pending requests --}}
        <div class="form-card">
            <div class="form-card-header">
                <h2>Permohonan Akses Tempahan</h2>
                <p>Pengguna yang memohon kebenaran tempahan.</p>
            </div>
            <div class="form-section">
                <div class="table-wrap">
                    <table class="app-table">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Emel</th>
                                <th>Bahagian</th>
                                <th>Jawatan</th>
                                <th>Wilayah</th>
                                <th>No. Telefon</th>
                                <th>Tarikh Daftar</th>
                                <th>Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($applicants as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->bahagian ?? '-' }}</td>
                                <td>{{ $user->jawatan ?? '-' }}</td>
                                <td>{{ $user->wilayah?->nama_wilayah ?? '-' }}</td>
                                <td>{{ $user->phone ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') }}</td>
                                <td>
                                    <form method="POST" action="{{ route('booking.admin.users.grant', $user->id) }}">
                                        @csrf
                                        <button type="submit" class="table-btn table-btn-success">Lulus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="table-empty">Tiada pemohon baharu.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Users who currently can book --}}
        <div class="form-card">
            <div class="form-card-header">
                <h2>Pengguna Yang Boleh Membuat Tempahan</h2>
                <p>Senarai pengguna yang mempunyai akses tempahan aktif.</p>
            </div>
            <div class="form-section">
                <div class="table-wrap">
                    <table class="app-table">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Emel</th>
                                <th>Bahagian</th>
                                <th>Jawatan</th>
                                <th>Wilayah</th>
                                <th>No. Telefon</th>
                                <th>Tarikh Daftar</th>
                                <th>Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($bookableUsers as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->bahagian ?? '-' }}</td>
                                <td>{{ $user->jawatan ?? '-' }}</td>
                                <td>{{ $user->wilayah?->nama_wilayah ?? '-' }}</td>
                                <td>{{ $user->phone ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') }}</td>
                                <td>
                                    <form method="POST" action="{{ route('booking.admin.users.withdraw', $user->id) }}"
                                          onsubmit="return confirm('Tarik balik kebenaran tempahan {{ $user->name }}?');">
                                        @csrf
                                        <button type="submit" class="table-btn table-btn-warning">Tarik Balik</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="table-empty">Tiada pengguna yang mempunyai akses tempahan.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
(function () {
    const input   = document.getElementById('userSearchInput');
    const results = document.getElementById('userSearchResults');
    let debounceTimer;

    input.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        const q = input.value.trim();

        if (q.length < 2) {
            results.style.display = 'none';
            results.innerHTML = '';
            return;
        }

        debounceTimer = setTimeout(() => {
            fetch(`{{ route('booking.admin.users.search') }}?q=${encodeURIComponent(q)}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(users => {
                if (!users.length) {
                    results.innerHTML = '<div class="search-item search-empty">Tiada pengguna dijumpai.</div>';
                    results.style.display = 'block';
                    return;
                }

                results.innerHTML = users.map(u => `
                    <form method="POST" action="/booking/admin/users/${u.id}/grant" class="search-item">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <button type="submit" class="search-item-btn">
                            <strong>${u.name}</strong> — ${u.email}
                        </button>
                    </form>
                `).join('');
                results.style.display = 'block';
            })
            .catch(() => {
                results.style.display = 'none';
            });
        }, 250);
    });

    document.addEventListener('click', function (e) {
        if (!results.contains(e.target) && e.target !== input) {
            results.style.display = 'none';
        }
    });
})();
</script>

<x-footer />

</body>
</html>