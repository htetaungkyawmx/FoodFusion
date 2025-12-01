<?php
$page_title = "Recipe Details - FoodFusion";
include 'includes/header.php';
include 'config/database.php';

$recipe_id = $_GET['id'] ?? 0;

if (!$recipe_id) {
    header('Location: recipes.php');
    exit();
}

$database = new Database();
$db = $database->getConnection();

// Get recipe details with user info
$query = "SELECT r.*, 
                 u.first_name, 
                 u.last_name,
                 u.profile_image,
                 COUNT(DISTINCT rl.id) as likes_count,
                 COUNT(DISTINCT rc.id) as comments_count
          FROM recipes r 
          LEFT JOIN users u ON r.user_id = u.id 
          LEFT JOIN recipe_likes rl ON r.id = rl.recipe_id
          LEFT JOIN recipe_comments rc ON r.id = rc.recipe_id
          WHERE r.id = ?
          GROUP BY r.id";

$stmt = $db->prepare($query);
$stmt->execute([$recipe_id]);
$recipe = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$recipe) {
    echo '<div class="container"><div class="recipe-not-found">
            <h2>Recipe not found</h2>
            <p>The recipe you\'re looking for doesn\'t exist or has been removed.</p>
            <a href="recipes.php" class="btn btn-primary">Browse Recipes</a>
          </div></div>';
    include 'includes/footer.php';
    exit();
}

// Increment view count
$update_query = "UPDATE recipes SET views = views + 1 WHERE id = ?";
$update_stmt = $db->prepare($update_query);
$update_stmt->execute([$recipe_id]);

$page_title = $recipe['title'] . " - FoodFusion";
?>

<div class="container">
    <div class="recipe-detail-page">
        <!-- Recipe Header -->
        <div class="recipe-header">
            <nav class="breadcrumb">
                <a href="index.php">Home</a>
                <i class="fas fa-chevron-right"></i>
                <a href="recipes.php">Recipes</a>
                <i class="fas fa-chevron-right"></i>
                <span><?php echo htmlspecialchars($recipe['title']); ?></span>
            </nav>
            
            <h1 class="recipe-title"><?php echo htmlspecialchars($recipe['title']); ?></h1>
            
            <div class="recipe-meta-header">
                <div class="author-info">
                    <div class="author-avatar">
                        <?php if (!empty($recipe['profile_image'])): ?>
                            <img src="uploads/profiles/<?php echo htmlspecialchars($recipe['profile_image']); ?>" 
                                 alt="<?php echo htmlspecialchars($recipe['first_name'] . ' ' . $recipe['last_name']); ?>">
                        <?php else: ?>
                            <div class="avatar-placeholder">
                                <i class="fas fa-user"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="author-details">
                        <span class="author-name"><?php echo htmlspecialchars($recipe['first_name'] . ' ' . $recipe['last_name']); ?></span>
                        <span class="recipe-date"><?php echo date('F j, Y', strtotime($recipe['created_at'])); ?></span>
                    </div>
                </div>
                
                <div class="recipe-stats">
                    <div class="stat-item">
                        <i class="fas fa-eye"></i>
                        <span><?php echo number_format($recipe['views'] + 1); ?> views</span>
                    </div>
                    <div class="stat-item">
                        <i class="fas fa-heart"></i>
                        <span><?php echo $recipe['likes_count']; ?> likes</span>
                    </div>
                    <div class="stat-item">
                        <i class="fas fa-comment"></i>
                        <span><?php echo $recipe['comments_count']; ?> comments</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Recipe Content -->
        <div class="recipe-main">
            <!-- Left Column: Recipe Image & Actions -->
            <div class="recipe-left">
                <div class="recipe-image-container">
                    <img src="<?php echo !empty($recipe['featured_image']) ? htmlspecialchars($recipe['featured_image']) : 'assets/images/default-recipe.jpg'; ?>" 
                         alt="<?php echo htmlspecialchars($recipe['title']); ?>"
                         class="main-recipe-image">
                    
                    <div class="image-overlay">
                        <div class="overlay-badges">
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
                            <?php if ($recipe['category']): ?>
                                <span class="badge badge-category">
                                    <?php echo $recipe['category']; ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="recipe-actions">
                    <button class="action-btn btn-like" data-recipe-id="<?php echo $recipe['id']; ?>">
                        <i class="far fa-heart"></i>
                        <span>Like</span>
                    </button>
                    <button class="action-btn btn-save">
                        <i class="far fa-bookmark"></i>
                        <span>Save</span>
                    </button>
                    <button class="action-btn btn-print" onclick="window.print()">
                        <i class="fas fa-print"></i>
                        <span>Print</span>
                    </button>
                    <button class="action-btn btn-share" onclick="shareRecipe()">
                        <i class="fas fa-share-alt"></i>
                        <span>Share</span>
                    </button>
                </div>

                <!-- Quick Info Cards -->
                <div class="quick-info-cards">
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="info-content">
                            <h4>Prep Time</h4>
                            <p><?php echo $recipe['prep_time'] ?? '--'; ?> min</p>
                        </div>
                    </div>
                    
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="info-content">
                            <h4>Cook Time</h4>
                            <p><?php echo $recipe['cooking_time']; ?> min</p>
                        </div>
                    </div>
                    
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="info-content">
                            <h4>Servings</h4>
                            <p><?php echo $recipe['servings']; ?> people</p>
                        </div>
                    </div>
                    
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-fire"></i>
                        </div>
                        <div class="info-content">
                            <h4>Calories</h4>
                            <p><?php echo $recipe['calories'] ?? '--'; ?> kcal</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Recipe Details -->
            <div class="recipe-right">
                <!-- Description -->
                <div class="recipe-section">
                    <h2><i class="fas fa-align-left"></i> Description</h2>
                    <div class="section-content">
                        <p><?php echo nl2br(htmlspecialchars($recipe['description'])); ?></p>
                    </div>
                </div>

                <!-- Ingredients -->
                <div class="recipe-section">
                    <h2><i class="fas fa-shopping-basket"></i> Ingredients</h2>
                    <div class="section-content">
                        <div class="ingredients-list">
                            <?php
                            $ingredients = $recipe['ingredients'] ? json_decode($recipe['ingredients'], true) : [];
                            if (is_array($ingredients) && count($ingredients) > 0):
                                foreach ($ingredients as $ingredient):
                            ?>
                            <div class="ingredient-item">
                                <label class="checkbox-label">
                                    <input type="checkbox">
                                    <span class="checkbox-custom"></span>
                                    <span class="ingredient-text">
                                        <strong><?php echo htmlspecialchars($ingredient['quantity'] ?? ''); ?></strong>
                                        <?php echo htmlspecialchars($ingredient['name'] ?? ''); ?>
                                        <?php if (!empty($ingredient['notes'])): ?>
                                            <em class="ingredient-notes">(<?php echo htmlspecialchars($ingredient['notes']); ?>)</em>
                                        <?php endif; ?>
                                    </span>
                                </label>
                            </div>
                            <?php
                                endforeach;
                            else:
                                $ingredients_text = $recipe['ingredients'] ?? '';
                                $ingredient_lines = explode("\n", $ingredients_text);
                                foreach ($ingredient_lines as $line):
                                    if (trim($line)):
                            ?>
                            <div class="ingredient-item">
                                <label class="checkbox-label">
                                    <input type="checkbox">
                                    <span class="checkbox-custom"></span>
                                    <span class="ingredient-text"><?php echo htmlspecialchars(trim($line)); ?></span>
                                </label>
                            </div>
                            <?php
                                    endif;
                                endforeach;
                            endif;
                            ?>
                        </div>
                        
                        <button class="btn btn-outline copy-ingredients">
                            <i class="fas fa-copy"></i> Copy Ingredients
                        </button>
                    </div>
                </div>

                <!-- Instructions -->
                <div class="recipe-section">
                    <h2><i class="fas fa-book-open"></i> Instructions</h2>
                    <div class="section-content">
                        <div class="instructions-list">
                            <?php
                            $instructions = $recipe['instructions'] ? json_decode($recipe['instructions'], true) : [];
                            if (is_array($instructions) && count($instructions) > 0):
                                $step = 1;
                                foreach ($instructions as $instruction):
                            ?>
                            <div class="instruction-step">
                                <div class="step-number"><?php echo $step; ?></div>
                                <div class="step-content">
                                    <h3>Step <?php echo $step; ?></h3>
                                    <p><?php echo nl2br(htmlspecialchars($instruction['description'] ?? '')); ?></p>
                                    <?php if (!empty($instruction['image'])): ?>
                                        <div class="step-image">
                                            <img src="<?php echo htmlspecialchars($instruction['image']); ?>" 
                                                 alt="Step <?php echo $step; ?>">
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php
                                    $step++;
                                endforeach;
                            else:
                                $instructions_text = $recipe['instructions'] ?? '';
                                $instruction_lines = explode("\n", $instructions_text);
                                $step = 1;
                                foreach ($instruction_lines as $line):
                                    if (trim($line)):
                            ?>
                            <div class="instruction-step">
                                <div class="step-number"><?php echo $step; ?></div>
                                <div class="step-content">
                                    <h3>Step <?php echo $step; ?></h3>
                                    <p><?php echo htmlspecialchars(trim($line)); ?></p>
                                </div>
                            </div>
                            <?php
                                        $step++;
                                    endif;
                                endforeach;
                            endif;
                            ?>
                        </div>
                    </div>
                </div>

                <!-- Nutrition Facts -->
                <?php if ($recipe['calories'] || $recipe['protein'] || $recipe['carbs'] || $recipe['fat']): ?>
                <div class="recipe-section">
                    <h2><i class="fas fa-apple-alt"></i> Nutrition Facts</h2>
                    <div class="section-content">
                        <div class="nutrition-facts">
                            <div class="nutrition-grid">
                                <div class="nutrition-item">
                                    <span class="nutrition-label">Calories</span>
                                    <span class="nutrition-value"><?php echo $recipe['calories'] ?? '--'; ?> kcal</span>
                                </div>
                                <div class="nutrition-item">
                                    <span class="nutrition-label">Protein</span>
                                    <span class="nutrition-value"><?php echo $recipe['protein'] ?? '--'; ?>g</span>
                                </div>
                                <div class="nutrition-item">
                                    <span class="nutrition-label">Carbs</span>
                                    <span class="nutrition-value"><?php echo $recipe['carbs'] ?? '--'; ?>g</span>
                                </div>
                                <div class="nutrition-item">
                                    <span class="nutrition-label">Fat</span>
                                    <span class="nutrition-value"><?php echo $recipe['fat'] ?? '--'; ?>g</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Tags -->
                <div class="recipe-section">
                    <h2><i class="fas fa-tags"></i> Tags</h2>
                    <div class="section-content">
                        <div class="tags-list">
                            <?php
                            $tags = [];
                            if ($recipe['category']) $tags[] = $recipe['category'];
                            if ($recipe['cuisine']) $tags[] = $recipe['cuisine'];
                            if ($recipe['difficulty_level']) $tags[] = $recipe['difficulty_level'];
                            if ($recipe['dietary_preference']) $tags[] = $recipe['dietary_preference'];
                            
                            foreach (array_unique($tags) as $tag):
                                if ($tag):
                            ?>
                            <a href="recipes.php?search=<?php echo urlencode($tag); ?>" class="tag">
                                <?php echo htmlspecialchars($tag); ?>
                            </a>
                            <?php
                                endif;
                            endforeach;
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Comments Section -->
        <div class="comments-section">
            <h2><i class="fas fa-comments"></i> Comments</h2>
            <div class="comments-container">
                <?php if (isset($_SESSION['user_id'])): ?>
                <div class="comment-form">
                    <div class="comment-avatar">
                        <?php if (!empty($_SESSION['profile_image'])): ?>
                            <img src="uploads/profiles/<?php echo htmlspecialchars($_SESSION['profile_image']); ?>" 
                                 alt="<?php echo htmlspecialchars($_SESSION['user_name']); ?>">
                        <?php else: ?>
                            <div class="avatar-placeholder small">
                                <i class="fas fa-user"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <form class="comment-input">
                        <textarea placeholder="Add a comment..." rows="3"></textarea>
                        <div class="comment-actions">
                            <button type="submit" class="btn btn-primary">Post Comment</button>
                        </div>
                    </form>
                </div>
                <?php else: ?>
                <div class="login-prompt">
                    <p><a href="login.php">Login</a> or <a href="register.php">Register</a> to leave a comment</p>
                </div>
                <?php endif; ?>
                
                <div class="comments-list">
                    <!-- Comments will be loaded here -->
                    <div class="no-comments">
                        <i class="fas fa-comment-slash"></i>
                        <p>No comments yet. Be the first to comment!</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Recipes -->
        <div class="related-recipes">
            <h2><i class="fas fa-utensils"></i> You May Also Like</h2>
            <div class="related-grid">
                <?php
                // Get related recipes
                $related_query = "SELECT * FROM recipes 
                                 WHERE category = ? AND id != ? 
                                 ORDER BY RAND() LIMIT 3";
                $related_stmt = $db->prepare($related_query);
                $related_stmt->execute([$recipe['category'], $recipe['id']]);
                $related_recipes = $related_stmt->fetchAll(PDO::FETCH_ASSOC);
                
                if ($related_recipes):
                    foreach ($related_recipes as $related):
                ?>
                <a href="recipe-detail.php?id=<?php echo $related['id']; ?>" class="related-card">
                    <div class="related-image">
                        <img src="<?php echo !empty($related['featured_image']) ? htmlspecialchars($related['featured_image']) : 'assets/images/default-recipe.jpg'; ?>" 
                             alt="<?php echo htmlspecialchars($related['title']); ?>">
                    </div>
                    <div class="related-content">
                        <h3><?php echo htmlspecialchars($related['title']); ?></h3>
                        <div class="related-meta">
                            <span><i class="fas fa-clock"></i> <?php echo $related['cooking_time']; ?> min</span>
                            <span><i class="fas fa-fire"></i> <?php echo $related['difficulty_level']; ?></span>
                        </div>
                    </div>
                </a>
                <?php
                    endforeach;
                else:
                    // Fallback to random recipes
                    $random_query = "SELECT * FROM recipes WHERE id != ? ORDER BY RAND() LIMIT 3";
                    $random_stmt = $db->prepare($random_query);
                    $random_stmt->execute([$recipe['id']]);
                    $random_recipes = $random_stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    foreach ($random_recipes as $random):
                ?>
                <a href="recipe-detail.php?id=<?php echo $random['id']; ?>" class="related-card">
                    <div class="related-image">
                        <img src="<?php echo !empty($random['featured_image']) ? htmlspecialchars($random['featured_image']) : 'assets/images/default-recipe.jpg'; ?>" 
                             alt="<?php echo htmlspecialchars($random['title']); ?>">
                    </div>
                    <div class="related-content">
                        <h3><?php echo htmlspecialchars($random['title']); ?></h3>
                        <div class="related-meta">
                            <span><i class="fas fa-clock"></i> <?php echo $random['cooking_time']; ?> min</span>
                            <span><i class="fas fa-fire"></i> <?php echo $random['difficulty_level']; ?></span>
                        </div>
                    </div>
                </a>
                <?php
                    endforeach;
                endif;
                ?>
            </div>
        </div>
    </div>
</div>

<style>
/* Recipe Detail Styles */
.recipe-detail-page {
    padding: 20px 0 50px;
}

/* Breadcrumb */
.breadcrumb {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
    font-size: 0.9rem;
    color: var(--gray);
}

.breadcrumb a {
    color: var(--gray);
    text-decoration: none;
    transition: color 0.3s;
}

.breadcrumb a:hover {
    color: var(--primary);
}

.breadcrumb i {
    font-size: 0.8rem;
}

.breadcrumb span {
    color: var(--dark);
    font-weight: 500;
}

/* Recipe Header */
.recipe-title {
    font-size: 2.5rem;
    color: var(--dark);
    margin-bottom: 25px;
    line-height: 1.3;
    font-weight: 800;
}

.recipe-meta-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding-bottom: 25px;
    border-bottom: 1px solid #eee;
}

@media (max-width: 768px) {
    .recipe-meta-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 20px;
    }
}

/* Author Info */
.author-info {
    display: flex;
    align-items: center;
    gap: 15px;
}

.author-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid white;
    box-shadow: var(--shadow-sm);
}

.author-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-placeholder {
    width: 100%;
    height: 100%;
    background: var(--gradient);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.avatar-placeholder.small {
    font-size: 14px;
}

.author-details {
    display: flex;
    flex-direction: column;
}

.author-name {
    font-weight: 600;
    color: var(--dark);
    font-size: 1rem;
}

.recipe-date {
    color: var(--gray);
    font-size: 0.9rem;
}

/* Recipe Stats */
.recipe-stats {
    display: flex;
    gap: 25px;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--gray);
    font-size: 0.95rem;
}

.stat-item i {
    color: var(--primary);
    font-size: 1rem;
}

/* Main Layout */
.recipe-main {
    display: grid;
    grid-template-columns: 350px 1fr;
    gap: 40px;
    margin-bottom: 50px;
}

@media (max-width: 1024px) {
    .recipe-main {
        grid-template-columns: 1fr;
        gap: 30px;
    }
}

/* Left Column */
.recipe-image-container {
    position: relative;
    border-radius: var(--radius-lg);
    overflow: hidden;
    margin-bottom: 20px;
    box-shadow: var(--shadow-lg);
}

.main-recipe-image {
    width: 100%;
    height: 300px;
    object-fit: cover;
    display: block;
}

.image-overlay {
    position: absolute;
    top: 20px;
    left: 20px;
}

.overlay-badges {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.badge {
    padding: 8px 16px;
    border-radius: 25px;
    font-size: 0.8rem;
    font-weight: 600;
    color: white;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    backdrop-filter: blur(10px);
}

.badge-quick {
    background: rgba(255, 193, 7, 0.9);
}

.badge-category {
    background: rgba(78, 205, 196, 0.9);
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

/* Recipe Actions */
.recipe-actions {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
    margin-bottom: 30px;
}

.action-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    padding: 15px 10px;
    background: white;
    border: 2px solid #eee;
    border-radius: var(--radius-md);
    color: var(--gray);
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.3s;
}

.action-btn:hover {
    border-color: var(--primary);
    color: var(--primary);
    transform: translateY(-3px);
    box-shadow: var(--shadow-sm);
}

.action-btn i {
    font-size: 1.2rem;
}

.action-btn.btn-like.liked {
    border-color: var(--primary);
    background: var(--primary);
    color: white;
}

/* Quick Info Cards */
.quick-info-cards {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
}

.info-card {
    background: white;
    border-radius: var(--radius-md);
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    box-shadow: var(--shadow-sm);
    transition: all 0.3s;
}

.info-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-md);
}

.info-icon {
    width: 50px;
    height: 50px;
    background: var(--gradient);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
}

.info-content h4 {
    margin: 0 0 5px 0;
    color: var(--gray);
    font-size: 0.9rem;
    font-weight: 500;
}

.info-content p {
    margin: 0;
    color: var(--dark);
    font-size: 1.1rem;
    font-weight: 600;
}

/* Right Column */
.recipe-section {
    margin-bottom: 40px;
}

.recipe-section h2 {
    display: flex;
    align-items: center;
    gap: 10px;
    color: var(--dark);
    margin-bottom: 20px;
    font-size: 1.5rem;
    padding-bottom: 15px;
    border-bottom: 2px solid #eee;
}

.recipe-section h2 i {
    color: var(--primary);
}

.section-content {
    background: white;
    border-radius: var(--radius-lg);
    padding: 25px;
    box-shadow: var(--shadow-sm);
}

/* Ingredients */
.ingredients-list {
    margin-bottom: 20px;
}

.ingredient-item {
    padding: 12px 0;
    border-bottom: 1px solid #f0f0f0;
}

.ingredient-item:last-child {
    border-bottom: none;
}

.checkbox-label {
    display: flex;
    align-items: flex-start;
    gap: 15px;
    cursor: pointer;
    user-select: none;
}

.checkbox-label input {
    display: none;
}

.checkbox-custom {
    width: 20px;
    height: 20px;
    border: 2px solid #ddd;
    border-radius: 4px;
    flex-shrink: 0;
    position: relative;
    transition: all 0.3s;
    margin-top: 2px;
}

.checkbox-label input:checked + .checkbox-custom {
    background: var(--primary);
    border-color: var(--primary);
}

.checkbox-label input:checked + .checkbox-custom::after {
    content: '✓';
    position: absolute;
    color: white;
    font-size: 12px;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

.ingredient-text {
    flex: 1;
    line-height: 1.5;
}

.ingredient-notes {
    color: var(--gray);
    font-style: italic;
    font-size: 0.9rem;
    margin-left: 5px;
}

.copy-ingredients {
    margin-top: 10px;
}

/* Instructions */
.instruction-step {
    display: flex;
    gap: 20px;
    margin-bottom: 30px;
    padding-bottom: 30px;
    border-bottom: 1px solid #f0f0f0;
}

.instruction-step:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.step-number {
    width: 40px;
    height: 40px;
    background: var(--gradient);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 1.2rem;
    flex-shrink: 0;
}

.step-content {
    flex: 1;
}

.step-content h3 {
    color: var(--dark);
    margin: 0 0 10px 0;
    font-size: 1.2rem;
}

.step-content p {
    color: var(--gray);
    line-height: 1.6;
    margin-bottom: 15px;
}

.step-image {
    border-radius: var(--radius-md);
    overflow: hidden;
    margin-top: 15px;
}

.step-image img {
    width: 100%;
    max-height: 200px;
    object-fit: cover;
}

/* Nutrition Facts */
.nutrition-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 20px;
}

.nutrition-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    background: #f8f9fa;
    border-radius: var(--radius-md);
}

.nutrition-label {
    color: var(--gray);
    font-weight: 500;
}

.nutrition-value {
    color: var(--dark);
    font-weight: 600;
    font-size: 1.1rem;
}

/* Tags */
.tags-list {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.tag {
    padding: 8px 16px;
    background: var(--light);
    color: var(--dark);
    text-decoration: none;
    border-radius: 25px;
    font-size: 0.9rem;
    transition: all 0.3s;
}

.tag:hover {
    background: var(--primary);
    color: white;
    transform: translateY(-2px);
}

/* Comments Section */
.comments-section {
    margin-bottom: 50px;
}

.comments-container {
    background: white;
    border-radius: var(--radius-lg);
    padding: 30px;
    box-shadow: var(--shadow-md);
}

.comment-form {
    display: flex;
    gap: 20px;
    margin-bottom: 30px;
    padding-bottom: 30px;
    border-bottom: 1px solid #eee;
}

.comment-avatar {
    flex-shrink: 0;
}

.comment-avatar img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
}

.comment-input {
    flex: 1;
}

.comment-input textarea {
    width: 100%;
    padding: 15px;
    border: 2px solid #eee;
    border-radius: var(--radius-md);
    font-family: inherit;
    font-size: 1rem;
    resize: vertical;
    transition: border-color 0.3s;
}

.comment-input textarea:focus {
    outline: none;
    border-color: var(--primary);
}

.comment-actions {
    margin-top: 15px;
    text-align: right;
}

.login-prompt {
    text-align: center;
    padding: 30px;
    background: #f8f9fa;
    border-radius: var(--radius-md);
    margin-bottom: 30px;
}

.login-prompt a {
    color: var(--primary);
    text-decoration: none;
    font-weight: 600;
}

.login-prompt a:hover {
    text-decoration: underline;
}

.no-comments {
    text-align: center;
    padding: 40px 20px;
    color: var(--gray);
}

.no-comments i {
    font-size: 3rem;
    margin-bottom: 15px;
    opacity: 0.3;
}

/* Related Recipes */
.related-recipes {
    margin-bottom: 50px;
}

.related-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 25px;
}

.related-card {
    background: white;
    border-radius: var(--radius-lg);
    overflow: hidden;
    text-decoration: none;
    box-shadow: var(--shadow-md);
    transition: all 0.3s;
}

.related-card:hover {
    transform: translateY(-10px);
    box-shadow: var(--shadow-lg);
}

.related-image {
    height: 150px;
    overflow: hidden;
}

.related-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s;
}

.related-card:hover .related-image img {
    transform: scale(1.05);
}

.related-content {
    padding: 20px;
}

.related-content h3 {
    color: var(--dark);
    margin: 0 0 10px 0;
    font-size: 1.1rem;
    line-height: 1.4;
}

.related-meta {
    display: flex;
    gap: 15px;
    color: var(--gray);
    font-size: 0.9rem;
}

.related-meta i {
    color: var(--primary);
    margin-right: 5px;
}

/* Print Styles */
@media print {
    .recipe-actions,
    .comments-section,
    .related-recipes,
    .header,
    .footer {
        display: none;
    }
    
    .recipe-main {
        grid-template-columns: 1fr;
    }
    
    body {
        background: white;
        color: black;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Like button functionality
    const likeBtn = document.querySelector('.btn-like');
    if (likeBtn) {
        likeBtn.addEventListener('click', function() {
            const icon = this.querySelector('i');
            const span = this.querySelector('span');
            const recipeId = this.getAttribute('data-recipe-id');
            
            if (this.classList.contains('liked')) {
                this.classList.remove('liked');
                icon.className = 'far fa-heart';
                span.textContent = 'Like';
                // In real app: Send AJAX to unlike
                console.log('Unliked recipe', recipeId);
            } else {
                this.classList.add('liked');
                icon.className = 'fas fa-heart';
                span.textContent = 'Liked';
                // In real app: Send AJAX to like
                console.log('Liked recipe', recipeId);
                showNotification('Recipe liked!', 'success');
            }
        });
    }
    
    // Save button functionality
    const saveBtn = document.querySelector('.btn-save');
    if (saveBtn) {
        saveBtn.addEventListener('click', function() {
            const icon = this.querySelector('i');
            const span = this.querySelector('span');
            
            if (icon.classList.contains('far')) {
                icon.className = 'fas fa-bookmark';
                span.textContent = 'Saved';
                showNotification('Recipe saved to your favorites!', 'success');
            } else {
                icon.className = 'far fa-bookmark';
                span.textContent = 'Save';
                showNotification('Recipe removed from favorites!', 'info');
            }
        });
    }
    
    // Copy ingredients functionality
    const copyIngredientsBtn = document.querySelector('.copy-ingredients');
    if (copyIngredientsBtn) {
        copyIngredientsBtn.addEventListener('click', function() {
            const ingredients = [];
            document.querySelectorAll('.ingredient-text').forEach(item => {
                ingredients.push(item.textContent.trim());
            });
            
            const text = ingredients.join('\n');
            navigator.clipboard.writeText(text)
                .then(() => {
                    const originalHTML = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-check"></i> Copied!';
                    this.disabled = true;
                    
                    setTimeout(() => {
                        this.innerHTML = originalHTML;
                        this.disabled = false;
                    }, 2000);
                    
                    showNotification('Ingredients copied to clipboard!', 'success');
                })
                .catch(err => {
                    console.error('Failed to copy: ', err);
                    showNotification('Failed to copy ingredients', 'error');
                });
        });
    }
    
    // Check/uncheck all ingredients
    const ingredientCheckboxes = document.querySelectorAll('.ingredient-item input[type="checkbox"]');
    let allChecked = true;
    
    ingredientCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateShoppingList();
        });
        
        if (!checkbox.checked) allChecked = false;
    });
    
    // Share functionality
    window.shareRecipe = function() {
        const shareData = {
            title: document.querySelector('.recipe-title').textContent,
            text: 'Check out this amazing recipe on FoodFusion!',
            url: window.location.href
        };
        
        if (navigator.share && navigator.canShare(shareData)) {
            navigator.share(shareData)
                .then(() => console.log('Shared successfully'))
                .catch(err => console.log('Error sharing:', err));
        } else {
            // Fallback: Copy URL to clipboard
            navigator.clipboard.writeText(window.location.href)
                .then(() => showNotification('Link copied to clipboard!', 'success'))
                .catch(err => {
                    console.error('Failed to copy: ', err);
                    showNotification('Failed to copy link', 'error');
                });
        }
    };
    
    // Update shopping list function
    function updateShoppingList() {
        const checkedItems = document.querySelectorAll('.ingredient-item input[type="checkbox"]:checked');
        console.log(`${checkedItems.length} ingredients checked`);
        // In real app: Update shopping list UI or send to server
    }
    
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
        Object.assign(notification.style, {
            position: 'fixed',
            top: '20px',
            right: '20px',
            background: type === 'success' ? '#4CAF50' : type === 'error' ? '#F44336' : '#2196F3',
            color: 'white',
            padding: '15px 20px',
            borderRadius: 'var(--radius-md)',
            boxShadow: 'var(--shadow-lg)',
            zIndex: '9999',
            display: 'flex',
            alignItems: 'center',
            gap: '10px',
            animation: 'slideIn 0.3s ease'
        });
        
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
});
</script>

<?php include 'includes/footer.php'; ?>