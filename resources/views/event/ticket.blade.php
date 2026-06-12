<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket QR Code</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 40%, #4c1d95 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 24px;
        }

        .page-wrapper {
            width: 100%;
            max-width: 420px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        /* ===== TICKET CARD ===== */
        .ticket-wrapper {
            background: white;
            border-radius: 24px;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.35);
            overflow: hidden;
            position: relative;
        }

        /* Header strip with gradient */
        .ticket-header {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            padding: 24px 30px 20px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .ticket-header::before {
            content: '';
            position: absolute;
            top: -30px; right: -30px;
            width: 120px; height: 120px;
            background: rgba(255,255,255,0.07);
            border-radius: 50%;
        }
        .ticket-header::after {
            content: '';
            position: absolute;
            bottom: -20px; left: -20px;
            width: 80px; height: 80px;
            background: rgba(255,255,255,0.07);
            border-radius: 50%;
        }

        .header-label {
            font-size: 11px;
            font-weight: 600;
            color: rgba(255,255,255,0.65);
            letter-spacing: 2.5px;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        .header-title {
            font-size: 13px;
            font-weight: 700;
            color: white;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            line-height: 1.5;
        }

        /* Body - ticket area to be captured */
        #ticket-card {
            background: white;
            padding: 28px 30px 24px;
            text-align: center;
        }

        /* Logo — fixed small circle badge */
        .logo-badge {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            border: 3px solid #ede9fe;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.15);
            margin: 0 auto 16px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
        }
        .logo-badge img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .event-name {
            font-size: 15px;
            font-weight: 800;
            color: #1e1b4b;
            text-transform: uppercase;
            letter-spacing: 1px;
            line-height: 1.4;
            margin-bottom: 20px;
        }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }
        .divider-line {
            flex: 1;
            height: 2px;
            background: #e2e8f0;
            border-radius: 2px;
        }
        .divider-dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: #c7d2fe;
        }

        /* QR Code */
        .qr-container {
            background: #fafafa;
            padding: 14px;
            border-radius: 20px;
            display: inline-block;
            margin-bottom: 22px;
            border: 1.5px solid #ede9fe;
            box-shadow: 0 4px 20px rgba(79, 70, 229, 0.08);
        }
        .qr-container img, .qr-container canvas {
            display: block;
            margin: 0 auto;
        }

        /* Attendee info */
        .attendee-section {
            background: #f5f3ff;
            border-radius: 14px;
            padding: 14px 20px;
        }
        .attendee-label {
            font-size: 10px;
            font-weight: 600;
            color: #7c3aed;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        .mhs-name {
            font-size: 18px;
            font-weight: 800;
            color: #1e1b4b;
            margin-bottom: 3px;
        }
        .mhs-nim {
            font-size: 13px;
            color: #6d28d9;
            font-weight: 600;
            font-family: monospace;
            letter-spacing: 2px;
        }

        /* Ticket tear edge */
        .ticket-tear {
            position: relative;
            height: 0;
            border-top: 2px dashed #e2e8f0;
            margin: 0 -1px;
        }
        .ticket-tear::before,
        .ticket-tear::after {
            content: '';
            position: absolute;
            top: -14px;
            width: 24px; height: 24px;
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 40%, #4c1d95 100%);
            border-radius: 50%;
        }
        .ticket-tear::before { left: -13px; }
        .ticket-tear::after { right: -13px; }

        /* Action section */
        .action-section {
            padding: 20px 24px 24px;
            background: #fafafa;
            text-align: center;
        }

        button {
            width: 100%;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            border: none;
            padding: 15px;
            border-radius: 14px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.35);
            font-family: 'Inter', sans-serif;
        }
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(79, 70, 229, 0.45);
        }
        button:active { transform: translateY(0); }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-top: 14px;
            color: rgba(255,255,255,0.65);
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: color 0.2s;
        }
        .back-link:hover { color: white; }

        /* Decorative dots on body background */
        .bg-decoration {
            position: fixed;
            pointer-events: none;
            z-index: 0;
        }

        /* Mode Scan Khusus WebCam */
        body.scan-mode {
            background: #000000 !important;
        }
        body.scan-mode .page-wrapper {
            max-width: 100%;
        }
        body.scan-mode .ticket-wrapper {
            background: transparent;
            box-shadow: none;
        }
        body.scan-mode .ticket-header,
        body.scan-mode .logo-badge,
        body.scan-mode .divider,
        body.scan-mode .attendee-section,
        body.scan-mode .ticket-tear,
        body.scan-mode .back-link,
        body.scan-mode .used-badge {
            display: none !important;
        }
        body.scan-mode #ticket-card {
            background: transparent;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 60vh;
        }
        body.scan-mode .qr-container {
            transform: scale(1.3);
            border: none;
            box-shadow: 0 0 50px rgba(255,255,255,0.2);
            margin: 0;
        }
        body.scan-mode .scan-instruction {
            display: block !important;
            color: white;
            margin-top: 50px;
            font-size: 14px;
            opacity: 0.8;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="page-wrapper">
    <div class="ticket-wrapper">
        {{-- Header strip (NOT part of QR download) --}}
        <div class="ticket-header">
            <div class="header-label">E-Tiket Resmi</div>
            <div class="header-title">{{ $event->nama_event }}</div>
        </div>

        {{-- ===== AREA YANG AKAN DIDOWNLOAD ===== --}}
        <div id="ticket-card">

            {{-- Logo sebagai badge bulat kecil --}}
            <div class="logo-badge">
                <img src="/images/logo.png" alt="Logo Kampus">
            </div>

            <div class="divider">
                <div class="divider-line"></div>
                <div class="divider-dot"></div>
                <div class="divider-dot"></div>
                <div class="divider-dot"></div>
                <div class="divider-line right"></div>
            </div>

            {{-- QR Code dengan Logo di tengah --}}
            <div class="qr-container" style="position: relative;">
                {!! $qr_svg !!}
                <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 4px; border-radius: 8px;">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" style="width: 44px; height: 44px; object-fit: contain;">
                </div>

                {{-- Overlay Jika Sudah Digunakan (Scan Mode = Hidden so it doesn't block QR) --}}
                @if($kehadiran)
                    <div class="used-badge" style="position: absolute; inset: 0; background: rgba(255, 255, 255, 0.85); display: flex; flex-direction: column; align-items: center; justify-content: center; border-radius: 16px; backdrop-filter: blur(2px); z-index: 10;">
                        <i class="ph-fill ph-check-circle" style="font-size: 64px; color: #059669; margin-bottom: 8px;"></i>
                        <span style="font-weight: 800; color: #059669; font-size: 18px; text-transform: uppercase; letter-spacing: 1px;">Telah Digunakan</span>
                        <span style="font-size: 12px; color: #334155; margin-top: 4px; font-weight: 600; text-align: center; background: #fff; padding: 4px 10px; border-radius: 12px; border: 1px solid #e2e8f0;">
                            {{ $kehadiran->waktu_scan->setTimezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB
                        </span>
                    </div>
                @endif
            </div>

            {{-- Status Kehadiran Teks Bawah --}}
            @if($kehadiran)
                <div style="margin-top: -10px; margin-bottom: 20px; background: #ecfdf5; color: #059669; border: 1px solid #10b981; padding: 10px; border-radius: 10px; font-size: 13px; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 6px;">
                    <i class="ph-bold ph-shield-check"></i> Tiket Valid & Sudah Di-scan
                </div>
            @else
                <div style="margin-top: -10px; margin-bottom: 20px; background: #fffbeb; color: #d97706; border: 1px solid #f59e0b; padding: 10px; border-radius: 10px; font-size: 13px; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 6px;">
                    <i class="ph-bold ph-warning-circle"></i> Belum Digunakan
                </div>
            @endif

            {{-- Info Peserta --}}
            <div class="attendee-section">
                <div class="attendee-label">Peserta Terdaftar</div>
                <div class="mhs-name">{{ $ticket_nama }}</div>
                <div class="mhs-nim">{{ $ticket_nim }}</div>
            </div>

            {{-- Pesan bantuan untuk scan --}}
            <div class="scan-instruction" style="display: none;">
                <p>Naikkan *Brightness* HP ke maksimal.<br>Dekatkan QR Code ini ke WebCam.</p>
            </div>

        </div>
        {{-- ===== AKHIR AREA DOWNLOAD ===== --}}

        {{-- Garis putus tiket --}}
        <div class="ticket-tear"></div>

        {{-- Tombol Action --}}
        <div class="action-section">
            <button onclick="downloadTicket()" id="download-btn">
                <i class="ph-bold ph-download-simple"></i> Download Tiket
            </button>
            <button onclick="toggleScanMode()" id="scan-btn" style="background: #1e293b; margin-top: 10px;">
                <i class="ph-bold ph-scan"></i> Mode Scan WebCam (Anti Silau)
            </button>
        </div>
    </div>

    <a href="{{ url('/') }}" class="back-link" style="text-align:center; display:block;">
        <i class="ph-bold ph-arrow-left"></i> Kembali ke Beranda
    </a>
</div>

<script>
function toggleScanMode() {
    const isScanMode = document.body.classList.toggle('scan-mode');
    const btn = document.getElementById('scan-btn');
    if (isScanMode) {
        btn.innerHTML = '<i class="ph-bold ph-x"></i> Keluar Mode Scan';
        btn.style.background = '#dc2626';
    } else {
        btn.innerHTML = '<i class="ph-bold ph-scan"></i> Mode Scan WebCam (Anti Silau)';
        btn.style.background = '#1e293b';
    }
}

function downloadTicket() {
    const btn = document.getElementById('download-btn');
    btn.innerHTML = '<i class="ph-bold ph-spinner ph-spin"></i> Memproses...';
    btn.disabled = true;

    const ticketElement = document.getElementById('ticket-card');

    html2canvas(ticketElement, {
        scale: 3,
        backgroundColor: '#ffffff',
        logging: false,
        useCORS: true,
    }).then(canvas => {
        const image = canvas.toDataURL('image/png');
        const link = document.createElement('a');
        link.download = 'Tiket_{{ str_replace(" ", "_", $event->nama_event) }}_{{ $ticket_nim }}.png';
        link.href = image;
        link.click();

        btn.innerHTML = '<i class="ph-bold ph-check"></i> Berhasil Didownload!';
        setTimeout(() => {
            btn.innerHTML = '<i class="ph-bold ph-download-simple"></i> Download Tiket';
            btn.disabled = false;
        }, 2500);
    }).catch(err => {
        console.error("html2canvas error:", err);
        btn.innerHTML = '<i class="ph-bold ph-warning"></i> Gagal, Coba Lagi';
        btn.disabled = false;
    });
}
</script>

</body>
</html>
