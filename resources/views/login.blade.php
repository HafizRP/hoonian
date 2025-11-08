<!-- resources/views/auth/login.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="author" content="Hoonian" />
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}" />

    <title>Login — Hoonian</title>

    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/fonts/icomoon/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/aos.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />

    <style>
        body {
            font-family: 'Work Sans', sans-serif;
            background: linear-gradient(135deg, #004aad 0%, #00b4d8 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 100%;
            max-width: 420px;
            animation: fadeInUp 0.6s ease forwards;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-card h2 {
            font-weight: 700;
            text-align: center;
            margin-bottom: 10px;
        }

        .login-card p {
            color: #6c757d;
            text-align: center;
            margin-bottom: 30px;
            font-size: 14px;
        }

        .form-control {
            border-radius: 10px;
            height: 45px;
        }

        .btn-primary {
            width: 100%;
            border-radius: 10px;
            font-weight: 600;
            padding: 12px;
        }

        .login-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }

        .login-footer a {
            color: #004aad;
            text-decoration: none;
            font-weight: 600;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }

        /* Tambahan Google button */
        .google-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            background: #fff;
            color: #444;
            border: 1px solid #ddd;
            border-radius: 10px;
            font-weight: 600;
            padding: 10px 0;
            transition: all 0.2s ease-in-out;
            margin-top: 15px;
        }

        .google-btn:hover {
            background: #f7f7f7;
        }

        .google-btn img {
            width: 20px;
            margin-right: 8px;
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 20px 0;
        }

        .divider::before,
        .divider::after {
            content: "";
            flex: 1;
            border-bottom: 1px solid #ddd;
        }

        .divider span {
            margin: 0 10px;
            color: #999;
            font-size: 13px;
        }
    </style>
</head>

<body>
    <div class="login-card" data-aos="fade-up">
        <h2>Welcome Back</h2>
        <p>Masuk ke akun Hoonian kamu</p>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="Email" required />
            </div>

            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Password" required />
            </div>

            <button type="submit" class="btn btn-primary">Login</button>
        </form>

        <!-- Divider -->
        <div class="divider"><span>atau</span></div>

        <!-- Tombol Google -->
        <a href="{{ route('register') }}" class="google-btn">
            <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google">
            Login dengan Google
        </a>

        <div class="login-footer">
            <p>Belum punya akun?
                <a href="{{ route('register') }}">Daftar Sekarang</a>
            </p>
        </div>
    </div>

    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/tiny-slider.js') }}"></script>
    <script src="{{ asset('assets/js/aos.js') }}"></script>
    <script src="{{ asset('assets/js/navbar.js') }}"></script>
    <script src="{{ asset('assets/js/counter.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
</body>

</html>
