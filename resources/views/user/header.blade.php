<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Car Showroom')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Montserrat Font -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">

    <style>
        /* responsivitas */
        .search-group {
            flex: 1;
            min-width: 100px;
            max-width: 280px;
        }

        .logo-container {
            text-align: center;
            flex: 2;
        }

        .icons-container {
            flex: 1;
            display: flex;
            justify-content: flex-end;
            min-width: 100px;
            max-width: 280px;
        }

        @media (max-width: 1200px) {
            .brand-text {
                font-size: 1.8rem !important;
            }

            .search-input {
                font-size: 0.9rem !important;
            }
        }

        @media (max-width: 991.98px) {
            .brand-text {
                font-size: 1.4rem !important;
            }

            .logo-img {
                height: 45px !important;
            }

            .search-input {
                font-size: 0.8rem !important;
                padding: 0.3rem 0.5rem !important;
            }

            .search-button {
                padding: 0.3rem 0.5rem !important;
            }

            .icon {
                font-size: 1.5em !important;
            }

            .icon-spacing {
                margin-right: 1rem !important;
            }
        }

        @media (max-width: 767.98px) {
            .brand-text {
                font-size: 1.2rem !important;
            }

            .logo-img {
                height: 40px !important;
                margin-right: 0.5rem !important;
            }

            .icon {
                font-size: 1.2em !important;
            }

            .icon-spacing {
                margin-right: 0.5rem !important;
            }

            .search-button i {
                font-size: 0.8em !important;
            }
        }

        @media (max-width: 575.98px) {
            .search-group {
                max-width: 80px;
            }

            .brand-text {
                font-size: 1rem !important;
            }

            .logo-img {
                height: 35px !important;
            }

            .search-input::placeholder {
                font-size: 0.7rem;
            }
        }
    </style>

    @yield('styles')
</head>

<body style="font-family: 'Montserrat', sans-serif;">
    <!--  Navbar -->
    <nav class="navbar navbar-dark bg-dark py-2 py-sm-3">
        <div class="container">
            <div class="d-flex align-items-center w-100">
                <!-- Search Bar  -->
                <div class="search-group me-2">
                    <form action="{{ route('user.catalog') }}" method="GET">
                        <div class="input-group input-group-sm input-group-md-lg">
                            <input type="text" class="form-control search-input" name="search" 
                                   value="{{ request('search') }}" placeholder="Search..."
                                   aria-label="Search">
                            <button class="btn btn-outline-light search-button" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Logo and Brand -->
                <div class="logo-container mx-2">
                    <a class="navbar-brand d-flex align-items-center justify-content-center"
                        href="{{ route('user.home') }}">
                        <img src="{{ asset('storage/logo-white.png') }}" alt="Logo" height="60" class="me-2 logo-img">
                        <span class="fw-bold fs-1 brand-text">LUXURY TOYS</span>
                    </a>
                </div>

                <!-- icons -->
                <div class="icons-container ms-2">
                    <a href="{{route('user.order')}}" class="text-white p-2">
                        <i class="far fa-solid fa-receipt fa-2x icon"></i>
                    </a>
                    <a href="{{route('wishlist.index')}}" class="text-white p-2">
                        <i class="far fa-heart fa-2x icon"></i>
                    </a>
                    <a href="{{route('cart.index')}}" class="text-white p-2">
                        <i class="fas fa-shopping-cart fa-2x icon"></i>
                    </a>
                    <form method="POST" action="{{ route('user.logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn text-white p-2 border-0 bg-transparent">
                            <i class="fas fa-sign-out-alt fa-2x icon"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @yield('scripts')
</body>

</html>