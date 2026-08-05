<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Kursus — JHS</title>
    <link rel="icon" href="{{ asset('images/logo-icon.png')}}">
    <link rel="stylesheet" href="{{ asset('style.css') }}">
</head>
<body>
<x-header />
<x-navbar :breadcrumbs="[['label' => 'Katalog Kursus']]" />

<section class="catalog-hero">
    <div class="catalog-shell">
        <div>
            <span class="catalog-eyebrow">Latihan & Pembangunan</span>
            <h2>Setiap kursus yang diluluskan, dalam satu katalog.</h2>
            <p>
                Kursus ditadbir dalam satu paparan yang mudah dicapai. Pengguna berdaftar boleh menghantar permohonan kursus,
                manakala pengguna tempahan yang mempunyai kebenaran boleh menambah kursus baharu ke dalam katalog ini.
            </p>
        </div>

        <div class="catalog-stats">
            <div class="catalog-stat">
                <strong>{{ $stats['total'] }}</strong>
                <span>Jumlah kursus</span>
            </div>
            <div class="catalog-stat">
                <strong>{{ $stats['dalam'] }}</strong>
                <span>Di dalam Sarawak</span>
            </div>
            <div class="catalog-stat">
                <strong>{{ $stats['luar'] }}</strong>
                <span>Di luar Sarawak</span>
            </div>
        </div>
    </div>
</section>

<div class="catalog-shell">
    @if(session('success'))
        <div class="alert alert-success catalog-note-card">{{ session('success') }}</div>
    @endif

    @if(session('info'))
        <div class="alert alert-info catalog-note-card">{{ session('info') }}</div>
    @endif

    <div class="catalog-toolbar">
        <div class="catalog-filters">
            <a href="{{ route('booking.kursus.index', array_filter(['q' => $search])) }}" class="catalog-filter-pill {{ $scope === '' ? 'is-active' : '' }}">Semua kursus</a>
            <a href="{{ route('booking.kursus.index', array_filter(['scope' => 'dalam', 'q' => $search])) }}" class="catalog-filter-pill {{ $scope === 'dalam' ? 'is-active' : '' }}">Di Dalam Sarawak</a>
            <a href="{{ route('booking.kursus.index', array_filter(['scope' => 'luar', 'q' => $search])) }}" class="catalog-filter-pill {{ $scope === 'luar' ? 'is-active' : '' }}">Di Luar Sarawak</a>
        </div>

        <div class="catalog-search-form">
            <form method="GET" action="{{ route('booking.kursus.index') }}" class="catalog-search-form">
                @if($scope !== '')
                    <input type="hidden" name="scope" value="{{ $scope }}">
                @endif
                <input type="text" name="q" value="{{ $search }}" placeholder="Cari tajuk, tempat atau penganjur">
                <button type="submit" class="table-btn table-btn-neutral">Cari</button>
            </form>

            @if($bookingUser && $bookingUser->can_book)
                <a href="{{ route('booking.kursus.create') }}" class="catalog-add-btn">+ Tambah</a>
            @endif
        </div>
    </div>

    @if($kursusList->isEmpty())
        <div class="catalog-empty">
            <p>Tiada kursus ditemui buat masa ini.</p>
        </div>
    @else
        <div class="course-grid">
            @foreach($kursusList as $kursus)
                <article class="course-card">
                    <div class="course-card-top">
                        <span class="course-scope {{ $kursus->is_dalam_sarawak ? '' : 'outside' }}">
                            {{ $kursus->is_dalam_sarawak ? 'Di Dalam Sarawak' : 'Di Luar Sarawak' }}
                        </span>
                        <span class="course-fee">
                            {{ $kursus->yuran !== null ? 'RM ' . number_format((float) $kursus->yuran, 2) : 'Percuma' }}
                        </span>
                    </div>

                    <div>
                        <h3>{{ $kursus->tajuk }}</h3>
                        <p class="course-summary">{{ \Illuminate\Support\Str::limit($kursus->ringkasan, 170) }}</p>
                    </div>

                    <div class="course-meta-list">
                        <div class="course-meta-item">
                            <span>📍</span>
                            <div><strong>{{ $kursus->lokasi }}</strong></div>
                        </div>
                        <div class="course-meta-item">
                            <span>🗓️</span>
                            <div>{{ $kursus->tarikh_mula->format('d M Y') }} — {{ $kursus->tarikh_tamat->format('d M Y') }}</div>
                        </div>
                        <div class="course-meta-item">
                            <span>👥</span>
                            <div>{{ $kursus->jumlah_tempat }} tempat duduk</div>
                        </div>
                        <div class="course-meta-item">
                            <span>🏢</span>
                            <div>{{ $kursus->penganjur }}</div>
                        </div>
                    </div>

                    <div class="course-footer">
                        <div class="course-footer-note">
                            Ditambah oleh {{ $kursus->creator?->name ?? 'Pengguna sistem' }} · {{ $kursus->applications_count }} permohonan
                        </div>

                        @auth('booking_user')
                            @if($appliedCourseIds->contains($kursus->id))
                                <button type="button" class="course-action-btn" disabled>Permohonan telah dihantar</button>
                            @else
                                <form method="POST" action="{{ route('booking.kursus.apply', $kursus) }}">
                                    @csrf
                                    <button type="submit" class="course-action-btn">Mohon kursus ini</button>
                                </form>
                            @endif
                        @else
                            <a href="/booking/daftar" class="course-action-btn">Daftar untuk memohon</a>
                        @endauth
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</div>

<x-footer />
</body>
</html>