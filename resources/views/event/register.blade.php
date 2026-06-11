<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Event - {{ $event->nama_event }}</title>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        body { font-family: 'Inter', sans-serif; background: #f8fafc; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .card { background: white; padding: 40px; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); width: 100%; max-width: 450px; text-align: center; }
        .icon { width: 80px; height: 80px; background: #eef2ff; color: #4f46e5; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: 40px; margin: 0 auto 20px; }
        h1 { font-size: 24px; color: #0f172a; margin-bottom: 8px; }
        p { color: #64748b; font-size: 14px; margin-bottom: 30px; }
        .form-group { text-align: left; margin-bottom: 20px; }
        label { display: block; font-weight: 600; color: #334155; margin-bottom: 8px; font-size: 14px; }
        input { width: 100%; padding: 12px 16px; border: 1px solid #cbd5e1; border-radius: 12px; font-size: 16px; outline: none; transition: 0.2s; box-sizing: border-box; }
        input:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,0.1); }
        button { width: 100%; background: #4f46e5; color: white; border: none; padding: 14px; border-radius: 12px; font-size: 16px; font-weight: 600; cursor: pointer; transition: 0.2s; display: flex; justify-content: center; align-items: center; gap: 8px; }
        button:hover { background: #4338ca; }
        .alert { background: #fef2f2; color: #dc2626; padding: 16px; border-radius: 12px; border: 1px solid #fecaca; margin-bottom: 24px; font-size: 14px; text-align: left; }
    </style>
</head>
<body>

<div class="card">
    <div class="icon">
        <i class="ph-bold ph-ticket"></i>
    </div>
    <h1>Registrasi Event</h1>
    <p>Silakan masukkan NIM Anda untuk mendaftar pada event <strong>{{ $event->nama_event }}</strong>.</p>

    @if(session('error'))
        <div class="alert">
            <i class="ph-bold ph-warning-circle"></i> {{ session('error') }}
        </div>
    @endif

    <form action="{{ url('/event/'.$event->id.'/register') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>NIM (Nomor Induk Mahasiswa)</label>
            <input type="text" name="nim" required placeholder="Contoh: 23.1.9.0049" autocomplete="off">
        </div>
        <button type="submit">Dapatkan Tiket QR Code <i class="ph-bold ph-arrow-right"></i></button>
    </form>
</div>

</body>
</html>
