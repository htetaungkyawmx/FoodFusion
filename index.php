<?php
$page_title = "FoodFusion - Home";
include 'includes/header.php';
include 'includes/functions.php';

$featured_recipes = getFeaturedRecipes(6); // Get 6 featured recipes
?>

<!-- Hero Section with Slider -->
<section class="hero-section">
    <!-- Slider -->
    <div class="slider-container">
        <div class="slider">
            <!-- Slide 1 - Pizza -->
            <div class="slide active">
                <img src="https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=1400&auto=format&fit=crop&q=80" 
                     alt="Delicious Pizza">
                <div class="slide-overlay"></div>
                <div class="slide-content">
                    <h1>Welcome to FoodFusion</h1>
                    <p>Discover Amazing Recipes from Around the World</p>
                </div>
            </div>
            
            <!-- Slide 2 - Pasta -->
            <div class="slide">
                <img src="https://images.unsplash.com/photo-1598866594230-a7c12756260f?w=1400&auto=format&fit=crop&q=80" 
                     alt="Tasty Pasta">
                <div class="slide-overlay"></div>
                <div class="slide-content">
                    <h1>Italian Delights</h1>
                    <p>Authentic pasta recipes for every occasion</p>
                </div>
            </div>
            
            <!-- Slide 3 - Burger -->
            <div class="slide">
                <img src="https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=1400&auto=format&fit=crop&q=80" 
                     alt="Juicy Burger">
                <div class="slide-overlay"></div>
                <div class="slide-content">
                    <h1>Burger Paradise</h1>
                    <p>Create perfect burgers at home</p>
                </div>
            </div>
            
            <!-- Slide 4 - Sushi -->
            <div class="slide">
                <img src="https://images.unsplash.com/photo-1579584425555-c3ce17fd4351?w=1400&auto=format&fit=crop&q=80" 
                     alt="Fresh Sushi">
                <div class="slide-overlay"></div>
                <div class="slide-content">
                    <h1>Asian Cuisine</h1>
                    <p>Explore authentic Asian recipes</p>
                </div>
            </div>
            
            <!-- Slide 5 - Dessert -->
            <div class="slide">
                <img src="https://images.unsplash.com/photo-1563729784474-d77dbb933a9e?w=1400&auto=format&fit=crop&q=80" 
                     alt="Delicious Dessert">
                <div class="slide-overlay"></div>
                <div class="slide-content">
                    <h1>Sweet Treats</h1>
                    <p>Indulge in heavenly desserts</p>
                </div>
            </div>
        </div>
        
        <!-- Slider Navigation -->
        <div class="slider-nav">
            <div class="dots">
                <span class="dot active"></span>
                <span class="dot"></span>
                <span class="dot"></span>
                <span class="dot"></span>
                <span class="dot"></span>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section">
    <div class="container">
        <div class="section-header">
            <h2>Why Choose FoodFusion?</h2>
            <p>Everything you need for your culinary journey</p>
        </div>
        
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-book-open"></i>
                </div>
                <h3>Recipe Collection</h3>
                <p>Thousands of recipes from different cuisines and skill levels</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3>Food Community</h3>
                <p>Connect with food lovers, share tips, and get inspired</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-star"></i>
                </div>
                <h3>Easy to Follow</h3>
                <p>Step-by-step instructions with clear measurements</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <h3>Mobile Friendly</h3>
                <p>Access recipes anywhere, anytime on any device</p>
            </div>
        </div>
    </div>
</section>

<!-- Featured Recipes -->
<section class="featured-section">
    <div class="container">
        <div class="section-header">
            <h2>Featured Recipes</h2>
            <p>Handpicked delicious recipes from our community</p>
            <a href="recipes.php" class="view-all">
                View All Recipes
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        
        <?php if ($featured_recipes): ?>
            <div class="recipes-grid">
                <?php foreach ($featured_recipes as $recipe): ?>
                <div class="recipe-card">
                    <div class="card-image">
                        <img src="<?php echo !empty($recipe['featured_image']) ? htmlspecialchars($recipe['featured_image']) : 'https://images.unsplash.com/photo-1490818387583-1baba5e638af?w=600&auto=format&fit=crop&q=80'; ?>" 
                             alt="<?php echo htmlspecialchars($recipe['title']); ?>">
                        <div class="image-overlay"></div>
                        
                        <?php if ($recipe['difficulty_level']): ?>
                        <span class="difficulty-badge <?php echo strtolower($recipe['difficulty_level']); ?>">
                            <?php echo $recipe['difficulty_level']; ?>
                        </span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="card-content">
                        <h3 class="recipe-title"><?php echo htmlspecialchars($recipe['title']); ?></h3>
                        
                        <p class="recipe-desc">
                            <?php echo htmlspecialchars(substr($recipe['description'], 0, 80)); ?>...
                        </p>
                        
                        <div class="recipe-meta">
                            <div class="meta-item">
                                <i class="fas fa-clock"></i>
                                <span><?php echo $recipe['cooking_time']; ?> min</span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-users"></i>
                                <span><?php echo $recipe['servings']; ?> servings</span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-fire"></i>
                                <span><?php echo $recipe['difficulty_level']; ?></span>
                            </div>
                        </div>
                        
                        <div class="card-footer">
                            <div class="author">
                                <i class="fas fa-user-circle"></i>
                                <span><?php echo htmlspecialchars($recipe['first_name'] ?? 'Chef'); ?></span>
                            </div>
                            <a href="recipe-detail.php?id=<?php echo $recipe['id']; ?>" class="view-btn">
                                View Recipe
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container">
        <div class="cta-content">
            <h2>Ready to Start Cooking?</h2>
            <p>Join thousands of food lovers sharing their culinary creations</p>
            
            <div class="cta-actions">
                <a href="recipes.php" class="btn btn-light">
                    <i class="fas fa-utensils"></i>
                    Browse Recipes
                </a>
                
                <?php if (!isset($_SESSION['user_id'])): ?>
                <a href="register.php" class="btn btn-outline-light">
                    <i class="fas fa-user-plus"></i>
                    Create Free Account
                </a>
                <?php else: ?>
                <a href="contact.php" class="btn btn-outline-light">
                    <i class="fas fa-plus"></i>
                    Share Your Recipe
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<style>
/* Hero Section with Slider - Improved image quality */
.hero-section {
    position: relative;
    height: 550px; /* Increased height for better image display */
    overflow: hidden;
    margin-top: -20px;
}

.slider-container {
    position: relative;
    width: 100%;
    height: 100%;
}

.slider {
    width: 100%;
    height: 100%;
    position: relative;
}

.slide {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    transition: opacity 0.8s ease-in-out;
}

.slide.active {
    opacity: 1;
}

.slide img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    /* Better image rendering */
    image-rendering: -webkit-optimize-contrast;
    image-rendering: crisp-edges;
}

.slide-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.4));
}

.slide-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
    color: white;
    width: 90%;
    max-width: 800px;
    z-index: 2;
    animation: fadeInUp 0.8s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translate(-50%, -40%);
    }
    to {
        opacity: 1;
        transform: translate(-50%, -50%);
    }
}

.slide-content h1 {
    font-size: 3rem; /* Slightly larger */
    margin-bottom: 15px;
    font-weight: 800;
    text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.7);
}

.slide-content p {
    font-size: 1.4rem;
    opacity: 0.95;
    text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.7);
}

/* Slider Navigation - Smaller dots */
.slider-nav {
    position: absolute;
    bottom: 25px;
    left: 0;
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 3;
}

.dots {
    display: flex;
    gap: 8px;
}

.dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.4);
    cursor: pointer;
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.dot.active {
    background: white;
    transform: scale(1.4);
    box-shadow: 0 0 8px rgba(255, 255, 255, 0.7);
}

/* Features Section - Fixed hover animation */
.features-section {
    padding: 80px 0;
    background: white;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
}

.feature-card {
    text-align: center;
    padding: 40px 25px; /* More padding */
    background: white;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    border: 1px solid #eee;
    position: relative;
    overflow: hidden;
}

.feature-card:hover {
    transform: translateY(-12px) scale(1.02); /* Better hover effect */
    box-shadow: 0 15px 40px rgba(0,0,0,0.15);
    border-color: #FF6B6B;
}

.feature-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(90deg, #FF6B6B, #4ECDC4);
    transform: scaleX(0);
    transition: transform 0.4s ease;
}

.feature-card:hover::before {
    transform: scaleX(1);
}

.feature-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #FF6B6B, #4ECDC4);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 25px;
    color: white;
    font-size: 2rem;
    transition: all 0.4s ease;
}

.feature-card:hover .feature-icon {
    transform: rotateY(180deg) scale(1.1);
    background: linear-gradient(135deg, #4ECDC4, #FF6B6B);
}

.feature-card h3 {
    color: #333;
    margin-bottom: 15px;
    font-size: 1.4rem;
    transition: color 0.3s ease;
}

.feature-card:hover h3 {
    color: #FF6B6B;
}

.feature-card p {
    color: #666;
    line-height: 1.7;
    margin: 0;
    transition: color 0.3s ease;
}

.feature-card:hover p {
    color: #555;
}

/* Card Image */
.card-image img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    transition: transform 0.6s ease;
}

.card-image {
    position: relative;
    height: 200px;
    overflow: hidden;
}

/* Keep all other CSS the same as before */
.section-header {
    text-align: center;
    margin-bottom: 50px;
}

.section-header h2 {
    font-size: 2.5rem;
    color: #333;
    margin-bottom: 15px;
    font-weight: 700;
}

.section-header p {
    color: #666;
    margin-bottom: 20px;
    font-size: 1.2rem;
}

.view-all {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #FF6B6B;
    text-decoration: none;
    font-weight: 600;
    font-size: 1.1rem;
    padding: 10px 25px;
    border-radius: 50px;
    background-color: rgba(255, 107, 107, 0.1);
    transition: all 0.3s;
}

.view-all:hover {
    gap: 12px;
    background-color: rgba(255, 107, 107, 0.2);
    transform: translateY(-2px);
}

/* Featured Recipes */
.featured-section {
    padding: 80px 0;
    background: #f8f9fa;
}

.recipes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 35px;
}

.recipe-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.recipe-card:hover {
    transform: translateY(-12px) scale(1.02);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.recipe-card:hover .card-image img {
    transform: scale(1.08);
}

.image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to bottom, transparent 40%, rgba(0,0,0,0.15));
}

.difficulty-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    padding: 6px 15px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    color: white;
    text-transform: uppercase;
    z-index: 2;
}

.difficulty-badge.easy { background: linear-gradient(135deg, #4CAF50, #45a049); }
.difficulty-badge.medium { background: linear-gradient(135deg, #FF9800, #f57c00); }
.difficulty-badge.hard { background: linear-gradient(135deg, #F44336, #d32f2f); }

.card-content {
    padding: 25px;
}

.recipe-title {
    font-size: 1.4rem;
    color: #333;
    margin-bottom: 12px;
    font-weight: 600;
    line-height: 1.4;
}

.recipe-desc {
    color: #666;
    font-size: 0.95rem;
    line-height: 1.6;
    margin-bottom: 20px;
}

.recipe-meta {
    display: flex;
    justify-content: space-between;
    margin-bottom: 25px;
    padding-bottom: 20px;
    border-bottom: 1px solid #eee;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #777;
    font-size: 0.9rem;
}

.meta-item i {
    color: #FF6B6B;
    font-size: 1rem;
}

.card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.author {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #555;
    font-size: 0.95rem;
    font-weight: 500;
}

.author i {
    color: #4ECDC4;
    font-size: 1.2rem;
}

.view-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, #FF6B6B, #FF5252);
    color: white;
    padding: 10px 25px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.view-btn:hover {
    background: linear-gradient(135deg, #FF5252, #FF6B6B);
    gap: 12px;
    transform: translateX(5px);
}

/* CTA Section */
.cta-section {
    background: linear-gradient(135deg, #FF6B6B, #4ECDC4);
    color: white;
    padding: 100px 0;
    position: relative;
    overflow: hidden;
}

.cta-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: url('https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=800&auto=format&fit=crop&q=80') center/cover;
    opacity: 0.1;
    z-index: 0;
}

.cta-content {
    text-align: center;
    max-width: 700px;
    margin: 0 auto;
    position: relative;
    z-index: 1;
}

.cta-content h2 {
    font-size: 3rem;
    margin-bottom: 20px;
    font-weight: 700;
    text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
}

.cta-content p {
    font-size: 1.3rem;
    opacity: 0.95;
    margin-bottom: 40px;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
}

.cta-actions {
    display: flex;
    gap: 25px;
    justify-content: center;
    flex-wrap: wrap;
}

.btn-light {
    background: white;
    color: #FF6B6B;
    padding: 16px 35px;
    border-radius: 50px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 12px;
    font-weight: 700;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(255, 255, 255, 0.2);
}

.btn-light:hover {
    background: #f8f9fa;
    transform: translateY(-5px) scale(1.05);
    box-shadow: 0 10px 25px rgba(255, 255, 255, 0.3);
}

.btn-outline-light {
    background: transparent;
    color: white;
    border: 2px solid rgba(255, 255, 255, 0.8);
    padding: 14px 35px;
    border-radius: 50px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 12px;
    font-weight: 700;
    font-size: 1.1rem;
    transition: all 0.3s ease;
}

.btn-outline-light:hover {
    background: white;
    color: #FF6B6B;
    transform: translateY(-5px) scale(1.05);
    border-color: white;
    box-shadow: 0 10px 25px rgba(255, 255, 255, 0.2);
}

/* Responsive */
@media (max-width: 992px) {
    .hero-section {
        height: 500px;
    }
    
    .slide-content h1 {
        font-size: 2.5rem;
    }
    
    .slide-content p {
        font-size: 1.2rem;
    }
}

@media (max-width: 768px) {
    .hero-section {
        height: 450px;
    }
    
    .slide-content h1 {
        font-size: 2rem;
    }
    
    .slide-content p {
        font-size: 1.1rem;
    }
    
    .section-header h2 {
        font-size: 2rem;
    }
    
    .recipes-grid {
        grid-template-columns: 1fr;
        gap: 30px;
    }
    
    .features-grid {
        grid-template-columns: 1fr;
        gap: 25px;
    }
    
    .feature-card {
        padding: 30px 20px;
    }
    
    .cta-actions {
        flex-direction: column;
        align-items: center;
        gap: 15px;
    }
    
    .cta-actions .btn {
        width: 100%;
        max-width: 300px;
        justify-content: center;
    }
    
    .cta-content h2 {
        font-size: 2.2rem;
    }
    
    .cta-content p {
        font-size: 1.1rem;
    }
    
    .dots {
        gap: 6px;
    }
    
    .dot {
        width: 6px;
        height: 6px;
    }
}

@media (max-width: 480px) {
    .hero-section {
        height: 400px;
    }
    
    .slide-content h1 {
        font-size: 1.8rem;
    }
    
    .slide-content p {
        font-size: 1rem;
    }
    
    .card-image {
        height: 180px;
    }
    
    .card-image img {
        height: 180px;
    }
    
    .slider-nav {
        bottom: 20px;
    }
    
    .feature-card {
        padding: 25px 15px;
    }
    
    .feature-icon {
        width: 65px;
        height: 65px;
        font-size: 1.6rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Slider functionality
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.dot');
    let currentSlide = 0;
    
    // Function to show slide
    function showSlide(index) {
        // Remove active class from all slides and dots
        slides.forEach(slide => slide.classList.remove('active'));
        dots.forEach(dot => dot.classList.remove('active'));
        
        // Add active class to current slide and dot
        slides[index].classList.add('active');
        dots[index].classList.add('active');
        currentSlide = index;
        
        // Add animation to slide content
        const activeSlideContent = slides[index].querySelector('.slide-content');
        activeSlideContent.style.animation = 'none';
        setTimeout(() => {
            activeSlideContent.style.animation = 'fadeInUp 0.8s ease-out';
        }, 10);
    }
    
    // Next slide
    function nextSlide() {
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide);
    }
    
    // Dot click events
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            showSlide(index);
        });
    });
    
    // Auto slide every 2 seconds (2000 milliseconds)
    let slideInterval = setInterval(nextSlide, 2000);
    
    // Pause auto slide on hover
    const slider = document.querySelector('.slider-container');
    slider.addEventListener('mouseenter', () => {
        clearInterval(slideInterval);
    });
    
    slider.addEventListener('mouseleave', () => {
        slideInterval = setInterval(nextSlide, 2000);
    });
    
    // Add hover animations for recipe cards
    document.querySelectorAll('.recipe-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-12px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
    
    // Add hover animations for feature cards
    document.querySelectorAll('.feature-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-12px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
    
    // Add click animation to buttons
    document.querySelectorAll('.btn').forEach(btn => {
        btn.addEventListener('click', function() {
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = '';
            }, 200);
        });
    });
});
</script>

<?php include 'includes/footer.php'; ?>