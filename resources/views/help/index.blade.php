@extends('layouts.help')

@section('content')

<div>

    {{-- Page Title --}}
    <div style="margin-bottom: 1.5rem;">
        <h2 style="margin:0; color:#213458;">Manual Pengguna</h2>
        <p style="margin:5px 0 0; color:#666; font-size:14px;">
            Akses panduan sistem untuk setiap aplikasi
        </p>
    </div>

    {{-- OUTER BOX --}}
    <div style="
        background:#fff;
        padding:20px;
        border-radius:12px;
        box-shadow:0 2px 10px rgba(0,0,0,0.05);
    ">

        {{-- Cards Grid --}}
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(260px, 1fr)); gap:1rem;">

            <div style="border:1px solid #eee; border-radius:10px; padding:1rem;">
                <h4>Aplikasi Aduan ICT</h4>
                <a href="{{ asset('manuals/aduan-manual.pdf') }}" target="_blank"
                   style="display:inline-block; margin-top:10px; padding:6px 10px; background:#213458; color:#fff; text-decoration:none; border-radius:5px; font-size:13px;">
                    Lihat Manual Pengguna
                </a>
            </div>

            <div style="border:1px solid #eee; border-radius:10px; padding:1rem;">
                <h4>Aplikasi Pengurusan Laman Web</h4>
                <a href="{{ asset('manuals/mohon-manual.pdf') }}" target="_blank"
                   style="display:inline-block; margin-top:10px; padding:6px 10px; background:#213458; color:#fff; text-decoration:none; border-radius:5px; font-size:13px;">
                    Lihat Manual Pengguna
                </a>
            </div>

            <div style="border:1px solid #eee; border-radius:10px; padding:1rem;">
                <h4>Aplikasi Menempah Bilik Mesyuarat</h4>
                <a href="{{ asset('manuals/booking-manual.pdf') }}" target="_blank"
                style="display:inline-block; margin-top:10px; padding:6px 10px; background:#213458; color:#fff; text-decoration:none; border-radius:5px; font-size:13px;">
                    View Manual Pengguna
                </a>
            </div>

            <div style="border:1px solid #eee; border-radius:10px; padding:1rem;">
                <h4>Aplikasi Pergerakan Pegawai</h4>
                <a href="{{ asset('manuals/pergerakan-manual.pdf') }}" target="_blank"
                   style="display:inline-block; margin-top:10px; padding:6px 10px; background:#213458; color:#fff; text-decoration:none; border-radius:5px; font-size:13px;">
                    Lihat Manual Pengguna
                </a>
            </div>

        </div>
    </div>

</div>

@endsection