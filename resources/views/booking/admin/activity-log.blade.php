<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Aktiviti — Tempahan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans+Flex:opsz,wght@6..144,1..1000&family=Lora:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('style.css') }}">
</head>
<body>
<header>
    <div class="logo"></div>
    <div>
        <a href="/" style="color: white; text-decoration: none;"><h1>Jabatan Hutan Sarawak</h1></a>
        <p> Hub Aplikasi Perkhidmatan Atas Talian</p>
    </div>
</header>
<x-navbar :breadcrumbs="[['label' => 'Tempahan Bilik', 'url' => '/booking/admin/dashboard'], ['label' => 'Log Aktiviti']]" />
<div class="dashboard-body">
    <div class="page-header">
        <div>
            <h1>Log Aktiviti</h1>
            <p>Lihat semua aktiviti tempahan dan pendaftaran pengguna.</p>
        </div>
        <a href="/booking/admin/dashboard" class="db-link">Kembali ke Dashboard</a>
    </div>

    <form method="GET" class="activity-filter">
        <label>
            <span class="filter-label">Tapis mengikut jenis aktiviti</span>
            <select name="activity">
                <option value="">Semua aktiviti</option>
                @foreach($activityTypes as $type)
                    <option value="{{ $type }}" {{ $selectedActivity === $type ? 'selected' : '' }}>
                        {{ $activityLabels[$type] ?? $type }}
                    </option>
                @endforeach
            </select>
        </label>
        <button type="submit">Tapis</button>
        @if($selectedActivity)
            <a href="/booking/admin/activity-log" class="btn-reset">Set semula</a>
        @endif
    </form>

    <div class="activity-card">
        @if($activityLogs->isEmpty())
            <div class="empty-state">Tiada log aktiviti untuk dipaparkan.</div>
        @else
            @foreach($activityLogs as $log)
                <div class="activity-row">
                    <div>
                        <div class="activity-label">{{ $log->description }}</div>
                        <div class="activity-meta">{{ $log->actor_name }} · {{ $activityLabels[$log->action] ?? $log->action }}</div>
                    </div>
                    <div>
                        <span class="badge {{ $log->actor_type === 'admin' ? 'badge-admin' : 'badge-user' }}">
                            {{ $log->actor_type === 'admin' ? 'Admin' : 'Pengguna' }}
                        </span>
                    </div>
                    <div class="activity-time">
                        <span>{{ $log->created_at->translatedFormat('d M Y, H:i') }}</span>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
</body>
</html>
