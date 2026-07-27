<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tempahan Saya — Sistem Tempahan</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link rel="icon" href="{{ asset('images/logo-icon.png')}}">
</head>
<body>
<x-header />
<x-navbar :breadcrumbs="[['label' => 'Tempah Bilik Mesyuarat', 'url' => '/booking/calendar'], ['label' => 'Tempahan Saya']]" />

<div class="pg-body">

    @if(session('success'))
        <div class="form-card">
            <div class="form-section alert alert-success">
            {{ session('success') }}
            </div>
        </div>
    @endif
    @if(session('info'))
        <div class="form-card">
            <div class="form-section alert alert-info">
            {{ session('info') }}
            </div>
        </div>
    @endif

    {{-- Upcoming --}}
    <div class="form-card">
        <div class="form-card-header">
            <h2>Tempahan Akan Datang</h2>
            <p>Jumlah tempahan: {{ $upcoming->count() }}</p>
        </div>

        @if($upcoming->isEmpty())
            <div class="form-section">
                <p>Tiada tempahan akan datang.</p>
                <div class="form-footer">
                    <span></span>
                    <a href="/booking/book" class="btn-submit">+ Buat Tempahan</a>
                </div>
            </div>
        @else
            <div class="form-section">
                <div class="table-wrap">
                <table class="app-table">
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
                            <strong>{{ $b->bilik->nama_bilik }}</strong><br>
                            <span class="table-meta">{{ $b->bilik->aras }}</span>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($b->tarikh)->translatedFormat('d M Y') }}</td>
                        <td class="table-meta">{{ substr($b->masa_mula,0,5) }} – {{ substr($b->masa_tamat,0,5) }}</td>
                        <td>{{ $b->tajuk_mesyuarat }}</td>
                        <td class="table-meta">{{ $b->remarks ?? '-' }}</td>
                        <td>
                            <div class="table-actions">
                                <a href="/booking/calendar?bilik={{ $b->bilik_id }}&week={{ $b->tarikh }}" class="btn-back table-btn">Kalendar</a>
                                <form method="POST" action="{{ route('booking.cancel', $b->cancel_token) }}"
                                      onsubmit="return confirm('Batalkan tempahan {{ addslashes($b->tajuk_mesyuarat) }}?')">
                                    @csrf
                                    <button type="submit" class="btn-submit table-btn">Batal</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </table>
                </div>
            </div>
        @endif
    </div>

    {{-- Past --}}
    <div class="form-card">
        <div class="form-card-header">
            <h2>Sejarah Tempahan</h2>
        </div>

        @if($past->isEmpty())
            <div class="form-section">
                <p>Tiada sejarah tempahan.</p>
            </div>
        @else
            <div class="form-section">
                <div class="table-wrap">
                <table class="app-table">
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
                            <strong>{{ $b->bilik->nama_bilik }}</strong><br>
                            <span class="table-meta">{{ $b->bilik->aras }}</span>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($b->tarikh)->translatedFormat('d M Y') }}</td>
                        <td class="table-meta">{{ substr($b->masa_mula,0,5) }} – {{ substr($b->masa_tamat,0,5) }}</td>
                        <td>{{ $b->tajuk_mesyuarat }}</td>
                        <td>
                            @if($b->status === 'confirmed')
                                <span class="badge badge-done">Selesai</span>
                            @else
                                <span class="badge badge-pending">Dibatalkan</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </table>
                </div>
                <div class="form-footer table-pagination">{{ $past->links() }}</div>
            </div>
        @endif
    </div>

</div>

<x-footer />
</body>
</html>