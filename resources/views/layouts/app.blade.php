<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', config('app.name', 'Laravel'))</title>
    
    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    @yield('styles')
</head>
<body class="font-sans antialiased h-100 d-flex flex-column">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">Nike Store</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products.index') }}">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/about') }}">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/contact') }}">Contact</a>
                    </li>
                    @auth
                        @if(Auth::user()->hasRole('admin'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">Admin</a>
                            </li>
                        @endif
                    @endauth
                </ul>
                <ul class="navbar-nav">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('wishlist.index') }}">
                                <i class="bi bi-heart"></i> Wishlist
                                @if(Auth::user() && Auth::user()->wishlistItems && Auth::user()->wishlistItems->count() > 0)
                                    <span class="badge bg-primary wishlist-count">{{ Auth::user()->wishlistItems->count() }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('compare.index') }}">
                                <i class="bi bi-layout-wtf"></i> Compare
                                @if(Auth::user() && Auth::user()->compareProducts && Auth::user()->compareProducts->count() > 0)
                                    <span class="badge bg-primary compare-count">{{ Auth::user()->compareProducts->count() }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('recently-viewed.index') }}">
                                <i class="bi bi-clock-history"></i> Recently Viewed
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('cart.index') }}">
                                <i class="bi bi-cart"></i> Cart
                                @if(Auth::user() && Auth::user()->cartItems && Auth::user()->cartItems->count() > 0)
                                    <span class="badge bg-primary">{{ Auth::user()->cartItems->count() }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                @if(Auth::user()->profile_photo_path)
                                    <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" 
                                         class="rounded-circle" 
                                         style="width: 32px; height: 32px; object-fit: cover;" 
                                         alt="User Avatar">
                                @else
                                    <img src="{{ asset('images/default-avatar.png') }}" 
                                         class="rounded-circle" 
                                         style="width: 32px; height: 32px; object-fit: cover;" 
                                         alt="Default Avatar">
                                @endif
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('orders.index') }}">My Orders</a></li>
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                    @endauth
                    <li class="nav-item">
                        <button class="nav-link btn btn-link" id="darkModeToggle" type="button">
                            <i class="bi bi-moon-fill" id="darkModeIcon"></i>
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-shrink-0">
        @if(session('success'))
            <div class="container mt-3">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif
        
        @if(session('error'))
            <div class="container mt-3">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif
        
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-body-tertiary text-dark py-4 mt-auto">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Nike Store</h5>
                    <p>Experience the future of footwear with our premium collection of athletic shoes and apparel.</p>
                </div>
                <div class="col-md-3">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ url('/') }}" class="text-dark text-decoration-none">Home</a></li>
                        <li><a href="{{ route('products.index') }}" class="text-dark text-decoration-none">Products</a></li>
                        <li><a href="{{ url('/about') }}" class="text-dark text-decoration-none">About</a></li>
                        <li><a href="{{ route('cart.index') }}" class="text-dark text-decoration-none">Cart</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Contact Us</h5>
                    <address>
                        <strong>Nike Store</strong><br>
                        123 Shoe Street<br>
                        Portland, OR 97205<br>
                        <abbr title="Phone">P:</abbr> (123) 456-7890
                    </address>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center">
                <p>&copy; {{ date('Y') }} Nike Store. All rights reserved.</p>
            </div>
        </div>
    </footer>
    
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: true
        });
        
        // Dark mode toggle
        function initializeDarkMode() {
            const darkModeToggle = document.getElementById('darkModeToggle');
            const darkModeIcon = document.getElementById('darkModeIcon');
            const htmlElement = document.documentElement;
            
            // Check for saved theme preference or respect OS preference
            const savedTheme = localStorage.getItem('theme');
            const prefersDarkScheme = window.matchMedia('(prefers-color-scheme: dark)');
            
            // Set initial theme
            if (savedTheme === 'dark' || (!savedTheme && prefersDarkScheme.matches)) {
                htmlElement.setAttribute('data-bs-theme', 'dark');
                darkModeIcon.classList.remove('bi-moon-fill');
                darkModeIcon.classList.add('bi-sun-fill');
            } else {
                htmlElement.setAttribute('data-bs-theme', 'light');
                darkModeIcon.classList.remove('bi-sun-fill');
                darkModeIcon.classList.add('bi-moon-fill');
            }
            
            // Toggle theme on button click
            if (darkModeToggle) {
                darkModeToggle.addEventListener('click', function() {
                    const currentTheme = htmlElement.getAttribute('data-bs-theme');
                    
                    if (currentTheme === 'light') {
                        htmlElement.setAttribute('data-bs-theme', 'dark');
                        darkModeIcon.classList.remove('bi-moon-fill');
                        darkModeIcon.classList.add('bi-sun-fill');
                        localStorage.setItem('theme', 'dark');
                    } else {
                        htmlElement.setAttribute('data-bs-theme', 'light');
                        darkModeIcon.classList.remove('bi-sun-fill');
                        darkModeIcon.classList.add('bi-moon-fill');
                        localStorage.setItem('theme', 'light');
                    }
                });
            }
        }
        
        // Initialize dark mode when DOM is loaded
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initializeDarkMode);
        } else {
            initializeDarkMode();
        }
        
        // Also initialize dark mode immediately if DOM is already loaded
        initializeDarkMode();
    </script>
    
    @yield('scripts')
</body>
</html>