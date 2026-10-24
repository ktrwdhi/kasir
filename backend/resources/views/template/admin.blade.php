<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'Admin Dashboard')
    </title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet"
    >

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f5f7fb;
            font-family: Arial, sans-serif;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background: #19387c;
            position: fixed;
            left: 0;
            top: 0;
            padding: 25px 15px;
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

        .main {
            margin-left: 250px;
            min-height: 100vh;
            padding-top: 70px;
        }

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
        }

        .content {
            padding: 30px;
        }
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

        .table {
            vertical-align: middle;
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

        .btn-primary {
            background: #19387c;
            border-color: #19387c;
        }

        .btn-primary:hover {
            background: #102b64;
            border-color: #102b64;
        }
    </style>

    @yield('styles')
</head>

<body>

    <aside class="sidebar">

        <div class="brand">
            Kasir<span>App</span>
        </div>

        <div class="menu-title">
            Menu
        </div>

        <a
            href="{{ route('admin.dashboard') }}"
            class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
        >
            <i class="bi bi-grid"></i>
            Dashboard
        </a>

        <a
            href="{{ route('admin.products.index') }}"
            class="{{ request()->routeIs('admin.products.index') ? 'active' : '' }}"
            >
            <i class="bi bi-box-seam"></i>
            Produk
        </a>
        
        <a href="{{ route('admin.outlet.index') }}"
            class="{{ request()->routeIs('admin.outlet.index') ? 'active' : '' }}"
        >
            <i class="bi bi-shop"></i>
            <span>Outlet</span>
        </a>

        <div class="menu-title mt-4">
            Lainnya
        </div>

        <a href="#">
            <i class="bi bi-receipt"></i>
            Transaksi
        </a>

        <a href="#">
            <i class="bi bi-people"></i>
            Pengguna
        </a>
        <form
            action="{{ route('admin.logout') }}"
            method="POST"
            class="mt-4"
        >
            @csrf

            <button
                type="submit"
                class="w-100 border-0 text-start"
                style="
                    background: transparent;
                    color: rgba(255,255,255,.8);
                    padding: 12px 15px;
                    border-radius: 10px;
                "
            >
                <i class="bi bi-box-arrow-right me-2"></i>
                Logout
            </button>

        </form>

    </aside>


    <main class="main">

        <nav class="navbar-admin">

            <div>
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
                        Administrator
                    </small>
                </div>

                <div
                    class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                    style="width: 42px;height:42px;"
                >
                    <i class="bi bi-person"></i>
                </div>

            </div>

        </nav>


        <section class="content">

            @if(session('success'))

                <div class="alert alert-success alert-dismissible fade show">

                    <i class="bi bi-check-circle me-2"></i>

                    {{ session('success') }}

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                    ></button>

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

    @yield('scripts')

</body>
</html>