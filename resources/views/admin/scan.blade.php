@extends('layouts.admin')

@section('title', 'Scan Absensi')

@section('content')

    <div class="header-section">
        <h2>Scan Absensi Mahasiswa</h2>
        <p>Arahkan kamera ke Barcode/QR Code KTM atau upload *screenshot*.</p>
    </div>

    <div class="form-container" style="max-width: 600px; margin: 0 auto;">
        
        <div style="text-align: center; margin-bottom: 24px;">
            <div style="width: 64px; height: 64px; background: #eef2ff; color: #4f46e5; border-radius: 16px; display: inline-flex; align-items: center; justify-content: center; font-size: 32px; margin-bottom: 16px;">
                <i class="ph-bold ph-qr-code"></i>
            </div>
            <h3 style="font-size: 18px; color: #0f172a; font-weight: 600;">Scanner Kehadiran</h3>
            <p style="color: #64748b; font-size: 14px; margin-top: 4px;">Pastikan pencahayaan cukup agar kamera dapat mendeteksi dengan cepat.</p>
        </div>

        @if(session('success'))
            <div style="background: #ecfdf5; color: #059669; padding: 16px; border-radius: 12px; border: 1px solid #a7f3d0; margin-bottom: 24px; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                <i class="ph-bold ph-check-circle" style="font-size: 20px;"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="background: #fef2f2; color: #dc2626; padding: 16px; border-radius: 12px; border: 1px solid #fecaca; margin-bottom: 24px; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                <i class="ph-bold ph-warning-circle" style="font-size: 20px;"></i>
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div style="background: #fef2f2; color: #dc2626; padding: 16px; border-radius: 12px; border: 1px solid #fecaca; margin-bottom: 24px; font-size: 14px;">
                <ul style="margin-left: 15px; margin-bottom: 0;">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="scanForm" action="{{ url('/admin/scan') }}" method="POST">
            @csrf
            
            <!-- PILIH EVENT (DIPINDAH KE ATAS AGAR MUDAH & WAJIB DIISI) -->
            <div class="form-group" style="background: #f8fafc; border: 2px solid #cbd5e1; border-radius: 16px; padding: 20px; margin-bottom: 24px;">
                <label style="font-size: 16px; font-weight: 700; color: #0f172a; display: block; margin-bottom: 10px;">
                    <i class="ph-bold ph-calendar-check" style="color: #4f46e5;"></i> 1. Pilih Event Aktif (Wajib)
                </label>
                <select name="event_id" id="event_id" class="form-control" required style="cursor: pointer; font-size: 15px; padding: 12px 16px; border-radius: 12px; background: white; border: 1px solid #94a3b8; width: 100%; font-weight: 600; color: #334155;">
                    <option value="">-- Silakan Pilih Event Terlebih Dahulu --</option>
                    @foreach($events as $event)
                        <option value="{{ $event->id }}">{{ $event->nama_event }}</option>
                    @endforeach
                </select>
                <p style="color: #64748b; font-size: 13px; margin-top: 8px; margin-bottom: 0;">Pilih event di atas sebelum menyalakan kamera atau mengunggah gambar.</p>
            </div>

            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; padding: 20px; margin-bottom: 24px;">
                <label style="font-size: 14px; font-weight: 600; color: #334155; margin-bottom: 12px; display: block;"><i class="ph-bold ph-video-camera"></i> 2. Kamera Scanner</label>
                
                <!-- KOTAK SCANNER ASLI BAWAAN LIBRARY -->
                <div id="reader" style="width: 100%; min-height: 300px; background: white; border-radius: 12px; overflow: hidden; border: 1px solid #cbd5e1;"></div>
            </div>

            <div class="form-group">
                <label>NIM Mahasiswa</label>
                <input 
                    type="text"
                    name="nim"
                    id="nim"
                    placeholder="[ Menunggu hasil scan... ]"
                    required
                    readonly
                    class="form-control"
                    style="background: #f1f5f9; color: #64748b; font-weight: 600; text-align: center; font-family: monospace; font-size: 16px;"
                >
            </div>

            <button type="button" class="btn btn-secondary" disabled style="width: 100%; opacity: 0.7;">
                <i class="ph-bold ph-lock-key"></i> Otomatis submit setelah scan
            </button>
        </form>

    </div>
@endsection

@section('extra_js')
<!-- KUNCI VERSI STABIL 2.3.8 UNTUK MENCEGAH BUG UPDATE DARI PEMBUAT LIBRARY -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    
    function onScanSuccess(decodedText, decodedResult) {
        let eventSelect = document.getElementById('event_id');
        if (!eventSelect.value) {
            alert("⚠️ PERHATIAN: Silakan Pilih Event Aktif terlebih dahulu di bagian atas!");
            eventSelect.focus();
            return;
        }

        document.getElementById('nim').value = decodedText.trim();
        document.getElementById('nim').style.background = '#ecfdf5';
        document.getElementById('nim').style.color = '#059669';
        document.getElementById('scanForm').submit();
        
        // Hentikan scanner agar tidak scan dobel
        html5QrcodeScanner.clear();
    }

    function onScanFailure(error) {
        // Abaikan error per frame
    }

    // Inisialisasi arsitektur asli bawaan library yang terbukti stabil 100%
    let html5QrcodeScanner = new Html5QrcodeScanner(
        "reader",
        { 
            fps: 10, // Turunkan ke 10fps agar CPU tidak lag/hang saat memproses gambar
            useBarCodeDetectorIfSupported: false, // WAJIB FALSE: Karena web Anda berjalan di HTTP (Not Secure), API native browser diblokir oleh Chrome. Kita harus memaksa pakai Javascript murni.
            rememberLastUsedCamera: true, // Mengingat kamera USB jika dipilih
            // OPTIMASI KAMERA BIASA & CPU:
            qrbox: 250, // Meringankan beban CPU hingga 80% karena hanya memproses area dalam kotak
            videoConstraints: {
                width: { ideal: 640 },
                height: { ideal: 480 }
            }
        },
        /* verbose= */ false
    );
    
    html5QrcodeScanner.render(onScanSuccess, onScanFailure);
});
</script>
@endsection