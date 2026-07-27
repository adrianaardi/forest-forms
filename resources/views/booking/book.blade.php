<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Tempahan</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link rel="icon" href="{{ asset('images/logo-icon.png')}}">
</head>
<body>
<x-header />

<x-navbar :breadcrumbs="[['label' => 'Tempah Bilik Mesyuarat', 'url' => '/booking/calendar'], ['label' => 'Buat Tempahan']]" />
    <div class="pg-body" style="max-width:600px;">
    <div class="form-card">
        <div class="form-card-header">
            <h2>Buat Tempahan</h2>
            <p>Isi butiran tempahan bilik mesyuarat anda.</p>
        </div>
        <form method="POST" action="{{ route('booking.book.store') }}">
            @csrf
            <div class="form-section">

                @if(session('error'))
                    <div class="alert alert-error" style="margin-bottom:1rem;">
                        {{ session('error') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="form-error-box alert alert-error">
                        <ul class="form-error-list">
                            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                <div style="background:#f0f4f1; border:1px solid #dde8e1; border-radius:8px; padding:0.75rem 1rem; margin-bottom:1rem; font-size:13px; color:#444;">
                    Tempahan sebagai <strong>{{ $user->name }}</strong> ({{ $user->bahagian ?? '-' }})
                </div>
<div class="field">
    <label>Bilik Mesyuarat <span class="required">*</span></label>
    <select name="bilik_id" required onchange="updateCalendarLink(this.value)">
        <option value="">-- Pilih Bilik --</option>
        
        {{-- Use dot notation to group by the nested property 'nama_wilayah' --}}
        @foreach($bilikList->groupBy('wilayah.nama_wilayah') as $namaWilayah => $rooms)
            <optgroup label="Wilayah: {{ $namaWilayah }}">
                @foreach($rooms as $room)
                    <option value="{{ $room->id }}"
                        {{ old('bilik_id', $bilik?->id) == $room->id ? 'selected' : '' }}>
                        {{ $room->nama_bilik }} ({{ $room->aras }})
                    </option>
                @endforeach
            </optgroup>
        @endforeach

    </select>
</div>
                <div class="field">
                    <label>Tajuk Mesyuarat <span class="required">*</span></label>
                    <input type="text" name="tajuk_mesyuarat" value="{{ old('tajuk_mesyuarat') }}"
                        placeholder="Cth: Mesyuarat Jabatan Q2" required>
                </div>

                <div class="field">
                    <label>Remarks</label>
                    <textarea name="remarks" rows="2"  style="resize:none;">{{ old('remarks') }}</textarea>
                </div>

                <div class="field">
                    <label>Tarikh <span class="required">*</span></label>
                    <input type="date" name="tarikh" value="{{ old('tarikh', $tarikh) }}"
                        min="{{ \Carbon\Carbon::today()->toDateString() }}" required>
                </div>

                    <div class="field-row">
                        <div class="field">
                            <label>Masa Mula <span class="required">*</span></label>
                            <select id="bk-mula" name="masa_mula" required>
                                @for ($hour = 8; $hour <= 16; $hour++)
                                    @foreach (['00', '30'] as $minute)
                                        @php
                                            $time = sprintf('%02d:%s', $hour, $minute);
                                        @endphp
                                        <option value="{{ $time }}">{{ $time }}</option>
                                    @endforeach
                                @endfor
                            </select>
                        </div>

                        <div class="field">
                            <label>Masa Tamat <span class="required">*</span></label>
                            <select id="bk-tamat" name="masa_tamat" required>
                                @for ($hour = 8; $hour <= 17; $hour++)
                                    @foreach (['00', '30'] as $minute)
                                        @php
                                            $time = sprintf('%02d:%s', $hour, $minute);
                                        @endphp
                                        <option value="{{ $time }}">{{ $time }}</option>
                                    @endforeach
                                @endfor
                            </select>
                        </div>
                    </div>

            </div>
            <div class="form-footer">
                <a id="back-link" href="/booking/calendar{{ $bilik ? '?bilik='.$bilik->id : '' }}" class="btn-back">← Kembali</a>
                <button type="submit" class="btn-submit">Sahkan Tempahan</button>
            </div>
        </form>
    </div>
</div>

<x-footer />

<script>
function updateCalendarLink(bilikId) {
    const link = document.getElementById('back-link');
    link.href = bilikId ? '/booking/calendar?bilik=' + bilikId : '/booking/calendar';
}
</script>
</body>
</html>