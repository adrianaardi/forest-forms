<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengurusan Cawangan - Pergerakan Pegawai</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link rel="icon" href="{{ asset('images/logo-icon.png') }}">
</head>
<body>

<x-header />

<x-navbar :breadcrumbs="[['label' => 'Pergerakan Pegawai', 'url' => route('admin.pergerakan.index')], ['label' => 'Pengurusan Cawangan']]" />

<div class="pg-body">

    <div class="form-card">
        <div class="form-card-header">
            <h2>Sistem Pergerakan Pegawai</h2>
            <p>Bahagian: {{ Auth::user()->bahagian->nama ?? 'Umum' }}</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <div class="dashboard-split">

    <div class="form-card">
        <div class="form-card-header">
            <h2>Roster & Kehadiran Pegawai</h2>
        </div>

        <div class="form-section">
            <form action="{{ route('admin.pergerakan.pegawai.store') }}" method="POST">
                @csrf
                <div class="field-row">
                    <div class="field">
                        <label>Nama Pegawai</label>
                        <input type="text" name="nama" placeholder="Nama penuh" required>
                    </div>
                    <div class="field">
                        <label>Gred</label>
                        <input type="text" name="gred" placeholder="N9" required>
                    </div>
                </div>
                <div class="field-row">
                    <div class="field">
                        <label>Seksyen/Unit</label>
                        <input type="text" name="seksyen_unit" placeholder="Seksyen Pengurusan" required>
                    </div>
                    <div class="field">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn-submit">+ Tambah</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="form-section">
            <div class="table-wrap">
            <table class="app-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Pegawai</th>
                        <th>Gred</th>
                        <th>Seksyen/Unit</th>
                        <th>Kehadiran</th>
                        <th>Catatan</th>
                        <th>Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pegawaiList as $i => $peg)
                        <tr>
                            <td class="table-meta">{{ $i + 1 }}</td>
                            <td><strong>{{ $peg->nama }}</strong></td>
                            <td>{{ $peg->gred }}</td>
                            <td>{{ $peg->seksyen_unit }}</td>
                            <td>
                                <form action="{{ route('admin.pergerakan.pegawai.toggle', $peg->id) }}" method="POST" class="table-actions">
                                    @csrf @method('PATCH')
                                    <label>
                                        <input type="checkbox" onChange="this.form.submit()" {{ $peg->is_hadir ? 'checked' : '' }}>
                                    </label>
                                    <span class="badge {{ $peg->is_hadir ? 'badge-done' : 'badge-pending' }}">
                                        {{ $peg->is_hadir ? 'Hadir' : 'Tidak Hadir' }}
                                    </span>
                                </form>
                            </td>
                            <td>
                                <form action="{{ route('admin.pergerakan.pegawai.updateRemarks', $peg->id) }}" method="POST" class="table-actions">
                                    @csrf @method('PATCH')
                                    <input type="text" name="remarks" value="{{ $peg->remarks }}" placeholder="-">
                                    <button type="submit" class="btn-back table-btn">Simpan</button>
                                </form>
                            </td>
                            <td>
                                <form action="{{ route('admin.pergerakan.pegawai.destroy', $peg->id) }}" method="POST" onsubmit="return confirm('Padam pegawai ini daripada roster?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-submit table-btn">Padam</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="table-empty">
                                Tiada pegawai didaftarkan. Tambah pegawai menggunakan borang di atas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>
    </div>

    <div class="dashboard-right-column">

    <div class="form-card">
        <div class="form-card-header">
            <h2>Aktiviti Mingguan</h2>
        </div>

        <div class="form-section">
            <div class="alert alert-info">Aktiviti dipaparkan selama 7 hari dari tarikh mula. Selepas tempoh tamat, rekod akan hilang secara automatik.</div>

            <form action="{{ route('admin.pergerakan.aktiviti.store') }}" method="POST">
                @csrf
                <div class="field">
                    <label>Nama Aktiviti / Program</label>
                    <input type="text" name="nama_aktiviti" placeholder="Cth: Taklimat Pengurusan Sempadan" required>
                </div>
                <div class="field-row">
                    <div class="field">
                        <label>Tarikh Mula</label>
                        <input type="date" name="tarikh" required>
                    </div>
                    <div class="field">
                        <label>Seksyen/Unit Pengurus</label>
                        <input type="text" name="seksyen_unit" placeholder="Cth: Seksyen Pengurusan" required>
                    </div>
                </div>
                <div class="form-footer">
                    <span></span>
                    <button type="submit" class="btn-submit">+ Tambah Aktiviti</button>
                </div>
            </form>
        </div>

        <div class="form-section">
            <div class="table-wrap">
            <table class="app-table">
                <thead>
                    <tr>
                        <th>Program</th>
                        <th>Tarikh</th>
                        <th>Unit</th>
                        <th>Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($aktivitiList as $akt)
                        <tr>
                            <td><strong>{{ $akt->nama_aktiviti }}</strong></td>
                            <td class="table-meta">{{ \Carbon\Carbon::parse($akt->tarikh)->format('d/m/Y') }}</td>
                            <td>{{ $akt->seksyen_unit }}</td>
                            <td>
                                <div class="table-actions">
                                    <button type="button" class="btn-back table-btn"
                                        onclick="openEditAktiviti({{ $akt->id }}, '{{ addslashes($akt->nama_aktiviti) }}', '{{ $akt->tarikh }}', '{{ addslashes($akt->seksyen_unit) }}')">
                                        Edit
                                    </button>
                                    <form action="{{ route('admin.pergerakan.aktiviti.destroy', $akt->id) }}" method="POST"
                                        onsubmit="return confirm('Padam aktiviti ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-submit table-btn">Padam</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="table-empty">Tiada aktiviti aktif pada minggu ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>
    </div>

    <div class="form-card">
        @php $newsCount = $newsList->count(); $maxNews = 5; @endphp

        <div class="form-card-header">
            <h2>Berita & Pengumuman</h2>
            <p>{{ $newsCount }} / {{ $maxNews }} slot</p>
        </div>

        <div class="form-section">
            <div class="alert alert-info">Maksimum 5 pengumuman aktif. Kandungan ini akan bergerak sebagai news ticker pada paparan utama.</div>

            <div class="table-wrap">
            <table class="app-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Pengumuman</th>
                        <th>Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($newsList as $i => $news)
                        <tr>
                            <td class="table-meta">{{ $i + 1 }}</td>
                            <td>{{ $news->headline }}</td>
                            <td>
                                <form action="{{ route('admin.pergerakan.news.destroy', $news->id) }}" method="POST"
                                    onsubmit="return confirm('Padam pengumuman ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-submit table-btn" title="Padam">Padam</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="table-empty">Tiada pengumuman aktif. Tambah pengumuman di bawah.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>

            @if($newsCount < $maxNews)
                <form action="{{ route('admin.pergerakan.news.store') }}" method="POST">
                    @csrf
                    <div class="field">
                        <label>Pengumuman Baharu</label>
                        <input type="text" name="headline" placeholder="Taip pengumuman baharu..." required>
                    </div>
                    <div class="form-footer">
                        <span class="table-meta">{{ $maxNews - $newsCount }} slot lagi tersedia.</span>
                        <button type="submit" class="btn-submit">+ Siarkan</button>
                    </div>
                </form>
            @else
                <div class="alert alert-info">Had maksimum 5 pengumuman telah dicapai. Padam slot sedia ada untuk menambah yang baharu.</div>
            @endif
        </div>
    </div>

    </div>

    </div>

</div>

<x-footer />

<div class="ticket-modal-overlay" id="modalEditAktiviti">
    <div class="ticket-modal">
        <div class="ticket-modal-header">
            <h3>Kemaskini Aktiviti</h3>
            <button class="ticket-modal-close" onclick="closeEditAktiviti()">&times;</button>
        </div>
        <div class="ticket-modal-body">
            <form id="formEditAktiviti" method="POST">
                @csrf @method('PATCH')
                <div class="field">
                    <label>Nama Aktiviti</label>
                    <input type="text" name="nama_aktiviti" id="edit_akt_nama" required>
                </div>
                <div class="field">
                    <label>Tarikh</label>
                    <input type="date" name="tarikh" id="edit_akt_tarikh" required>
                </div>
                <div class="field">
                    <label>Seksyen/Unit</label>
                    <input type="text" name="seksyen_unit" id="edit_akt_seksyen" required>
                </div>
                <div class="form-footer">
                    <span></span>
                    <button type="submit" class="btn-submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openEditAktiviti(id, nama, tarikh, seksyen) {
    document.getElementById('formEditAktiviti').action = `/admin/pergerakan-pegawai/aktiviti/${id}`;
    document.getElementById('edit_akt_nama').value = nama;
    document.getElementById('edit_akt_tarikh').value = tarikh;
    document.getElementById('edit_akt_seksyen').value = seksyen;
    document.getElementById('modalEditAktiviti').classList.add('active');
}

function closeEditAktiviti() {
    document.getElementById('modalEditAktiviti').classList.remove('active');
}

window.addEventListener('click', function(e) {
    const modal = document.getElementById('modalEditAktiviti');
    if (e.target === modal) closeEditAktiviti();
});
</script>

</body>
</html>