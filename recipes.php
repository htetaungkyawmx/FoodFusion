<?php
$page_title = "Recipes - FoodFusion";
include 'includes/header.php';
include 'includes/functions.php';

// Get filters
$filters = [];
$search = '';
$category = '';

if (isset($_GET['search'])) {
    $search = sanitizeInput($_GET['search']);
    $filters['search'] = $search;
}

if (isset($_GET['category'])) {
    $category = sanitizeInput($_GET['category']);
    $filters['category'] = $category;
}

if (isset($_GET['cuisine'])) {
    $filters['cuisine'] = sanitizeInput($_GET['cuisine']);
}

if (isset($_GET['difficulty'])) {
    $filters['difficulty'] = sanitizeInput($_GET['difficulty']);
}

$recipes = getAllRecipes($filters, 12);
?>

<div class="container">
    <div class="recipes-page">
        <!-- Hero Section -->
        <div class="recipes-hero">
            <div class="hero-content">
                <h1 class="hero-title">Discover Amazing Recipes</h1>
                <p class="hero-subtitle">Explore thousands of recipes from our community of food lovers</p>
                
                <!-- Search Bar -->
                <form method="GET" action="" class="search-form">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" name="search" placeholder="Search recipes..." 
                               value="<?php echo htmlspecialchars($search); ?>">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="recipes-main">
            <!-- Filters Sidebar -->
            <aside class="filters-sidebar">
                <div class="filters-header">
                    <h3><i class="fas fa-filter"></i> Filters</h3>
                    <a href="recipes.php" class="clear-filters">Clear All</a>
                </div>

                <div class="filter-section">
                    <h4>Categories</h4>
                    <div class="category-filters">
                        <a href="recipes.php?category=Main+Course" class="category-tag <?php echo $category == 'Main Course' ? 'active' : ''; ?>">
                            <i class="fas fa-utensils"></i> Main Course
                        </a>
                        <a href="recipes.php?category=Dessert" class="category-tag <?php echo $category == 'Dessert' ? 'active' : ''; ?>">
                            <i class="fas fa-ice-cream"></i> Dessert
                        </a>
                        <a href="recipes.php?category=Appetizer" class="category-tag <?php echo $category == 'Appetizer' ? 'active' : ''; ?>">
                            <i class="fas fa-wine-glass-alt"></i> Appetizer
                        </a>
                        <a href="recipes.php?category=Breakfast" class="category-tag <?php echo $category == 'Breakfast' ? 'active' : ''; ?>">
                            <i class="fas fa-bacon"></i> Breakfast
                        </a>
                        <a href="recipes.php?category=Vegetarian" class="category-tag <?php echo $category == 'Vegetarian' ? 'active' : ''; ?>">
                            <i class="fas fa-leaf"></i> Vegetarian
                        </a>
                        <a href="recipes.php?category=Vegan" class="category-tag <?php echo $category == 'Vegan' ? 'active' : ''; ?>">
                            <i class="fas fa-seedling"></i> Vegan
                        </a>
                    </div>
                </div>

                <div class="filter-section">
                    <h4>Difficulty Level</h4>
                    <form method="GET" action="">
                        <?php if ($search): ?>
                            <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
                        <?php endif; ?>
                        <?php if ($category): ?>
                            <input type="hidden" name="category" value="<?php echo htmlspecialchars($category); ?>">
                        <?php endif; ?>
                        
                        <div class="difficulty-filters">
                            <label class="difficulty-option">
                                <input type="radio" name="difficulty" value="Easy" 
                                       <?php echo ($filters['difficulty'] ?? '') == 'Easy' ? 'checked' : ''; ?>>
                                <span class="difficulty-label easy">
                                    <i class="fas fa-circle"></i> Easy
                                </span>
                            </label>
                            <label class="difficulty-option">
                                <input type="radio" name="difficulty" value="Medium" 
                                       <?php echo ($filters['difficulty'] ?? '') == 'Medium' ? 'checked' : ''; ?>>
                                <span class="difficulty-label medium">
                                    <i class="fas fa-circle"></i> Medium
                                </span>
                            </label>
                            <label class="difficulty-option">
                                <input type="radio" name="difficulty" value="Hard" 
                                       <?php echo ($filters['difficulty'] ?? '') == 'Hard' ? 'checked' : ''; ?>>
                                <span class="difficulty-label hard">
                                    <i class="fas fa-circle"></i> Hard
                                </span>
                            </label>
                            <label class="difficulty-option">
                                <input type="radio" name="difficulty" value="" 
                                       <?php echo empty($filters['difficulty']) ? 'checked' : ''; ?>>
                                <span class="difficulty-label all">
                                    <i class="fas fa-circle"></i> All Levels
                                </span>
                            </label>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-block">
                            Apply Filters
                        </button>
                    </form>
                </div>

                <div class="filter-section">
                    <h4>Cooking Time</h4>
                    <div class="time-filters">
                        <a href="recipes.php?time=15" class="time-tag">
                            <i class="fas fa-clock"></i> Under 15 min
                        </a>
                        <a href="recipes.php?time=30" class="time-tag">
                            <i class="fas fa-clock"></i> 30 min
                        </a>
                        <a href="recipes.php?time=60" class="time-tag">
                            <i class="fas fa-clock"></i> 1 hour
                        </a>
                    </div>
                </div>

                <?php if (isset($_SESSION['user_id'])): ?>
                <div class="user-action">
                    <a href="add-recipe.php" class="btn btn-primary btn-block">
                        <i class="fas fa-plus"></i> Add New Recipe
                    </a>
                </div>
                <?php endif; ?>
            </aside>

            <!-- Recipes Grid -->
            <main class="recipes-grid-container">
                <div class="grid-header">
                    <h2>Recipes <span class="recipe-count">(<?php echo count($recipes); ?>)</span></h2>
                    <div class="sort-options">
                        <select class="form-control">
                            <option>Newest First</option>
                            <option>Most Popular</option>
                            <option>Highest Rated</option>
                            <option>Quickest</option>
                        </select>
                    </div>
                </div>

                <?php if ($recipes): ?>
                    <div class="recipes-grid">
                        <?php foreach ($recipes as $recipe): ?>
                            <div class="recipe-card">
                                <div class="recipe-image">
                                    <img src="<?php echo !empty($recipe['featured_image']) ? htmlspecialchars($recipe['featured_image']) : 'assets/images/default-recipe.jpg'; ?>" 
                                         alt="<?php echo htmlspecialchars($recipe['title']); ?>"
                                         loading="lazy">
                                    <div class="recipe-badges">
                                        <?php if ($recipe['difficulty_level']): ?>
                                            <span class="badge difficulty-<?php echo strtolower($recipe['difficulty_level']); ?>">
                                                <?php echo $recipe['difficulty_level']; ?>
                                            </span>
                                        <?php endif; ?>
                                        <?php if ($recipe['cooking_time'] < 30): ?>
                                            <span class="badge badge-quick">
                                                <i class="fas fa-bolt"></i> Quick
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <button class="save-recipe-btn" data-recipe-id="<?php echo $recipe['id']; ?>">
                                        <i class="far fa-heart"></i>
                                    </button>
                                </div>
                                <div class="recipe-content">
                                    <h3 class="recipe-title"><?php echo htmlspecialchars($recipe['title']); ?></h3>
                                    <p class="recipe-description">
                                        <?php echo htmlspecialchars(truncateText($recipe['description'], 80)); ?>
                                    </p>
                                    <div class="recipe-meta">
                                        <div class="meta-item">
                                            <i class="fas fa-clock"></i>
                                            <span><?php echo $recipe['cooking_time']; ?> min</span>
                                        </div>
                                        <div class="meta-item">
                                            <i class="fas fa-user"></i>
                                            <span><?php echo $recipe['servings']; ?> servings</span>
                                        </div>
                                        <div class="meta-item">
                                            <i class="fas fa-user-circle"></i>
                                            <span><?php echo htmlspecialchars($recipe['first_name'] ?? 'User'); ?></span>
                                        </div>
                                    </div>
                                    <a href="recipe-detail.php?id=<?php echo $recipe['id']; ?>" class="btn btn-primary btn-block">
                                        View Recipe
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <h3>No Recipes Found</h3>
                        <p>Try adjusting your search or filters to find what you're looking for.</p>
                        <a href="recipes.php" class="btn btn-primary">
                            Clear Filters
                        </a>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="add-recipe.php" class="btn btn-outline">
                                Add Your First Recipe
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <!-- Pagination -->
                <?php if (count($recipes) >= 12): ?>
                <div class="pagination">
                    <a href="#" class="page-link disabled">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                    <a href="#" class="page-link active">1</a>
                    <a href="#" class="page-link">2</a>
                    <a href="#" class="page-link">3</a>
                    <a href="#" class="page-link">4</a>
                    <a href="#" class="page-link">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
                <?php endif; ?>
            </main>
        </div>
    </div>
</div>

<style>
:root {
    --primary: #FF6B6B;
    --primary-dark: #FF5252;
    --secondary: #4ECDC4;
    --dark: #292F36;
    --light: #F7F7F7;
    --gray: #6C757D;
    --gradient: linear-gradient(135deg, #FF6B6B 0%, #4ECDC4 100%);
    --shadow-sm: 0 2px 4px rgba(0,0,0,0.1);
    --shadow-md: 0 4px 6px rgba(0,0,0,0.1);
    --shadow-lg: 0 10px 25px rgba(0,0,0,0.1);
    --radius-sm: 8px;
    --radius-md: 12px;
    --radius-lg: 16px;
}

.recipes-page {
    padding: 20px 0 50px;
}

/* Hero Section */
.recipes-hero {
    background: linear-gradient(rgba(0,0,0,0.8), rgba(0,0,0,0.8)), url('assets/images/recipes-bg.jpg');
    background-size: cover;
    background-position: center;
    color: white;
    padding: 80px 0;
    border-radius: var(--radius-lg);
    margin-bottom: 40px;
    text-align: center;
}

.hero-content {
    max-width: 800px;
    margin: 0 auto;
    padding: 0 20px;
}

.hero-title {
    font-size: 3rem;
    margin-bottom: 1rem;
    font-weight: 800;
}

.hero-subtitle {
    font-size: 1.2rem;
    opacity: 0.9;
    margin-bottom: 2rem;
}

/* Search Form */
.search-form {
    max-width: 600px;
    margin: 0 auto;
}

.search-box {
    display: flex;
    align-items: center;
    background: white;
    border-radius: var(--radius-lg);
    padding: 5px;
    box-shadow: var(--shadow-lg);
}

.search-box i {
    color: var(--gray);
    margin: 0 15px;
    font-size: 1.2rem;
}

.search-box input {
    flex: 1;
    padding: 15px 0;
    border: none;
    outline: none;
    font-size: 1rem;
    background: transparent;
}

.search-box button {
    padding: 15px 30px;
    border-radius: var(--radius-md);
}

/* Main Layout */
.recipes-main {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 30px;
}

@media (max-width: 1024px) {
    .recipes-main {
        grid-template-columns: 1fr;
    }
}

/* Filters Sidebar */
.filters-sidebar {
    background: white;
    border-radius: var(--radius-lg);
    padding: 25px;
    box-shadow: var(--shadow-md);
    height: fit-content;
    position: sticky;
    top: 20px;
}

.filters-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 1px solid #eee;
}

.filters-header h3 {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--dark);
    margin: 0;
    font-size: 1.2rem;
}

.clear-filters {
    color: var(--primary);
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 500;
}

.clear-filters:hover {
    text-decoration: underline;
}

.filter-section {
    margin-bottom: 30px;
    padding-bottom: 25px;
    border-bottom: 1px solid #eee;
}

.filter-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.filter-section h4 {
    color: var(--dark);
    margin-bottom: 15px;
    font-size: 1rem;
    font-weight: 600;
}

/* Category Filters */
.category-filters {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.category-tag {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 15px;
    background: var(--light);
    border-radius: var(--radius-md);
    color: var(--dark);
    text-decoration: none;
    transition: all 0.3s;
    font-size: 0.95rem;
}

.category-tag:hover {
    background: var(--primary);
    color: white;
    transform: translateX(5px);
}

.category-tag.active {
    background: var(--gradient);
    color: white;
    font-weight: 500;
}

.category-tag i {
    font-size: 1rem;
}

/* Difficulty Filters */
.difficulty-filters {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 20px;
}

.difficulty-option {
    display: flex;
    align-items: center;
    cursor: pointer;
    padding: 8px 0;
}

.difficulty-option input {
    display: none;
}

.difficulty-label {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 15px;
    border-radius: var(--radius-md);
    background: var(--light);
    flex: 1;
    transition: all 0.3s;
}

.difficulty-option input:checked + .difficulty-label {
    background: var(--gradient);
    color: white;
    font-weight: 500;
}

.difficulty-label i {
    font-size: 0.8rem;
}

.difficulty-label.easy i { color: #4CAF50; }
.difficulty-label.medium i { color: #FF9800; }
.difficulty-label.hard i { color: #F44336; }
.difficulty-label.all i { color: var(--gray); }

.difficulty-option input:checked + .difficulty-label i {
    color: white;
}

/* Time Filters */
.time-filters {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.time-tag {
    padding: 10px 15px;
    background: var(--light);
    border-radius: var(--radius-md);
    color: var(--dark);
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s;
    font-size: 0.95rem;
}

.time-tag:hover {
    background: var(--secondary);
    color: white;
}

.time-tag i {
    font-size: 0.9rem;
}

/* User Action */
.user-action {
    margin-top: 25px;
}

/* Recipes Grid */
.recipes-grid-container {
    background: transparent;
}

.grid-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding: 0 10px;
}

.grid-header h2 {
    color: var(--dark);
    font-size: 1.8rem;
    margin: 0;
}

.recipe-count {
    color: var(--primary);
    font-weight: 600;
}

.sort-options select {
    padding: 10px 15px;
    border: 2px solid #eee;
    border-radius: var(--radius-md);
    background: white;
    color: var(--dark);
    font-size: 0.95rem;
    cursor: pointer;
}

.recipes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 25px;
    margin-bottom: 40px;
}

/* Recipe Card */
.recipe-card {
    background: white;
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-md);
    transition: all 0.3s ease;
    animation: fadeIn 0.5s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.recipe-card:hover {
    transform: translateY(-10px);
    box-shadow: var(--shadow-lg);
}

.recipe-image {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.recipe-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.recipe-card:hover .recipe-image img {
    transform: scale(1.05);
}

.recipe-badges {
    position: absolute;
    top: 15px;
    left: 15px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    align-items: flex-start;
}

.badge {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    color: white;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    backdrop-filter: blur(10px);
    background: rgba(0,0,0,0.7);
}

.badge-quick {
    background: rgba(255, 193, 7, 0.9);
}

.difficulty-easy {
    background: rgba(76, 175, 80, 0.9);
}

.difficulty-medium {
    background: rgba(255, 152, 0, 0.9);
}

.difficulty-hard {
    background: rgba(244, 67, 54, 0.9);
}

.save-recipe-btn {
    position: absolute;
    top: 15px;
    right: 15px;
    width: 40px;
    height: 40px;
    background: white;
    border: none;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--gray);
    cursor: pointer;
    transition: all 0.3s;
    box-shadow: var(--shadow-sm);
}

.save-recipe-btn:hover {
    background: var(--primary);
    color: white;
    transform: scale(1.1);
}

.save-recipe-btn.saved {
    background: var(--primary);
    color: white;
}

.save-recipe-btn.saved i {
    font-weight: 900;
}

.recipe-content {
    padding: 20px;
}

.recipe-title {
    font-size: 1.2rem;
    margin-bottom: 10px;
    color: var(--dark);
    font-weight: 600;
    line-height: 1.4;
}

.recipe-description {
    color: var(--gray);
    font-size: 0.9rem;
    line-height: 1.5;
    margin-bottom: 15px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.recipe-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-top: 15px;
    border-top: 1px solid #eee;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 5px;
    color: var(--gray);
    font-size: 0.85rem;
}

.meta-item i {
    color: var(--primary);
    font-size: 0.9rem;
}

.btn-block {
    width: 100%;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    background: white;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-md);
}

.empty-icon {
    width: 80px;
    height: 80px;
    background: var(--gradient);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    color: white;
    font-size: 32px;
}

.empty-state h3 {
    color: var(--dark);
    margin-bottom: 10px;
    font-size: 1.5rem;
}

.empty-state p {
    color: var(--gray);
    margin-bottom: 25px;
    font-size: 1rem;
}

.empty-state .btn {
    margin: 0 5px;
}

/* Pagination */
.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
    margin-top: 40px;
}

.page-link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: var(--radius-md);
    background: white;
    color: var(--dark);
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s;
    box-shadow: var(--shadow-sm);
}

.page-link:hover {
    background: var(--light);
    transform: translateY(-2px);
}

.page-link.active {
    background: var(--gradient);
    color: white;
}

.page-link.disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none !important;
}

.page-link i {
    font-size: 0.9rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .recipes-hero {
        padding: 50px 0;
    }
    
    .hero-title {
        font-size: 2.2rem;
    }
    
    .hero-subtitle {
        font-size: 1rem;
    }
    
    .search-box {
        flex-direction: column;
        background: transparent;
        box-shadow: none;
        gap: 10px;
    }
    
    .search-box input {
        background: white;
        padding: 15px;
        border-radius: var(--radius-md);
        width: 100%;
    }
    
    .search-box button {
        width: 100%;
    }
    
    .grid-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
    
    .sort-options select {
        width: 100%;
    }
    
    .recipes-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Save recipe functionality
    document.querySelectorAll('.save-recipe-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const recipeId = this.getAttribute('data-recipe-id');
            const icon = this.querySelector('i');
            
            // Toggle saved state
            if (this.classList.contains('saved')) {
                this.classList.remove('saved');
                icon.className = 'far fa-heart';
                // In real app: Send AJAX to remove from saved
                console.log('Removed recipe', recipeId, 'from saved');
            } else {
                this.classList.add('saved');
                icon.className = 'fas fa-heart';
                // In real app: Send AJAX to save recipe
                console.log('Saved recipe', recipeId);
                
                // Show success message
                showNotification('Recipe saved to your favorites!', 'success');
            }
        });
    });
    
    // Auto-submit filter form on radio change
    document.querySelectorAll('.difficulty-option input').forEach(radio => {
        radio.addEventListener('change', function() {
            this.closest('form').submit();
        });
    });
    
    // Notification function
    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}-circle"></i>
                <span>${message}</span>
            </div>
            <button class="notification-close">&times;</button>
        `;
        
        // Add styles
        notification.style.position = 'fixed';
        notification.style.top = '20px';
        notification.style.right = '20px';
        notification.style.background = type === 'success' ? '#4CAF50' : '#F44336';
        notification.style.color = 'white';
        notification.style.padding = '15px 20px';
        notification.style.borderRadius = 'var(--radius-md)';
        notification.style.boxShadow = 'var(--shadow-lg)';
        notification.style.zIndex = '9999';
        notification.style.display = 'flex';
        notification.style.alignItems = 'center';
        notification.style.gap = '10px';
        notification.style.animation = 'slideIn 0.3s ease';
        
        document.body.appendChild(notification);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
        
        // Close button
        notification.querySelector('.notification-close').addEventListener('click', () => {
            notification.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        });
    }
    
    // Add animations
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
    `;
    document.head.appendChild(style);
    
    // Lazy loading images
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src || img.src;
                    img.classList.remove('lazy');
                    observer.unobserve(img);
                }
            });
        });
        
        document.querySelectorAll('img[loading="lazy"]').forEach(img => {
            imageObserver.observe(img);
        });
    }
});
</script>

<?php include 'includes/footer.php'; ?>