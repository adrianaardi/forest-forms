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

        <div class="form-card">
            <div class="form-card-header">
                <h2>Senarai Pemohon Akses Tempahan</h2>
                <p>Semak permohonan akses tempahan dan buat keputusan lulus atau tolak.</p>
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
                                    <div class="table-actions">
                                        <form method="POST" action="{{ route('booking.admin.users.status', $user->id) }}">
                                            @csrf
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="table-btn table-btn-success">Lulus</button>
                                        </form>
                                        <!-- <form method="POST" action="{{ route('booking.admin.users.status', $user->id) }}">
                                            @csrf
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="table-btn table-btn-warning">Tolak</button>
                                        </form> -->
                                    </div>
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
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="table-empty">Tiada pengguna yang mempunyai akses tempahan.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<x-footer />

</body>
</html>