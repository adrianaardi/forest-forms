<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengurusan Cawangan - Pergerakan Pegawai</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans+Flex:opsz,wght@6..144,1..1000&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link rel="icon" href="{{ asset('images/logo-icon.png') }}">
    <style>
        .grid-bottom { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
        @media (max-width: 900px) { .grid-bottom { grid-template-columns: 1fr; } }

        .card { background: #fff; border-radius: 12px; padding: 1.5rem; box-shadow: 0 4px 12px rgba(0,0,0,0.05); border: 1px solid #eef2f5; margin-bottom: 1.5rem; }
        .card-title { font-size: 15px; font-weight: 700; color: #1e293b; margin: 0; padding-bottom: 0.6rem; border-bottom: 2px solid #f0f4f8; }
        .card-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; padding-bottom: 0.6rem; border-bottom: 2px solid #f0f4f8; }

        .form-group { margin-bottom: 0.85rem; }
        .form-group label { display: block; font-size: 11px; color: #666; margin-bottom: 3px; font-weight: 500; }
        .form-control { width: 100%; padding: 8px 10px; border: 1px solid #dcdcdc; border-radius: 6px; font-size: 13px; box-sizing: border-box; }
        .btn-submit { background: #334155; color: #fff; border: none; padding: 8px 18px; border-radius: 6px; font-size: 13px; font-weight: 500; cursor: pointer; }
        .btn-submit:hover { background: #1e293b; }

        .table-wrapper { max-height: 400px; overflow-y: auto; border: 1px solid #eef2f5; border-radius: 6px; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        th { background: #f8fafc; color: #475569; padding: 9px 10px; position: sticky; top: 0; z-index: 10; font-size: 11px; text-transform: uppercase; letter-spacing: 0.04em; }
        td { padding: 9px 10px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }

        .switch { position: relative; display: inline-block; width: 38px; height: 20px; }
        .switch input { opacity: 0; width: 0; height: 0; }
        .slider { position: absolute; cursor: pointer; inset: 0; background: #cbd5e1; border-radius: 20px; transition: .3s; }
        .slider:before { position: absolute; content: ""; height: 14px; width: 14px; left: 3px; bottom: 3px; background: white; border-radius: 50%; transition: .3s; }
        input:checked + .slider { background: #22c55e; }
        input:checked + .slider:before { transform: translateX(18px); }

        .badge { font-size: 11px; padding: 2px 8px; border-radius: 4px; font-weight: 600; }
        .bg-hadir { background: #dcfce7; color: #166534; }
        .bg-tiada { background: #fee2e2; color: #991b1b; }

        .scope-badge { background: #eff6ff; color: #1d4ed8; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; border: 1px solid #bfdbfe; }
        .week-badge { font-size: 11px; color: #64748b; background: #f1f5f9; border: 1px solid #e2e8f0; border-radius: 6px; padding: 3px 8px; }
        .slot-limit { font-size: 11px; background: #fef3c7; color: #92400e; border: 1px solid #fde68a; padding: 2px 8px; border-radius: 20px; font-weight: 600; }

        .info-note { font-size: 11px; color: #64748b; background: #f8fafc; padding: 8px 10px; border-radius: 6px; border: 1px solid #e2e8f0; margin-bottom: 12px; }

        .news-slot { display: flex; align-items: center; gap: 8px; padding: 7px 0; border-bottom: 1px solid #f1f5f9; }
        .news-idx { width: 22px; height: 22px; border-radius: 50%; background: #334155; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: 700; flex-shrink: 0; }

        .btn-remove { background: #ef4444; color: white; border: none; border-radius: 4px; width: 22px; height: 22px; display: flex; align-items: center; justify-content: center; font-weight: bold; cursor: pointer; font-size: 14px; padding: 0; flex-shrink: 0; line-height: 1; }
        .btn-remove:hover { background: #dc2626; }

        .btn-small { height: 30px; padding: 0 10px; font-size: 12px; }

        .alert-success { background: #dcfce7; color: #166534; padding: 10px 14px; border-radius: 6px; font-size: 13px; margin-bottom: 1.25rem; border: 1px solid #bbf7d0; }
        .alert-error { background: #fee2e2; color: #991b1b; padding: 10px 14px; border-radius: 6px; font-size: 13px; margin-bottom: 1.25rem; border: 1px solid #fecaca; }
    </style>
</head>
<body>

<header>
        <div class="logo"></div>
    <div>
        <a href="/" style="color: white; text-decoration: none;"><h1>Jabatan Hutan Sarawak</h1></a>
        <p> Hub Aplikasi Perkhidmatan Atas Talian</p>
    </div>
</header>

<x-navbar :breadcrumbs="[['label' => 'Pergerakan Pegawai', 'url' => route('admin.pergerakan.index')], ['label' => 'Pengurusan Cawangan']]" />

<div class="dashboard-body">

    <div style="margin-bottom: 1rem;">
        <a href="/admin/dashboard" class="btn-back">← Kembali ke Dashboard</a>
    </div>

    <p class="section-heading" style="margin-bottom: 0.25rem;">Sistem Pergerakan Pegawai</p>
    <span class="scope-badge">🔒 Bahagian: {{ Auth::user()->bahagian->nama ?? 'Umum' }}</span>

    @if(session('success'))
        <div class="alert-success" style="margin-top: 1rem;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert-error" style="margin-top: 1rem;">{{ session('error') }}</div>
    @endif

    {{-- ── ROSTER ── --}}
    <div class="card" style="margin-top: 1.5rem;">
        <div class="card-header">
            <h2 class="card-title" style="border:none; padding:0;">👥 Roster & Kehadiran Pegawai</h2>
        </div>

        <form action="{{ route('admin.pergerakan.pegawai.store') }}" method="POST"
            style="display:grid; grid-template-columns:2fr 0.6fr 1.5fr auto; gap:8px; align-items:end; margin-bottom:14px;">
            @csrf
            <div class="form-group" style="margin:0;">
                <label>Nama Pegawai</label>
                <input type="text" name="nama" class="form-control" placeholder="Nama penuh" required>
            </div>
            <div class="form-group" style="margin:0;">
                <label>Gred</label>
                <input type="text" name="gred" class="form-control" placeholder="N9" required>
            </div>
            <div class="form-group" style="margin:0;">
                <label>Seksyen/Unit</label>
                <input type="text" name="seksyen_unit" class="form-control" placeholder="Seksyen Pengurusan" required>
            </div>
            <button type="submit" class="btn-submit" style="height:36px; white-space:nowrap;">+ Tambah</button>
        </form>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th style="width:28px; text-align:center;">#</th>
                        <th>Pegawai</th>
                        <th>Gred</th>
                        <th>Seksyen/Unit</th>
                        <th style="text-align:center; width:155px;">Kehadiran</th>
                        <th>Catatan</th>
                        <th style="width:32px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pegawaiList as $i => $peg)
                        <tr>
                            <td style="text-align:center; color:#999; font-size:11px;">{{ $i + 1 }}</td>
                            <td><strong>{{ $peg->nama }}</strong></td>
                            <td>{{ $peg->gred }}</td>
                            <td>{{ $peg->seksyen_unit }}</td>
                            <td style="text-align:center;">
                                <form action="{{ route('admin.pergerakan.pegawai.toggle', $peg->id) }}" method="POST"
                                    style="display:inline-flex; align-items:center; gap:6px;">
                                    @csrf @method('PATCH')
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
                                    style="display:flex; gap:5px; align-items:center;">
                                    @csrf @method('PATCH')
                                    <input type="text" name="remarks" value="{{ $peg->remarks }}"
                                        class="form-control" placeholder="-"
                                        style="max-width:130px; padding:5px 8px; font-size:12px;">
                                    <button type="submit" class="btn-submit btn-small">Simpan</button>
                                </form>
                            </td>
                            <td>
                                <form action="{{ route('admin.pergerakan.pegawai.destroy', $peg->id) }}" method="POST"
                                    onsubmit="return confirm('Padam pegawai ini daripada roster?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-remove">−</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align:center; color:#999; padding:2rem;">
                                Tiada pegawai didaftarkan. Tambah pegawai menggunakan borang di atas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ── BOTTOM GRID: Aktiviti + News ── --}}
    <div class="grid-bottom">

    {{-- AKTIVITI MINGGUAN --}}
    <div class="card" style="margin-bottom:0;">
        <div class="card-header">
            <h2 class="card-title" style="border:none; padding:0;">📅 Aktiviti Mingguan</h2>
        </div>

        <p class="info-note">
            📌 Aktiviti dipaparkan selama 7 hari dari tarikh mula. Selepas tempoh tamat, rekod akan hilang secara automatik.
        </p>

        <form action="{{ route('admin.pergerakan.aktiviti.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Nama Aktiviti / Program</label>
                <input type="text" name="nama_aktiviti" class="form-control" placeholder="Cth: Taklimat Pengurusan Sempadan" required>
            </div>
            <div class="form-group">
                <label>Tarikh Mula</label>
                <input type="date" name="tarikh" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Seksyen/Unit Pengurus</label>
                <input type="text" name="seksyen_unit" class="form-control" placeholder="Cth: Seksyen Pengurusan" required>
            </div>
            <div style="text-align:right;">
                <button type="submit" class="btn-submit">+ Tambah Aktiviti</button>
            </div>
        </form>

        <div class="table-wrapper" style="margin-top:1rem;">
            <table>
                <thead>
                    <tr>
                        <th>Program</th>
                        <th style="width:90px;">Tarikh</th>
                        <th>Unit</th>
                        <th style="width:32px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($aktivitiList as $akt)
                        <tr>
                            <td><strong>{{ $akt->nama_aktiviti }}</strong></td>
                            <td style="font-size:12px; color:#64748b;">
                                {{ \Carbon\Carbon::parse($akt->tarikh)->format('d/m/Y') }}
                            </td>
                            <td>{{ $akt->seksyen_unit }}</td>
                            <td style="display:flex; gap:4px;">
                                <button type="button" class="btn-submit btn-small"
                                    onclick="openEditAktiviti({{ $akt->id }}, '{{ addslashes($akt->nama_aktiviti) }}', '{{ $akt->tarikh }}', '{{ addslashes($akt->seksyen_unit) }}')">
                                    Edit
                                </button>
                                <form action="{{ route('admin.pergerakan.aktiviti.destroy', $akt->id) }}" method="POST"
                                    onsubmit="return confirm('Padam aktiviti ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-remove">−</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align:center; color:#999; padding:1.5rem;">
                                Tiada aktiviti aktif pada minggu ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

        {{-- BERITA & PENGUMUMAN --}}
        <div class="card" style="margin-bottom:0;">
            @php $newsCount = $newsList->count(); $maxNews = 5; @endphp

            <div class="card-header">
                <h2 class="card-title" style="border:none; padding:0;">📢 Berita & Pengumuman</h2>
                <span class="slot-limit">{{ $newsCount }} / {{ $maxNews }} slot</span>
            </div>

            <p class="info-note">
                📌 Maksimum <strong>5 pengumuman</strong> aktif. Kandungan ini akan bergerak sebagai news ticker pada paparan utama.
            </p>

            <div style="margin-bottom:12px;">
                @forelse($newsList as $i => $news)
                    <div class="news-slot">
                        <div class="news-idx">{{ $i + 1 }}</div>
                        <div style="flex:1; font-size:12px; color:#334155;">{{ $news->headline }}</div>
                        <form action="{{ route('admin.pergerakan.news.destroy', $news->id) }}" method="POST"
                            onsubmit="return confirm('Padam pengumuman ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-remove" title="Padam">−</button>
                        </form>
                    </div>
                @empty
                    <div style="text-align:center; color:#999; padding:1.5rem 0; font-size:12px;">
                        Tiada pengumuman aktif. Tambah pengumuman di bawah.
                    </div>
                @endforelse
            </div>

            @if($newsCount < $maxNews)
                <form action="{{ route('admin.pergerakan.news.store') }}" method="POST"
                    style="display:flex; gap:8px; align-items:center; border-top:1px solid #f1f5f9; padding-top:12px;">
                    @csrf
                    <input type="text" name="headline" class="form-control"
                        placeholder="Taip pengumuman baharu..." required style="flex:1;">
                    <button type="submit" class="btn-submit" style="height:36px; white-space:nowrap;">+ Siarkan</button>
                </form>
                <p style="font-size:11px; color:#94a3b8; margin-top:6px;">
                    {{ $maxNews - $newsCount }} slot lagi tersedia.
                </p>
            @else
                <div style="border-top:1px solid #f1f5f9; padding-top:12px; font-size:12px; color:#92400e; background:#fef3c7; border-radius:6px; padding:10px; border:1px solid #fde68a; margin-top:4px;">
                    ⚠️ Had maksimum 5 pengumuman telah dicapai. Padam slot sedia ada untuk menambah yang baharu.
                </div>
            @endif
        </div>

    </div>{{-- end grid-bottom --}}

</div>{{-- end dashboard-body --}}

<footer>
    <div style="text-align:center; font-size:13px; color:#666;">
        <strong>Jabatan Hutan Sarawak</strong> | Wisma Sumber Alam, Petra Jaya, 93660 Kuching, Sarawak
    </div>
    <div style="text-align:center; font-size:12px; color:#888; margin-top:4px;">
        © {{ date('Y') }} Jabatan Hutan Sarawak. Hak Cipta Terpelihara.
    </div>
</footer>

<div class="modal-overlay" id="modalEditAktiviti" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:999; align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:12px; padding:1.5rem; width:100%; max-width:460px; box-shadow:0 8px 32px rgba(0,0,0,0.15);">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
            <strong style="font-size:14px;">✏️ Kemaskini Aktiviti</strong>
            <button onclick="closeEditAktiviti()" style="background:none; border:none; font-size:20px; cursor:pointer; color:#64748b;">&times;</button>
        </div>
        <form id="formEditAktiviti" method="POST">
            @csrf @method('PATCH')
            <div class="form-group">
                <label>Nama Aktiviti</label>
                <input type="text" name="nama_aktiviti" id="edit_akt_nama" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Tarikh</label>
                <input type="date" name="tarikh" id="edit_akt_tarikh" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Seksyen/Unit</label>
                <input type="text" name="seksyen_unit" id="edit_akt_seksyen" class="form-control" required>
            </div>
            <div style="text-align:right; margin-top:1rem;">
                <button type="submit" class="btn-submit">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditAktiviti(id, nama, tarikh, seksyen) {
    document.getElementById('formEditAktiviti').action = `/admin/pergerakan-pegawai/aktiviti/${id}`;
    document.getElementById('edit_akt_nama').value = nama;
    document.getElementById('edit_akt_tarikh').value = tarikh;
    document.getElementById('edit_akt_seksyen').value = seksyen;
    const modal = document.getElementById('modalEditAktiviti');
    modal.style.display = 'flex';
}

function closeEditAktiviti() {
    document.getElementById('modalEditAktiviti').style.display = 'none';
}

window.addEventListener('click', function(e) {
    const modal = document.getElementById('modalEditAktiviti');
    if (e.target === modal) closeEditAktiviti();
});
</script>

</body>
</html>