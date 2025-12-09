@extends('layouts.app')

@section('title', 'About Us')

@section('styles')
<style>
    .about-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 4rem 0;
        margin-bottom: 3rem;
    }
    
    .about-header h1 {
        font-size: 3rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }
    
    .about-header p {
        font-size: 1.2rem;
        opacity: 0.9;
        max-width: 700px;
        margin: 0 auto;
    }
    
    .section-title {
        position: relative;
        margin-bottom: 3rem;
        text-align: center;
    }
    
    .section-title h2 {
        font-size: 2.5rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 1rem;
    }
    
    .section-title h2:after {
        content: '';
        display: block;
        width: 80px;
        height: 4px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        margin: 1rem auto;
        border-radius: 2px;
    }
    
    .feature-card {
        text-align: center;
        padding: 2rem;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        height: 100%;
        background: white;
    }
    
    .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
    }
    
    .feature-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        color: white;
        font-size: 2rem;
    }
    
    .feature-card h3 {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: #333;
    }
    
    .feature-card p {
        color: #666;
        line-height: 1.6;
    }
    
    .team-member {
        text-align: center;
        margin-bottom: 2rem;
    }
    
    .team-photo {
        width: 200px;
        height: 200px;
        border-radius: 50%;
        object-fit: cover;
        margin: 0 auto 1.5rem;
        border: 5px solid #f8f9fa;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .team-member h4 {
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #333;
    }
    
    .team-member .position {
        color: #667eea;
        font-weight: 500;
        margin-bottom: 1rem;
    }
    
    .team-member p {
        color: #666;
        font-style: italic;
    }
    
    .stats-counter {
        text-align: center;
        padding: 3rem 0;
    }
    
    .stat-item {
        margin-bottom: 2rem;
    }
    
    .stat-number {
        font-size: 3rem;
        font-weight: 700;
        color: #667eea;
        margin-bottom: 0.5rem;
    }
    
    .stat-label {
        font-size: 1.1rem;
        color: #666;
        font-weight: 500;
    }
    
    .developer-section {
        background: #f8f9fa;
        padding: 4rem 0;
        margin: 3rem 0;
        text-align: center;
    }
    
    .developer-images {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 2rem;
        margin: 2rem 0;
        flex-wrap: wrap;
    }
    
    .developer-item {
        text-align: center;
    }
    
    .developer-image {
        width: 250px;
        height: 250px;
        border-radius: 50%;
        object-fit: cover;
        border: 5px solid #fff;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }
    
    .developer-image:hover {
        transform: scale(1.05);
    }
    
    .developer-item h4 {
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #333;
    }
    
    .developer-item p {
        color: #667eea;
        font-weight: 500;
        margin-bottom: 0;
    }
    
    .mission-vision {
        background: #f8f9fa;
        padding: 4rem 0;
        margin: 3rem 0;
    }
    
    .mission-vision-content {
        max-width: 800px;
        margin: 0 auto;
    }
    
    .mission-vision-content h3 {
        font-size: 1.8rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        color: #333;
    }
    
    .mission-vision-content p {
        font-size: 1.1rem;
        line-height: 1.7;
        color: #555;
    }
    
    @media (max-width: 768px) {
        .about-header {
            padding: 2rem 0;
        }
        
        .about-header h1 {
            font-size: 2rem;
        }
        
        .about-header p {
            font-size: 1rem;
        }
        
        .section-title h2 {
            font-size: 2rem;
        }
        
        .feature-card {
            padding: 1.5rem;
        }
        
        .stat-number {
            font-size: 2rem;
        }
        
        .developer-images {
            flex-direction: column;
            gap: 1rem;
        }
        
        .developer-image {
            width: 200px;
            height: 200px;
        }
    }
</style>
@endsection

@section('content')
<div class="about-header">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h1>About Nike Store</h1>
                <p>We are passionate about providing the best athletic footwear and apparel to help you achieve your goals.</p>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="section-title">
                <h2>Our Story</h2>
            </div>
            <p class="lead text-center mb-5">Founded with a vision to revolutionize the athletic wear industry, Nike Store has been at the forefront of innovation and design for over a decade. Our commitment to quality, performance, and style has made us a trusted name among athletes and fitness enthusiasts worldwide.</p>
        </div>
    </div>
    
    <div class="row mb-5">
        <div class="col-md-6 mb-4">
            <div class="mission-vision-content">
                <h3>Our Mission</h3>
                <p>To inspire and innovate for every athlete in the world. We believe that if you have a body, you are an athlete, and we are committed to bringing inspiration and innovation to every athlete we serve.</p>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="mission-vision-content">
                <h3>Our Vision</h3>
                <p>To be the world's most innovative and sustainable athletic wear company, creating products that enhance performance while protecting the planet for future generations.</p>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="section-title">
                <h2>Why Choose Us</h2>
            </div>
        </div>
    </div>
    
    <div class="row g-4 mb-5">
        <div class="col-md-6 col-lg-3">
            <div class="feature-card" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-icon">
                    <i class="bi bi-shield-check"></i>
                </div>
                <h3>Premium Quality</h3>
                <p>Only the finest materials and craftsmanship go into every product we create.</p>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="feature-card" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-icon">
                    <i class="bi bi-lightning"></i>
                </div>
                <h3>Innovative Design</h3>
                <p>Cutting-edge technology and design in every product for optimal performance.</p>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="feature-card" data-aos="fade-up" data-aos-delay="300">
                <div class="feature-icon">
                    <i class="bi bi-truck"></i>
                </div>
                <h3>Global Shipping</h3>
                <p>Fast and reliable delivery worldwide with tracking and insurance.</p>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="feature-card" data-aos="fade-up" data-aos-delay="400">
                <div class="feature-icon">
                    <i class="bi bi-headset"></i>
                </div>
                <h3>24/7 Support</h3>
                <p>Dedicated customer service team ready to assist you anytime.</p>
            </div>
        </div>
    </div>
    
    <!-- Developer Section -->
    <div class="developer-section">
        <div class="row">
            <div class="col-12">
                <div class="section-title">
                    <h2>DEVELOPER</h2>
                </div>
                <p class="lead">Meet the talented developers behind our platform</p>
                
                <div class="developer-images">
                    <div class="developer-item">
                        <img src="{{ asset('images/ALDRIN SARMIENTO.png') }}" alt="ALDRIN SARMIENTO" class="developer-image">
                        <h4 class="mt-3">ALDRIN SARMIENTO</h4>
                        <p class="text-muted">Lead Developer</p>
                    </div>
                    <div class="developer-item">
                        <img src="{{ asset('images/LORIE JANE LEAL.png') }}" alt="LORIE JANE LEAL" class="developer-image">
                        <h4 class="mt-3">LORIE JANE LEAL</h4>
                        <p class="text-muted">UI/UX Designer</p>
                    </div>
                    <div class="developer-item">
                        <img src="{{ asset('images/DANIEL HIDALGO.png') }}" alt="DANIEL HIDALGO" class="developer-image">
                        <h4 class="mt-3">DANIEL HIDALGO</h4>
                        <p class="text-muted">Backend Specialist</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mission-vision">
        <div class="row">
            <div class="col-12">
                <div class="mission-vision-content text-center">
                    <h3>Our Commitment</h3>
                    <p>Every product in our collection is meticulously designed and tested to ensure maximum comfort, durability, and performance. We believe that the right footwear can make all the difference in your athletic journey. Our team of designers, engineers, and athletes work tirelessly to push the boundaries of innovation and create products that not only meet but exceed your expectations.</p>
                    <p>We are committed to sustainability and ethical manufacturing practices, ensuring that our products are made with respect for both people and the planet.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Initialize AOS
    document.addEventListener('DOMContentLoaded', function() {
        AOS.init({
            duration: 1000,
            once: true
        });
    });
</script>
@endsection