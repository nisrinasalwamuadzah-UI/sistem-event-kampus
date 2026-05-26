<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pimpinan Panel</title>

    <style>

        body{
            margin:0;
            font-family:Arial;
            background:#f1f5f9;
            display:flex;
        }

        .sidebar{
            width:250px;
            height:100vh;
            background:#111827;
            color:white;
            padding:20px;
        }

        .sidebar a{
            display:block;
            color:white;
            text-decoration:none;
            padding:12px;
            background:#1f2937;
            margin-bottom:10px;
            border-radius:10px;
        }

        .main{
            flex:1;
            padding:30px;
        }

        .card{
            background:white;
            padding:25px;
            border-radius:15px;
            margin-bottom:20px;
        }

    </style>

</head>
<body>

<div class="sidebar">

    <h2>PIMPINAN</h2>

    <a href="/pimpinan">
        Dashboard
    </a>

    <a href="#">
        Monitoring
    </a>

    <a href="#">
        ACC Laporan
    </a>

    <a href="#">
        Logout
    </a>

</div>

<div class="main">

    @yield('content')

</div>

</body>
</html>