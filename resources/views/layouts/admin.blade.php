<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Event Campus</title>
    
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <!-- Unified CSS -->
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}?v=2">
    
    @yield('extra_css')
</head>
<body>

    <!-- SIDEBAR / BOTTOM NAV -->
    <div class="sidebar">
        <div class="brand">
            <img src="{{ asset('images/logo.png') }}" alt="Logo CampusEvent" class="brand-logo">
            <span>CampusEvent</span>
        </div>

        <div class="menu">
            @if(session('role') == 'admin')
                <a href="{{ url('/admin/dashboard') }}" class="{{ request()->is('admin/dashboard') ? 'active' : '' }}">
                    <i class="ph ph-squares-four"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ url('/admin/event') }}" class="{{ request()->is('admin/event*') ? 'active' : '' }}">
                    <i class="ph ph-calendar-check"></i>
                    <span>Kelola Event</span>
                </a>
                <a href="{{ url('/admin/mahasiswa') }}" class="{{ request()->is('admin/mahasiswa*') ? 'active' : '' }}">
                    <i class="ph ph-users-three"></i>
                    <span>Data Mahasiswa</span>
                </a>
                <a href="{{ url('/admin/kehadiran') }}" class="{{ request()->is('admin/kehadiran*') ? 'active' : '' }}">
                    <i class="ph ph-users"></i>
                    <span>Kehadiran</span>
                </a>
                <a href="{{ url('/admin/scan') }}" class="{{ request()->is('admin/scan') ? 'active' : '' }}">
                    <i class="ph ph-qr-code"></i>
                    <span>Scan Absen</span>
                </a>
            @elseif(session('role') == 'pimpinan')
                <a href="{{ url('/pimpinan/dashboard') }}" class="{{ request()->is('pimpinan/dashboard') ? 'active' : '' }}">
                    <i class="ph ph-squares-four"></i>
                    <span>Dashboard</span>
                </a>
            @endif
        </div>

        <a href="{{ url('/logout') }}" class="logout">
            <i class="ph ph-sign-out"></i>
            <span>Logout</span>
        </a>
    </div>

    <!-- MAIN CONTENT WRAPPER -->
    <div class="main-wrapper">
        <!-- TOPBAR -->
        <div class="topbar">
            <div class="topbar-title">
                <h1>@yield('title')</h1>
            </div>
            
            <div class="user-profile">
                <!-- Mobile Logout Icon in Topbar -->
                <a href="{{ url('/logout') }}" class="mobile-logout" style="display: none; color: #ef4444; text-decoration: none;">
                    <i class="ph ph-sign-out" style="font-size: 24px;"></i>
                </a>
                
                <span>{{ ucfirst(session('role') ?? 'Guest') }} User</span>
                <div class="avatar">
                    <i class="ph-fill ph-user"></i>
                </div>
            </div>
        </div>

        <!-- PAGE CONTENT -->
        <div class="main-content">
            @yield('content')
        </div>
    </div>
    
    <!-- Mobile specific inline styles to handle logout moving to topbar -->
    <style>
        @media (max-width: 768px) {
            .mobile-logout { display: block !important; margin-right: 15px; }
        }
    </style>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                alert('Link Registrasi berhasil di-copy:\n' + text);
            }, function(err) {
                alert('Gagal copy link: ', err);
            });
        }
    </script>

    @yield('extra_js')
</body>
</html>
