
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Admin Dashboard')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f5f7fb;
            font-family: Arial, sans-serif;
            overflow-x: hidden;
        }

        /* SIDEBAR */
        .sidebar {
            width: 250px;
            height: 100vh;
            background: #19387c;
            position: fixed;
            left: 0;
            top: 0;
            padding: 25px 15px;
            z-index: 1050;
            overflow-y: auto;
            transition: transform .3s ease;
        }

        .brand {
            color: white;
            font-size: 24px;
            font-weight: bold;
            padding: 0 15px;
            margin-bottom: 35px;
        }

        .brand span {
            color: #FFD166;
        }

        .menu-title {
            color: rgba(255,255,255,.55);
            font-size: 12px;
            text-transform: uppercase;
            padding: 0 15px;
            margin-bottom: 10px;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            gap: 12px;
            color: rgba(255,255,255,.8);
            text-decoration: none;
            padding: 12px 15px;
            border-radius: 10px;
            margin-bottom: 5px;
            transition: .2s;
        }

        .sidebar a:hover {
            background: rgba(255,255,255,.1);
            color: white;
        }

        .sidebar a.active {
            background: white;
            color: #19387c;
            font-weight: bold;
        }

        /* MAIN */
        .main {
            margin-left: 250px;
            min-height: 100vh;
            padding-top: 70px;
            transition: margin-left .3s ease;
        }

        /* NAVBAR */
        .navbar-admin {
            height: 70px;
            background: white;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            position: fixed;
            top: 0;
            left: 250px;
            right: 0;
            z-index: 1000;
            transition: left .3s ease;
        }

        .content {
            padding: 30px;
        }

        /* CARD */
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 3px 15px rgba(0,0,0,.05);
        }

        .stat-card {
            padding: 25px;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #e9f1ff;
            color: #19387c;
            font-size: 23px;
        }

        /* TABLE */
        .table {
            vertical-align: middle;
            white-space: nowrap;
        }

        .table thead th {
            color: #777;
            font-size: 14px;
            font-weight: 600;
            border-bottom: 1px solid #eee;
        }

        .table td {
            padding: 15px 10px;
        }

        /* BUTTON */
        .btn-primary {
            background: #19387c;
            border-color: #19387c;
        }

        .btn-primary:hover {
            background: #102b64;
            border-color: #102b64;
        }

        /* MOBILE OVERLAY */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.45);
            z-index: 1040;
        }

        .menu-toggle {
            display: none;
            border: none;
            background: transparent;
            font-size: 25px;
            color: #19387c;
            padding: 0;
        }

        /* TABLET & MOBILE */
        @media (max-width: 991.98px) {

            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .sidebar-overlay.show {
                display: block;
            }

            .main {
                margin-left: 0;
            }

            .navbar-admin {
                left: 0;
            }

            .menu-toggle {
                display: block;
            }

            .content {
                padding: 20px;
            }
        }

        /* MOBILE */
        @media (max-width: 576px) {

            .navbar-admin {
                height: 60px;
                padding: 0 15px;
            }

            .main {
                padding-top: 60px;
            }

            .content {
                padding: 15px;
            }

            .navbar-admin h5 {
                font-size: 16px;
            }

            .navbar-admin .text-end {
                font-size: 13px;
            }

            .navbar-admin small {
                font-size: 11px;
            }

            .navbar-admin .rounded-circle {
                width: 35px !important;
                height: 35px !important;
                flex-shrink: 0;
            }

            .stat-card {
                padding: 18px;
            }

            .card {
                border-radius: 12px;
            }

            .btn {
                font-size: 13px;
            }
        }
    </style>

    @yield('styles')
</head>

<body>

    <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebar">

        <div class="brand">
            Kasir<span>App</span>
        </div>

        <div class="menu-title">Menu</div>

        <a href="{{ route('admin.dashboard') }}"
            class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid"></i>
            Dashboard
        </a>

        <a href="{{ route('admin.products.index') }}"
            class="{{ request()->routeIs('admin.products.index') ? 'active' : '' }}">
            <i class="bi bi-box-seam"></i>
            Produk
        </a>

        @if (Auth()->user()->role == "owner")    
            <a href="{{ route('admin.outlet.index') }}"
                class="{{ request()->routeIs('admin.outlet.index') ? 'active' : '' }}">
                <i class="bi bi-shop"></i>
                <span>Outlet</span>
            </a>
        @endif

        <a href="{{ route('admin.transactions.index') }}"
            class="{{ request()->routeIs('admin.transactions.*') ? 'active' : '' }}">
            <i class="bi bi-receipt"></i>
            Transaksi
        </a>

        <a href="{{ route('admin.users.index') }}"
            class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i>
            Pengguna
        </a>

        <form action="{{ route('admin.logout') }}" method="POST" class="mt-4">
            @csrf

            <button type="submit"
                class="w-100 border-0 text-start"
                style="
                    background: transparent;
                    color: rgba(255,255,255,.8);
                    padding: 12px 15px;
                    border-radius: 10px;
                ">
                <i class="bi bi-box-arrow-right me-2"></i>
                Logout
            </button>
        </form>

    </aside>

    <!-- OVERLAY -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- MAIN -->
    <main class="main">

        <!-- NAVBAR -->
        <nav class="navbar-admin">

            <div class="d-flex align-items-center gap-3">

                <button class="menu-toggle" id="menuToggle" type="button">
                    <i class="bi bi-list"></i>
                </button>

                <h5 class="mb-0 fw-bold">
                    @yield('page-title', 'Dashboard')
                </h5>

            </div>

            <div class="d-flex align-items-center gap-3">

                <div class="text-end">
                    <div class="fw-bold">
                        {{ auth()->user()->name ?? 'Admin' }}
                    </div>

                    <small class="text-muted">
                        {{ ucfirst(auth()->user()->role ?? 'Admin') }}
                    </small>
                </div>

                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                    style="width: 42px;height:42px;">
                    <i class="bi bi-person"></i>
                </div>

            </div>

        </nav>

        <!-- CONTENT -->
        <section class="content">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle me-2"></i>
                    {{ session('success') }}

                    <button type="button" class="btn-close"
                        data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <strong>Terjadi kesalahan:</strong>

                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')

        </section>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const menuToggle = document.getElementById('menuToggle');

        function toggleSidebar() {
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        }

        function closeSidebar() {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        }

        menuToggle.addEventListener('click', toggleSidebar);
        overlay.addEventListener('click', closeSidebar);

        // Tutup sidebar setelah memilih menu di mobile
        sidebar.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', closeSidebar);
        });

        // Tutup sidebar jika kembali ke desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth > 991) {
                closeSidebar();
            }
        });
    </script>

    @yield('scripts')

</body>
</html>