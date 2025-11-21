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
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        /* --- Bootstrap Carousel Background --- */
        .carousel,
        .carousel-inner,
        .carousel-item,
        .carousel-item img {
            position: absolute;
            width: 100%;
            height: 100%;
            object-fit: cover;
            top: 0;
            left: 0;
            z-index: 0;
        }

        .carousel::after {
            content: "";
            position: absolute;
            inset: 0;
            /* background: rgba(0, 74, 173, 0.55); */
            z-index: 1;
        }

        /* --- Login Card --- */
        .login-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            padding: 40px;
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 2;
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
            background-color: #004aad;
            border: none;
        }

        .btn-primary:hover {
            background-color: #003580;
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

        /* Tombol Google */
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

    <!-- Bootstrap Carousel Background -->
    <div id="bgCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="4000">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('assets/images/hero_bg_1.jpg') }}" alt="bg1">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('assets/images/hero_bg_2.jpg') }}" alt="bg2">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('assets/images/hero_bg_3.jpg') }}" alt="bg3">
            </div>
        </div>
    </div>

    <!-- Login Card -->
    <div class="login-card">

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <h2>Welcome Back</h2>
        <p>Masuk ke akun Hoonian kamu</p>

        <form method="POST" action="{{ route('auth.login') }}">
            @csrf
            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required />
            </div>

            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Password" required />
            </div>

            <button type="submit" class="btn btn-primary">Login</button>
        </form>

        <div class="divider"><span>atau</span></div>

        <!-- Tombol Google -->
        <a href="{{ route('auth.login') }}" class="google-btn">
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
</body>

</html>
