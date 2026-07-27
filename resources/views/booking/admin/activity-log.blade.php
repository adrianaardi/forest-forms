<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Aktiviti — Tempahan</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
</head>
<body>
<x-header />
<x-navbar :breadcrumbs="[['label' => 'Tempahan Bilik', 'url' => '/booking/admin/dashboard'], ['label' => 'Log Aktiviti']]" />
<div class="pg-body">
        <a href="/booking/admin/dashboard" class="btn-back">Kembali ke Dashboard</a>
<br><br>
    <div class="card-stack">
    <div class="form-card">
        <div class="form-card-header">
            <h2>Log Aktiviti</h2>
            <p>Lihat semua aktiviti tempahan dan pendaftaran pengguna.</p>
        </div>
    </div>

    <form method="GET" class="form-card">
        <div class="form-section">
            <div class="field-row">
                <div class="field">
                    <label for="activity">Tapis mengikut jenis aktiviti</label>
                    <select id="activity" name="activity">
                        <option value="">Semua aktiviti</option>
                        @foreach($activityTypes as $type)
                            <option value="{{ $type }}" {{ $selectedActivity === $type ? 'selected' : '' }}>
                                {{ $activityLabels[$type] ?? $type }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="form-footer">
            @if($selectedActivity)
                <a href="/booking/admin/activity-log" class="btn-back">Set semula</a>
            @else
                <span class="table-meta">Pilih aktiviti untuk menapis rekod.</span>
            @endif
            <button type="submit" class="btn-submit">Tapis</button>
        </div>
    </form>

    <div class="form-card">
        <div class="form-card-header">
            <h2>Rekod Aktiviti</h2>
            <p>Senarai terkini aktiviti yang direkodkan dalam sistem.</p>
        </div>
        <div class="form-section">
            @if($activityLogs->isEmpty())
                <div class="alert alert-info">Tiada log aktiviti untuk dipaparkan.</div>
            @else
                <div class="table-wrap">
                    <table class="app-table">
                        <thead>
                            <tr>
                                <th>Aktiviti</th>
                                <th>Pelaku</th>
                                <th>Kategori</th>
                                <th>Masa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($activityLogs as $log)
                                <tr>
                                    <td>
                                        {{ $log->description }}
                                        <div class="table-meta">{{ $activityLabels[$log->action] ?? $log->action }}</div>
                                    </td>
                                    <td>{{ $log->actor_name }}</td>
                                    <td>{{ $log->actor_type === 'admin' ? 'Admin' : 'Pengguna' }}</td>
                                    <td>{{ $log->created_at->translatedFormat('d M Y, H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
    </div>
</div>
</body>
</html>
