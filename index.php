<?php
$page_title = "Home - Discover Culinary Delights";
include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-content">
        <h1>Discover the Art of Cooking</h1>
        <p>Join thousands of food enthusiasts sharing recipes, tips, and culinary experiences from around the world.</p>
        <div class="hero-buttons">
            <a href="recipes.php" class="btn btn-primary btn-lg">
                <i class="fas fa-utensils"></i> Explore Recipes
            </a>
            <a href="register.php" class="btn btn-secondary btn-lg">
                <i class="fas fa-user-plus"></i> Join Community
            </a>
        </div>
    </div>
</section>

<!-- Featured Recipes -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Featured Recipes</h2>
            <p class="section-subtitle">Handpicked culinary creations from our community</p>
        </div>
        
        <div class="recipe-grid">
            <?php
            include 'config/database.php';
            $database = new Database();
            $db = $database->getConnection();
            
            $query = "SELECT r.*, u.first_name, u.last_name 
                     FROM recipes r 
                     JOIN users u ON r.user_id = u.id 
                     WHERE r.is_featured = 1 
                     ORDER BY r.created_at DESC 
                     LIMIT 6";
            $stmt = $db->prepare($query);
            $stmt->execute();
            
            if($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo '
                    <div class="recipe-card fade-in">
                        <div class="recipe-image">
                            <img src="assets/images/recipes/' . ($row['featured_image'] ?: 'default-recipe.jpg') . '" alt="' . htmlspecialchars($row['title']) . '">
                            <div class="recipe-badge">Featured</div>
                        </div>
                        <div class="recipe-content">
                            <h3 class="recipe-title">' . htmlspecialchars($row['title']) . '</h3>
                            <div class="recipe-meta">
                                <span><i class="fas fa-clock"></i> ' . htmlspecialchars($row['cooking_time']) . ' min</span>
                                <span><i class="fas fa-user"></i> ' . htmlspecialchars($row['first_name']) . '</span>
                                <span><i class="fas fa-fire"></i> ' . htmlspecialchars($row['difficulty_level']) . '</span>
                            </div>
                            <p class="recipe-description">' . htmlspecialchars(substr($row['description'], 0, 120)) . '...</p>
                            <div class="recipe-actions">
                                <a href="recipe-detail.php?id=' . $row['id'] . '" class="btn btn-primary btn-sm">View Recipe</a>
                                <button class="btn btn-outline btn-sm"><i class="far fa-heart"></i></button>
                            </div>
                        </div>
                    </div>';
                }
            } else {
                echo '<p class="text-center">No featured recipes available at the moment.</p>';
            }
            ?>
        </div>
        
        <div class="text-center mt-3">
            <a href="recipes.php" class="btn btn-primary">View All Recipes</a>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="section featured-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Browse by Category</h2>
            <p class="section-subtitle">Explore recipes from different culinary traditions</p>
        </div>
        
        <div class="categories-grid">
            <?php
            $query = "SELECT * FROM categories ORDER BY name LIMIT 6";
            $stmt = $db->prepare($query);
            $stmt->execute();
            
            if($stmt->rowCount() > 0) {
                while ($category = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo '
                    <a href="recipes.php?category=' . $category['id'] . '" class="category-card">
                        <div class="category-icon">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <h3>' . htmlspecialchars($category['name']) . '</h3>
                        <p>' . htmlspecialchars(substr($category['description'], 0, 80)) . '...</p>
                    </a>';
                }
            }
            ?>
        </div>
    </div>
</section>

<!-- Community Highlights -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Community Highlights</h2>
            <p class="section-subtitle">See what our community is cooking and sharing</p>
        </div>
        
        <div class="community-highlights">
            <div class="stats-grid">
                <div class="stat-card">
                    <i class="fas fa-users"></i>
                    <h3>10,000+</h3>
                    <p>Food Enthusiasts</p>
                </div>
                <div class="stat-card">
                    <i class="fas fa-utensils"></i>
                    <h3>5,000+</h3>
                    <p>Recipes Shared</p>
                </div>
                <div class="stat-card">
                    <i class="fas fa-comments"></i>
                    <h3>25,000+</h3>
                    <p>Community Posts</p>
                </div>
                <div class="stat-card">
                    <i class="fas fa-heart"></i>
                    <h3>50,000+</h3>
                    <p>Recipe Likes</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Upcoming Events -->
<section class="section featured-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Upcoming Cooking Events</h2>
            <p class="section-subtitle">Join our virtual cooking classes and workshops</p>
        </div>
        
        <div class="events-carousel">
            <div class="event-card">
                <div class="event-date">March 15, 2024</div>
                <h3>Italian Pasta Masterclass</h3>
                <p>Learn authentic pasta making techniques from Chef Marco Romano</p>
                <div class="event-meta">
                    <span><i class="fas fa-clock"></i> 6:00 PM - 8:00 PM</span>
                    <span><i class="fas fa-video"></i> Virtual Event</span>
                </div>
                <button class="btn btn-primary btn-sm mt-2">Register Now</button>
            </div>
            
            <div class="event-card">
                <div class="event-date">March 22, 2024</div>
                <h3>Asian Street Food Festival</h3>
                <p>Explore the vibrant flavors of Asian street cuisine with expert chefs</p>
                <div class="event-meta">
                    <span><i class="fas fa-clock"></i> 5:00 PM - 7:00 PM</span>
                    <span><i class="fas fa-video"></i> Virtual Event</span>
                </div>
                <button class="btn btn-primary btn-sm mt-2">Register Now</button>
            </div>
            
            <div class="event-card">
                <div class="event-date">March 29, 2024</div>
                <h3>Baking Basics Workshop</h3>
                <p>Perfect your baking skills with our comprehensive beginner's guide</p>
                <div class="event-meta">
                    <span><i class="fas fa-clock"></i> 3:00 PM - 5:00 PM</span>
                    <span><i class="fas fa-video"></i> Virtual Event</span>
                </div>
                <button class="btn btn-primary btn-sm mt-2">Register Now</button>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="section newsletter-section">
    <div class="container">
        <div class="newsletter-content">
            <h2>Stay Updated with FoodFusion</h2>
            <p>Get the latest recipes, cooking tips, and community updates delivered to your inbox</p>
            <form class="newsletter-form">
                <input type="email" placeholder="Enter your email address" required>
                <button type="submit" class="btn btn-primary">Subscribe</button>
            </form>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>