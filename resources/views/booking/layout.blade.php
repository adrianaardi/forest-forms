<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Tempahan Bilik — Jabatan Hutan Sarawak')</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <style>
        .booking-nav { background: #1a4731; padding: 0 1.5rem; display: flex; align-items: center; gap: 1.5rem; overflow-x: auto; }
        .booking-nav a { color: rgba(255,255,255,0.75); text-decoration: none; font-size: 13px; padding: 0.6rem 0; white-space: nowrap; border-bottom: 2px solid transparent; display: inline-block; }
        .booking-nav a.active { color: #fff; border-bottom-color: #7ec99a; }
        .booking-nav .nav-right { margin-left: auto; display: flex; align-items: center; gap: 1rem; }
        .booking-nav form button { background: none; border: none; color: rgba(255,255,255,0.75); font-size: 13px; cursor: pointer; padding: 0; }
        .booking-nav form button:hover { color: #fff; }
        .bilik-card { background: #fff; border: 1px solid #dde8e1; border-radius: 12px; padding: 1.25rem; text-decoration: none; color: inherit; display: block; transition: border-color 0.15s, box-shadow 0.15s; }
        .bilik-card:hover { border-color: #1a4731; box-shadow: 0 2px 8px rgba(26,71,49,0.08); }
        .bilik-card h3 { font-size: 14px; font-weight: 500; margin-bottom: 0.25rem; }
        .bilik-card p { font-size: 12px; color: #777; margin: 0; }
        .aras-title { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.07em; color: #1a4731; margin: 1.25rem 0 0.6rem; display: flex; align-items: center; gap: 8px; }
        .aras-title::after { content: ''; flex: 1; height: 1px; background: #dde8e1; }
        .bilik-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 10px; }
        /* calendar */
        .cal-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 4px; margin-top: 0.75rem; }
        .cal-header { display: grid; grid-template-columns: repeat(7, 1fr); gap: 4px; margin-bottom: 4px; }
        .cal-header span { text-align: center; font-size: 11px; font-weight: 600; color: #777; padding: 4px 0; }
        .cal-day { border-radius: 8px; padding: 6px 4px; min-height: 52px; font-size: 12px; cursor: pointer; border: 1px solid transparent; transition: border-color 0.15s; position: relative; }
        .cal-day:hover { border-color: #1a4731; }
        .cal-day .day-num { font-weight: 500; font-size: 13px; }
        .cal-day.empty { background: none; cursor: default; border: none; }
        .cal-day.past { background: #f5f5f5; color: #bbb; cursor: default; }
        .cal-day.past:hover { border-color: transparent; }
        .cal-day.available { background: #eaf3de; }
        .cal-day.partial { background: #fef9e7; }
        .cal-day.full { background: #fdf0f0; }
        .cal-day.today { outline: 2px solid #1a4731; }
        .cal-legend { display: flex; gap: 1rem; flex-wrap: wrap; margin-top: 0.75rem; font-size: 12px; }
        .cal-legend span { display: flex; align-items: center; gap: 5px; }
        .cal-legend .dot { width: 12px; height: 12px; border-radius: 3px; }
        /* booking slots */
        .slot-item { display: flex; justify-content: space-between; align-items: center; padding: 8px 12px; border-radius: 8px; background: #f9fafb; border: 1px solid #eee; margin-bottom: 6px; font-size: 13px; }
        .slot-time { font-weight: 500; color: #1a4731; }
        .slot-user { color: #555; font-size: 12px; }
    </style>
</head>
<body>

<header>
    <div class="logo"></div>
    <div>
        <h1>Jabatan Hutan Sarawak</h1>
        <p>Sistem Tempahan Bilik Mesyuarat</p>
    </div>
</header>

<x-navbar />

<div class="pg-body">
    @yield('content')
</div>

<footer>
    <div><strong>Jabatan Hutan Sarawak</strong> &nbsp;|&nbsp; Wisma Sumber Alam, Petra Jaya, 93660 Kuching, Sarawak</div>
    <div>© 2025 Jabatan Hutan Sarawak. Hak Cipta Terpelihara.</div>
</footer>

</body>
</html>