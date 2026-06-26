@extends('layouts.admin')

@section('title', 'Scan Absensi')

@section('content')

    <div class="header-section">
        <h2>Scan Absensi Mahasiswa</h2>
        <p>Pilih metode scan: Upload Foto, QR Code, atau Barcode 1D.</p>
    </div>

    <div class="form-container" style="max-width: 660px; margin: 0 auto;">

        @if(session('success'))
            <div style="background: #ecfdf5; color: #059669; padding: 16px; border-radius: 12px; border: 1px solid #a7f3d0; margin-bottom: 24px; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                <i class="ph-bold ph-check-circle" style="font-size: 20px;"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="background: #fef2f2; color: #dc2626; padding: 16px; border-radius: 12px; border: 1px solid #fecaca; margin-bottom: 24px; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                <i class="ph-bold ph-warning-circle" style="font-size: 20px;"></i> {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div style="background: #fef2f2; color: #dc2626; padding: 16px; border-radius: 12px; border: 1px solid #fecaca; margin-bottom: 24px; font-size: 14px;">
                <ul style="margin-left: 15px; margin-bottom: 0;">
                    @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach
                </ul>
            </div>
        @endif

        <form id="scanForm" action="{{ url('/admin/scan') }}" method="POST">
            @csrf

            {{-- ============================================================
                 STEP 1 — PILIH EVENT
            ============================================================= --}}
            <div class="form-group" style="background: #f8fafc; border: 2px solid #cbd5e1; border-radius: 16px; padding: 20px; margin-bottom: 24px;">
                <label style="font-size: 15px; font-weight: 700; color: #0f172a; display: block; margin-bottom: 10px;">
                    <i class="ph-bold ph-calendar-check" style="color: #4f46e5;"></i> 1. Pilih Event Aktif (Wajib)
                </label>
                <select name="event_id" id="event_id" class="form-control" required
                    style="cursor: pointer; font-size: 15px; padding: 12px 16px; border-radius: 12px; background: white; border: 1px solid #94a3b8; width: 100%; font-weight: 600; color: #334155;">
                    <option value="">-- Silakan Pilih Event Terlebih Dahulu --</option>
                    @foreach($events as $event)
                        <option value="{{ $event->id }}">{{ $event->nama_event }}</option>
                    @endforeach
                </select>
            </div>

            {{-- ============================================================
                 STEP 2 — TAB SELECTOR (3 Mode Terisolasi)
            ============================================================= --}}
            <div style="display: flex; gap: 6px; margin-bottom: 0;">
                <button type="button" id="tab-upload"
                    onclick="activateMode('upload')"
                    style="flex: 1; padding: 10px 6px; border-radius: 12px 12px 0 0; font-size: 13px; font-weight: 700; border: 2px solid #4f46e5; background: #4f46e5; color: white; cursor: pointer;">
                    <i class="ph-bold ph-image-square"></i><br>Upload Foto
                </button>
                <button type="button" id="tab-qr"
                    onclick="activateMode('qr')"
                    style="flex: 1; padding: 10px 6px; border-radius: 12px 12px 0 0; font-size: 13px; font-weight: 700; border: 2px solid #e2e8f0; background: #f8fafc; color: #64748b; cursor: pointer;">
                    <i class="ph-bold ph-qr-code"></i><br>Kamera QR Code
                </button>
                <button type="button" id="tab-barcode"
                    onclick="activateMode('barcode')"
                    style="flex: 1; padding: 10px 6px; border-radius: 12px 12px 0 0; font-size: 13px; font-weight: 700; border: 2px solid #e2e8f0; background: #f8fafc; color: #64748b; cursor: pointer;">
                    <i class="ph-bold ph-barcode"></i><br>Kamera Barcode
                </button>
            </div>

            {{-- ============================================================
                 PANEL A — UPLOAD FOTO (Terisolasi)
            ============================================================= --}}
            <div id="panel-upload"
                style="background: #f8fafc; border: 2px solid #4f46e5; border-top: none; border-radius: 0 0 16px 16px; padding: 24px; margin-bottom: 24px;">

                <p style="color: #334155; font-size: 13px; margin-bottom: 16px; line-height: 1.7;">
                    📸 <strong>Cara terbaik & paling andal:</strong> Foto bagian <u>Barcode atau QR Code</u> di KTM menggunakan HP Anda, lalu upload fotonya. Sistem membaca NIM dari file gambar secara langsung — tidak tergantung kualitas kamera laptop.
                </p>

                <label for="uploadFile" id="dropzone"
                    style="display: block; border: 2px dashed #a5b4fc; border-radius: 12px; padding: 28px 16px; text-align: center; cursor: pointer; background: white; transition: all 0.2s; margin-bottom: 0;">
                    <i class="ph-bold ph-upload-simple" style="font-size: 36px; color: #a5b4fc; display: block; margin-bottom: 8px;"></i>
                    <span style="font-weight: 600; color: #4f46e5; font-size: 15px;">Klik untuk memilih / Drag & Drop foto di sini</span>
                    <p style="color: #94a3b8; font-size: 12px; margin: 6px 0 0;">Mendukung JPG, PNG, HEIC (foto dari HP langsung)</p>
                    <input type="file" id="uploadFile" accept="image/*" style="display: none;">
                </label>

                <div id="uploadPreviewArea" style="display: none; margin-top: 16px;">
                    <img id="uploadPreviewImg" src="" alt="Preview"
                        style="max-width: 100%; max-height: 200px; object-fit: contain; border-radius: 8px; border: 1px solid #e2e8f0; display: block; margin: 0 auto 12px;">
                    <div id="uploadStatus" style="padding: 12px 16px; border-radius: 8px; font-weight: 600; font-size: 14px; text-align: center;"></div>
                </div>

                {{-- DOM terisolasi untuk file scanner — tidak boleh dipakai oleh kamera --}}
                <div id="reader-upload" style="position: absolute; top: -9999px; left: -9999px; width: 1px; height: 1px; visibility: hidden;"></div>
            </div>

            {{-- ============================================================
                 PANEL B — KAMERA QR CODE (Terisolasi)
            ============================================================= --}}
            <div id="panel-qr"
                style="display: none; background: #f0fdf4; border: 2px solid #e2e8f0; border-top: none; border-radius: 0 0 16px 16px; padding: 20px; margin-bottom: 24px;">

                <div style="background: #dcfce7; border: 1px solid #bbf7d0; border-radius: 10px; padding: 12px 16px; margin-bottom: 16px; font-size: 13px; color: #166534;">
                    <strong>Mode QR Code:</strong> Scanner hanya akan membaca QR Code. Arahkan QR Code KTM tepat ke tengah kotak hijau.
                </div>

                {{-- DOM terisolasi untuk QR Live Camera --}}
                <div id="reader-qr" style="width: 100%; background: white; border-radius: 12px; overflow: hidden; border: 1px solid #bbf7d0;"></div>
                <p style="color: #64748b; font-size: 12px; margin-top: 8px; text-align: center;">Jaga jarak 10–20cm · Cahaya cukup · Posisi tegak lurus</p>
            </div>

            {{-- ============================================================
                 PANEL C — KAMERA BARCODE 1D (Terisolasi)
            ============================================================= --}}
            <div id="panel-barcode"
                style="display: none; background: #fff7ed; border: 2px solid #e2e8f0; border-top: none; border-radius: 0 0 16px 16px; padding: 20px; margin-bottom: 24px;">

                <div style="background: #ffedd5; border: 1px solid #fed7aa; border-radius: 10px; padding: 12px 16px; margin-bottom: 16px; font-size: 13px; color: #9a3412;">
                    <strong>Mode Barcode 1D:</strong> Scanner hanya akan membaca Barcode garis (Code 39 / Code 128). Pastikan seluruh garis barcode dari ujung kiri sampai kanan masuk ke dalam kotak oranye.
                </div>

                {{-- DOM terisolasi untuk Barcode Live Camera --}}
                <div id="reader-barcode" style="width: 100%; background: white; border-radius: 12px; overflow: hidden; border: 1px solid #fed7aa;"></div>
                <p style="color: #64748b; font-size: 12px; margin-top: 8px; text-align: center;">Jaga jarak 15–30cm · Barcode harus seluruhnya masuk kotak · Tidak boleh miring</p>
            </div>

            {{-- NIM RESULT --}}
            <div class="form-group">
                <label>NIM Mahasiswa (terisi otomatis setelah scan)</label>
                <input type="text" name="nim" id="nim"
                    placeholder="[ Menunggu hasil scan... ]"
                    required readonly class="form-control"
                    style="background: #f1f5f9; color: #64748b; font-weight: 700; text-align: center; font-family: monospace; font-size: 18px; letter-spacing: 1px;">
            </div>

            <button type="button" class="btn btn-secondary" disabled style="width: 100%; opacity: 0.6;">
                <i class="ph-bold ph-lock-key"></i> Otomatis submit setelah scan berhasil
            </button>
        </form>
    </div>

@endsection

@section('extra_js')
{{-- Versi terkunci untuk stabilitas --}}
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {

    // ====================================================================
    // STATE — satu instance aktif per waktu
    // ====================================================================
    let activeScanner = null;   // Html5QrcodeScanner instance yang sedang berjalan
    let activeMode   = 'upload'; // 'upload' | 'qr' | 'barcode'
    const MODES = ['upload', 'qr', 'barcode'];

    // ====================================================================
    // UTILS
    // ====================================================================
    function setNimResult(text) {
        const nimField = document.getElementById('nim');
        nimField.value = text.trim();
        nimField.style.background = '#ecfdf5';
        nimField.style.color = '#059669';
    }

    function processResult(decodedText) {
        const eventSelect = document.getElementById('event_id');
        if (!eventSelect.value) {
            alert("⚠️ Pilih Event Aktif terlebih dahulu sebelum scan!");
            eventSelect.focus();
            return;
        }
        setNimResult(decodedText);
        // Hancurkan scanner sebelum submit agar tidak ada konflik
        destroyActiveScanner().then(() => {
            document.getElementById('scanForm').submit();
        });
    }

    // ====================================================================
    // DESTROY: selalu hancurkan scanner aktif dulu sebelum membuat baru
    // ====================================================================
    function destroyActiveScanner() {
        if (activeScanner) {
            const s = activeScanner;
            activeScanner = null;
            return s.clear().catch(() => Promise.resolve());
        }
        return Promise.resolve();
    }

    // ====================================================================
    // ACTIVATE MODE — Tab Switching
    // ====================================================================
    window.activateMode = function(mode) {
        if (activeMode === mode) return; // Sudah aktif, tidak perlu apa-apa
        activeMode = mode;

        // 1. Hancurkan scanner aktif sebelumnya (penting agar tidak double-stream)
        destroyActiveScanner().then(() => {
            // 2. Sembunyikan semua panel & reset semua tab button
            MODES.forEach(m => {
                document.getElementById('panel-' + m).style.display = 'none';
                const btn = document.getElementById('tab-' + m);
                btn.style.background = '#f8fafc';
                btn.style.color = '#64748b';
                btn.style.borderColor = '#e2e8f0';
            });

            // 3. Aktifkan tab yang dipilih
            const activeBtn = document.getElementById('tab-' + mode);
            activeBtn.style.background = '#4f46e5';
            activeBtn.style.color = 'white';
            activeBtn.style.borderColor = '#4f46e5';
            document.getElementById('panel-' + mode).style.display = 'block';

            // 4. Mulai scanner yang sesuai (hanya jika mode kamera)
            if (mode === 'qr') {
                startQrScanner();
            } else if (mode === 'barcode') {
                startBarcodeScanner();
            }
            // Mode 'upload' tidak perlu start apapun
        });
    };

    // ====================================================================
    // SCANNER QR CODE — Terisolasi di div #reader-qr
    // Format: HANYA QR_CODE (enum 0)
    // ====================================================================
    function startQrScanner() {
        activeScanner = new Html5QrcodeScanner(
            "reader-qr",  // <-- Container terisolasi milik QR saja
            {
                fps: 10,
                useBarCodeDetectorIfSupported: false, // Paksa ZXing JS (HTTP safe)
                formatsToSupport: [ 0 ], // 0 = QR_CODE saja — tidak ada format lain
                qrbox: function(w, h) {
                    const side = Math.min(Math.round(Math.min(w, h) * 0.75), 280);
                    return { width: side, height: side }; // Kotak persegi untuk QR
                },
                videoConstraints: { width: { ideal: 640 }, height: { ideal: 480 } },
                rememberLastUsedCamera: true
            },
            /* verbose= */ false
        );
        activeScanner.render(
            (decodedText) => processResult(decodedText),
            () => {} // Abaikan error per frame
        );
    }

    // ====================================================================
    // SCANNER BARCODE 1D — Terisolasi di div #reader-barcode
    // Format: HANYA Code 39 (3) + Code 128 (5) — tidak ada QR
    // ====================================================================
    function startBarcodeScanner() {
        activeScanner = new Html5QrcodeScanner(
            "reader-barcode",  // <-- Container terisolasi milik Barcode saja
            {
                fps: 10,
                useBarCodeDetectorIfSupported: false, // Paksa ZXing JS (HTTP safe)
                formatsToSupport: [ 3, 5 ], // 3 = CODE_39, 5 = CODE_128 — tidak ada QR
                qrbox: function(w, h) {
                    // Kotak ultrawide persegi panjang khusus barcode 1D
                    const boxW = Math.min(Math.round(w * 0.88), 520);
                    const boxH = Math.min(Math.round(h * 0.30), 140);
                    return { width: boxW, height: boxH };
                },
                videoConstraints: { width: { ideal: 640 }, height: { ideal: 480 } },
                rememberLastUsedCamera: true
            },
            /* verbose= */ false
        );
        activeScanner.render(
            (decodedText) => processResult(decodedText),
            () => {} // Abaikan error per frame
        );
    }

    // ====================================================================
    // UPLOAD FOTO — Terisolasi di div #reader-upload
    // Menggunakan Html5Qrcode (low-level), bukan Scanner wrapper
    // ====================================================================
    const uploadInput = document.getElementById('uploadFile');
    const dropzone    = document.getElementById('dropzone');

    uploadInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) handleUploadFile(e.target.files[0]);
    });

    // Drag & Drop
    dropzone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropzone.style.borderColor = '#4f46e5';
        dropzone.style.background  = '#eef2ff';
    });
    dropzone.addEventListener('dragleave', () => {
        dropzone.style.borderColor = '#a5b4fc';
        dropzone.style.background  = 'white';
    });
    dropzone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropzone.style.borderColor = '#a5b4fc';
        dropzone.style.background  = 'white';
        if (e.dataTransfer.files.length > 0) handleUploadFile(e.dataTransfer.files[0]);
    });

    function handleUploadFile(file) {
        const previewArea = document.getElementById('uploadPreviewArea');
        const previewImg  = document.getElementById('uploadPreviewImg');
        const statusDiv   = document.getElementById('uploadStatus');

        // Tampilkan preview gambar
        const fr = new FileReader();
        fr.onload = (e) => { previewImg.src = e.target.result; previewArea.style.display = 'block'; };
        fr.readAsDataURL(file);

        // Loading state
        statusDiv.innerHTML = '⏳ Sedang membaca barcode dari gambar...';
        statusDiv.style.background = '#fefce8';
        statusDiv.style.color = '#a16207';

        // Gunakan instance terpisah di div #reader-upload
        // Tidak ada formatsToSupport → membaca QR DAN Barcode dari file
        const fileScanner = new Html5Qrcode("reader-upload");
        fileScanner.scanFile(file, /* showImage= */ false)
            .then((decodedText) => {
                statusDiv.innerHTML = '✅ Terdeteksi: <strong>' + decodedText + '</strong> — Menyimpan kehadiran...';
                statusDiv.style.background = '#ecfdf5';
                statusDiv.style.color = '#059669';
                setTimeout(() => processResult(decodedText), 700);
            })
            .catch((err) => {
                statusDiv.innerHTML = '❌ Barcode/QR tidak terdeteksi. Pastikan foto tajam, tidak terpotong, dan pencahayaan cukup. Coba zoom lebih dekat ke bagian barcode saja.';
                statusDiv.style.background = '#fef2f2';
                statusDiv.style.color = '#dc2626';
                uploadInput.value = '';
                console.error('[Upload Scanner] Error:', err);
            })
            .finally(() => {
                // Hapus instance setelah selesai agar DOM tetap bersih
                fileScanner.clear().catch(() => {});
            });
    }

});
</script>
@endsection