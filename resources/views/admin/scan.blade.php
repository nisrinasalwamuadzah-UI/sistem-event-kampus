@extends('layouts.admin')

@section('title', 'Scan Absensi')

@section('content')

    <div class="header-section">
        <h2>Scan Absensi Mahasiswa</h2>
        <p>Pilih mode kamera sesuai dengan jenis kartu (KTM Fisik = Barcode 1D, HP = QR Code).</p>
    </div>

    <div class="form-container" style="max-width: 600px; margin: 0 auto;">



        <form action="{{ url('/admin/scan') }}" method="POST" id="scanForm">
            @csrf
            
            <!-- STEP 1: PILIH EVENT -->
            <div class="form-group" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; padding: 20px; margin-bottom: 24px;">
                <label style="font-size: 14px; font-weight: 600; color: #334155; margin-bottom: 12px; display: block;">
                    <i class="ph-bold ph-calendar-blank"></i> 1. Pilih Event Aktif (Wajib)
                </label>
                <select name="event_id" id="event_id" class="form-control" required style="padding: 12px; border-radius: 10px; border: 1px solid #cbd5e1; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                    <option value="">-- Pilih Event --</option>
                    @foreach($events as $event)
                        <option value="{{ $event->id }}">{{ $event->nama_event }}</option>
                    @endforeach
                </select>
            </div>

            <!-- STEP 2: DUAL ENGINE CAMERA TABS -->
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; padding: 20px; margin-bottom: 24px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                    <label style="font-size: 14px; font-weight: 600; color: #334155; margin: 0;">
                        <i class="ph-bold ph-video-camera"></i> 2. Arahkan Kamera
                    </label>
                    <span id="scanIndicator" style="display: inline-flex; align-items: center; font-size: 12px; font-weight: 600; color: #059669; background: #dcfce7; padding: 4px 8px; border-radius: 12px;">
                        <span class="pulsing-dot" style="width: 8px; height: 8px; background: #059669; border-radius: 50%; display: inline-block; margin-right: 6px;"></span>
                        Kamera Aktif
                    </span>
                </div>

                <!-- TABS -->
                <div style="display: flex; gap: 10px; margin-bottom: 15px;">
                    <button type="button" id="tab-barcode" onclick="switchTab('barcode')" style="flex: 1; padding: 10px; border-radius: 8px; border: none; background: #3b82f6; color: white; font-weight: bold; cursor: pointer; transition: all 0.2s;">
                        <i class="ph-bold ph-barcode"></i> Barcode (KTM)
                    </button>
                    <button type="button" id="tab-qr" onclick="switchTab('qr')" style="flex: 1; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1; background: #f1f5f9; color: #64748b; font-weight: bold; cursor: pointer; transition: all 0.2s;">
                        <i class="ph-bold ph-qr-code"></i> QR Code (HP)
                    </button>
                </div>
                
                <!-- CONTAINER BARCODE (QUAGGA2) -->
                <div id="container-barcode" style="position: relative; border-radius: 12px; overflow: hidden; border: 2px solid #3b82f6; background: black; min-height: 250px; display: block;">
                    <div id="barcode-reader" class="viewport" style="width: 100%;"></div>
                    <div id="laser-overlay-barcode" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; display: block;">
                        <div class="laser-line"></div>
                    </div>
                </div>

                <!-- CONTAINER QR CODE (ZXING) -->
                <div id="container-qr" style="position: relative; border-radius: 12px; overflow: hidden; border: 2px solid #cbd5e1; background: black; min-height: 250px; display: none;">
                    <div id="qr-reader" style="width: 100%;"></div>
                </div>

                <p id="helper-text" style="color: #64748b; font-size: 12px; margin-top: 10px; text-align: center;">
                    💡 Pastikan garis barcode terlihat jelas di dalam layar kamera.
                </p>
            </div>

            <!-- NIM RESULT (MANUAL INPUT UNLOCKED) -->
            <div class="form-group">
                <label style="display: flex; justify-content: space-between; align-items: center;">
                    <span>NIM Mahasiswa</span>
                    <span style="font-size: 11px; color: #b45309; background: #fef3c7; padding: 2px 8px; border-radius: 8px;">Bisa diketik manual</span>
                </label>
                <input type="text" name="nim" id="nim"
                    placeholder="Scan otomatis, atau ketik manual..."
                    required class="form-control"
                    style="background: white; color: #0f172a; font-weight: 700; text-align: center; font-family: monospace; font-size: 18px; letter-spacing: 1px; border: 2px solid #cbd5e1;">
            </div>

            <button id="submitBtn" type="submit" class="btn btn-primary" style="width: 100%; font-weight: bold; background: #4f46e5; border-color: #4f46e5; padding: 12px; font-size: 16px;">
                <i class="ph-bold ph-check-circle"></i> Submit Kehadiran
            </button>
        </form>

    </div>

@endsection

@section('extra_js')
<style>
.laser-line { width: 100%; height: 3px; background: rgba(239, 68, 68, 0.9); box-shadow: 0 0 15px 3px rgba(239, 68, 68, 0.7); position: absolute; animation: scan 2.5s infinite ease-in-out; }
@keyframes scan { 0% { top: 5%; opacity: 0; } 10% { opacity: 1; } 90% { opacity: 1; } 100% { top: 95%; opacity: 0; } }
.pulsing-dot { animation: pulse 1s infinite alternate; }
@keyframes pulse { 0% { opacity: 0.3; } 100% { opacity: 1; } }

/* Quagga2 Inject Styles */
#barcode-reader video { width: 100% !important; object-fit: cover; }
#barcode-reader canvas.drawing, #barcode-reader canvas.drawingBuffer { position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; }

</style>

<!-- Load Both Engines -->
<script src="https://cdn.jsdelivr.net/npm/@ericblade/quagga2/dist/quagga.min.js"></script>
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", () => {

    // --- 1. STATE MANAGEMENT ---
    let currentMode = localStorage.getItem('scan_mode') || 'barcode';
    let html5QrcodeScanner = null;
    let isProcessing = false;
    let isAlertShowing = false;

    // Pulihkan Event ID yang terakhir dipilih
    const eventSelect = document.getElementById('event_id');
    const savedEventId = localStorage.getItem('selected_event_id');
    if (savedEventId && [...eventSelect.options].some(opt => opt.value === savedEventId)) {
        eventSelect.value = savedEventId;
    }
    eventSelect.addEventListener('change', () => {
        localStorage.setItem('selected_event_id', eventSelect.value);
    });

    // --- 2. AUDIO FEEDBACK ---
    function playBeep(type) {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = ctx.createOscillator();
            const gainNode = ctx.createGain();
            osc.connect(gainNode);
            gainNode.connect(ctx.destination);
            
            if (type === 'success') {
                osc.type = 'sine';
                osc.frequency.setValueAtTime(800, ctx.currentTime);
                osc.frequency.exponentialRampToValueAtTime(1200, ctx.currentTime + 0.15);
                gainNode.gain.setValueAtTime(1, ctx.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.15);
                osc.start();
                osc.stop(ctx.currentTime + 0.15);
            } else {
                osc.type = 'sawtooth';
                osc.frequency.setValueAtTime(150, ctx.currentTime);
                gainNode.gain.setValueAtTime(1, ctx.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.4);
                osc.start();
                osc.stop(ctx.currentTime + 0.4);
            }
        } catch (e) {
            console.log("Audio API not supported");
        }
    }

    // --- 3. UI RESETTER ---
    function resetUI() {
        const nimField = document.getElementById('nim');
        nimField.value = '';
        nimField.style.background = 'white';
        nimField.style.color = '#0f172a';
        nimField.style.borderColor = '#cbd5e1';

        const submitBtn = document.getElementById('submitBtn');
        submitBtn.innerHTML = '<i class="ph-bold ph-check-circle"></i> Submit Kehadiran';
        submitBtn.style.background = '#4f46e5';
        submitBtn.style.borderColor = '#4f46e5';
        
        const indicator = document.getElementById('scanIndicator');
        indicator.innerHTML = '<span class="pulsing-dot" style="width: 8px; height: 8px; background: #059669; border-radius: 50%; display: inline-block; margin-right: 6px;"></span> Kamera Aktif';
        indicator.style.background = '#dcfce7';
        indicator.style.color = '#059669';

        isProcessing = false;
        
        // Resume kamera
        if (currentMode === 'barcode') {
            document.getElementById('laser-overlay-barcode').style.display = 'block';
            // Quagga is mostly continuous, it will keep scanning
        } else if (currentMode === 'qr') {
            if (html5QrcodeScanner) html5QrcodeScanner.resume();
        }
        
        // Fokus kembali ke input NIM untuk ketik manual
        nimField.focus();
    }

    // --- 4. AJAX SUBMIT ---
    async function submitScan(nimValue) {
        if (!eventSelect.value) {
            alert("⚠️ Pilih Event Aktif terlebih dahulu sebelum scan!");
            eventSelect.focus();
            isProcessing = false;
            return;
        }

        // Hentikan sementara UI Kamera
        if (currentMode === 'barcode') {
            document.getElementById('laser-overlay-barcode').style.display = 'none';
        } else if (currentMode === 'qr') {
            if (html5QrcodeScanner) html5QrcodeScanner.pause(true);
        }

        try {
            const formData = new FormData();
            formData.append('nim', nimValue);
            formData.append('event_id', eventSelect.value);
            formData.append('_token', document.querySelector('input[name="_token"]').value);

            const response = await fetch('{{ url('/admin/scan') }}', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });

            const data = await response.json();
            isAlertShowing = true;

            if (data.status === 'success') {
                playBeep('success');
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message,
                    timer: 6000,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    backdrop: `rgba(16, 185, 129, 0.2)`
                }).then(() => {
                    isAlertShowing = false;
                    resetUI();
                });
            } else {
                playBeep('error');
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: data.message || 'Terjadi kesalahan sistem.',
                    showConfirmButton: true,
                    confirmButtonText: 'Tutup & Lanjutkan',
                    confirmButtonColor: '#ef4444',
                    allowOutsideClick: false,
                    backdrop: `rgba(239, 68, 68, 0.3)`
                }).then(() => {
                    isAlertShowing = false;
                    resetUI();
                });
            }

        } catch (error) {
            console.error("AJAX Error:", error);
            isAlertShowing = true;
            playBeep('error');
            Swal.fire({
                icon: 'error',
                title: 'Error Sistem / Koneksi!',
                text: 'Detail: ' + error.message,
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#ef4444',
            }).then(() => {
                isAlertShowing = false;
                resetUI();
            });
        }
    }

    // --- 5. GLOBAL RESULT HANDLER ---
    window.processResult = function(decodedText) {
        if (isProcessing || isAlertShowing) return;
        isProcessing = true;

        // Visual Feedback Instant
        const nimField = document.getElementById('nim');
        nimField.value = decodedText.trim();
        nimField.style.background = '#ecfdf5';
        nimField.style.color = '#059669';
        nimField.style.borderColor = '#059669';

        const submitBtn = document.getElementById('submitBtn');
        submitBtn.innerHTML = '<i class="ph-bold ph-spinner ph-spin"></i> Menyimpan...';
        submitBtn.style.background = '#059669';
        submitBtn.style.borderColor = '#059669';
        
        const indicator = document.getElementById('scanIndicator');
        indicator.innerHTML = '✅ Memproses...';
        indicator.style.background = '#ecfdf5';
        indicator.style.color = '#059669';

        submitScan(decodedText.trim());
    }

    // Override manual form submit
    document.getElementById('scanForm').addEventListener('submit', function(e) {
        e.preventDefault();
        if (isProcessing || isAlertShowing) return;
        isProcessing = true;
        const nimValue = document.getElementById('nim').value.trim();
        if (nimValue) {
            submitScan(nimValue);
        } else {
            isProcessing = false;
        }
    });

    // --- ENGINE 1: QUAGGA2 (BARCODE 1D) ---
    function startBarcodeScanner() {
        Quagga.init({
            inputStream: {
                name: "Live",
                type: "LiveStream",
                target: document.querySelector('#barcode-reader'),
                constraints: { facingMode: "environment" },
            },
            decoder: { readers: ["code_128_reader", "code_39_reader"] },
            locate: true,
        }, function(err) {
            if (err) return;
            Quagga.start();
        });
        Quagga.onDetected(function(result) {
            if (!isProcessing && !isAlertShowing) {
                processResult(result.codeResult.code);
            }
        });
    }
    function stopBarcodeScanner() {
        try { Quagga.stop(); } catch(e) {}
    }

    // --- ENGINE 2: ZXING (QR CODE) ---
    function startQrScanner() {
        html5QrcodeScanner = new Html5QrcodeScanner(
            "qr-reader",
            {
                fps: 10,
                formatsToSupport: [ 0 ],
                supportedScanTypes: [0],
                videoConstraints: { facingMode: "environment" }
            },
            false
        );
        html5QrcodeScanner.render(
            (decodedText) => {
                if (!isProcessing && !isAlertShowing) {
                    processResult(decodedText);
                }
            },
            () => {} 
        );
    }
    function stopQrScanner() {
        if (html5QrcodeScanner) {
            html5QrcodeScanner.clear().catch(()=>{});
            html5QrcodeScanner = null;
        }
    }

    // --- TAB MANAGER ---
    window.switchTab = function(mode) {
        if (currentMode === 'barcode' && mode !== 'barcode') stopBarcodeScanner();
        if (currentMode === 'qr' && mode !== 'qr') stopQrScanner();

        currentMode = mode;
        localStorage.setItem('scan_mode', mode);

        const btnBarcode = document.getElementById('tab-barcode');
        const btnQr = document.getElementById('tab-qr');
        const contBarcode = document.getElementById('container-barcode');
        const contQr = document.getElementById('container-qr');
        const helperText = document.getElementById('helper-text');

        if (mode === 'barcode') {
            btnBarcode.style.background = '#3b82f6';
            btnBarcode.style.color = 'white';
            btnBarcode.style.border = 'none';
            btnQr.style.background = '#f1f5f9';
            btnQr.style.color = '#64748b';
            btnQr.style.border = '1px solid #cbd5e1';
            contBarcode.style.display = 'block';
            contQr.style.display = 'none';
            helperText.innerHTML = '💡 Pastikan garis barcode terlihat jelas di dalam layar kamera.';
            startBarcodeScanner();
        } else {
            btnQr.style.background = '#3b82f6';
            btnQr.style.color = 'white';
            btnQr.style.border = 'none';
            btnBarcode.style.background = '#f1f5f9';
            btnBarcode.style.color = '#64748b';
            btnBarcode.style.border = '1px solid #cbd5e1';
            contQr.style.display = 'block';
            contBarcode.style.display = 'none';
            helperText.innerHTML = '💡 Pastikan seluruh kotak QR Code terlihat di layar.';
            startQrScanner();
        }
    }

    // Initialize with saved mode (prevent redundant double init)
    if (currentMode === 'qr') {
        // Pindah tab visual dan start QR
        switchTab('qr');
    } else {
        startBarcodeScanner();
    }
});
</script>
@endsection