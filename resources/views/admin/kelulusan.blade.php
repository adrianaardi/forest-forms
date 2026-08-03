<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelulusan Perjalanan - Tetapan Emel</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link rel="icon" href="{{ asset('images/logo-icon.png')}}">
</head>
<body>
<x-header />
<x-navbar :breadcrumbs="[['label' => 'Admin', 'url' => '/admin/ict-aduan'], ['label' => 'Kelulusan Perjalanan']]" />

<div class="pg-body">
    <div class="card-stack">

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="form-card" data-role="supervisor">
            <div class="form-card-header">
                <h2>Senarai Supervisor</h2>
                <p>Cari emel pengguna sedia ada dan tambah ke senarai supervisor.</p>
            </div>
            <div class="form-section">
                <div class="search-add-wrap">
                    <input type="text" class="form-input role-search" data-role="supervisor" placeholder="Cari emel supervisor..." autocomplete="off">
                    <div class="search-dropdown" id="search-supervisor" style="display:none;"></div>
                </div>
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
                                <th>Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($supervisors as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->bahagian ?? '-' }}</td>
                                <td>{{ $user->jawatan ?? '-' }}</td>
                                <td>
                                    <form method="POST" action="{{ route('admin.kelulusan.remove') }}" onsubmit="return confirm('Buang {{ $user->email }} dari senarai Supervisor?');">
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                                        <input type="hidden" name="role" value="supervisor">
                                        <button type="submit" class="table-btn table-btn-danger">Buang</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="table-empty">Belum ada supervisor ditetapkan.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="form-card" data-role="hod">
            <div class="form-card-header">
                <h2>Senarai Head of Department (HOD)</h2>
                <p>Cari emel pengguna sedia ada dan tambah ke senarai HOD.</p>
            </div>
            <div class="form-section">
                <div class="search-add-wrap">
                    <input type="text" class="form-input role-search" data-role="hod" placeholder="Cari emel HOD..." autocomplete="off">
                    <div class="search-dropdown" id="search-hod" style="display:none;"></div>
                </div>
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
                                <th>Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($hods as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->bahagian ?? '-' }}</td>
                                <td>{{ $user->jawatan ?? '-' }}</td>
                                <td>
                                    <form method="POST" action="{{ route('admin.kelulusan.remove') }}" onsubmit="return confirm('Buang {{ $user->email }} dari senarai HOD?');">
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                                        <input type="hidden" name="role" value="hod">
                                        <button type="submit" class="table-btn table-btn-danger">Buang</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="table-empty">Belum ada HOD ditetapkan.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="form-card" data-role="accountant">
            <div class="form-card-header">
                <h2>Senarai Akauntan</h2>
                <p>Cari emel pengguna sedia ada dan tambah ke senarai akauntan.</p>
            </div>
            <div class="form-section">
                <div class="search-add-wrap">
                    <input type="text" class="form-input role-search" data-role="accountant" placeholder="Cari emel akauntan..." autocomplete="off">
                    <div class="search-dropdown" id="search-accountant" style="display:none;"></div>
                </div>
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
                                <th>Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($accountants as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->bahagian ?? '-' }}</td>
                                <td>{{ $user->jawatan ?? '-' }}</td>
                                <td>
                                    <form method="POST" action="{{ route('admin.kelulusan.remove') }}" onsubmit="return confirm('Buang {{ $user->email }} dari senarai Akauntan?');">
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                                        <input type="hidden" name="role" value="accountant">
                                        <button type="submit" class="table-btn table-btn-danger">Buang</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="table-empty">Belum ada akauntan ditetapkan.</td></tr>
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
    const searchInputs = document.querySelectorAll('.role-search');
    let timer;

    function hideAll() {
        document.querySelectorAll('.search-dropdown').forEach(el => {
            el.style.display = 'none';
            el.innerHTML = '';
        });
    }

    searchInputs.forEach(input => {
        input.addEventListener('input', function () {
            clearTimeout(timer);
            const q = input.value.trim();
            const role = input.dataset.role;
            const dropdown = document.getElementById('search-' + role);

            if (q.length < 2) {
                dropdown.style.display = 'none';
                dropdown.innerHTML = '';
                return;
            }

            timer = setTimeout(() => {
                fetch(`{{ route('admin.kelulusan.search') }}?role=${encodeURIComponent(role)}&q=${encodeURIComponent(q)}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(res => res.json())
                .then(users => {
                    if (!Array.isArray(users) || !users.length) {
                        dropdown.innerHTML = '<div class="search-empty">Tiada pengguna dijumpai.</div>';
                        dropdown.style.display = 'block';
                        return;
                    }

                    dropdown.innerHTML = users.map(u => `
                        <form method="POST" action="{{ route('admin.kelulusan.assign') }}" class="search-item">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="user_id" value="${u.id}">
                            <input type="hidden" name="role" value="${role}">
                            <button type="submit" class="search-item-btn">
                                <strong>${u.name}</strong> - ${u.email}
                            </button>
                        </form>
                    `).join('');
                    dropdown.style.display = 'block';
                })
                .catch(() => {
                    dropdown.style.display = 'none';
                });
            }, 250);
        });
    });

    document.addEventListener('click', function (e) {
        if (!e.target.closest('.search-add-wrap')) {
            hideAll();
        }
    });
})();
</script>

<x-footer />
</body>
</html>
