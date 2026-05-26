<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>SIA Event Campus</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins', sans-serif;
}

body{
    background:#f5f7fb; /* clean campus background */
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}

/* MAIN CONTAINER */
.wrapper{
    width:900px;
    display:flex;
    background:white;
    border-radius:18px;
    overflow:hidden;
    box-shadow:0 10px 30px rgba(0,0,0,0.08);
}

/* LEFT SIDE (INFO) */
.left{
    flex:1;
    padding:60px;
    background:linear-gradient(135deg,#1e3a8a,#2563eb);
    color:white;
}

.left h1{
    font-size:28px;
    margin-bottom:15px;
    font-weight:600;
}

.left p{
    font-size:14px;
    line-height:22px;
    color:#e5e7eb;
}

/* RIGHT SIDE (LOGIN) */
.right{
    flex:1;
    padding:60px;
}

.title{
    font-size:20px;
    font-weight:600;
    color:#1e3a8a;
    margin-bottom:10px;
}

.subtitle{
    font-size:13px;
    color:#6b7280;
    margin-bottom:30px;
}

/* BUTTON */
.btn{
    display:block;
    padding:12px;
    margin-bottom:12px;
    text-align:center;
    border-radius:10px;
    text-decoration:none;
    font-weight:500;
    transition:0.2s;
}

.admin{
    background:#2563eb;
    color:white;
}

.admin:hover{
    background:#1d4ed8;
}

.pimpinan{
    border:1.5px solid #2563eb;
    color:#2563eb;
}

.pimpinan:hover{
    background:#eff6ff;
}

.footer{
    margin-top:30px;
    font-size:11px;
    color:#9ca3af;
    text-align:center;
}

.logo{
    width:45px;
    height:45px;
    background:white;
    border-radius:10px;
    margin-bottom:20px;
}

</style>

</head>

<body>

<div class="wrapper">

    <!-- LEFT -->
    <div class="left">

        <div class="logo"></div>

        <h1>Sistem Kehadiran Event Kampus</h1>

        <p>
            Platform resmi untuk monitoring kehadiran kegiatan mahasiswa
            berbasis digital yang terintegrasi dengan sistem akademik kampus.
        </p>

    </div>

    <!-- RIGHT -->
    <div class="right">

        <div class="title">Selamat Datang</div>
        <div class="subtitle">Silakan pilih akses login</div>

        <a href="{{ url('/admin/login') }}" class="btn admin">
            Login Admin
        </a>

        <a href="{{ url('/pimpinan/login') }}" class="btn pimpinan">
            Login Pimpinan
        </a>

        <div class="footer">
            © 2026 Politeknik Bhakti Praja Tegal
        </div>

    </div>

</div>

</body>
</html>