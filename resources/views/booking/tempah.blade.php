@extends('booking.layout')

@section('title', 'Buat Tempahan — ' . $bilik->nama_bilik)

@section('content')

<div style="max-width:560px; margin:0 auto;">
    <div class="form-card">
        <div class="form-card-header">
            <h2>Buat Tempahan</h2>
            <p>{{ $bilik->nama_bilik }} — {{ $bilik->aras }}, {{ $bilik->lokasi }}</p>
        </div>
        <form method="POST" action="/booking/tempah/{{ $bilik->id }}">
            @csrf
            <div class="form-section">
                @if(session('error'))
                    <div style="background:#fdf0f0; border:1px solid #f5c1c1; color:#a32d2d; padding:0.75rem 1rem; border-radius:8px; margin-bottom:1rem; font-size:13px;">
                        {{ session('error') }}
                    </div>
                @endif
                @if($errors->any())
                    <div style="background:#fdf0f0; border:1px solid #f5c1c1; color:#a32d2d; padding:0.75rem 1rem; border-radius:8px; margin-bottom:1rem; font-size:13px;">
                        <ul style="margin:0; padding-left:1.2rem;">
                            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                <div class="field">
                    <label>Tarikh <span class="required">*</span></label>
                    <input type="date" name="tarikh" value="{{ request('tarikh', old('tarikh')) }}"
                        min="{{ \Carbon\Carbon::today()->toDateString() }}" required>
                </div>
                <div class="field-row">
                    <div class="field">
                        <label>Masa Mula <span class="required">*</span></label>
                        <input type="time" name="masa_mula" value="{{ old('masa_mula', '08:00') }}" min="08:00" max="17:00" required>
                    </div>
                    <div class="field">
                        <label>Masa Tamat <span class="required">*</span></label>
                        <input type="time" name="masa_tamat" value="{{ old('masa_tamat', '09:00') }}" min="08:00" max="17:00" required>
                    </div>
                </div>
                <div class="field">
                    <label>Tujuan / Perkara <span class="required">*</span></label>
                    <textarea name="tujuan" rows="3" placeholder="Cth: Mesyuarat Jabatan Q2 2025">{{ old('tujuan') }}</textarea>
                </div>
            </div>
            <div class="form-footer">
                <a href="/booking/calendar/{{ $bilik->id }}" class="btn-back">← Kembali</a>
                <button type="submit" class="btn-submit">Hantar Tempahan</button>
            </div>
        </form>
    </div>
</div>

@endsection