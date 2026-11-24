<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - KasirApp</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        *, *::before, *::after {
            box-sizing: border-box;
        }

        :root {
            --primary: #19387c;
            --primary-dark: #102657;
            --primary-light: #254ea8;
            --accent: #FFD166;
            --surface: #f4f7fc;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: var(--surface);
            color: var(--text-main);
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        .login-wrapper {
            min-height: 100vh;
            display: flex;
        }

        /* LEFT PANEL */
        .login-left {
            width: 48%;
            background: radial-gradient(circle at top right, rgba(255, 209, 102, 0.14), transparent 42%),
                        linear-gradient(155deg, var(--primary-light) 0%, var(--primary) 52%, var(--primary-dark) 100%);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 56px;
            position: relative;
            overflow: hidden;
        }

        .login-left::after {
            content: "";
            position: absolute;
            width: 340px;
            height: 340px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.04);
            bottom: -110px;
            left: -90px;
            pointer-events: none;
        }

        .login-left-content {
            max-width: 460px;
            position: relative;
            z-index: 1;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 32px;
            font-weight: 800;
            letter-spacing: -0.02em;
            margin-bottom: 28px;
        }

        .brand-badge {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: rgba(255, 209, 102, 0.18);
            border: 1px solid rgba(255, 209, 102, 0.35);
            color: var(--accent);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .brand span {
            color: var(--accent);
        }

        .login-left h1 {
            font-size: 38px;
            font-weight: 800;
            line-height: 1.2;
            letter-spacing: -0.025em;
            margin-bottom: 16px;
        }

        .login-left p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 15.5px;
            line-height: 1.7;
            margin-bottom: 32px;
        }

        .feature-list {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .feature {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 16px;
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(6px);
        }

        .feature-icon {
            width: 38px;
            height: 38px;
            flex-shrink: 0;
            border-radius: 10px;
            background: rgba(255, 209, 102, 0.16);
            color: var(--accent);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
        }

        .feature-title {
            font-weight: 600;
            font-size: 14.5px;
            color: #ffffff;
        }

        .feature-desc {
            font-size: 12.5px;
            color: rgba(255, 255, 255, 0.72);
        }

        /* RIGHT PANEL */
        .login-right {
            width: 52%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 36px 24px;
        }

        .login-card {
            width: 100%;
            max-width: 440px;
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 22px;
            padding: 40px 36px;
            box-shadow: 0 20px 50px rgba(15, 23, 42, 0.06);
        }

        .login-title {
            font-size: 26px;
            font-weight: 800;
            color: var(--text-main);
            letter-spacing: -0.02em;
            margin-bottom: 6px;
        }

        .login-subtitle {
            color: var(--text-muted);
            font-size: 14.5px;
            margin-bottom: 28px;
        }

        .form-label {
            font-weight: 600;
            font-size: 13.5px;
            color: var(--text-main);
            margin-bottom: 8px;
        }

        .custom-input-group {
            display: flex;
            align-items: stretch;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            background: #ffffff;
            overflow: hidden;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .custom-input-group:focus-within {
            border-color: var(--primary);
            box-shadow: 0 0 0 3.5px rgba(25, 56, 124, 0.12);
        }

        .custom-input-group.is-invalid-group {
            border-color: #dc3545;
        }

        .custom-input-group .input-prefix {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 14px;
            color: var(--text-muted);
            background: #f8fafc;
            border-right: 1px solid #e2e8f0;
            font-size: 16px;
        }

        .custom-input-group .form-control {
            height: 48px;
            border: none;
            box-shadow: none !important;
            font-size: 14.5px;
            padding: 0 14px;
            background: transparent;
        }

        .btn-toggle-pass {
            border: none;
            background: transparent;
            color: var(--text-muted);
            padding: 0 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: color 0.15s ease, background-color 0.15s ease;
        }

        .btn-toggle-pass:hover {
            color: var(--primary);
            background: #f8fafc;
        }

        .alert {
            border-radius: 12px;
            font-size: 13.5px;
            padding: 12px 14px;
        }

        .btn-login {
            height: 48px;
            width: 100%;
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary) 100%);
            color: #ffffff;
            font-weight: 700;
            font-size: 15px;
            letter-spacing: 0.01em;
            box-shadow: 0 8px 20px rgba(25, 56, 124, 0.22);
            transition: transform 0.15s ease, box-shadow 0.2s ease, filter 0.2s ease;
        }

        .btn-login:hover:not(:disabled) {
            filter: brightness(1.05);
            box-shadow: 0 10px 24px rgba(25, 56, 124, 0.28);
            color: #ffffff;
        }

        .btn-login:active:not(:disabled) {
            transform: translateY(1px);
        }

        .login-footer {
            text-align: center;
            margin-top: 26px;
            padding-top: 18px;
            border-top: 1px solid #f1f5f9;
            color: var(--text-muted);
            font-size: 12.5px;
        }

        @media (max-width: 991.98px) {
            .login-left {
                display: none;
            }

            .login-right {
                width: 100%;
                padding: 24px 16px;
            }

            .login-card {
                padding: 32px 24px;
                border-radius: 18px;
            }
        }
    </style>
</head>

<body>

<div class="login-wrapper">

    <!-- LEFT PANEL -->
    <div class="login-left">
        <div class="login-left-content">
            <div class="brand">
                <span class="brand-badge">
                    <i class="bi bi-shop"></i>
                </span>
                <div>Kasir<span>App</span></div>
            </div>

            <h1>Kelola bisnis lebih mudah &amp; terarah.</h1>

            <p>
                Selamat datang di panel administrator KasirApp. Pantau stok lintas outlet,
                riwayat transaksi, dan performa penjualan secara real-time.
            </p>

            <div class="feature-list">
                <div class="feature">
                    <div class="feature-icon">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <div>
                        <div class="feature-title">Kelola Produk &amp; Stok</div>
                        <div class="feature-desc">Atur varian produk dan pantau stok menipis per outlet</div>
                    </div>
                </div>

                <div class="feature">
                    <div class="feature-icon">
                        <i class="bi bi-receipt"></i>
                    </div>
                    <div>
                        <div class="feature-title">Pantau Transaksi</div>
                        <div class="feature-desc">Cek detail penjualan masuk dan status pembayaran</div>
                    </div>
                </div>

                <div class="feature">
                    <div class="feature-icon">
                        <i class="bi bi-graph-up"></i>
                    </div>
                    <div>
                        <div class="feature-title">Analisis Perkembangan Bisnis</div>
                        <div class="feature-desc">Lihat tren omzet harian, produk terlaris, dan performa outlet</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT PANEL -->
    <div class="login-right">
        <div class="login-card">
            <div class="login-title">Login Admin</div>
            <div class="login-subtitle">Masuk menggunakan akun administrator untuk melanjutkan.</div>

            @if($errors->any())
                <div class="alert alert-danger d-flex align-items-center gap-2 mb-3" role="alert">
                    <i class="bi bi-exclamation-circle-fill flex-shrink-0"></i>
                    <div>{{ $errors->first() }}</div>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success d-flex align-items-center gap-2 mb-3" role="alert">
                    <i class="bi bi-check-circle-fill flex-shrink-0"></i>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            <form id="loginForm" action="{{ route('login.store') }}" method="POST">
                @csrf

                <!-- Username -->
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <div class="custom-input-group @error('username') is-invalid-group @enderror">
                        <span class="input-prefix">
                            <i class="bi bi-person"></i>
                        </span>
                        <input
                            type="text"
                            id="username"
                            name="username"
                            class="form-control"
                            value="{{ old('username') }}"
                            placeholder="Masukkan username admin"
                            autocomplete="username"
                            autofocus
                            required
                        >
                    </div>
                    @error('username')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <div class="custom-input-group @error('password') is-invalid-group @enderror">
                        <span class="input-prefix">
                            <i class="bi bi-lock"></i>
                        </span>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control"
                            placeholder="Masukkan password"
                            autocomplete="current-password"
                            required
                        >
                        <button
                            type="button"
                            class="btn-toggle-pass"
                            onclick="togglePassword()"
                            aria-label="Tampilkan atau sembunyikan password"
                        >
                            <i class="bi bi-eye" id="passwordIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" id="btnSubmit" class="btn btn-login">
                    <span id="btnText">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Masuk ke Dashboard
                    </span>
                    <span id="btnLoading" class="d-none">
                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                        Memproses...
                    </span>
                </button>
            </form>

            <div class="login-footer">
                &copy; {{ date('Y') }} KasirApp &bull; Sistem Manajemen Kasir &amp; Outlet
            </div>
        </div>
    </div>

</div>

<script>
    function togglePassword() {
        const password = document.getElementById('password');
        const icon = document.getElementById('passwordIcon');

        if (password.type === 'password') {
            password.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            password.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    }

    document.getElementById('loginForm').addEventListener('submit', function () {
        const btnSubmit = document.getElementById('btnSubmit');
        const btnText = document.getElementById('btnText');
        const btnLoading = document.getElementById('btnLoading');

        if (this.checkValidity()) {
            btnSubmit.disabled = true;
            btnText.classList.add('d-none');
            btnLoading.classList.remove('d-none');
        }
    });
</script>

</body>
</html>