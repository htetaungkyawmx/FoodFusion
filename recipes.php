<?php
$page_title = "Recipes - FoodFusion";
include 'includes/header.php';

// Database connection
include 'config/database.php';
$database = new Database();
$db = $database->getConnection();

// Get recipes
try {
    $query = "SELECT r.*, u.first_name, u.last_name 
              FROM recipes r 
              LEFT JOIN users u ON r.user_id = u.id 
              ORDER BY r.created_at DESC 
              LIMIT 9"; // 3x3 grid အတွက် 9 ခုပဲပြ

    $stmt = $db->prepare($query);
    $stmt->execute();
    $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    echo '<div class="container">';
    echo '<div class="alert alert-danger">';
    echo '<p>Sorry, there was an error loading recipes.</p>';
    echo '</div>';
    echo '</div>';
    
    $recipes = [];
    include 'includes/footer.php';
    exit();
}
?>

<div class="container">
    <!-- Simple Header -->
    <div class="recipes-header">
        <h1>Delicious Recipes</h1>
        <p>Discover amazing recipes from our community</p>
    </div>

    <!-- Recipes Grid - 3 columns -->
    <?php if (count($recipes) > 0): ?>
        <div class="recipes-grid">
            <?php foreach ($recipes as $recipe): ?>
            <div class="recipe-card"> <!-- Changed from <a> to <div> -->
                <div class="recipe-image">
                    <?php if (!empty($recipe['featured_image'])): ?>
                        <img src="<?php echo htmlspecialchars($recipe['featured_image']); ?>" 
                             alt="<?php echo htmlspecialchars($recipe['title']); ?>"
                             onerror="this.src='https://images.unsplash.com/photo-1490818387583-1baba5e638af?w=400&auto=format&fit=crop'">
                    <?php else: ?>
                        <img src="https://images.unsplash.com/photo-1490818387583-1baba5e638af?w=400&auto=format&fit=crop" 
                             alt="<?php echo htmlspecialchars($recipe['title']); ?>">
                    <?php endif; ?>
                    
                    <?php if (!empty($recipe['difficulty_level'])): ?>
                        <span class="difficulty <?php echo strtolower($recipe['difficulty_level']); ?>">
                            <?php echo $recipe['difficulty_level']; ?>
                        </span>
                    <?php endif; ?>
                </div>
                
                <div class="recipe-content">
                    <h3><?php echo htmlspecialchars($recipe['title']); ?></h3>
                    
                    <p class="recipe-desc">
                        <?php 
                        $desc = !empty($recipe['description']) ? $recipe['description'] : 'A delicious recipe';
                        echo htmlspecialchars(substr($desc, 0, 120)) . (strlen($desc) > 120 ? '...' : ''); 
                        ?>
                    </p>
                    
                    <div class="recipe-meta">
                        <span><i class="fas fa-clock"></i> <?php echo $recipe['cooking_time'] ?? '30'; ?> min</span>
                        <span><i class="fas fa-users"></i> <?php echo $recipe['servings'] ?? '4'; ?> servings</span>
                    </div>
                    
                    <div class="recipe-author">
                        <i class="fas fa-user"></i> 
                        <?php echo htmlspecialchars($recipe['first_name'] ?? 'Chef'); ?>
                    </div>
                </div>
            </div> <!-- End of recipe-card div -->
            <?php endforeach; ?>
        </div>
        
    <?php else: ?>
        <div class="no-recipes">
            <i class="fas fa-utensils"></i>
            <h3>No Recipes Found</h3>
            <p>Be the first to add a recipe!</p>
            
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="add-recipe.php" class="btn-primary">Add Your First Recipe</a>
            <?php else: ?>
                <a href="register.php" class="btn-primary">Join to Add Recipes</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<style>
/* Recipes Page Styles */
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.recipes-header {
    text-align: center;
    padding: 40px 0 30px;
    margin-bottom: 30px;
}

.recipes-header h1 {
    color: #333;
    font-size: 2.2rem;
    margin-bottom: 10px;
    font-weight: 700;
}

.recipes-header p {
    color: #666;
    font-size: 1.1rem;
    margin-bottom: 0;
}

/* Recipes Grid - 3 columns */
.recipes-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
    margin-bottom: 50px;
}

@media (max-width: 992px) {
    .recipes-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 25px;
    }
}

@media (max-width: 768px) {
    .recipes-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
}

.recipe-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    text-decoration: none;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    border: 1px solid #eee;
    display: block;
}

.recipe-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.15);
    border-color: #4CAF50;
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

.difficulty {
    position: absolute;
    top: 15px;
    left: 15px;
    padding: 6px 15px;
    border-radius: 5px;
    font-size: 0.8rem;
    font-weight: 600;
    color: white;
    text-transform: uppercase;
    z-index: 2;
}

.difficulty.easy { background: #4CAF50; }
.difficulty.medium { background: #FF9800; }
.difficulty.hard { background: #f44336; }

.recipe-content {
    padding: 25px;
}

.recipe-content h3 {
    font-size: 1.3rem;
    color: #333;
    margin: 0 0 15px 0;
    line-height: 1.4;
    font-weight: 600;
}

.recipe-desc {
    color: #666;
    font-size: 0.95rem;
    line-height: 1.6;
    margin-bottom: 20px;
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
    margin-right: 6px;
    color: #4CAF50;
}

.recipe-author {
    color: #555;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 500;
    border-top: 1px solid #eee;
    padding-top: 15px;
}

.recipe-author i {
    color: #764ba2;
}

/* No Recipes */
.no-recipes {
    text-align: center;
    padding: 80px 30px;
    background: #f8f9fa;
    border-radius: 15px;
    border: 2px dashed #dee2e6;
    margin: 40px 0;
}

.no-recipes i {
    font-size: 4rem;
    color: #4CAF50;
    margin-bottom: 25px;
    opacity: 0.8;
}

.no-recipes h3 {
    color: #333;
    margin-bottom: 15px;
    font-size: 2rem;
}

.no-recipes p {
    color: #666;
    margin-bottom: 30px;
    font-size: 1.2rem;
}

.btn-primary {
    display: inline-block;
    background: #4CAF50;
    color: white;
    padding: 14px 35px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s;
    font-size: 1.1rem;
}

.btn-primary:hover {
    background: #45a049;
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(76, 175, 80, 0.3);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .recipes-header {
        padding: 30px 0 20px;
    }
    
    .recipes-header h1 {
        font-size: 1.8rem;
    }
    
    .recipe-content h3 {
        font-size: 1.2rem;
    }
    
    .recipe-desc {
        min-height: auto;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add hover animations
    document.querySelectorAll('.recipe-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.zIndex = '10';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.zIndex = '1';
        });
    });
    
    // Remove click animation since cards are no longer clickable
    document.querySelectorAll('.recipe-card').forEach(card => {
        card.style.cursor = 'default';
    });
});
</script>

<?php include 'includes/footer.php'; ?>