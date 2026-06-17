<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIA Event Campus</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #f5f7fb; /* clean campus background */
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        /* MAIN CONTAINER */
        .wrapper {
            width: 100%;
            max-width: 900px;
            display: flex;
            flex-direction: row;
            background: white;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }

        /* LEFT SIDE (INFO) */
        .left {
            flex: 1;
            padding: 60px;
            background: linear-gradient(135deg, #1e3a8a, #2563eb);
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .left h1 {
            font-size: 28px;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .left p {
            font-size: 14px;
            line-height: 22px;
            color: #e5e7eb;
        }

        /* RIGHT SIDE (LOGIN) */
        .right {
            flex: 1;
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .title {
            font-size: 24px;
            font-weight: 600;
            color: #1e3a8a;
            margin-bottom: 10px;
        }

        .subtitle {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 35px;
        }

        /* BUTTON */
        .btn {
            display: block;
            padding: 14px;
            margin-bottom: 15px;
            text-align: center;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 500;
            transition: 0.3s;
        }

        .admin {
            background: #2563eb;
            color: white;
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.2);
        }

        .admin:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.3);
        }

        .pimpinan {
            border: 2px solid #2563eb;
            color: #2563eb;
            background: transparent;
        }

        .pimpinan:hover {
            background: #eff6ff;
            transform: translateY(-2px);
        }

        .footer {
            margin-top: 40px;
            font-size: 12px;
            color: #9ca3af;
            text-align: center;
        }

        .logo {
            width: 50px;
            height: 50px;
            background: white;
            border-radius: 12px;
            margin-bottom: 25px;
        }

        /* RESPONSIVE DESIGN FOR MOBILE */
        @media (max-width: 768px) {
            .wrapper {
                flex-direction: column;
            }
            .left, .right {
                padding: 40px 30px;
            }
            .left h1 {
                font-size: 24px;
            }
            .logo {
                margin-bottom: 20px;
            }
            .title {
                font-size: 22px;
            }
            body {
                padding: 15px;
            }
        }
    </style>
</head>
<body>

<div class="wrapper">
    <!-- LEFT -->
    <div class="left">
        <div class="logo">
            <img src="{{ asset('./images/logo.png') }}" alt="Logo Kampus" style="width: 100%; height: 100%; object-fit: contain; border-radius: 12px; padding: 5px;">
        </div>
        <h1>Sistem Kehadiran Event Kampus</h1>
        <p>Platform resmi untuk monitoring kehadiran kegiatan mahasiswa berbasis digital yang terintegrasi dengan sistem akademik kampus.</p>
    </div>

    <!-- RIGHT -->
    <div class="right">
        <div class="title">Selamat Datang</div>
        <div class="subtitle">Silakan pilih akses login Anda</div>

        <a href="{{ url('/admin/login') }}" class="btn admin">Login Admin</a>
        <a href="{{ url('/pimpinan/login') }}" class="btn pimpinan">Login Pimpinan</a>

        <div class="footer">
            &copy; 2026 Politeknik Bhakti Praja Tegal
        </div>
    </div>
</div>

</body>
</html>