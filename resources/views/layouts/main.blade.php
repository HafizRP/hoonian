<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="author" content="Untree.co" />
    <meta name="description" content="Platform Beli Properti Modern dan Terpercaya" />
    <meta name="keywords" content="bootstrap, bootstrap5, property, hoonian" />

    <link rel="shortcut icon" href="{{ asset('favicon.png') }}" />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet" />

    {{-- Font Awesome for Icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    {{-- Style Assets --}}
    <link rel="stylesheet" href="{{ asset('assets/fonts/icomoon/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/fonts/flaticon/font/flaticon.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/tiny-slider.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/aos.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />

    <title>@yield('title', 'Hoonian — Platform Properti Terpercaya')</title>
</head>

<body>
    {{-- Mobile Menu --}}
    <div class="site-mobile-menu site-navbar-target">
        <div class="site-mobile-menu-header">
            <div class="site-mobile-menu-close">
                <span class="icofont-close js-menu-toggle"></span>
            </div>
        </div>
        <div class="site-mobile-menu-body"></div>
    </div>

    {{-- Desktop Navigation --}}
    <nav class="site-nav">
        <div class="container">
            <div class="menu-bg-wrap">
                <div class="site-navigation">
                    <a href="{{ route('main') }}" class="logo m-0 float-start">Hoonian</a>

                    <ul class="js-clone-nav d-none d-lg-inline-block text-start site-menu float-end">
                        <li class="{{ Route::is('main') ? 'active' : '' }}">
                            <a href="{{ route('main') }}"><i class="fas fa-home me-1"></i> Home</a>
                        </li>
                        <li class="{{ Route::is('properties.index') || Route::is('properties.show') ? 'active' : '' }}">
                            <a href="{{ route('properties.index') }}"><i class="fas fa-building me-1"></i> Properties</a>
                        </li>
                        @auth
                            <li>
                                <a href="{{ route('properties.create') }}"
                                    class="btn btn-primary text-white py-2 px-3"><i class="fas fa-plus-circle me-1"></i> Post Property</a>
                            </li>
                        @endauth

                        @guest
                            <li class="{{ Route::is('login') ? 'active' : '' }}">
                                <a href="{{ route('login') }}"><i class="fas fa-sign-in-alt me-1"></i> Login</a>
                            </li>
                        @else
                            <li class="has-children">
                                <a href="#"><i class="fas fa-user-circle me-1"></i> Hi, {{ Auth::user()->name }}</a>
                                <ul class="dropdown">
                                    <li><a href="{{ route('users.profile') }}"><i class="fas fa-user me-2"></i> Profile</a></li>
                                    <li><a href="{{ route('bidding.list') }}"><i class="fas fa-gavel me-2"></i> My Bidding</a></li>

                                    @if (Auth::user()->role != 3)
                                        <li class="border-top mt-2 pt-2">
                                            <a href="{{ route('backoffice.index') }}" class="text-primary"><i class="fas fa-tachometer-alt me-2"></i> Backoffice</a>
                                        </li>
                                    @endif

                                    <li>
                                        <a href="javascript:void(0)"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                            class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>

                    <a href="#"
                        class="burger light me-auto float-end mt-1 site-menu-toggle js-menu-toggle d-inline-block d-lg-none"
                        data-toggle="collapse" data-target="#main-navbar">
                        <span></span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer Section --}}
    <footer class="site-footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="widget">
                        <h3>Contact</h3>
                        <address>43 Raymouth Rd. Baltemoer, London 3910</address>
                        <ul class="list-unstyled links">
                            <li><a href="tel://62812345678">+62 812 345 678</a></li>
                            <li><a href="mailto:info@hoonian.com">info@hoonian.com</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="widget">
                        <h3>Sources</h3>
                        <div class="row">
                            <div class="col-6">
                                <ul class="list-unstyled links">
                                    <li><a href="#">About us</a></li>
                                    <li><a href="#">Services</a></li>
                                    <li><a href="#">Terms</a></li>
                                    <li><a href="#">Privacy</a></li>
                                </ul>
                            </div>
                            <div class="col-6">
                                <ul class="list-unstyled links">
                                    <li><a href="#">Partners</a></li>
                                    <li><a href="#">Careers</a></li>
                                    <li><a href="#">FAQ</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="widget">
                        <h3>Follow Us</h3>
                        <ul class="list-unstyled social">
                            <li><a href="#"><span class="icon-instagram"></span></a></li>
                            <li><a href="#"><span class="icon-twitter"></span></a></li>
                            <li><a href="#"><span class="icon-facebook"></span></a></li>
                            <li><a href="#"><span class="icon-linkedin"></span></a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-12 text-center">
                    <p class="copyright">
                        Copyright &copy; {{ date('Y') }}. All Rights Reserved. &mdash; Designed by <a
                            href="https://untree.co">Untree.co</a>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    {{-- Scripts Assets --}}
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/tiny-slider.js') }}"></script>
    <script src="{{ asset('assets/js/aos.js') }}"></script>
    <script src="{{ asset('assets/js/navbar.js') }}"></script>
    <script src="{{ asset('assets/js/counter.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
</body>

</html>
