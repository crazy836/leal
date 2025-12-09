@extends('layouts.app')

@section('title', 'Contact Us')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 text-center mb-5" data-aos="fade-up">
            <h1 class="display-4 mb-4">Contact Us</h1>
            <p class="lead">Have questions or need assistance? Our team is here to help you with any inquiries about our products or services.</p>
        </div>
    </div>
    
    <div class="row g-5">
        <!-- Contact Information -->
        <div class="col-lg-6" data-aos="fade-right">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h2 class="mb-4">Get in Touch</h2>
                    
                    <div class="d-flex mb-4">
                        <div class="flex-shrink-0">
                            <i class="bi bi-geo-alt-fill text-primary" style="font-size: 1.5rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5>Our Location</h5>
                            <p class="mb-0">123 Shoe Street</p>
                            <p class="mb-0">Pozorrubio, Pangasinan</p>
                        </div>
                    </div>
                    
                    <div class="d-flex mb-4">
                        <div class="flex-shrink-0">
                            <i class="bi bi-telephone-fill text-primary" style="font-size: 1.5rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5>Call Us</h5>
                            <p class="mb-0">(123) 456-7890</p>
                            <p class="mb-0">Mon-Fri, 9AM-6PM PST</p>
                        </div>
                    </div>
                    
                    <div class="d-flex mb-4">
                        <div class="flex-shrink-0">
                            <i class="bi bi-envelope-fill text-primary" style="font-size: 1.5rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5>Email Us</h5>
                            <p class="mb-0">support@nikestore.com</p>
                            <p class="mb-0">orders@nikestore.com</p>
                        </div>
                    </div>
                    
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="bi bi-clock-fill text-primary" style="font-size: 1.5rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5>Business Hours</h5>
                            <p class="mb-0">Monday - Friday: 9:00 AM - 6:00 PM</p>
                            <p class="mb-0">Saturday: 10:00 AM - 4:00 PM</p>
                            <p class="mb-0">Sunday: Closed</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Contact Form -->
        <div class="col-lg-6" data-aos="fade-left">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h2 class="mb-4">Send us a Message</h2>
                    <form>
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" placeholder="Enter your name">
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" placeholder="Enter your email">
                        </div>
                        
                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject</label>
                            <input type="text" class="form-control" id="subject" placeholder="Enter subject">
                        </div>
                        
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" rows="5" placeholder="Enter your message"></textarea>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">Send Message</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Map Section -->
    <div class="row mt-5" data-aos="fade-up">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="ratio ratio-21x9">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2803.219580099999!2d-122.68250568443994!3d45.5230629791009!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x54950a0516efe135%3A0x4000000000000000!2sNike+World+Headquarters!5e0!3m2!1sen!2sus!4v1609459200000!5m2!1sen!2sus" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .ratio-21x9 {
        --bs-aspect-ratio: 42.857%;
    }
    
    iframe {
        border: 0;
        width: 100%;
        height: 100%;
    }
</style>
@endsection