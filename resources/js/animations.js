import gsap from 'gsap';

// Initialize animations when the DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Hero section animations
    const heroContent = document.querySelector('.hero-content');
    if (heroContent) {
        gsap.from(heroContent.children, {
            duration: 1,
            y: 50,
            opacity: 0,
            stagger: 0.2,
            ease: "power2.out"
        });
    }
    
    // Parallax effect for hero background
    const heroBackground = document.querySelector('.hero-background');
    if (heroBackground) {
        window.addEventListener('scroll', function() {
            const scrollPosition = window.scrollY;
            heroBackground.style.transform = `translateY(${scrollPosition * 0.5}px)`;
        });
    }
    
    // Product card hover effects
    const productCards = document.querySelectorAll('.product-grid .card');
    productCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            gsap.to(this, {
                duration: 0.3,
                y: -10,
                boxShadow: '0 10px 20px rgba(0,0,0,0.15)',
                ease: "power2.out"
            });
        });
        
        card.addEventListener('mouseleave', function() {
            gsap.to(this, {
                duration: 0.3,
                y: 0,
                boxShadow: '0 2px 10px rgba(0,0,0,0.05)',
                ease: "power2.out"
            });
        });
    });
    
    // New arrivals carousel animation
    const carouselItems = document.querySelectorAll('#newArrivalsCarousel .carousel-item');
    carouselItems.forEach(item => {
        item.addEventListener('transitionend', function() {
            gsap.from(item.querySelectorAll('.card'), {
                duration: 0.5,
                y: 20,
                opacity: 0,
                stagger: 0.1,
                ease: "power2.out"
            });
        });
    });
    
    // Category showcase hover effects
    const categoryCards = document.querySelectorAll('.category-showcase .card');
    categoryCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            gsap.to(this.querySelector('img'), {
                duration: 0.3,
                scale: 1.05,
                ease: "power2.out"
            });
        });
        
        card.addEventListener('mouseleave', function() {
            gsap.to(this.querySelector('img'), {
                duration: 0.3,
                scale: 1,
                ease: "power2.out"
            });
        });
    });
});