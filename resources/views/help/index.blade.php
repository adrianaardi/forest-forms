@extends('layouts.help')

@section('content')

<section class="section">
    <h2 class="section-title">Manual Pengguna</h2>

    <div class="cards">
        <div class="card">
            <div class="card-icon">🖥️</div>
            <h3>Aplikasi Aduan ICT</h3>
            <p>Sistem untuk melaporkan dan memantau aduan ICT.</p>
            <a href="{{ asset('manuals/aduan-manual.pdf') }}" target="_blank" class="card-link">Lihat Manual Pengguna →</a>
        </div>

        <div class="card">
            <div class="card-icon">📂</div>
            <h3>Aplikasi Pengurusan Laman Web</h3>
            <p>Muat naik dan urus kandungan portal rasmi jabatan.</p>
            <a href="{{ asset('manuals/mohon-manual.pdf') }}" target="_blank" class="card-link">Lihat Manual Pengguna →</a>
        </div>

        <div class="card">
            <div class="card-icon">📅</div>
            <h3>Aplikasi Menempah Bilik Mesyuarat</h3>
            <p>Tempah bilik dan lihat jadual penggunaan bilik.</p>
            <a href="{{ asset('manuals/booking-manual.pdf') }}" target="_blank" class="card-link">Lihat Manual Pengguna →</a>
        </div>

        <div class="card">
            <div class="card-icon">📌</div>
            <h3>Aplikasi Pergerakan Pegawai</h3>
            <p>Rekod kehadiran dan aktiviti pegawai jabatan.</p>
            <a href="{{ asset('manuals/pergerakan-manual.pdf') }}" target="_blank" class="card-link">Lihat Manual Pengguna →</a>
        </div>
    </div>
</section>

@endsection