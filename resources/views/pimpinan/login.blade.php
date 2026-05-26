<!DOCTYPE html>
<html>
<head>
    <title>Login Pimpinan</title>

    <style>
        body{
            margin:0;
            font-family:Poppins, sans-serif;
            background: linear-gradient(135deg, #0f172a, #1e3a8a);
            height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
        }

        .card{
            width:350px;
            background:white;
            padding:40px;
            border-radius:20px;
            box-shadow:0 20px 40px rgba(0,0,0,0.3);
            text-align:center;
        }

        h2{
            color:#1e3a8a;
            margin-bottom:20px;
        }

        input{
            width:100%;
            padding:12px;
            margin:10px 0;
            border:1px solid #ddd;
            border-radius:10px;
        }

        button{
            width:100%;
            padding:12px;
            background:#2563eb;
            color:white;
            border:none;
            border-radius:10px;
            cursor:pointer;
        }

        button:hover{
            background:#1e3a8a;
        }

        .back{
            margin-top:15px;
            display:block;
            font-size:12px;
            color:#666;
            text-decoration:none;
        }
    </style>

</head>
<body>

<div class="card">

    <h2>LOGIN PIMPINAN</h2>

    <form method="POST" action="{{ url('/pimpinan/login') }}">
        @csrf

        <input type="text" name="username" placeholder="Username">
        <input type="password" name="password" placeholder="Password">

        <button type="submit">LOGIN</button>
    </form>

    <a class="back" href="{{ url('/') }}">Kembali</a>

</div>

</body>
</html>