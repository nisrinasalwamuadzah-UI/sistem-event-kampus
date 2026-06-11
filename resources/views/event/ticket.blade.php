<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket QR Code</title>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; background: #f8fafc; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; padding: 20px; box-sizing: border-box;}
        
        .ticket-wrapper {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            width: 100%;
            max-width: 400px;
            overflow: hidden;
            position: relative;
        }

        /* TICKET DESIGN TO BE CAPTURED */
        #ticket-card {
            background: white;
            padding: 40px 30px;
            text-align: center;
            position: relative;
            border-bottom: 2px dashed #cbd5e1;
        }

        .logo-container {
            width: 60px;
            margin: 0 auto 20px;
        }

        .event-title {
            font-size: 18px;
            color: #0f172a;
            font-weight: 700;
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .qr-container {
            background: #fff;
            padding: 15px;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            display: inline-block;
            margin-bottom: 25px;
            border: 1px solid #f1f5f9;
        }

        .qr-container svg {
            display: block;
        }

        .mhs-name {
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 5px;
        }

        .mhs-nim {
            font-size: 16px;
            color: #4f46e5;
            font-weight: 600;
            font-family: monospace;
            letter-spacing: 1px;
        }

        /* DOWNLOAD SECTION */
        .action-section {
            padding: 25px 30px;
            background: #f8fafc;
            text-align: center;
        }

        button { width: 100%; background: #4f46e5; color: white; border: none; padding: 14px; border-radius: 12px; font-size: 16px; font-weight: 600; cursor: pointer; transition: 0.2s; display: flex; justify-content: center; align-items: center; gap: 8px; }
        button:hover { background: #4338ca; }
        
        .back-link {
            display: block;
            margin-top: 15px;
            color: #64748b;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }
        .back-link:hover { color: #0f172a; }
    </style>
</head>
<body>

<div class="ticket-wrapper">
    <!-- Area ini yang akan didownload -->
    <div id="ticket-card">
        <div class="logo-container">
            <!-- Menampilkan SVG Logo Bawaan Aplikasi -->
            @include('components.application-logo')
        </div>
        
        <div class="event-title">
            {{ $event->nama_event }}
        </div>

        <div class="qr-container">
            {!! QrCode::size(200)->margin(0)->generate($ticket_nim) !!}
        </div>

        <div class="mhs-name">{{ $ticket_nama }}</div>
        <div class="mhs-nim">{{ $ticket_nim }}</div>
    </div>

    <!-- Area Action -->
    <div class="action-section">
        <button onclick="downloadTicket()" id="download-btn">
            <i class="ph-bold ph-download-simple"></i> Download Tiket
        </button>
        <a href="{{ url('/') }}" class="back-link">Kembali ke Beranda</a>
    </div>
</div>

<script>
function downloadTicket() {
    const btn = document.getElementById('download-btn');
    btn.innerHTML = '<i class="ph-bold ph-spinner ph-spin"></i> Memproses...';
    btn.disabled = true;

    const ticketElement = document.getElementById('ticket-card');

    html2canvas(ticketElement, {
        scale: 3, // High resolution
        backgroundColor: "#ffffff",
        logging: false
    }).then(canvas => {
        // Convert to image
        const image = canvas.toDataURL("image/png");
        
        // Create download link
        const link = document.createElement('a');
        link.download = 'Tiket_Event_{{ str_replace(" ", "_", $event->nama_event) }}_{{ $ticket_nim }}.png';
        link.href = image;
        link.click();

        // Restore button
        btn.innerHTML = '<i class="ph-bold ph-download-simple"></i> Download Tiket';
        btn.disabled = false;
    });
}
</script>

</body>
</html>
