<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Sistem Event</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Outfit', sans-serif;
            position: relative;
            z-index: 0;
            background-color: #0f172a;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            color: #fff;
        }

        .card {
            width: 100%;
            max-width: 400px;
            background: linear-gradient(rgba(15, 23, 42, 0.85), rgba(15, 23, 42, 0.85)), url('{{ asset('images/poltek-baja.png') }}') no-repeat center center;
            background-size: cover;
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            padding: 40px 30px;
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            text-align: center;
            animation: fadeIn 0.6s ease-out;
            position: relative;
            overflow: hidden;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h2 {
            color: #ffffff;
            margin-bottom: 30px;
            font-weight: 600;
            letter-spacing: 1px;
            font-size: 24px;
        }

        .input-group {
            margin-bottom: 15px;
            text-align: left;
        }

        input {
            width: 100%;
            padding: 14px 16px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            color: #fff;
            font-size: 15px;
            font-family: 'Outfit', sans-serif;
            transition: all 0.3s ease;
        }

        input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        input:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.15);
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.2);
        }

        button {
            width: 100%;
            padding: 14px;
            background: #3b82f6;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 15px;
            font-family: 'Outfit', sans-serif;
        }

        button:hover {
            background: #2563eb;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -10px rgba(37, 99, 235, 0.5);
        }

        button:active {
            transform: translateY(0);
        }

        .back {
            margin-top: 25px;
            display: inline-block;
            font-size: 14px;
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .back:hover {
            color: #ffffff;
        }

        .logo-container {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }

        .login-logo {
            width: 60px;
            height: 60px;
            object-fit: contain;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.1);
            padding: 8px;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
    </style>
</head>
<body>

<div class="card">
    <div class="logo-container">
        <img src="{{ asset('images/logo.png') }}" alt="Logo CampusEvent" class="login-logo">
    </div>
    <h2>LOGIN ADMIN</h2>
    <form method="POST" action="{{ url('/admin/login') }}">
        @csrf
        <div class="input-group">
            <input type="text" name="username" placeholder="Username" required>
        </div>
        <div class="input-group">
            <input type="password" name="password" placeholder="Password" required>
        </div>
        <button type="submit">MASUK SEKARANG</button>
    </form>
    <a class="back" href="{{ url('/') }}">← Kembali ke Beranda</a>
</div>

</body>
</html>