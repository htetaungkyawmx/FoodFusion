<?php
$page_title = "Recipes - FoodFusion";
include 'includes/header.php';

// Database connection check
include 'config/database.php';
$database = new Database();
$db = $database->getConnection();

// Check connection
if (!$db) {
    echo "<div class='container'><div class='alert alert-danger'>Database connection failed!</div></div>";
    include 'includes/footer.php';
    exit();
}

// Check if recipes table exists
$tableCheck = $db->query("SHOW TABLES LIKE 'recipes'");
if ($tableCheck->rowCount() == 0) {
    echo "<div class='container'><div class='alert alert-warning'>Recipes table doesn't exist!</div></div>";
    
    // Show create table SQL
    echo "<div class='container' style='background:#f8f9fa; padding:20px; margin-top:20px; border-radius:5px;'>
            <h4>Create this SQL to create recipes table:</h4>
            <pre style='background:white; padding:15px; border:1px solid #ddd;'>
CREATE TABLE recipes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    ingredients TEXT,
    instructions TEXT,
    prep_time INT,
    cooking_time INT,
    servings INT,
    difficulty_level VARCHAR(50),
    category VARCHAR(100),
    featured_image VARCHAR(500),
    views INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
            </pre>
          </div>";
    include 'includes/footer.php';
    exit();
}

// Get recipes manually
$query = "SELECT r.*, u.first_name, u.last_name 
          FROM recipes r 
          LEFT JOIN users u ON r.user_id = u.id 
          ORDER BY r.created_at DESC 
          LIMIT 12";
$stmt = $db->prepare($query);
$stmt->execute();
$recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Debug: Show number of recipes
// echo "<div style='display:none;'>Debug: Found " . count($recipes) . " recipes</div>";
?>

<div class="container">
    <!-- Simple Header -->
    <div class="simple-header">
        <h1>Browse Recipes</h1>
        <p>Discover delicious recipes from our community</p>
        
        <!-- Simple Search -->
        <form method="GET" action="" class="simple-search">
            <div class="search-box">
                <input type="text" name="search" placeholder="Search recipes..." 
                       value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <button type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
    </div>

    <!-- Main Content -->
    <div class="simple-main">
        <!-- Simple Filters -->
        <div class="simple-filters">
            <h3>Categories</h3>
            <div class="category-list">
                <a href="recipes.php" class="category-tag <?php echo empty($_GET['category']) ? 'active' : ''; ?>">All</a>
                <a href="recipes.php?category=Main+Course" class="category-tag <?php echo (isset($_GET['category']) && $_GET['category'] == 'Main Course') ? 'active' : ''; ?>">Main Course</a>
                <a href="recipes.php?category=Dessert" class="category-tag <?php echo (isset($_GET['category']) && $_GET['category'] == 'Dessert') ? 'active' : ''; ?>">Dessert</a>
                <a href="recipes.php?category=Appetizer" class="category-tag <?php echo (isset($_GET['category']) && $_GET['category'] == 'Appetizer') ? 'active' : ''; ?>">Appetizer</a>
                <a href="recipes.php?category=Breakfast" class="category-tag <?php echo (isset($_GET['category']) && $_GET['category'] == 'Breakfast') ? 'active' : ''; ?>">Breakfast</a>
                <a href="recipes.php?category=Vegetarian" class="category-tag <?php echo (isset($_GET['category']) && $_GET['category'] == 'Vegetarian') ? 'active' : ''; ?>">Vegetarian</a>
            </div>
            
            <?php if (isset($_SESSION['user_id'])): ?>
            <div class="add-recipe-btn">
                <a href="add-recipe.php">
                    <i class="fas fa-plus"></i> Add Recipe
                </a>
            </div>
            <?php endif; ?>
        </div>

        <!-- Recipes Grid -->
        <div class="simple-grid">
            <div class="grid-header">
                <h2>Recipes (<?php echo count($recipes); ?>)</h2>
            </div>

            <?php if (count($recipes) > 0): ?>
                <div class="recipes-container">
                    <?php foreach ($recipes as $recipe): ?>
                        <div class="recipe-item">
                            <div class="recipe-image">
                                <?php if (!empty($recipe['featured_image'])): ?>
                                    <img src="<?php echo htmlspecialchars($recipe['featured_image']); ?>" 
                                         alt="<?php echo htmlspecialchars($recipe['title']); ?>">
                                <?php else: ?>
                                    <img src="assets/images/default-recipe.jpg" 
                                         alt="<?php echo htmlspecialchars($recipe['title']); ?>">
                                <?php endif; ?>
                                
                                <?php if (!empty($recipe['difficulty_level'])): ?>
                                    <span class="difficulty <?php echo strtolower($recipe['difficulty_level']); ?>">
                                        <?php echo $recipe['difficulty_level']; ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="recipe-info">
                                <h3><?php echo htmlspecialchars($recipe['title']); ?></h3>
                                
                                <?php if (!empty($recipe['description'])): ?>
                                    <p><?php echo htmlspecialchars(substr($recipe['description'], 0, 80)); ?>...</p>
                                <?php endif; ?>
                                
                                <div class="recipe-meta">
                                    <span class="time">
                                        <i class="fas fa-clock"></i>
                                        <?php echo $recipe['cooking_time'] ?? '--'; ?> min
                                    </span>
                                    <span class="servings">
                                        <i class="fas fa-users"></i>
                                        <?php echo $recipe['servings'] ?? '--'; ?> servings
                                    </span>
                                </div>
                                
                                <div class="recipe-author">
                                    <span>By <?php echo htmlspecialchars($recipe['first_name'] ?? 'Unknown'); ?> <?php echo htmlspecialchars($recipe['last_name'] ?? ''); ?></span>
                                </div>
                                
                                <a href="recipe-detail.php?id=<?php echo $recipe['id']; ?>" class="view-btn">
                                    View Recipe
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="no-recipes">
                    <i class="fas fa-utensils"></i>
                    <h3>No Recipes Yet</h3>
                    <p>Be the first to share a recipe!</p>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="add-recipe.php" class="btn">
                            <i class="fas fa-plus"></i> Add Your First Recipe
                        </a>
                    <?php else: ?>
                        <a href="register.php" class="btn">
                            <i class="fas fa-user-plus"></i> Join to Add Recipes
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Simple Pagination -->
            <?php if (count($recipes) >= 12): ?>
            <div class="simple-pagination">
                <a href="#" class="page-btn active">1</a>
                <a href="#" class="page-btn">2</a>
                <a href="#" class="page-btn">3</a>
                <a href="#" class="page-btn next">Next</a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
/* Simple Clean CSS */
.simple-header {
    text-align: center;
    padding: 40px 0;
    margin-bottom: 30px;
    background: linear-gradient(135deg, #FF6B6B, #FFD753FF);
    color: white;
    border-radius: 10px;
    margin-top: 20px;
}

.simple-header h1 {
    color: white;
    margin-bottom: 10px;
    font-size: 2.2rem;
}

.simple-header p {
    color: rgba(255, 255, 255, 0.9);
    margin-bottom: 25px;
    font-size: 1.1rem;
}

.simple-search {
    max-width: 500px;
    margin: 0 auto;
}

.search-box {
    display: flex;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.search-box input {
    flex: 1;
    padding: 14px 20px;
    border: none;
    outline: none;
    font-size: 1rem;
}

.search-box button {
    background: white;
    color: #FF6B6B;
    border: none;
    padding: 0 25px;
    cursor: pointer;
    font-size: 1.2rem;
    transition: all 0.3s;
}

.search-box button:hover {
    background: #f8f9fa;
}

/* Main Layout */
.simple-main {
    display: flex;
    gap: 30px;
    max-width: 1200px;
    margin: 30px auto 50px;
}

@media (max-width: 768px) {
    .simple-main {
        flex-direction: column;
    }
}

/* Filters */
.simple-filters {
    flex: 0 0 250px;
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.08);
    height: fit-content;
}

.simple-filters h3 {
    margin-bottom: 20px;
    color: #333;
    font-size: 1.2rem;
    padding-bottom: 15px;
    border-bottom: 1px solid #eee;
}

.category-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 30px;
}

.category-tag {
    display: block;
    padding: 12px 18px;
    background: #f8f9fa;
    border-radius: 6px;
    text-decoration: none;
    color: #555;
    transition: all 0.3s;
    border-left: 4px solid transparent;
}

.category-tag:hover {
    background: #FF6B6B;
    color: white;
    padding-left: 25px;
    transform: translateX(5px);
}

.category-tag.active {
    background: #FF6B6B;
    color: white;
    border-left-color: #FF5252;
    font-weight: 500;
}

.add-recipe-btn a {
    display: block;
    background: #4ECDC4;
    color: white;
    padding: 14px;
    border-radius: 6px;
    text-decoration: none;
    text-align: center;
    font-weight: 500;
    transition: all 0.3s;
    box-shadow: 0 4px 10px rgba(78, 205, 196, 0.3);
}

.add-recipe-btn a:hover {
    background: #44b7af;
    transform: translateY(-2px);
}

/* Grid */
.simple-grid {
    flex: 1;
}

.grid-header {
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 1px solid #eee;
}

.grid-header h2 {
    color: #333;
    font-size: 1.5rem;
    margin: 0;
}

/* Recipes Container */
.recipes-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 25px;
    margin-bottom: 40px;
}

/* Recipe Item */
.recipe-item {
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 3px 15px rgba(0,0,0,0.08);
    transition: all 0.3s;
    border: 1px solid #eee;
}

.recipe-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.recipe-image {
    position: relative;
    height: 180px;
    overflow: hidden;
}

.recipe-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s;
}

.recipe-item:hover .recipe-image img {
    transform: scale(1.05);
}

.difficulty {
    position: absolute;
    top: 10px;
    left: 10px;
    padding: 5px 12px;
    border-radius: 4px;
    font-size: 0.8rem;
    font-weight: 600;
    color: white;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.difficulty.easy { background: #4CAF50; }
.difficulty.medium { background: #FF9800; }
.difficulty.hard { background: #F44336; }

.recipe-info {
    padding: 20px;
}

.recipe-info h3 {
    margin: 0 0 12px 0;
    color: #333;
    font-size: 1.2rem;
    line-height: 1.4;
    min-height: 3.4em;
}

.recipe-info p {
    color: #666;
    font-size: 0.95rem;
    line-height: 1.5;
    margin-bottom: 15px;
    min-height: 4.5em;
}

.recipe-meta {
    display: flex;
    gap: 20px;
    margin-bottom: 15px;
    color: #777;
    font-size: 0.9rem;
}

.recipe-meta i {
    margin-right: 5px;
    color: #FF6B6B;
}

.recipe-author {
    margin-bottom: 20px;
    font-size: 0.9rem;
    color: #555;
    font-style: italic;
}

.view-btn {
    display: inline-block;
    background: #FF6B6B;
    color: white;
    padding: 10px 25px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s;
    border: none;
    cursor: pointer;
    width: 100%;
    text-align: center;
    font-size: 0.95rem;
}

.view-btn:hover {
    background: #FF5252;
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(255, 107, 107, 0.3);
}

/* No Recipes */
.no-recipes {
    text-align: center;
    padding: 60px 30px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 3px 15px rgba(0,0,0,0.08);
    border: 2px dashed #ddd;
}

.no-recipes i {
    font-size: 4rem;
    color: #FF6B6B;
    margin-bottom: 20px;
    opacity: 0.5;
}

.no-recipes h3 {
    color: #333;
    margin-bottom: 15px;
    font-size: 1.8rem;
}

.no-recipes p {
    color: #666;
    margin-bottom: 25px;
    font-size: 1.1rem;
}

.no-recipes .btn {
    display: inline-block;
    background: #FF6B6B;
    color: white;
    padding: 12px 30px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s;
    box-shadow: 0 4px 10px rgba(255, 107, 107, 0.3);
}

.no-recipes .btn:hover {
    background: #FF5252;
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(255, 107, 107, 0.4);
}

/* Pagination */
.simple-pagination {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-top: 40px;
}

.page-btn {
    display: inline-block;
    padding: 10px 18px;
    background: white;
    border: 1px solid #ddd;
    border-radius: 6px;
    text-decoration: none;
    color: #555;
    transition: all 0.3s;
    font-weight: 500;
}

.page-btn:hover {
    background: #f8f9fa;
    border-color: #FF6B6B;
    color: #FF6B6B;
}

.page-btn.active {
    background: #FF6B6B;
    color: white;
    border-color: #FF6B6B;
}

.page-btn.next {
    padding: 10px 25px;
    background: #4ECDC4;
    color: white;
    border-color: #4ECDC4;
}

.page-btn.next:hover {
    background: #44b7af;
    border-color: #44b7af;
}

/* Responsive */
@media (max-width: 768px) {
    .simple-header {
        padding: 30px 15px;
        border-radius: 0;
        margin-top: 0;
    }
    
    .simple-header h1 {
        font-size: 1.8rem;
    }
    
    .recipes-container {
        grid-template-columns: 1fr;
    }
    
    .simple-filters {
        flex: none;
        width: 100%;
    }
    
    .category-list {
        flex-direction: row;
        flex-wrap: wrap;
    }
    
    .category-tag {
        padding: 8px 15px;
        font-size: 0.9rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Simple search focus
    const searchInput = document.querySelector('.search-box input');
    if (searchInput) {
        searchInput.focus();
    }
    
    // Add active state to clicked category
    document.querySelectorAll('.category-tag').forEach(tag => {
        tag.addEventListener('click', function(e) {
            document.querySelectorAll('.category-tag').forEach(t => {
                t.classList.remove('active');
            });
            this.classList.add('active');
        });
    });
    
    // Add click animation to buttons
    document.querySelectorAll('.view-btn, .btn, .category-tag').forEach(btn => {
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