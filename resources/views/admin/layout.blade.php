<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <style>
        body {
            margin: 0;
            font-family: Arial;
            background: #f5f6fa;
        }

        .sidebar {
            width: 200px;
            height: 100vh;
            background: #2c3e50;
            color: white;
            position: fixed;
            padding: 20px;
        }

        .content {
            margin-left: 220px;
            padding: 20px;
        }

        a {
            color: white;
            display: block;
            margin: 10px 0;
            text-decoration: none;
        }

        a:hover {
            color: #1abc9c;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h3>Admin</h3>
    <a href="/admin/dashboard">Dashboard</a>
    <a href="/admin/event">Kelola Event</a>
</div>

<div class="content">
    @yield('content')
</div>

</body>
</html>