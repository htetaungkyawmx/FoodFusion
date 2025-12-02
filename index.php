<?php
$page_title = "FoodFusion - Home";
include 'includes/header.php';
include 'includes/functions.php';

$featured_recipes = getFeaturedRecipes(6); // Get 6 featured recipes
$total_recipes = countRecipes();
$total_users = countUsers();
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-overlay">
        <div class="hero-content">
            <h1 class="hero-title">Welcome to FoodFusion</h1>
            <p class="hero-subtitle">Discover, Share, and Enjoy Amazing Recipes from Around the World</p>
            
            <div class="hero-stats">
                <div class="stat-item">
                    <i class="fas fa-utensils"></i>
                    <div>
                        <h3><?php echo number_format($total_recipes); ?>+</h3>
                        <p>Recipes</p>
                    </div>
                </div>
                <div class="stat-item">
                    <i class="fas fa-users"></i>
                    <div>
                        <h3><?php echo number_format($total_users); ?>+</h3>
                        <p>Food Lovers</p>
                    </div>
                </div>
                <div class="stat-item">
                    <i class="fas fa-globe"></i>
                    <div>
                        <h3>50+</h3>
                        <p>Cuisines</p>
                    </div>
                </div>
            </div>
            
            <div class="hero-actions">
                <a href="recipes.php" class="btn btn-primary">
                    <i class="fas fa-search"></i>
                    Explore Recipes
                </a>
                <?php if (!isset($_SESSION['user_id'])): ?>
                <a href="register.php" class="btn btn-secondary">
                    <i class="fas fa-user-plus"></i>
                    Join Free
                </a>
                <?php endif; ?>
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
                        <img src="<?php echo !empty($recipe['featured_image']) ? htmlspecialchars($recipe['featured_image']) : 'https://images.unsplash.com/photo-1490818387583-1baba5e638af?w=800&auto=format&fit=crop'; ?>" 
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
                <a href="add-recipe.php" class="btn btn-outline-light">
                    <i class="fas fa-plus"></i>
                    Share Your Recipe
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<style>
/* Hero Section */
.hero-section {
    background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), 
                url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=1600&auto=format&fit=crop');
    background-size: cover;
    background-position: center;
    color: white;
    padding: 100px 0 80px;
    margin-top: -20px;
    position: relative;
}

.hero-overlay {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.hero-content {
    text-align: center;
}

.hero-title {
    font-size: 3rem;
    margin-bottom: 15px;
    font-weight: 800;
}

.hero-subtitle {
    font-size: 1.2rem;
    opacity: 0.9;
    margin-bottom: 40px;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

.hero-stats {
    display: flex;
    justify-content: center;
    gap: 40px;
    margin-bottom: 40px;
    flex-wrap: wrap;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 15px;
    background: rgba(255, 255, 255, 0.1);
    padding: 20px;
    border-radius: 10px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.stat-item i {
    font-size: 2rem;
    color: #FF6B6B;
}

.stat-item h3 {
    font-size: 2rem;
    margin: 0;
    font-weight: 700;
}

.stat-item p {
    margin: 0;
    opacity: 0.8;
    font-size: 0.9rem;
}

.hero-actions {
    display: flex;
    gap: 20px;
    justify-content: center;
    flex-wrap: wrap;
}

.btn-primary {
    background: #FF6B6B;
    color: white;
    padding: 15px 30px;
    border-radius: 8px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-primary:hover {
    background: #FF5252;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 107, 107, 0.3);
}

.btn-secondary {
    background: #4ECDC4;
    color: white;
    padding: 15px 30px;
    border-radius: 8px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-secondary:hover {
    background: #44b7af;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(78, 205, 196, 0.3);
}

/* Section Header */
.section-header {
    text-align: center;
    margin-bottom: 40px;
}

.section-header h2 {
    font-size: 2.2rem;
    color: #333;
    margin-bottom: 10px;
}

.section-header p {
    color: #666;
    margin-bottom: 20px;
    font-size: 1.1rem;
}

.view-all {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #FF6B6B;
    text-decoration: none;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s;
}

.view-all:hover {
    gap: 12px;
    color: #FF5252;
}

/* Featured Recipes */
.featured-section {
    padding: 80px 0;
    background: #f8f9fa;
}

.recipes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 30px;
}

.recipe-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    transition: all 0.3s;
}

.recipe-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
}

.card-image {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s;
}

.recipe-card:hover .card-image img {
    transform: scale(1.05);
}

.image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to bottom, transparent 50%, rgba(0,0,0,0.1));
}

.difficulty-badge {
    position: absolute;
    top: 15px;
    left: 15px;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    color: white;
    text-transform: uppercase;
}

.difficulty-badge.easy { background: #4CAF50; }
.difficulty-badge.medium { background: #FF9800; }
.difficulty-badge.hard { background: #F44336; }

.card-content {
    padding: 20px;
}

.recipe-title {
    font-size: 1.3rem;
    color: #333;
    margin-bottom: 10px;
    line-height: 1.4;
}

.recipe-desc {
    color: #666;
    font-size: 0.95rem;
    line-height: 1.5;
    margin-bottom: 15px;
}

.recipe-meta {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
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
}

.card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.author {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #555;
    font-size: 0.9rem;
}

.author i {
    color: #4ECDC4;
}

.view-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #FF6B6B;
    color: white;
    padding: 8px 20px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 500;
    font-size: 0.9rem;
    transition: all 0.3s;
}

.view-btn:hover {
    background: #FF5252;
    gap: 12px;
}

/* Features Section */
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
    padding: 30px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    transition: all 0.3s;
    border: 1px solid #eee;
}

.feature-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.feature-icon {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, #FF6B6B, #4ECDC4);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    color: white;
    font-size: 1.8rem;
}

.feature-card h3 {
    color: #333;
    margin-bottom: 15px;
    font-size: 1.3rem;
}

.feature-card p {
    color: #666;
    line-height: 1.6;
    margin: 0;
}

/* CTA Section */
.cta-section {
    background: linear-gradient(135deg, #FF6B6B, #4ECDC4);
    color: white;
    padding: 80px 0;
}

.cta-content {
    text-align: center;
    max-width: 600px;
    margin: 0 auto;
}

.cta-content h2 {
    font-size: 2.5rem;
    margin-bottom: 15px;
}

.cta-content p {
    font-size: 1.2rem;
    opacity: 0.9;
    margin-bottom: 30px;
}

.cta-actions {
    display: flex;
    gap: 20px;
    justify-content: center;
    flex-wrap: wrap;
}

.btn-light {
    background: white;
    color: #FF6B6B;
    padding: 15px 30px;
    border-radius: 8px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-light:hover {
    background: #f8f9fa;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 255, 255, 0.2);
}

.btn-outline-light {
    background: transparent;
    color: white;
    border: 2px solid white;
    padding: 13px 30px;
    border-radius: 8px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-outline-light:hover {
    background: white;
    color: #FF6B6B;
    transform: translateY(-2px);
}

/* Responsive */
@media (max-width: 768px) {
    .hero-title {
        font-size: 2.2rem;
    }
    
    .hero-stats {
        gap: 20px;
    }
    
    .stat-item {
        padding: 15px;
    }
    
    .stat-item h3 {
        font-size: 1.5rem;
    }
    
    .recipes-grid {
        grid-template-columns: 1fr;
    }
    
    .features-grid {
        grid-template-columns: 1fr;
    }
    
    .hero-actions,
    .cta-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .hero-actions .btn,
    .cta-actions .btn {
        width: 100%;
        max-width: 300px;
        justify-content: center;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add hover animations
    document.querySelectorAll('.recipe-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-10px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
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
    
    // Add stats counter animation (optional)
    const statItems = document.querySelectorAll('.stat-item h3');
    statItems.forEach(stat => {
        const target = parseInt(stat.textContent);
        let current = 0;
        const increment = target / 50;
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            stat.textContent = Math.floor(current) + '+';
        }, 30);
    });
});
</script>

<?php include 'includes/footer.php'; ?>