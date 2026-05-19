<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Tempahan</title>
    <link rel="icon" href="{{ asset('images/logo-icon.png')}}">
<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Google+Sans+Flex:opsz,wght@6..144,1..1000&family=Lora:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet"><link rel="stylesheet" href="{{ asset('style.css') }}"></head>
<body>
<header>
    <div class="logo"></div>
    <div>
        <h1>Jabatan Hutan Sarawak</h1>
        <p> Hub Aplikasi Perkhidmatan Atas Talian</p>
    </div>
</header>
<x-navbar :breadcrumbs="[['label' => 'Tempah Bilik Mesyuarat', 'url' => '/booking/calendar'], ['label' => 'Buat Tempahan']]" />
    <div class="pg-body" style="max-width:560px;">
    <div class="form-card">
        <div class="form-card-header">
            <h2>Buat Tempahan</h2>
            <p>Isi butiran tempahan bilik mesyuarat anda.</p>
        </div>
        <form method="POST" action="{{ route('booking.book.store') }}">
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
                        @foreach($bilikList->groupBy('aras') as $aras => $rooms)
                            <optgroup label="{{ $aras }}">
                                @foreach($rooms as $room)
                                    <option value="{{ $room->id }}"
                                        {{ old('bilik_id', $bilik?->id) == $room->id ? 'selected' : '' }}>
                                        {{ $room->nama_bilik }} ({{ $room->wing }})
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

<footer>
    <div><strong>Jabatan Hutan Sarawak</strong> &nbsp;|&nbsp; Bangunan Baitul Makmur II, Medan Raya, Petra Jaya, 93050 Kuching, Sarawak</div>
    <div>© <?php echo date("Y"); ?> Jabatan Hutan Sarawak. Hak Cipta Terpelihara.</div>
</footer>

<script>
function updateCalendarLink(bilikId) {
    const link = document.getElementById('back-link');
    link.href = bilikId ? '/booking/calendar?bilik=' + bilikId : '/booking/calendar';
}
</script>
</body>
</html>