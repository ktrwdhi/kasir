<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Login Admin - KasirApp</title>

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
            min-height: 100vh;
            background: #f5f7fb;
            font-family: Arial, sans-serif;
        }

        .login-wrapper {
            min-height: 100vh;
            display: flex;
        }

        .login-left {
            width: 50%;
            background: #19387c;
            color: white;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 50px;
        }

        .login-left-content {
            max-width: 450px;
        }

        .brand {
            font-size: 42px;
            font-weight: 800;
            margin-bottom: 20px;
        }

        .brand span {
            color: #FFD166;
        }

        .login-left h1 {
            font-size: 42px;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 20px;
        }

        .login-left p {
            color: rgba(255,255,255,.75);
            font-size: 17px;
            line-height: 1.7;
        }

        .feature {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 25px;
        }

        .feature-icon {
            width: 40px;
            height: 40px;

            border-radius: 10px;

            background: rgba(255,255,255,.12);

            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-right {
            width: 50%;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 30px;
        }

        .login-card {
            width: 100%;
            max-width: 430px;

            background: white;

            border-radius: 18px;

            padding: 40px;

            box-shadow:
                0 10px 40px rgba(0,0,0,.08);
        }

        .login-title {
            font-size: 28px;
            font-weight: 800;
            color: #222;

            margin-bottom: 8px;
        }

        .login-subtitle {
            color: #777;
            margin-bottom: 30px;
        }

        .form-label {
            font-weight: 600;
            color: #333;
        }

        .form-control {
            height: 50px;
            border-radius: 10px;
            border: 1px solid #ddd;
        }

        .form-control:focus {
            border-color: #19387c;
            box-shadow: 0 0 0 .2rem rgba(25,56,124,.1);
        }

        .input-group .form-control {
            border-radius: 0 10px 10px 0;
        }

        .input-group-text {
            background: white;
            border: 1px solid #ddd;
            border-right: none;
            border-radius: 10px 0 0 10px;
        }

        .btn-login {
            height: 50px;
            width: 100%;

            border: none;
            border-radius: 10px;

            background: #19387c;
            color: white;

            font-weight: 600;
            font-size: 16px;

            transition: .2s;
        }

        .btn-login:hover {
            background: #102b64;
            color: white;
        }

        .login-footer {
            text-align: center;
            margin-top: 25px;
            color: #999;
            font-size: 13px;
        }

        @media (max-width: 768px) {

            .login-left {
                display: none;
            }

            .login-right {
                width: 100%;
            }

            .login-card {
                padding: 30px 25px;
            }

        }

    </style>

</head>


<body>


<div class="login-wrapper">


    <!-- LEFT -->

    <div class="login-left">

        <div class="login-left-content">

            <div class="brand">
                Kasir<span>App</span>
            </div>

            <h1>
                Kelola bisnis lebih mudah.
            </h1>

            <p>
                Selamat datang di halaman administrator
                KasirApp. Kelola produk, stok, transaksi,
                dan data bisnis kamu dari satu tempat.
            </p>


            <div class="feature">

                <div class="feature-icon">
                    <i class="bi bi-box-seam"></i>
                </div>

                <div>
                    Kelola produk dan stok
                </div>

            </div>


            <div class="feature">

                <div class="feature-icon">
                    <i class="bi bi-receipt"></i>
                </div>

                <div>
                    Pantau transaksi
                </div>

            </div>


            <div class="feature">

                <div class="feature-icon">
                    <i class="bi bi-graph-up"></i>
                </div>

                <div>
                    Lihat perkembangan bisnis
                </div>

            </div>

        </div>

    </div>



    <!-- RIGHT -->

    <div class="login-right">


        <div class="login-card">


            <div class="login-title">
                Login Admin
            </div>

            <div class="login-subtitle">
                Masuk untuk mengakses dashboard.
            </div>


            @if($errors->any())

                <div class="alert alert-danger">

                    <i class="bi bi-exclamation-circle me-2"></i>

                    {{ $errors->first() }}

                </div>

            @endif


            @if(session('success'))

                <div class="alert alert-success">

                    {{ session('success') }}

                </div>

            @endif


            <form
                action="{{ route('login.store') }}"
                method="POST"
            >

                @csrf


                <!-- Username -->

                <div class="mb-3">

                    <label class="form-label">
                        Username
                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="bi bi-person"></i>
                        </span>

                        <input
                            type="text"
                            name="username"
                            class="form-control @error('username') is-invalid @enderror"
                            value="{{ old('username') }}"
                            placeholder="Masukkan username"
                            autocomplete="username"
                        >

                    </div>

                    @error('username')

                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                <!-- Password -->

                <div class="mb-4">

                    <label class="form-label">
                        Password
                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="bi bi-lock"></i>
                        </span>

                        <input
                            type="password"
                            name="password"
                            id="password"
                            class="form-control"
                            placeholder="Masukkan password"
                            autocomplete="current-password"
                        >

                        <button
                            type="button"
                            class="btn btn-outline-secondary"
                            onclick="togglePassword()"
                        >
                            <i
                                class="bi bi-eye"
                                id="passwordIcon"
                            ></i>
                        </button>

                    </div>

                </div>


                <button
                    type="submit"
                    class="btn btn-login"
                >
                    <i class="bi bi-box-arrow-in-right me-2"></i>
                    Login
                </button>


            </form>


            <div class="login-footer">

                © {{ date('Y') }} KasirApp

            </div>


        </div>


    </div>


</div>



<script>

    function togglePassword() {

        const password =
            document.getElementById('password');

        const icon =
            document.getElementById('passwordIcon');


        if (password.type === 'password') {

            password.type = 'text';

            icon.classList.remove('bi-eye');

            icon.classList.add('bi-eye-slash');

        } else {

            password.type = 'password';

            icon.classList.remove('bi-eye-slash');

            icon.classList.add('bi-eye');

        }

    }

</script>


</body>

</html>