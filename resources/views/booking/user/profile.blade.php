<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Saya — Sistem Tempahan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans+Flex:opsz,wght@6..144,1..1000&family=Lora:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet"><link rel="stylesheet" href="{{ asset('style.css') }}">        <link rel="icon" href="{{ asset('images/logo-icon.png')}}">
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
</head>
<body>
<x-header />
<x-navbar :breadcrumbs="[['label' => 'Tempah Bilik Mesyuarat', 'url' => '/booking/calendar'], ['label' => 'Profile Saya']]" />

<div class="pg-body" style="max-width:560px;">

    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom:1rem;">
            {{ session('success') }}
        </div>
    @endif

    {{-- Profile info --}}
    <div class="form-card" style="margin-bottom:1.5rem;">
        <div class="form-card-header">
            <h2>Profile Saya</h2>
            <p>Kemaskini maklumat akaun anda.</p>
        </div>
        <form method="POST" action="{{ route('booking.user.profile.update') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-section">
                @if($errors->has('name') || $errors->has('email') || $errors->has('bahagian') || $errors->has('jawatan'))
                    <div class="form-error-box alert alert-error">
                        <ul class="form-error-list">
                            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                        </ul>
                    </div>
                @endif
                <div class="field">
                    <label>Nama Penuh <span class="required">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
                </div>
                <div class="field">
                    <label>Emel <span class="required">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
                </div>
                <div class="field">
                    <label>Bahagian / Unit</label>
                    <select name="bahagian">
                        <option value="" {{ old('bahagian', $user->bahagian) == '' ? 'selected' : '' }}>-- Pilih Bahagian / Unit --</option>
                        <option value="Pejabat Direktorat" {{ old('bahagian', $user->bahagian) == 'Pejabat Direktorat' ? 'selected' : '' }}>Pejabat Direktorat</option>
                        <option value="Bahagian Perancangan dan Pengurusan Hutan" {{ old('bahagian', $user->bahagian) == 'Bahagian Perancangan dan Pengurusan Hutan' ? 'selected' : '' }}>Bahagian Perancangan dan Pengurusan Hutan</option>
                        <option value="Bahagian Pelesenan" {{ old('bahagian', $user->bahagian) == 'Bahagian Pelesenan' ? 'selected' : '' }}>Bahagian Pelesenan</option>
                        <option value="Bahagian Penyelidikan dan Pembangunan" {{ old('bahagian', $user->bahagian) == 'Bahagian Penyelidikan dan Pembangunan' ? 'selected' : '' }}>Bahagian Penyelidikan dan Pembangunan</option>
                        <option value="Bahagian Hasil dan Pengurusan Data" {{ old('bahagian', $user->bahagian) == 'Bahagian Hasil dan Pengurusan Data' ? 'selected' : '' }}>Bahagian Hasil dan Pengurusan Data</option>
                        <option value="Bahagian Hal Ehwal Antarabangsa" {{ old('bahagian', $user->bahagian) == 'Bahagian Hal Ehwal Antarabangsa' ? 'selected' : '' }}>Bahagian Hal Ehwal Antarabangsa</option>
                        <option value="Bahagian Teknologi Hutan dan Geospatial" {{ old('bahagian', $user->bahagian) == 'Bahagian Teknologi Hutan dan Geospatial' ? 'selected' : '' }}>Bahagian Teknologi Hutan dan Geospatial</option>
                        <option value="Bahagian Restorasi dan Hutan Industri" {{ old('bahagian', $user->bahagian) == 'Bahagian Restorasi dan Hutan Industri' ? 'selected' : '' }}>Bahagian Restorasi dan Hutan Industri</option>
                        <option value="Bahagian Pewartaan dan Konservasi" {{ old('bahagian', $user->bahagian) == 'Bahagian Pewartaan dan Konservasi' ? 'selected' : '' }}>Bahagian Pewartaan dan Konservasi</option>
                        <option value="Bahagian Perhutanan Sosial" {{ old('bahagian', $user->bahagian) == 'Bahagian Perhutanan Sosial' ? 'selected' : '' }}>Bahagian Perhutanan Sosial</option>
                        <option value="Bahagian Pencegahan dan Penguatkuasaan" {{ old('bahagian', $user->bahagian) == 'Bahagian Pencegahan dan Penguatkuasaan' ? 'selected' : '' }}>Bahagian Pencegahan dan Penguatkuasaan</option>
                        <option value="Bahagian Khidmat Pengurusan" {{ old('bahagian', $user->bahagian) == 'Bahagian Khidmat Pengurusan' ? 'selected' : '' }}>Bahagian Khidmat Pengurusan</option>
                        <option value="Bahagian Pembangunan Projek" {{ old('bahagian', $user->bahagian) == 'Bahagian Pembangunan Projek' ? 'selected' : '' }}>Bahagian Pembangunan Projek</option>
                        <option value="Unit Integriti dan Audit Dalaman" {{ old('bahagian', $user->bahagian) == 'Unit Integriti dan Audit Dalaman' ? 'selected' : '' }}>Unit Integriti dan Audit Dalaman</option>
                        <option value="Unit Korporat dan Permodenan Perkhidmatan" {{ old('bahagian', $user->bahagian) == 'Unit Korporat dan Permodenan Perkhidmatan' ? 'selected' : '' }}>Unit Korporat dan Permodenan Perkhidmatan</option>
                        <option value="Unit Pengurusan Geopark" {{ old('bahagian', $user->bahagian) == 'Unit Pengurusan Geopark' ? 'selected' : '' }}>Unit Pengurusan Geopark</option>
                        <option value="Unit Perancangan Strategik" {{ old('bahagian', $user->bahagian) == 'Unit Perancangan Strategik' ? 'selected' : '' }}>Unit Perancangan Strategik</option>
                        <option value="Unit Perundangan dan Khidmat Nasihat" {{ old('bahagian', $user->bahagian) == 'Unit Perundangan dan Khidmat Nasihat' ? 'selected' : '' }}>Unit Perundangan dan Khidmat Nasihat</option>
                        <option value="Pejabat Wilayah" {{ old('bahagian', $user->bahagian) == 'Pejabat Wilayah' ? 'selected' : '' }}>Pejabat Wilayah</option>
                    </select>
                </div>
                <div class="field">
                    <label>Wilayah <span class="required">*</span></label>
                    <select name="wilayah_id" required>
                        <option value="">-- Pilih Wilayah --</option>
                        @foreach($wilayahs as $w)
                            <option value="{{ $w->id }}" {{ old('wilayah_id', $user->wilayah_id) == $w->id ? 'selected' : '' }}>
                                {{ $w->nama_wilayah }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>Jawatan</label>
                    <input type="text" name="jawatan" value="{{ old('jawatan', $user->jawatan) }}" placeholder="Cth: Penolong Pegawai Hutan">
                </div>
                <div class="field">
                    <label>No. Telefon</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="Cth: 0123456789">
                </div>
                <div class="field">
                    <label>Tandatangan Digital</label>

                    {{-- Mode toggle --}}
                    <div class="mb-2">
                        <button type="button" id="mode-draw" class="btn btn-sm btn-primary">Lukis Tandatangan</button>
                        <button type="button" id="mode-upload" class="btn btn-sm btn-outline-secondary">Muat Naik Imej</button>
                    </div>

                    {{-- Draw mode --}}
                    <div id="draw-panel">
                        <canvas id="signature-pad" style="border:1px solid #ccc; border-radius:4px;" width="400" height="150"></canvas>
                        <div class="mt-1">
                            <button type="button" id="clear-signature" class="btn btn-sm btn-outline-danger">Kosongkan</button>
                        </div>
                    </div>

                    {{-- Upload mode --}}
                    <div id="upload-panel" style="display:none;">
                        <input type="file" id="signature-upload-input" accept="image/*">
                        <img id="upload-preview" style="display:none; max-width:300px; margin-top:8px;" />
                    </div>

                    {{-- Existing signature (edit profile only) --}}
                    @isset($user)
                        @if($user->signature)
                            <div class="mt-2">
                                <label>Tandatangan Semasa</label><br>
                                <img src="{{ Storage::url($user->signature) }}" width="200">
                            </div>
                        @endif
                    @endisset

                    {{-- This is the actual field submitted to Laravel --}}
                    <input type="file" name="signature" id="signature-final-input" style="display:none;"
                        {{ isset($user) ? '' : 'required' }}>

                    @error('signature')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-footer">
                <span></span>
                <button type="submit" class="btn-submit">Simpan</button>
            </div>
        </form>
    </div>

    <div class="form-card" style="margin-bottom:1.5rem;">
        <div class="form-card-header">
            <h2>Peranan/Posisi</h2>
            <p>Maklumat peranan/posisi akaun anda dalam sistem.</p>
        </div>
        <div class="form-section">
            <div>
                @if(Auth::guard('booking_user')->user()->can_book)
                    <span class="badge badge-success">Menempah bilik mesyuarat</span>
                @endif
            </div>
        </div>
    </div>

    {{-- Password --}}
    <div class="form-card">
        <div class="form-card-header">
            <h2>Tukar Kata Laluan</h2>
            <p>Kemaskini kata laluan akaun anda.</p>
        </div>
        <form method="POST" action="{{ route('booking.user.profile.password') }}">
            @csrf
            <div class="form-section">
                @error('current_password')
                    <div class="alert alert-error" style="margin-bottom:1rem;">
                        {{ $message }}
                    </div>
                @enderror
                <div class="field">
                    <label>Kata Laluan Semasa</label>
                    <input type="password" name="current_password" placeholder="••••••••" required>
                </div>
                <div class="field">
                    <label>Kata Laluan Baru</label>
                    <input type="password" name="password" placeholder="Minimum 8 aksara" required>
                    @error('password')
                        <div style="color:#a32d2d; font-size:12px; margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>
                <div class="field">
                    <label>Sahkan Kata Laluan Baru</label>
                    <input type="password" name="password_confirmation" placeholder="Taip semula" required>
                </div>
            </div>
            <div class="form-footer">
                <span></span>
                <button type="submit" class="btn-submit">Kemaskini Kata Laluan</button>
            </div>
        </form>
    </div>

</div>

<x-footer />

</body>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const canvas = document.getElementById('signature-pad');
    const signaturePad = new SignaturePad(canvas, {
        backgroundColor: 'rgb(255,255,255)' // important: white bg, otherwise PNG is transparent
    });

    const drawPanel = document.getElementById('draw-panel');
    const uploadPanel = document.getElementById('upload-panel');
    const modeDrawBtn = document.getElementById('mode-draw');
    const modeUploadBtn = document.getElementById('mode-upload');
    const uploadInput = document.getElementById('signature-upload-input');
    const uploadPreview = document.getElementById('upload-preview');
    const finalInput = document.getElementById('signature-final-input');
    const clearBtn = document.getElementById('clear-signature');
    const form = finalInput.closest('form');

    // Toggle modes
    modeDrawBtn.addEventListener('click', () => {
        drawPanel.style.display = 'block';
        uploadPanel.style.display = 'none';
        modeDrawBtn.classList.replace('btn-outline-secondary', 'btn-primary');
        modeUploadBtn.classList.replace('btn-primary', 'btn-outline-secondary');
    });

    modeUploadBtn.addEventListener('click', () => {
        uploadPanel.style.display = 'block';
        drawPanel.style.display = 'none';
        modeUploadBtn.classList.replace('btn-outline-secondary', 'btn-primary');
        modeDrawBtn.classList.replace('btn-primary', 'btn-outline-secondary');
    });

    // Clear canvas
    clearBtn.addEventListener('click', () => signaturePad.clear());

    // Preview uploaded image
    uploadInput.addEventListener('change', () => {
        const file = uploadInput.files[0];
        if (file) {
            uploadPreview.src = URL.createObjectURL(file);
            uploadPreview.style.display = 'block';
        }
    });

    // Before submit: figure out which mode is active and build the final file
    form.addEventListener('submit', function (e) {
        const isDrawMode = drawPanel.style.display !== 'none';

        if (isDrawMode) {
            if (signaturePad.isEmpty()) {
                // no drawing, no upload — let native "required" handle it if it's registration
                // for edit profile, allow submit through since it's optional
                return;
            }

            e.preventDefault(); // pause submit while we convert canvas -> file

            canvas.toBlob(function (blob) {
                const file = new File([blob], 'signature.png', { type: 'image/png' });
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                finalInput.files = dataTransfer.files;
                form.submit(); // now actually submit
            }, 'image/png');

        } else {
            // upload mode: copy the uploaded file into the final input
            if (uploadInput.files.length > 0) {
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(uploadInput.files[0]);
                finalInput.files = dataTransfer.files;
            }
        }
    });
});
</script>
</html>