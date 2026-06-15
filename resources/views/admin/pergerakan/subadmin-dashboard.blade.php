<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengurusan Cawangan - Pergerakan Pegawai</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans+Flex:opsz,wght@6..144,1..1000&family=Lora:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link rel="icon" href="{{ asset('images/logo-icon.png')}}">
    <style>
        .grid { display: grid; grid-template-columns: 1.1fr 0.9fr; gap: 2rem; }
        @media (max-width: 1000px) { .grid { grid-template-columns: 1fr; } }
        .card { background: #fff; border-radius: 12px; padding: 1.5rem; box-shadow: 0 4px 12px rgba(0,0,0,0.05); border: 1px solid #eef2f5; margin-bottom: 2rem; }
        .card-title { font-size: 16px; font-weight: 600; color: #334155; margin-top: 0; margin-bottom: 1.25rem; border-bottom: 2px solid #f0f4f8; padding-bottom: 0.5rem; }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; font-size: 12px; color: #666; margin-bottom: 4px; font-weight: 500; }
        .form-control { width: 100%; padding: 10px 12px; border: 1px solid #dcdcdc; border-radius: 6px; font-size: 13px; box-sizing: border-box; }
        .btn-submit { background: #334155; color: #fff; border: none; padding: 10px 20px; border-radius: 6px; font-size: 13px; font-weight: 500; cursor: pointer; width: 100%; }
        .table-wrapper { margin-top: 1.5rem; max-height: 450px; overflow-y: auto; border: 1px solid #eef2f5; border-radius: 6px; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; text-align: left; }
        th { background: #f8fafc; color: #475569; padding: 10px; position: sticky; top: 0; z-index: 10; }
        td { padding: 10px; border-bottom: 1px solid #f1f5f9; }
        
        .switch { position: relative; display: inline-block; width: 44px; height: 22px; }
        .switch input { opacity: 0; width: 0; height: 0; }
        .slider { position: absolute; cursor: pointer; inset: 0; background-color: #cbd5e1; transition: .3s; border-radius: 22px; }
        .slider:before { position: absolute; content: ""; height: 16px; width: 16px; left: 3px; bottom: 3px; background-color: white; transition: .3s; border-radius: 50%; }
        input:checked + .slider { background-color: #22c55e; }
        input:checked + .slider:before { transform: translateX(22px); }
        
        .badge { font-size: 11px; padding: 2px 6px; border-radius: 4px; font-weight: 500; }
        .bg-hadir { background: #dcfce7; color: #166534; }
        .bg-tiada { background: #fee2e2; color: #991b1b; }
        .alert-success { background: #dcfce7; color: #166534; padding: 10px; border-radius: 6px; font-size: 13px; margin-bottom: 1.5rem; border: 1px solid #bbf7d0; }
        .scope-badge { background: #eff6ff; color: #1d4ed8; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; display: inline-block; margin-top: 5px; border: 1px solid #bfdbfe;}

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

        .btn-small {
            height: 30px;
            padding: 0 10px;
            font-size: 12px;
            border-radius: 6px;
            line-height: 30px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
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

<x-navbar :breadcrumbs="[['label' => 'Pergerakan Pegawai', 'url' => route('admin.pergerakan.index')], ['label' => 'Pengurusan Cawangan']]" />

<div class="dashboard-body">

    <div style="margin-bottom: 1rem;">
        <a href="/admin/dashboard" class="btn-back">← Kembali ke Dashboard</a>
    </div>

    <p class="section-heading" style="margin-bottom: 0.25rem;">Sistem Pergerakan Pegawai</p>
    <div class="scope-badge">🔒 Bahagian Mengurus: {{ Auth::user()->bahagian->nama ?? 'Umum' }}</div>

    @if(session('success'))
        <div class="alert-success" style="margin-top: 1.5rem;">{{ session('success') }}</div>
    @endif

    <div class="grid" style="margin-top: 1.5rem;">
        
        <div class="card">
            <h2 class="card-title">👥 Roster & Kehadiran Pegawai</h2>
            
        <form action="{{ route('admin.pergerakan.pegawai.store') }}" method="POST" 
            style="display: grid; grid-template-columns: 2fr 1fr 2fr; gap: 15px; align-items: end;">
            @csrf
            
            <!-- ROW 1 -->
            <div class="form-group" style="margin: 0;">
                <label>Nama Pegawai</label>
                <input type="text" name="nama" class="form-control" placeholder="Nama penuh" required>
            </div>
            
            <div class="form-group" style="margin: 0;">
                <label>Gred</label>
                <input type="text" name="gred" class="form-control" placeholder="Cth: N9" required>
            </div>
            
            <div class="form-group" style="margin: 0;">
                <label>Seksyen/Unit</label>
                <input type="text" name="seksyen_unit" class="form-control" placeholder="Cth: Seksyen Pengurusan" required>
            </div>
            
            <!-- ROW 2: Button stretches across all 3 columns -->
            <button type="submit" class="btn-submit" style="grid-column: span 3; height: 40px; margin-top: 5px;">
                + Tambah Pegawai
            </button>
        </form>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Pegawai / Gred</th>
                            <th>Seksyen/Unit</th>
                            <th style="text-align: center; width: 140px;">Kehadiran Hari Ini</th>
                            <th>Catatan (Ketidakhadiran)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pegawaiList as $peg)
                            <tr>
                                <td>
<div style="display: flex; align-items: flex-start; gap: 8px;">
                                        <form action="{{ route('admin.pergerakan.pegawai.destroy', $peg->id) }}" method="POST" onsubmit="return confirm('Padam pegawai ini daripada roster?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-remove-small">−</button>
                                        </form>
                                        <div>
                                    <strong>{{ $peg->nama }}</strong><br>
                                    <span style="color:#666; font-size:11px;">Gred: {{ $peg->gred }}</span>
                                </td>
                                <td>{{ $peg->seksyen_unit }}</td>
                                <td style="text-align: center;">
                                    <form action="{{ route('admin.pergerakan.pegawai.toggle', $peg->id) }}" method="POST" style="display: inline-flex; align-items: center; gap: 8px;">
                                        @csrf
                                        @method('PATCH')
                                        <label class="switch">
                                            <input type="checkbox" onChange="this.form.submit()" {{ $peg->is_hadir ? 'checked' : '' }}>
                                            <span class="slider"></span>
                                        </label>
                                        <span class="badge {{ $peg->is_hadir ? 'bg-hadir' : 'bg-tiada' }}">
                                            {{ $peg->is_hadir ? 'Hadir' : 'Tidak Hadir' }}
                                        </span>
                                    </form>
                                </td>
                                <td>
                                    <form action="{{ route('admin.pergerakan.pegawai.updateRemarks', $peg->id) }}" method="POST"
                                        style="display:flex; gap:6px; align-items:center;">
                                        @csrf
                                        @method('PATCH')

                                        <input type="text"
                                            name="remarks"
                                            value="{{ $peg->remarks }}"
                                            class="form-control form-control-sm"
                                            placeholder="-"
                                            style="max-width:140px;">

                                        <button type="submit" class="btn-submit btn-small">
                                            Save
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" style="text-align:center; color:#999;">Tiada pegawai didaftarkan di bawah bahagian ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card" id="jadual-program">
            <h2 class="card-title">📅 Jadual & Aktiviti Program Luar</h2>
            <form action="{{ route('admin.pergerakan.aktiviti.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Nama Aktiviti / Program Jabatan</label>
                    <input type="text" name="nama_aktiviti" class="form-control" placeholder="Cth: Taklimat Pengurusan Sempadan" required>
                </div>
                <div class="form-group">
                    <label>Tarikh Dilaksanakan</label>
                    <input type="date" name="tarikh" class="form-control" required>
                </div>
               <div class="form-group">
                    <label>Seksyen/Unit Pengurus Program</label>
                    <input type="text" name="seksyen_unit" class="form-control" placeholder="Cth: Seksyen Pengurusan dan Transformasi Digital" required>
                </div>
                <div style="margin-top: 1rem; text-align: right;">
                    <button type="submit" class="btn-submit" style="width:auto; background:#334155; item-alignment: center; margin-bottom: 20px;">Simpan Jadual & Aktiviti</button>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Program</th>
                            <th>Tarikh</th>
                            <th>Seksyen/Unit Pengurus Program</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($aktivitiList as $akt)
                            <tr>
                                <td><strong>{{ $akt->nama_aktiviti }}</strong></td>
                                <td>{{ \Carbon\Carbon::parse($akt->tarikh)->format('d/m/Y') }}</td>
                                <td>{{ $akt->seksyen_unit }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" style="text-align:center; color:#999;">Tiada program dijadualkan bagi bahagian ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</div>

<div class="card" id="news-ticker" style="margin-top: 1rem;">
        <h2 class="card-title" style="color: #334155;">📢 Kemaskini Berita & Pengumuman Bergerak (News Ticker)</h2>
        <form action="/admin/pergerakan/news" method="POST" style="display: flex; gap: 12px; align-items: center;">
            @csrf
            <div style="flex-grow: 1;">
                <input type="text" name="headline" class="form-control" 
                       placeholder="Taip pemberitahuan penting atau makluman cawangan di sini untuk disiarkan..." 
                       required style="height: 42px;">
            </div>
            <div>
                <button type="submit" class="btn-submit" 
                        style="background: #334155; width: auto; height: 42px; padding: 0 24px; white-space: nowrap; margin: 0;">
                    Siarkan Berita
                </button>
            </div>
        </form>
    </div>

</div>

<footer>
    <div><strong>Jabatan Hutan Sarawak</strong> | Wisma Sumber Alam, Petra Jaya, 93660 Kuching, Sarawak</div>
    <div>© 2026 Jabatan Hutan Sarawak. Hak Cipta Terpelihara.</div>
</footer>

</body>
</html>