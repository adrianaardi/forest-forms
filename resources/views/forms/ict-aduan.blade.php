<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borang Aduan ICT — Jabatan Hutan Sarawak</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link rel="icon" href="{{ asset('images/logo-icon.png')}}">

</head>

<body class="pg">

<header>
    <div class="logo"></div>
    <div>
        <h1>Jabatan Hutan Sarawak</h1>
        <p>Forest Department Sarawak — Sistem Perkhidmatan Dalaman</p>
    </div>
</header>

<x-navbar />

<div class="pg-body">

    @if($errors->any())
        <div style="background:#fdf0f0; border:1px solid #f5c1c1; color:#a32d2d; padding:0.75rem 1rem; border-radius:8px; margin-bottom:1rem; font-size:14px;">
            <ul style="margin:0; padding-left:1.2rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-card">
        <div class="form-card-header">
            <h2>Borang Aduan Baikpulih ICT / Digital</h2>
            <p>Sila isi semua maklumat yang diperlukan dengan tepat.</p>
        </div>

        <form method="POST" action="/forms/ict-aduan" enctype="multipart/form-data" onsubmit="attachFilesToForm()">            @csrf

            <!-- SECTION A -->
            <div class="form-section">
                <div class="section-label">Bahagian A — Maklumat Pengadu</div>

                <div class="field-row">
                    <div class="field">
                        <label>Nama <span style="color:#c0392b">*</span></label>
                        <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Nama penuh" required>
                    </div>

                    <div class="field">
                        <label>Jawatan</label>
                        <input type="text" name="jawatan" value="{{ old('jawatan') }}" placeholder="Jawatan anda">
                    </div>
                </div>

                <div class="field-row">

                    <!-- BAHAGIAN -->
                    <div class="field">
                        <label>Bahagian / Unit</label>
                        <select name="bahagian" id="bahagian" onchange="toggleBahagianLain()">
                            <option value="">-- Pilih Bahagian --</option>

                            <option value="Director Office" {{ old('bahagian') == 'Director Office' ? 'selected' : '' }}>Director Office</option>
                            <option value="Deputy Director (Development & Forest Conservation) Office" {{ old('bahagian') == 'Deputy Director (Development & Forest Conservation) Office' ? 'selected' : '' }}>Deputy Director (Development & Forest Conservation) Office</option>
                            <option value="Deputy Director (Forest Management) Office" {{ old('bahagian') == 'Deputy Director (Forest Management) Office' ? 'selected' : '' }}>Deputy Director (Forest Management) Office</option>

                            <option value="Integrity & Internal Audit Unit" {{ old('bahagian') == 'Integrity & Internal Audit Unit' ? 'selected' : '' }}>Integrity & Internal Audit Unit</option>
                            <option value="Strategic Planning Unit" {{ old('bahagian') == 'Strategic Planning Unit' ? 'selected' : '' }}>Strategic Planning Unit</option>
                            <option value="Corporate & Service Modernization Unit" {{ old('bahagian') == 'Corporate & Service Modernization Unit' ? 'selected' : '' }}>Corporate & Service Modernization Unit</option>

                            <option value="Geopark Management Unit" {{ old('bahagian') == 'Geopark Management Unit' ? 'selected' : '' }}>Geopark Management Unit</option>
                            <option value="Legal & Advisory Unit" {{ old('bahagian') == 'Legal & Advisory Unit' ? 'selected' : '' }}>Legal & Advisory Unit</option>
                            <option value="Management Services Division" {{ old('bahagian') == 'Management Services Division' ? 'selected' : '' }}>Management Services Division</option>

                            <option value="Project Development Division" {{ old('bahagian') == 'Project Development Division' ? 'selected' : '' }}>Project Development Division</option>
                            <option value="Revenue & Data Management Division" {{ old('bahagian') == 'Revenue & Data Management Division' ? 'selected' : '' }}>Revenue & Data Management Division</option>
                            <option value="Social Forestry Division" {{ old('bahagian') == 'Social Forestry Division' ? 'selected' : '' }}>Social Forestry Division</option>

                            <option value="International Affairs Division" {{ old('bahagian') == 'International Affairs Division' ? 'selected' : '' }}>International Affairs Division</option>
                            <option value="Planning & Management Division" {{ old('bahagian') == 'Planning & Management Division' ? 'selected' : '' }}>Planning & Management Division</option>
                            <option value="Licensing Division" {{ old('bahagian') == 'Licensing Division' ? 'selected' : '' }}>Licensing Division</option>

                            <option value="Forest Technology & Geospatial Division" {{ old('bahagian') == 'Forest Technology & Geospatial Division' ? 'selected' : '' }}>Forest Technology & Geospatial Division</option>
                            <option value="Restoration & Industrial Forest Division" {{ old('bahagian') == 'Restoration & Industrial Forest Division' ? 'selected' : '' }}>Restoration & Industrial Forest Division</option>
                            <option value="Industrial Forest Research Centre (IFRC)" {{ old('bahagian') == 'Industrial Forest Research Centre (IFRC)' ? 'selected' : '' }}>Industrial Forest Research Centre (IFRC)</option>

                            <option value="Constitution and Conservation Division" {{ old('bahagian') == 'Constitution and Conservation Division' ? 'selected' : '' }}>Constitution and Conservation Division</option>
                            <option value="Preventive & Enforcement Division" {{ old('bahagian') == 'Preventive & Enforcement Division' ? 'selected' : '' }}>Preventive & Enforcement Division</option>
                            <option value="Research & Development Division" {{ old('bahagian') == 'Research & Development Division' ? 'selected' : '' }}>Research & Development Division</option>

                            <option value="lain" {{ old('bahagian') == 'lain' ? 'selected' : '' }}>Lain-lain (Sila nyatakan)</option>
                        </select>

                        <div id="bahagian-lain-box" style="display:none; margin-top:8px;">
                            <input type="text" name="bahagian_lain" value="{{ old('bahagian_lain') }}" placeholder="Sila nyatakan bahagian">
                        </div>
                    </div>

                    <!-- WILAYAH -->
                    <div class="field">
                        <label>Wilayah</label>
                        <select name="wilayah" id="wilayah" onchange="toggleWilayahLain()">
                            <option value="">-- Pilih Wilayah --</option>
                            <option value="Ibu Pejabat" {{ old('wilayah') == 'Ibu Pejabat' ? 'selected' : '' }}>Ibu Pejabat</option>
                            <option value="Kuching" {{ old('wilayah') == 'Kuching' ? 'selected' : '' }}>Kuching</option>
                            <option value="Sibu" {{ old('wilayah') == 'Sibu' ? 'selected' : '' }}>Sibu</option>
                            <option value="Bintulu" {{ old('wilayah') == 'Bintulu' ? 'selected' : '' }}>Bintulu</option>
                            <option value="Miri" {{ old('wilayah') == 'Miri' ? 'selected' : '' }}>Miri</option>
                            <option value="Sri Aman" {{ old('wilayah') == 'Sri Aman' ? 'selected' : '' }}>Sri Aman</option>
                            <option value="Sarikei" {{ old('wilayah') == 'Sarikei' ? 'selected' : '' }}>Sarikei</option>
                            <option value="Kapit" {{ old('wilayah') == 'Kapit' ? 'selected' : '' }}>Kapit</option>
                            <option value="Limbang" {{ old('wilayah') == 'Limbang' ? 'selected' : '' }}>Limbang</option>
                        </select>

                </div>
                <div class="field-row">
                    <div class="field">
                        <label>No Telefon</label>
                        <input type="text" name="telefon" value="{{ old('telefon') }}" placeholder="Cth: 082-XXXXXX">
                    </div>

                    <div class="field">
                        <label>Emel</label>
                        <input type="email" name="emel" value="{{ old('emel') }}" placeholder="contoh@emel.com" required>
                    </div>
                </div>
            </div>

            <!-- SECTION B -->
            <div class="form-section">
                <div class="section-label">Bahagian B — Maklumat Kerosakan</div>

                <div class="field">
                    <label>Kategori Masalah <span style="color:#c0392b">*</span></label>
                    <select name="kategori_masalah" id="kategori" onchange="toggleLain()" required>
                        <option value="">-- Pilih kategori --</option>
                        <option value="CPU" {{ old('kategori_masalah') == 'CPU' ? 'selected' : '' }}>CPU</option>
                        <option value="Monitor" {{ old('kategori_masalah') == 'Monitor' ? 'selected' : '' }}>Monitor</option>
                        <option value="Printer" {{ old('kategori_masalah') == 'Printer' ? 'selected' : '' }}>Printer</option>
                        <option value="Scanner" {{ old('kategori_masalah') == 'Scanner' ? 'selected' : '' }}>Scanner</option>
                        <option value="Perisian" {{ old('kategori_masalah') == 'Perisian' ? 'selected' : '' }}>Perisian</option>
                        <option value="Internet" {{ old('kategori_masalah') == 'Internet' ? 'selected' : '' }}>Internet</option>
                        <option value="lain" {{ old('kategori_masalah') == 'lain' ? 'selected' : '' }}>Lain-lain (Nyatakan)</option>
                    </select>

                    <div id="lain-box" style="display:none; margin-top:8px;">
                        <input type="text" name="masalah_lain" value="{{ old('masalah_lain') }}" placeholder="Sila nyatakan masalah">
                    </div>
                </div>

                <div class="field">
                    <label>Keterangan Kerosakan</label>
                    <textarea name="keterangan_kerosakan" rows="4" placeholder="Huraikan masalah dengan lebih lanjut...">{{ old('keterangan_kerosakan') }}</textarea>
                </div>
            </div>

            <!-- ATTACHMENT -->
            <div class="form-section">
                <div class="section-label">Bahagian C — Lampiran</div>

                <div class="field">

                    <label>Attachment (maksimum 5 fail)</label>
                    <input type="file" id="fileInput" multiple name="attachments[]" onchange="handleFiles(this)">                    <div id="file-preview" style="margin-top:10px;"></div>

                    <small style="color:gray;">
                        Anda boleh pilih banyak fail sekaligus atau satu per satu
                    </small>

                </div>
            </div>

            <!-- FOOTER -->
            <div class="form-footer">
                <a href="/" class="btn-back">← Kembali</a>
                <button type="submit" class="btn-submit">Hantar Aduan</button>
            </div>

        </form>
    </div>
</div>

<div id="imageModal" style="
    display:none;
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.85);
    justify-content:center;
    align-items:center;
    z-index:9999;
">

    <span onclick="closeModal()" style="
        position:absolute;
        top:20px;
        right:30px;
        color:white;
        font-size:35px;
        cursor:pointer;
    ">×</span>

    <img id="modalImg" style="
        max-width:90%;
        max-height:90%;
        border-radius:10px;
    ">
</div>


<script>
function toggleLain() {
    var v = document.getElementById('kategori').value;
    document.getElementById('lain-box').style.display = (v === 'lain') ? 'block' : 'none';
}

function toggleBahagianLain() {
    var v = document.getElementById('bahagian').value;
    document.getElementById('bahagian-lain-box').style.display = (v === 'lain') ? 'block' : 'none';
}

document.addEventListener("DOMContentLoaded", function () {
    toggleBahagianLain();
    toggleWilayahLain();
});

let selectedFiles = [];
const maxFiles = 5;

function handleFiles(input) {

    let newFiles = Array.from(input.files);

    // add new files (hybrid upload)
    selectedFiles = selectedFiles.concat(newFiles);

    // enforce max 5 files
    if (selectedFiles.length > maxFiles) {
        alert("Maksimum 5 fail sahaja dibenarkan");
        selectedFiles = selectedFiles.slice(0, maxFiles);
    }

    renderPreview();

    // reset input so same file can be re-selected if needed
    input.value = '';
}

function renderPreview() {

    let preview = document.getElementById('file-preview');
    preview.innerHTML = '';

    selectedFiles.forEach((file, index) => {

        if (file.type.startsWith('image/')) {

            let reader = new FileReader();

            reader.onload = function(e) {
                preview.innerHTML += `
                    <div style="display:inline-block;margin:5px;text-align:center;position:relative;">
                        
                        <img src="${e.target.result}"
                            style="width:100px;height:100px;object-fit:cover;border-radius:8px;cursor:pointer"
                            onclick="openModal('${e.target.result}')">

                        <!-- DELETE BUTTON -->
                        <button type="button" onclick="removeFile(${index})"
                            style="
                                position:absolute;
                                top:-5px;
                                right:-5px;
                                background:red;
                                color:white;
                                border:none;
                                border-radius:50%;
                                width:20px;
                                height:20px;
                                cursor:pointer;
                                font-size:12px;
                            ">×</button>

                        <div style="font-size:12px;">${file.name}</div>
                    </div>
                `;
            };

            reader.readAsDataURL(file);

        } else {
            preview.innerHTML += `
                <div style="margin:5px; position:relative; display:inline-block;">
                    📄 ${file.name}

                    <!-- DELETE BUTTON -->
                    <button type="button" onclick="removeFile(${index})"
                        style="
                            margin-left:10px;
                            background:red;
                            color:white;
                            border:none;
                            border-radius:5px;
                            cursor:pointer;
                        ">Remove</button>
                </div>
            `;
        }
    });
}

function removeFile(index) {
    selectedFiles.splice(index, 1);
    renderPreview();
}

function attachFilesToForm() {

    let input = document.getElementById('fileInput');

    let dataTransfer = new DataTransfer();

    selectedFiles.forEach(file => {
        dataTransfer.items.add(file);
    });

    input.files = dataTransfer.files;
}

function openModal(src) {
    document.getElementById('modalImg').src = src;
    document.getElementById('imageModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('imageModal').style.display = 'none';
}
</script>
</body>
</html>