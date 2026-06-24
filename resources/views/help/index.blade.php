@extends('layouts.help')

@section('content')

<div>

    {{-- Page Title --}}
    <div style="margin-bottom: 1.5rem;">
        <h2 style="margin:0; color:#213458; font-weight:600;">Manual Pengguna</h2>
        <p style="margin:5px 0 0; color:#666; font-size:14px;">
            Akses panduan sistem untuk setiap aplikasi
        </p>
    </div>

    {{-- OUTER BOX --}}
    <div style="
        background:#fff;
        padding:24px;
        border-radius:14px;
        box-shadow:0 4px 18px rgba(0,0,0,0.06);
    ">

        {{-- Cards Grid --}}
        <div style="
            display:grid;
            grid-template-columns: repeat(2, 1fr);
            @media (max-width: 700px) {
                grid-template-columns: 1fr;
            }
            gap:1.2rem;
        ">

            {{-- ICT --}}
            <div class="manual-card">
                <div class="manual-icon">🖥️</div>
                <h4>Aplikasi Aduan ICT</h4>
                <p>Sistem untuk melaporkan dan memantau aduan ICT.</p>

                <a href="{{ asset('manuals/aduan-manual.pdf') }}" target="_blank" class="manual-btn">
                    Lihat Manual Pengguna →
                </a>
            </div>

            {{-- Portal --}}
            <div class="manual-card">
                <div class="manual-icon">📂</div>
                <h4>Aplikasi Pengurusan Laman Web</h4>
                <p>Muat naik dan urus kandungan portal rasmi jabatan.</p>

                <a href="{{ asset('manuals/mohon-manual.pdf') }}" target="_blank" class="manual-btn">
                    Lihat Manual Pengguna →
                </a>
            </div>

            {{-- Booking --}}
            <div class="manual-card">
                <div class="manual-icon">📅</div>
                <h4>Aplikasi Menempah Bilik Mesyuarat</h4>
                <p>Tempah bilik dan lihat jadual penggunaan bilik.</p>

                <a href="{{ asset('manuals/booking-manual.pdf') }}" target="_blank" class="manual-btn">
                    Lihat Manual Pengguna →
                </a>
            </div>

            {{-- Pergerakan --}}
            <div class="manual-card">
                <div class="manual-icon">📌</div>
                <h4>Aplikasi Pergerakan Pegawai</h4>
                <p>Rekod kehadiran dan aktiviti pegawai jabatan.</p>

                <a href="{{ asset('manuals/pergerakan-manual.pdf') }}" target="_blank" class="manual-btn">
                    Lihat Manual Pengguna →
                </a>
            </div>

        </div>
    </div>

</div>

{{-- STYLE --}}
<style>
.manual-card {
    border: 1px solid #eee;
    border-radius: 12px;
    padding: 18px;
    background: #fff;
    transition: all 0.2s ease;

    display: flex;
    flex-direction: column;

    min-height: 180px;
}

.manual-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 16px rgba(0,0,0,0.08);
    border-color: #dcdcdc;
}

.manual-icon {
    font-size: 26px;
    margin-bottom: 10px;
}

.manual-card h4 {
    margin: 0 0 6px;
    font-size: 15px;
    color: #213458;
    text-align: left;
}

.manual-card p {
    font-size: 12.5px;
    color: #666;
    margin-bottom: 10px;
}

.manual-btn {
    display: inline-block;
    margin-top: auto;   
    padding: 7px 12px;
    background: #213458;
    color: #fff;
    text-decoration: none;
    border-radius: 6px;
    font-size: 12.5px;
    transition: 0.15s;
    text-align: center;
}

.manual-btn:hover {
    background: #1a2e46;
}
</style>

@endsection