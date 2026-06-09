<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Utama Pentadbir - Pergerakan Pegawai</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans+Flex:opsz,wght@6..144,1..1000&family=Lora:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link rel="icon" href="{{ asset('images/logo-icon.png')}}">
    <style>
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; }
        @media (max-width: 900px) { .grid { grid-template-columns: 1fr; } }
        .card { background: #fff; border-radius: 12px; padding: 1.5rem; box-shadow: 0 4px 12px rgba(0,0,0,0.05); border: 1px solid #eef2f5; margin-bottom: 2rem; }
        .card-title { font-size: 16px; font-weight: 600; color: #194169; margin-top: 0; margin-bottom: 1.25rem; border-bottom: 2px solid #f0f4f8; padding-bottom: 0.5rem; }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; font-size: 12px; color: #666; margin-bottom: 4px; font-weight: 500; }
        .form-control { width: 100%; padding: 10px 12px; border: 1px solid #dcdcdc; border-radius: 6px; font-size: 13px; box-sizing: border-box; }
        .btn-submit { background: #194169; color: #fff; border: none; padding: 10px 20px; border-radius: 6px; font-size: 13px; font-weight: 500; cursor: pointer; width: 100%; }
        .btn-submit:hover { background: #12304f; }
        .table-wrapper { margin-top: 1.5rem; max-height: 250px; overflow-y: auto; border: 1px solid #eef2f5; border-radius: 6px; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; text-align: left; }
        th { background: #f8fafc; color: #475569; padding: 10px; position: sticky; top: 0; z-index: 10; }
        td { padding: 10px; border-bottom: 1px solid #f1f5f9; }
        .alert-success { background: #dcfce7; color: #166534; padding: 10px; border-radius: 6px; font-size: 13px; margin-bottom: 1.5rem; border: 1px solid #bbf7d0; }

        .news-ticker-container {
            display: flex;
            background: #1e293b; /* Premium Dark Slate background */
            color: #f8fafc;
            height: 40px;
            align-items: center;
            overflow: hidden;
            border-top: 3px solid #194169; /* JHS Corporate Blue highlight border */
            font-family: 'Google Sans Flex', sans-serif;
            font-size: 13px;
            box-shadow: 0 -4px 12px rgba(0,0,0,0.08);
        }

        .ticker-title {
            background: #194169;
            padding: 0 16px;
            height: 100%;
            display: flex;
            align-items: center;
            font-weight: 700;
            font-size: 11px;
            letter-spacing: 0.75px;
            z-index: 10;
            white-space: nowrap;
            box-shadow: 4px 0 12px rgba(0,0,0,0.3);
        }

        .ticker-wrap {
            width: 100%;
            overflow: hidden;
        }

        .ticker-content {
            display: inline-block;
            white-space: nowrap;
            padding-left: 100%; /* Delays initial appearance gracefully */
            animation: marquee-roll 30s linear infinite;
        }

        .ticker-content:hover {
            animation-play-state: paused;
            cursor: pointer;
            color: #cbd5e1;
        }

        @keyframes marquee-roll {
            0% {
                transform: translate3d(0, 0, 0);
            }
            100% {
                transform: translate3d(-100%, 0, 0);
            }
        }
    </style>
</head>
<body>

<header>
    <div class="logo"></div>
    <div>
        <h1>Jabatan Hutan Sarawak</h1>
        <p>Hub Aplikasi Perkhidmatan Atas Talian</p>
    </div>
</header>

<x-navbar :breadcrumbs="[['label' => 'Pergerakan Pegawai', 'url' => route('admin.pergerakan.index')], ['label' => 'Panel Pentadbir Utama']]" />

<div class="dashboard-body">

    <div style="margin-bottom: 1rem;">
        <a href="/admin/dashboard" class="btn-back">← Kembali ke Dashboard</a>
    </div>

    <p class="section-heading">Sistem Pergerakan Pegawai (Super Admin)</p>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="grid">
        <div class="card">
            <h2 class="card-title">🏢 Urus Bahagian Jabatan</h2>
            <form action="{{ route('admin.pergerakan.bahagian.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Nama Bahagian Baru</label>
                    <input type="text" name="nama" class="form-control" placeholder="Cth: Bahagian ICT" required>
                </div>
                <button type="submit" class="btn-submit" style="background:#475569;">Daftar Bahagian Baru</button>
            </form>

            <div class="table-wrapper" style="max-height: 330px;">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Bahagian</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bahagianList as $bahagian)
                            <tr>
                                <td>{{ $bahagian->id }}</td>
                                <td><strong>{{ $bahagian->nama }}</strong></td>
                            </tr>
                        @empty
                            <tr><td colspan="2" style="text-align:center; color:#999;">Tiada bahagian berdaftar.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <h2 class="card-title">👥 Tambah Akaun Sub-Admin Bahagian</h2>
            <form action="{{ route('admin.pergerakan.subadmin.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Nama Penuh Sub-Admin</label>
                    <input type="text" name="name" class="form-control" placeholder="Nama pegawai pengurus" required>
                </div>
                <div class="form-group">
                    <label>Email Rasmi (ID Log Masuk)</label>
                    <input type="email" name="email" class="form-control" placeholder="username@sarawak.gov.my" required>
                </div>
                <div class="form-group">
                    <label>Bahagian Bertanggungjawab</label>
                    <select name="bahagian_id" class="form-control" required>
                        <option value="">-- Pilih Bahagian Terhad --</option>
                        @foreach($bahagianList as $bahagian)
                            <option value="{{ $bahagian->id }}">{{ $bahagian->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Kata Laluan Sementara</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn-submit">Daftar Sub-Admin</button>
            </form>

            <div class="table-wrapper" style="max-height: 200px;">
                <table>
                    <thead>
                        <tr>
                            <th>Nama Pengurus</th>
                            <th>Bahagian Terhad</th>
                            <th>Emel Akses</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subadmins as $sub)
                            <tr>
                                <td><strong>{{ $sub->name }}</strong></td>
                                <td><span style="color: #194169; font-weight: 500;">{{ $sub->bahagian->nama ?? 'Tiada Bahagian' }}</span></td>
                                <td>{{ $sub->email }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" style="text-align:center; color:#999;">Tiada sub-admin berdaftar.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



<footer>
    <div><strong>Jabatan Hutan Sarawak</strong> | Wisma Sumber Alam, Petra Jaya, 93660 Kuching, Sarawak</div>
    <div>© 2026 Jabatan Hutan Sarawak. Hak Cipta Terpelihara.</div>
</footer>

</body>
</html>