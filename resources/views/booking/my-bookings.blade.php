<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tempahan Saya — Sistem Tempahan</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Google+Sans+Flex:opsz,wght@6..144,1..1000&family=Lora:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet"><link rel="stylesheet" href="{{ asset('style.css') }}">    <link rel="icon" href="{{ asset('images/logo-icon.png')}}">
</head>
<body>
<header>
    <div class="logo"></div>
    <div>
        <h1>Jabatan Hutan Sarawak</h1>
        <p>Hub Aplikasi Perkhidmatan Atas Talian</p>
    </div>
</header>
<x-navbar :breadcrumbs="[['label' => 'Tempah Bilik Mesyuarat', 'url' => '/booking/calendar'], ['label' => 'Tempahan Saya']]" />

<div class="dashboard-body">

    @if(session('success'))
        <div style="background:#eaf3de; border:1px solid #c0dd97; color:#3b6d11; padding:0.75rem 1rem; border-radius:8px; margin-bottom:1rem; font-size:13px;">
            {{ session('success') }}
        </div>
    @endif
    @if(session('info'))
        <div style="background:#e6f1fb; border:1px solid #b5d4f4; color:#0c447c; padding:0.75rem 1rem; border-radius:8px; margin-bottom:1rem; font-size:13px;">
            {{ session('info') }}
        </div>
    @endif

    {{-- Upcoming --}}
    <p class="section-heading">
        Tempahan Akan Datang
        <span style="font-size:11px; background:#eaf3de; color:#27500a; padding:2px 10px; border-radius:20px; font-weight:600; text-transform:none; letter-spacing:0;">
            {{ $upcoming->count() }}
        </span>
    </p>

    @if($upcoming->isEmpty())
        <div class="form-card" style="margin-bottom:1.5rem;">
            <div class="form-section" style="text-align:center; color:#bbb; padding:2rem; font-size:13px;">
                Tiada tempahan akan datang.
                <div style="margin-top:0.75rem;">
                    <a href="/booking/book" class="btn-submit" style="text-decoration:none; font-size:13px;">+ Buat Tempahan</a>
                </div>
            </div>
        </div>
    @else
        <table class="data-table" style="margin-bottom:1.5rem;">
            <tr>
                <th>Bilik</th>
                <th>Tarikh</th>
                <th>Masa</th>
                <th>Tajuk</th>
                <th>Catatan</th>
                <th>Tindakan</th>
            </tr>
            @foreach($upcoming as $b)
            <tr>
                <td>
                    {{ $b->bilik->nama_bilik }}
                    <br><span style="font-size:11px; color:#999;">{{ $b->bilik->aras }}</span>
                </td>
                <td>{{ \Carbon\Carbon::parse($b->tarikh)->translatedFormat('d M Y') }}</td>
                <td style="font-size:12px; white-space:nowrap;">{{ substr($b->masa_mula,0,5) }} – {{ substr($b->masa_tamat,0,5) }}</td>
                <td class="td-truncate">{{ $b->tajuk_mesyuarat }}</td>
                <td class="td-truncate" style="color:#999; font-size:12px;">{{ $b->remarks ?? '-' }}</td>
                <td style="display:flex; gap:6px; flex-wrap:wrap;">
                    <a href="/booking/calendar?bilik={{ $b->bilik_id }}&week={{ $b->tarikh }}"
                       class="btn-view" style="font-size:12px; padding:4px 12px;">
                        Kalendar
                    </a>
                    <form method="POST" action="{{ route('booking.cancel', $b->cancel_token) }}"
                          onsubmit="return confirm('Batalkan tempahan {{ addslashes($b->tajuk_mesyuarat) }}?')">
                        @csrf
                        <button type="submit" class="btn-delete" style="padding:4px 12px; font-size:12px;">
                            Batal
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </table>
    @endif

    {{-- Past --}}
    <p class="section-heading">Sejarah Tempahan</p>

    @if($past->isEmpty())
        <div class="form-card">
            <div class="form-section" style="text-align:center; color:#bbb; padding:2rem; font-size:13px;">
                Tiada sejarah tempahan.
            </div>
        </div>
    @else
        <table class="data-table">
            <tr>
                <th>Bilik</th>
                <th>Tarikh</th>
                <th>Masa</th>
                <th>Tajuk</th>
                <th>Status</th>
            </tr>
            @foreach($past as $b)
            <tr>
                <td>
                    {{ $b->bilik->nama_bilik }}
                    <br><span style="font-size:11px; color:#999;">{{ $b->bilik->aras }}</span>
                </td>
                <td>{{ \Carbon\Carbon::parse($b->tarikh)->translatedFormat('d M Y') }}</td>
                <td style="font-size:12px; white-space:nowrap;">{{ substr($b->masa_mula,0,5) }} – {{ substr($b->masa_tamat,0,5) }}</td>
                <td class="td-truncate">{{ $b->tajuk_mesyuarat }}</td>
                <td>
                    @if($b->status === 'confirmed')
                        <span class="badge badge-done">Selesai</span>
                    @else
                        <span class="badge" style="background:#fdf0f0; color:#a32d2d;">Dibatalkan</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </table>
        <div class="pagination-wrap">{{ $past->links() }}</div>
    @endif

</div>

<footer>
    <div><strong>Jabatan Hutan Sarawak</strong> &nbsp;|&nbsp; Bangunan Baitul Makmur II, Medan Raya, Petra Jaya, 93050 Kuching, Sarawak</div>
    <div>© <?php echo date("Y"); ?> Jabatan Hutan Sarawak. Hak Cipta Terpelihara.</div>
</footer>
</body>
</html>