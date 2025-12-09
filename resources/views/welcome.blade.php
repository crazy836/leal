@extends('layouts.app')

@section('title', 'Nike Store - Premium Athletic Footwear')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section position-relative vh-100 overflow-hidden">
        <div class="hero-background position-absolute top-0 start-0 w-100 h-100">
            <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2070&q=80" alt="Hero Background" class="w-100 h-100 object-fit-cover">
        </div>
        <div class="hero-overlay position-absolute top-0 start-0 w-100 h-100 bg-black opacity-50"></div>
        <div class="hero-content position-relative h-100 d-flex align-items-center justify-content-center text-center text-white">
            <div data-aos="fade-up">
                <h1 class="display-1 fw-bold mb-4">JUST DO IT</h1>
                <p class="lead fs-3 mb-5">Experience the future of footwear</p>
                <a href="{{ route('products.index') }}" class="btn btn-light btn-lg px-5 py-3">Shop Now</a>
            </div>
        </div>
    </section>

    <!-- Mini Image Strip -->
    <section class="mini-image-strip py-5 bg-light">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-2 col-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="card border-0 shadow-sm h-100">
                        <img src="{{ asset('images/Air Force 1 \'07.png') }}" class="card-img-top" alt="Product 1">
                    </div>
                </div>
                <div class="col-md-2 col-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="card border-0 shadow-sm h-100">
                        <img src="{{ asset('images/Air Zoom Pegasus 38.png') }}" class="card-img-top" alt="Product 2">
                    </div>
                </div>
                <div class="col-md-2 col-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="card border-0 shadow-sm h-100">
                        <img src="{{ asset('images/LeBron 19.png') }}" class="card-img-top" alt="Product 3">
                    </div>
                </div>
                <div class="col-md-2 col-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="card border-0 shadow-sm h-100">
                        <img src="{{ asset('images/Metcon 7.png') }}" class="card-img-top" alt="Product 4">
                    </div>
                </div>
                <div class="col-md-2 col-6" data-aos="fade-up" data-aos-delay="500">
                    <div class="card border-0 shadow-sm h-100">
                        <img src="{{ asset('images/Mercurial Vapor 14.png') }}" class="card-img-top" alt="Product 5">
                    </div>
                </div>
                <div class="col-md-2 col-6" data-aos="fade-up" data-aos-delay="600">
                    <div class="card border-0 shadow-sm h-100">
                        <img src="{{ asset('images/React Infinity Run Flyknit 2.png') }}" class="card-img-top" alt="Product 6">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Collections -->
    <section class="featured-collections py-5">
        <div class="container">
            <h2 class="text-center mb-5" data-aos="fade-up">Featured Collections</h2>
            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-right">
                    <div class="card border-0 rounded-0 h-100">
                        <img src="https://images.unsplash.com/photo-1543508282-6319a3e2621f?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1000&q=80" class="card-img rounded-0" alt="Men's Collection">
                        <div class="card-img-overlay d-flex align-items-end">
                            <div class="bg-white p-3 w-100">
                                <h5 class="card-title mb-1">Men's Performance</h5>
                                <p class="card-text mb-2">For the everyday athlete</p>
                                <a href="{{ route('products.index') }}" class="btn btn-dark btn-sm">Shop Collection</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up">
                    <div class="card border-0 rounded-0 h-100">
                        <img src="{{ asset('images/hoodie.jpg') }}" class="card-img rounded-0" alt="Women's Collection">
                        <div class="card-img-overlay d-flex align-items-end">
                            <div class="bg-white p-3 w-100">
                                <h5 class="card-title mb-1">Women's Training</h5>
                                <p class="card-text mb-2">Designed for her journey</p>
                                <a href="{{ route('products.index') }}" class="btn btn-dark btn-sm">Shop Collection</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-left">
                    <div class="card border-0 rounded-0 h-100">
                        <img src="https://images.unsplash.com/photo-1600269452121-4f2416e55c28?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1000&q=80" class="card-img rounded-0" alt="Lifestyle Collection">
                        <div class="card-img-overlay d-flex align-items-end">
                            <div class="bg-white p-3 w-100">
                                <h5 class="card-title mb-1">Lifestyle Sneakers</h5>
                                <p class="card-text mb-2">Style meets comfort</p>
                                <a href="{{ route('products.index') }}" class="btn btn-dark btn-sm">Shop Collection</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Product Grid -->
    <section class="product-grid py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5" data-aos="fade-up">Popular Products</h2>
            <div class="row g-4">
                @foreach(App\Models\Product::take(6)->get() as $index => $product)
                    <div class="col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="{{ ($index + 1) * 100 }}">
                        <div class="card h-100 border-0 shadow-sm">
                            @if($product->images->count() > 0)
                                @php
                                    $imagePath = $product->images->first()->image_path;
                                    // Check if it's a local image path (not a URL)
                                    if (strpos($imagePath, 'http') === false) {
                                        $imageUrl = asset($imagePath);
                                    } else {
                                        $imageUrl = $imagePath;
                                    }
                                @endphp
                                <img src="{{ $imageUrl }}" class="card-img-top" alt="{{ $product->name }}" style="height: 250px; object-fit: cover;">
                            @else
                                <img src="https://via.placeholder.com/300x250?text=No+Image" class="card-img-top" alt="{{ $product->name }}" style="height: 250px; object-fit: cover;">
                            @endif
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ $product->name }}</h5>
                                <p class="card-text flex-grow-1">{{ Str::limit($product->description, 80) }}</p>
                                <div class="d-flex justify-content-between align-items-center mt-auto">
                                    <span class="h5 mb-0">${{ number_format($product->price, 2) }}</span>
                                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary">View</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="text-center mt-5" data-aos="fade-up">
                <a href="{{ route('products.index') }}" class="btn btn-outline-dark btn-lg">View All Products</a>
            </div>
        </div>
    </section>

    <!-- Short Video Banner -->
    <section class="video-banner position-relative vh-50 overflow-hidden">
        <div class="video-background position-absolute top-0 start-0 w-100 h-100">
            <img src="https://images.unsplash.com/photo-1559056132-5d30d80950e8?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2070&q=80" alt="Video Banner" class="w-100 h-100 object-fit-cover">
        </div>
        <div class="video-overlay position-absolute top-0 start-0 w-100 h-100 bg-black opacity-30"></div>
        <div class="video-content position-relative h-100 d-flex align-items-center justify-content-center text-center text-white">
            <div data-aos="fade-up">
                <h2 class="display-4 fw-bold mb-4">Innovation in Motion</h2>
                <p class="lead fs-4 mb-5">Experience the perfect blend of style and performance</p>
                <a href="{{ route('products.index') }}" class="btn btn-light btn-lg px-5 py-3">Discover More</a>
            </div>
        </div>
    </section>

    <!-- New Arrivals Carousel -->
    <section class="new-arrivals py-5">
        <div class="container">
            <h2 class="text-center mb-5" data-aos="fade-up">New Arrivals</h2>
            <div class="row">
                <div class="col-12">
                    <div id="newArrivalsCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach(App\Models\Product::take(6)->get()->chunk(3) as $chunkIndex => $chunk)
                                <div class="carousel-item {{ $chunkIndex === 0 ? 'active' : '' }}">
                                    <div class="row g-4">
                                        @foreach($chunk as $product)
                                            <div class="col-md-4">
                                                <div class="card h-100 border-0 shadow-sm">
                                                    @if($product->images->count() > 0)
                                                        @php
                                                            $imagePath = $product->images->first()->image_path;
                                                            // Check if it's a local image path (not a URL)
                                                            if (strpos($imagePath, 'http') === false) {
                                                                $imageUrl = asset($imagePath);
                                                            } else {
                                                                $imageUrl = $imagePath;
                                                            }
                                                        @endphp
                                                        <img src="{{ $imageUrl }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                                                    @else
                                                        <img src="https://via.placeholder.com/300x200?text=No+Image" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                                                    @endif
                                                    <div class="card-body">
                                                        <h5 class="card-title">{{ $product->name }}</h5>
                                                        <p class="card-text">${{ number_format($product->price, 2) }}</p>
                                                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary w-100">View Product</a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#newArrivalsCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#newArrivalsCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Behind the Design -->
    <section class="behind-design py-5 bg-light">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-md-6" data-aos="fade-right">
                    <img src="https://images.unsplash.com/photo-1543508282-6319a3e2621f?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1000&q=80" alt="Behind the Design" class="img-fluid rounded">
                </div>
                <div class="col-md-6" data-aos="fade-left">
                    <h2 class="mb-4">Behind the Design</h2>
                    <p class="lead">Our commitment to innovation drives every stitch, every sole, and every silhouette. We don't just make shoes; we engineer experiences.</p>
                    <p>From the drawing board to the factory floor, our designers and engineers work tirelessly to push boundaries and redefine what's possible in athletic footwear.</p>
                    <ul class="list-unstyled mt-4">
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i> Sustainable Materials</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i> Performance-Driven Innovation</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i> Athlete Collaboration</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i> Quality Craftsmanship</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Athlete Story -->
    <section class="athlete-story py-5">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-md-6 order-md-2" data-aos="fade-left">
                    <img src="{{ asset('images/Athlete Story.png') }}" alt="Athlete Story" class="img-fluid rounded">
                </div>
                <div class="col-md-6 order-md-1" data-aos="fade-right">
                    <h2 class="mb-4">Athlete Story</h2>
                    <p class="lead">Meet Sarah Johnson, marathon runner and our newest brand ambassador. Her journey from amateur to professional athlete mirrors our commitment to excellence.</p>
                    <blockquote class="mt-4">
                        <p>"These shoes aren't just footwear; they're my competitive edge. The cushioning and support have taken my performance to the next level."</p>
                        <footer class="blockquote-footer mt-2">Sarah Johnson, Marathon Runner</footer>
                    </blockquote>
                    <a href="#" class="btn btn-outline-dark mt-3">Read Full Story</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Category Showcase -->
    <section class="category-showcase py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5" data-aos="fade-up">Shop by Category</h2>
            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card border-0 rounded-0 h-100">
                        <img src="{{ asset('images/Running Shoes.png') }}" class="card-img rounded-0" alt="Running Shoes">
                        <div class="card-img-overlay d-flex align-items-end">
                            <div class="bg-white p-3 w-100">
                                <h5 class="card-title mb-1">Running Shoes</h5>
                                <a href="{{ route('products.index') }}" class="btn btn-dark btn-sm">Shop Now</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="card border-0 rounded-0 h-100">
                        <img src="{{ asset('images/Basketball Shoes.png') }}" class="card-img rounded-0" alt="Basketball Shoes">
                        <div class="card-img-overlay d-flex align-items-end">
                            <div class="bg-white p-3 w-100">
                                <h5 class="card-title mb-1">Basketball Shoes</h5>
                                <a href="{{ route('products.index') }}" class="btn btn-dark btn-sm">Shop Now</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="card border-0 rounded-0 h-100">
                        <img src="{{ asset('images/Casual Sneakers.png') }}" class="card-img rounded-0" alt="Casual Sneakers">
                        <div class="card-img-overlay d-flex align-items-end">
                            <div class="bg-white p-3 w-100">
                                <h5 class="card-title mb-1">Casual Sneakers</h5>
                                <a href="{{ route('products.index') }}" class="btn btn-dark btn-sm">Shop Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about-section py-5">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-md-6" data-aos="fade-right">
                    <h2 class="mb-4">About Nike Store</h2>
                    <p class="lead">We're more than just a footwear retailer. We're a community of athletes, creators, and innovators united by the belief that sport can move the world.</p>
                    <p>Founded in 1971, our mission has always been to bring inspiration and innovation to every athlete in the world. Today, we continue to push boundaries with cutting-edge technology and bold designs.</p>
                    <a href="{{ url('/about') }}" class="btn btn-outline-dark mt-3">Learn More</a>
                </div>
                <div class="col-md-6" data-aos="fade-left">
                    <img src="https://images.unsplash.com/photo-1600269452121-4f2416e55c28?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1000&q=80" alt="About Nike Store" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Us Section -->
    <section class="contact-section py-5 bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center" data-aos="fade-up">
                    <h2 class="mb-4">Contact Us</h2>
                    <p class="lead mb-5">Have questions or need assistance? Our team is here to help you with any inquiries about our products or services.</p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="bi bi-geo-alt-fill text-primary" style="font-size: 2rem;"></i>
                            </div>
                            <h5>Our Location</h5>
                            <p class="text-muted">123 Shoe Street<br>Portland, OR 97205</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="bi bi-telephone-fill text-primary" style="font-size: 2rem;"></i>
                            </div>
                            <h5>Call Us</h5>
                            <p class="text-muted">(123) 456-7890<br>Mon-Fri, 9AM-6PM PST</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="bi bi-envelope-fill text-primary" style="font-size: 2rem;"></i>
                            </div>
                            <h5>Email Us</h5>
                            <p class="text-muted">support@nikestore.com<br>orders@nikestore.com</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mt-5">
                <div class="col-12 text-center" data-aos="fade-up" data-aos-delay="400">
                    <a href="mailto:support@nikestore.com" class="btn btn-primary btn-lg px-5">Send us an Email</a>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
<script>
    // Initialize Bootstrap components
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize carousels
        var carousels = document.querySelectorAll('.carousel');
        carousels.forEach(function(carousel) {
            new bootstrap.Carousel(carousel, {
                interval: 5000,
                pause: 'hover'
            });
        });
    });
</script>
@endsection