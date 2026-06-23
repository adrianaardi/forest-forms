<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Help Center — Jabatan Hutan Sarawak</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Google+Sans+Flex:opsz,wght@6..144,1..1000&family=Lora:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link rel="icon" href="{{ asset('images/logo-icon.png')}}">

</head>

<body class="pg">

<header>
    <div class="logo"></div>
    <div>
        <h1>Jabatan Hutan Sarawak</h1>
        <p>Hub Aplikasi Perkhidmatan Atas Talian</p>
    </div>
</header>

{{-- IMPORTANT: use SAME navbar system --}}
<x-navbar :breadcrumbs="[['label' => 'Manual Pengguna']]" />

<div class="pg-body">

    @yield('content')

</div>

<footer>
        <div>
            <strong>Seksyen Pengurusan Dan Transformasi Digital</strong>
            &nbsp;|&nbsp;
            Tingkat 15, Bangunan Baitul Makmur II, Medan Raya, Petra Jaya, 93050 Kuching, Sarawak
        </div>
        <div>
            © {{ date('Y') }} Jabatan Hutan Sarawak. Hak Cipta Terpelihara.
        </div>
</footer>

</body>
</html>