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
    <!-- Recipe Hero Section -->
    <section class="recipe-hero">
        <div class="hero-background" style="background-image: url('<?php echo !empty($recipe['featured_image']) ? htmlspecialchars($recipe['featured_image']) : 'https://images.unsplash.com/photo-1490818387583-1baba5e638af?w=1600&auto=format&fit=crop'; ?>');"></div>
        
        <div class="hero-overlay">
            <div class="hero-content">
                <!-- Breadcrumb -->
                <nav class="breadcrumb-nav">
                    <a href="index.php" class="breadcrumb-link">
                        <i class="fas fa-home"></i>
                        Home
                    </a>
                    <i class="fas fa-chevron-right breadcrumb-separator"></i>
                    <a href="recipes.php" class="breadcrumb-link">Recipes</a>
                    <i class="fas fa-chevron-right breadcrumb-separator"></i>
                    <span class="breadcrumb-current"><?php echo htmlspecialchars($recipe['title']); ?></span>
                </nav>
                
                <!-- Recipe Title -->
                <h1 class="recipe-main-title"><?php echo htmlspecialchars($recipe['title']); ?></h1>
                
                <!-- Recipe Meta -->
                <div class="recipe-hero-meta">
                    <div class="meta-item">
                        <i class="fas fa-clock"></i>
                        <span><?php echo $recipe['cooking_time']; ?> min</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-signal"></i>
                        <span><?php echo $recipe['difficulty_level']; ?></span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-users"></i>
                        <span><?php echo $recipe['servings']; ?> servings</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-eye"></i>
                        <span><?php echo number_format($recipe['views'] + 1); ?> views</span>
                    </div>
                </div>
                
                <!-- Author Info -->
                <div class="author-section">
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
                    <div class="author-info">
                        <h4 class="author-name"><?php echo htmlspecialchars($recipe['first_name'] . ' ' . $recipe['last_name']); ?></h4>
                        <p class="author-date">Published on <?php echo date('F j, Y', strtotime($recipe['created_at'])); ?></p>
                    </div>
                    <div class="author-stats">
                        <div class="stat">
                            <i class="fas fa-heart"></i>
                            <span><?php echo $recipe['likes_count']; ?> likes</span>
                        </div>
                        <div class="stat">
                            <i class="fas fa-comment"></i>
                            <span><?php echo $recipe['comments_count']; ?> comments</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Recipe Content -->
    <div class="recipe-main-layout">
        <!-- Left Column: Recipe Info -->
        <aside class="recipe-sidebar">
            <!-- Action Buttons -->
            <div class="action-card">
                <button class="action-btn btn-like <?php echo isset($_SESSION['user_id']) && checkIfLiked($recipe_id, $_SESSION['user_id']) ? 'active' : ''; ?>" 
                        data-recipe-id="<?php echo $recipe['id']; ?>">
                    <div class="action-icon">
                        <i class="far fa-heart"></i>
                    </div>
                    <span class="action-text">Like</span>
                </button>
                
                <button class="action-btn btn-save">
                    <div class="action-icon">
                        <i class="far fa-bookmark"></i>
                    </div>
                    <span class="action-text">Save</span>
                </button>
                
                <button class="action-btn btn-share" onclick="shareRecipe()">
                    <div class="action-icon">
                        <i class="fas fa-share-alt"></i>
                    </div>
                    <span class="action-text">Share</span>
                </button>
                
                <button class="action-btn btn-print" onclick="window.print()">
                    <div class="action-icon">
                        <i class="fas fa-print"></i>
                    </div>
                    <span class="action-text">Print</span>
                </button>
            </div>

            <!-- Quick Info Cards -->
            <div class="info-cards">
                <div class="info-card">
                    <div class="card-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="card-content">
                        <h4>Prep Time</h4>
                        <p><?php echo $recipe['prep_time'] ?? '--'; ?> min</p>
                    </div>
                </div>
                
                <div class="info-card">
                    <div class="card-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="card-content">
                        <h4>Cook Time</h4>
                        <p><?php echo $recipe['cooking_time']; ?> min</p>
                    </div>
                </div>
                
                <div class="info-card">
                    <div class="card-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="card-content">
                        <h4>Servings</h4>
                        <p><?php echo $recipe['servings']; ?> people</p>
                    </div>
                </div>
                
                <div class="info-card">
                    <div class="card-icon">
                        <i class="fas fa-fire"></i>
                    </div>
                    <div class="card-content">
                        <h4>Calories</h4>
                        <p><?php echo $recipe['calories'] ?? '--'; ?> kcal</p>
                    </div>
                </div>
            </div>

            <!-- Nutrition Facts -->
            <?php if ($recipe['calories'] || $recipe['protein'] || $recipe['carbs'] || $recipe['fat']): ?>
            <div class="nutrition-card">
                <div class="card-header">
                    <i class="fas fa-apple-alt"></i>
                    <h3>Nutrition Facts</h3>
                </div>
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
            <?php endif; ?>

            <!-- Tags -->
            <div class="tags-card">
                <div class="card-header">
                    <i class="fas fa-tags"></i>
                    <h3>Tags</h3>
                </div>
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
        </aside>

        <!-- Main Content -->
        <main class="recipe-content">
            <!-- Description -->
            <section class="content-section">
                <div class="section-header">
                    <div class="header-icon">
                        <i class="fas fa-align-left"></i>
                    </div>
                    <h2>About This Recipe</h2>
                </div>
                <div class="section-body">
                    <p class="recipe-description"><?php echo nl2br(htmlspecialchars($recipe['description'])); ?></p>
                </div>
            </section>

            <!-- Ingredients -->
            <section class="content-section">
                <div class="section-header">
                    <div class="header-icon">
                        <i class="fas fa-shopping-basket"></i>
                    </div>
                    <h2>Ingredients</h2>
                    <button class="copy-ingredients-btn">
                        <i class="fas fa-copy"></i>
                        Copy List
                    </button>
                </div>
                <div class="section-body">
                    <div class="ingredients-list">
                        <?php
                        $ingredients = $recipe['ingredients'] ? json_decode($recipe['ingredients'], true) : [];
                        if (is_array($ingredients) && count($ingredients) > 0):
                            foreach ($ingredients as $ingredient):
                        ?>
                        <div class="ingredient-item">
                            <label class="checkbox-container">
                                <input type="checkbox">
                                <span class="checkmark"></span>
                                <div class="ingredient-content">
                                    <span class="ingredient-quantity"><?php echo htmlspecialchars($ingredient['quantity'] ?? ''); ?></span>
                                    <span class="ingredient-name"><?php echo htmlspecialchars($ingredient['name'] ?? ''); ?></span>
                                    <?php if (!empty($ingredient['notes'])): ?>
                                        <span class="ingredient-notes"><?php echo htmlspecialchars($ingredient['notes']); ?></span>
                                    <?php endif; ?>
                                </div>
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
                            <label class="checkbox-container">
                                <input type="checkbox">
                                <span class="checkmark"></span>
                                <span class="ingredient-text"><?php echo htmlspecialchars(trim($line)); ?></span>
                            </label>
                        </div>
                        <?php
                                endif;
                            endforeach;
                        endif;
                        ?>
                    </div>
                </div>
            </section>

            <!-- Instructions -->
            <section class="content-section">
                <div class="section-header">
                    <div class="header-icon">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <h2>Cooking Instructions</h2>
                </div>
                <div class="section-body">
                    <div class="instructions-timeline">
                        <?php
                        $instructions = $recipe['instructions'] ? json_decode($recipe['instructions'], true) : [];
                        if (is_array($instructions) && count($instructions) > 0):
                            $step = 1;
                            foreach ($instructions as $instruction):
                        ?>
                        <div class="timeline-step">
                            <div class="step-number"><?php echo $step; ?></div>
                            <div class="step-content">
                                <h3>Step <?php echo $step; ?></h3>
                                <p><?php echo nl2br(htmlspecialchars($instruction['description'] ?? '')); ?></p>
                                <?php if (!empty($instruction['image'])): ?>
                                    <div class="step-image">
                                        <img src="<?php echo htmlspecialchars($instruction['image']); ?>" 
                                             alt="Step <?php echo $step; ?>"
                                             loading="lazy">
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
                        <div class="timeline-step">
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
            </section>

            <!-- Notes (Optional) -->
            <?php if (!empty($recipe['notes'])): ?>
            <section class="content-section">
                <div class="section-header">
                    <div class="header-icon">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <h2>Chef's Notes</h2>
                </div>
                <div class="section-body">
                    <div class="notes-content">
                        <p><?php echo nl2br(htmlspecialchars($recipe['notes'])); ?></p>
                    </div>
                </div>
            </section>
            <?php endif; ?>

            <!-- Comments Section -->
            <section class="content-section comments-section">
                <div class="section-header">
                    <div class="header-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h2>Comments (<?php echo $recipe['comments_count']; ?>)</h2>
                </div>
                <div class="section-body">
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
                        <div class="comment-input-wrapper">
                            <textarea placeholder="Share your thoughts on this recipe..." rows="3"></textarea>
                            <div class="comment-actions">
                                <button type="submit" class="submit-comment-btn">
                                    <i class="fas fa-paper-plane"></i>
                                    Post Comment
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="login-prompt">
                        <p>
                            <a href="login.php" class="login-link">Login</a> 
                            or 
                            <a href="register.php" class="register-link">Register</a> 
                            to join the conversation
                        </p>
                    </div>
                    <?php endif; ?>
                    
                    <div class="comments-list">
                        <div class="no-comments">
                            <i class="fas fa-comment-slash"></i>
                            <h4>No comments yet</h4>
                            <p>Be the first to share your thoughts!</p>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <!-- Related Recipes -->
    <section class="related-recipes">
        <div class="section-header">
            <h2>You Might Also Like</h2>
            <a href="recipes.php" class="view-all">
                View All
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        
        <div class="related-grid">
            <?php
            // Get related recipes
            $related_query = "SELECT * FROM recipes 
                             WHERE category = ? AND id != ? 
                             ORDER BY RAND() LIMIT 3";
            $related_stmt = $db->prepare($related_query);
            $related_stmt->execute([$recipe['category'], $recipe['id']]);
            $related_recipes = $related_stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (!$related_recipes) {
                // Fallback to random recipes
                $related_query = "SELECT * FROM recipes WHERE id != ? ORDER BY RAND() LIMIT 3";
                $related_stmt = $db->prepare($related_query);
                $related_stmt->execute([$recipe['id']]);
                $related_recipes = $related_stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            
            foreach ($related_recipes as $related):
            ?>
            <a href="recipe-detail.php?id=<?php echo $related['id']; ?>" class="related-card">
                <div class="card-image">
                    <img src="<?php echo !empty($related['featured_image']) ? htmlspecialchars($related['featured_image']) : 'https://images.unsplash.com/photo-1490818387583-1baba5e638af?w=800&auto=format&fit=crop'; ?>" 
                         alt="<?php echo htmlspecialchars($related['title']); ?>"
                         loading="lazy">
                    <div class="image-overlay"></div>
                </div>
                <div class="card-content">
                    <h3><?php echo htmlspecialchars($related['title']); ?></h3>
                    <div class="card-meta">
                        <span class="meta-item">
                            <i class="fas fa-clock"></i>
                            <?php echo $related['cooking_time']; ?> min
                        </span>
                        <span class="meta-item">
                            <i class="fas fa-signal"></i>
                            <?php echo $related['difficulty_level']; ?>
                        </span>
                    </div>
                    <div class="card-footer">
                        <span class="view-recipe">
                            View Recipe
                            <i class="fas fa-arrow-right"></i>
                        </span>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </section>
</div>

<style>
/* Recipe Detail Page Styles */
:root {
    --primary: #FF6B35;
    --primary-light: #FF8E53;
    --secondary: #2EC4B6;
    --dark: #1A1A2E;
    --darker: #0F0F1E;
    --light: #F8F9FA;
    --lighter: #FFFFFF;
    --gray: #6C757D;
    --gray-light: #E9ECEF;
    --success: #06D6A0;
    --warning: #FFD166;
    --danger: #EF476F;
    --gradient: linear-gradient(135deg, #FF6B35 0%, #FF8E53 100%);
    --gradient-dark: linear-gradient(135deg, #1A1A2E 0%, #16213E 100%);
    --shadow-sm: 0 2px 8px rgba(0,0,0,0.06);
    --shadow-md: 0 4px 20px rgba(0,0,0,0.1);
    --shadow-lg: 0 8px 30px rgba(0,0,0,0.12);
    --shadow-xl: 0 20px 40px rgba(0,0,0,0.15);
    --radius-sm: 8px;
    --radius-md: 12px;
    --radius-lg: 16px;
    --radius-xl: 24px;
    --radius-2xl: 32px;
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Recipe Hero */
.recipe-hero {
    position: relative;
    margin: -20px -15px 40px;
    border-radius: 0 0 40px 40px;
    overflow: hidden;
    min-height: 500px;
}

.hero-background {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-size: cover;
    background-position: center;
    filter: brightness(0.7);
    transform: scale(1.1);
    transition: transform 0.8s ease;
}

.recipe-hero:hover .hero-background {
    transform: scale(1);
}

.hero-overlay {
    position: relative;
    z-index: 1;
    background: linear-gradient(to bottom, rgba(26, 26, 46, 0.9), rgba(26, 26, 46, 0.95));
    min-height: 500px;
    display: flex;
    align-items: flex-end;
    padding: 60px 40px;
}

.hero-content {
    max-width: 1200px;
    margin: 0 auto;
    width: 100%;
}

/* Breadcrumb */
.breadcrumb-nav {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 30px;
}

.breadcrumb-link {
    display: flex;
    align-items: center;
    gap: 8px;
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    font-size: 0.9rem;
    transition: var(--transition);
}

.breadcrumb-link:hover {
    color: white;
    transform: translateX(2px);
}

.breadcrumb-separator {
    color: rgba(255, 255, 255, 0.4);
    font-size: 0.8rem;
}

.breadcrumb-current {
    color: white;
    font-weight: 500;
    font-size: 0.9rem;
}

/* Recipe Title */
.recipe-main-title {
    font-size: 3.5rem;
    color: white;
    margin-bottom: 25px;
    font-weight: 800;
    line-height: 1.2;
    text-shadow: 0 4px 20px rgba(0,0,0,0.3);
}

/* Recipe Hero Meta */
.recipe-hero-meta {
    display: flex;
    gap: 30px;
    margin-bottom: 35px;
    flex-wrap: wrap;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 10px;
    color: rgba(255, 255, 255, 0.9);
    font-size: 1rem;
}

.meta-item i {
    color: var(--primary);
    font-size: 1.1rem;
}

/* Author Section */
.author-section {
    display: flex;
    align-items: center;
    gap: 20px;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(20px);
    border-radius: var(--radius-lg);
    padding: 20px;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.author-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid var(--primary);
    flex-shrink: 0;
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
    font-size: 1.5rem;
}

.avatar-placeholder.small {
    width: 40px;
    height: 40px;
    font-size: 1rem;
}

.author-info {
    flex: 1;
}

.author-name {
    color: white;
    font-size: 1.2rem;
    margin-bottom: 5px;
    font-weight: 600;
}

.author-date {
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.9rem;
    margin: 0;
}

.author-stats {
    display: flex;
    gap: 25px;
}

.stat {
    display: flex;
    align-items: center;
    gap: 8px;
    color: rgba(255, 255, 255, 0.9);
}

.stat i {
    color: var(--secondary);
}

/* Main Layout */
.recipe-main-layout {
    display: grid;
    grid-template-columns: 380px 1fr;
    gap: 40px;
    margin: 40px 0 60px;
}

@media (max-width: 1200px) {
    .recipe-main-layout {
        grid-template-columns: 1fr;
        gap: 30px;
    }
}

/* Recipe Sidebar */
.recipe-sidebar {
    position: sticky;
    top: 100px;
    align-self: start;
}

/* Action Card */
.action-card {
    background: white;
    border-radius: var(--radius-xl);
    padding: 25px;
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
    margin-bottom: 25px;
    box-shadow: var(--shadow-lg);
    border: 1px solid var(--gray-light);
}

.action-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    padding: 20px 15px;
    background: var(--light);
    border: 2px solid var(--gray-light);
    border-radius: var(--radius-md);
    color: var(--gray);
    cursor: pointer;
    transition: var(--transition);
    font-size: 0.9rem;
}

.action-btn:hover {
    transform: translateY(-3px);
    border-color: var(--primary);
    color: var(--primary);
    box-shadow: var(--shadow-md);
}

.action-btn.active {
    background: var(--primary);
    border-color: var(--primary);
    color: white;
}

.action-btn.active .action-icon {
    background: white;
    color: var(--primary);
}

.action-icon {
    width: 50px;
    height: 50px;
    background: white;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    transition: var(--transition);
}

.action-text {
    font-weight: 500;
}

/* Info Cards */
.info-cards {
    background: white;
    border-radius: var(--radius-xl);
    padding: 30px;
    margin-bottom: 25px;
    box-shadow: var(--shadow-lg);
    border: 1px solid var(--gray-light);
}

.info-card {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 20px;
    background: var(--light);
    border-radius: var(--radius-lg);
    margin-bottom: 15px;
    transition: var(--transition);
}

.info-card:last-child {
    margin-bottom: 0;
}

.info-card:hover {
    transform: translateX(5px);
    background: white;
    box-shadow: var(--shadow-sm);
}

.card-icon {
    width: 60px;
    height: 60px;
    background: var(--gradient);
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.card-content h4 {
    color: var(--gray);
    font-size: 0.9rem;
    margin: 0 0 5px 0;
    font-weight: 500;
}

.card-content p {
    color: var(--dark);
    font-size: 1.4rem;
    margin: 0;
    font-weight: 700;
}

/* Nutrition Card */
.nutrition-card,
.tags-card {
    background: white;
    border-radius: var(--radius-xl);
    padding: 30px;
    margin-bottom: 25px;
    box-shadow: var(--shadow-lg);
    border: 1px solid var(--gray-light);
}

.card-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 25px;
}

.card-header i {
    color: var(--primary);
    font-size: 1.3rem;
}

.card-header h3 {
    color: var(--dark);
    margin: 0;
    font-size: 1.3rem;
}

.nutrition-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
}

.nutrition-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    background: var(--light);
    border-radius: var(--radius-md);
}

.nutrition-label {
    color: var(--gray);
    font-size: 0.9rem;
    font-weight: 500;
}

.nutrition-value {
    color: var(--dark);
    font-weight: 700;
    font-size: 1.1rem;
}

/* Tags */
.tags-list {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.tag {
    padding: 8px 18px;
    background: var(--light);
    color: var(--dark);
    text-decoration: none;
    border-radius: 25px;
    font-size: 0.9rem;
    font-weight: 500;
    transition: var(--transition);
    border: 1px solid transparent;
}

.tag:hover {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
    transform: translateY(-2px);
}

/* Recipe Content */
.recipe-content {
    background: white;
    border-radius: var(--radius-xl);
    overflow: hidden;
    box-shadow: var(--shadow-lg);
    border: 1px solid var(--gray-light);
}

.content-section {
    padding: 40px;
    border-bottom: 1px solid var(--gray-light);
}

.content-section:last-child {
    border-bottom: none;
}

.section-header {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 30px;
}

.header-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, rgba(255, 107, 53, 0.1), rgba(255, 142, 83, 0.1));
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary);
    font-size: 1.5rem;
    flex-shrink: 0;
}

.section-header h2 {
    flex: 1;
    color: var(--dark);
    margin: 0;
    font-size: 1.8rem;
    font-weight: 700;
}

.copy-ingredients-btn {
    padding: 10px 20px;
    background: var(--light);
    color: var(--dark);
    border: 2px solid var(--gray-light);
    border-radius: var(--radius-md);
    font-weight: 500;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: var(--transition);
}

.copy-ingredients-btn:hover {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
    transform: translateY(-2px);
}

/* Recipe Description */
.recipe-description {
    color: var(--gray);
    font-size: 1.1rem;
    line-height: 1.8;
    margin: 0;
}

/* Ingredients */
.ingredients-list {
    margin-bottom: 25px;
}

.ingredient-item {
    padding: 20px;
    margin-bottom: 10px;
    background: var(--light);
    border-radius: var(--radius-lg);
    transition: var(--transition);
}

.ingredient-item:hover {
    background: white;
    box-shadow: var(--shadow-sm);
    transform: translateX(5px);
}

.ingredient-item:last-child {
    margin-bottom: 0;
}

.checkbox-container {
    display: flex;
    align-items: center;
    gap: 15px;
    cursor: pointer;
    user-select: none;
}

.checkbox-container input {
    display: none;
}

.checkmark {
    width: 22px;
    height: 22px;
    border: 2px solid var(--gray-light);
    border-radius: 6px;
    position: relative;
    flex-shrink: 0;
    transition: var(--transition);
}

.checkbox-container input:checked ~ .checkmark {
    background: var(--primary);
    border-color: var(--primary);
}

.checkbox-container input:checked ~ .checkmark::after {
    content: '✓';
    position: absolute;
    color: white;
    font-size: 14px;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

.ingredient-content {
    display: flex;
    align-items: baseline;
    gap: 10px;
    flex-wrap: wrap;
}

.ingredient-quantity {
    font-weight: 700;
    color: var(--dark);
    font-size: 1.1rem;
}

.ingredient-name {
    color: var(--dark);
    font-size: 1.1rem;
}

.ingredient-notes {
    color: var(--gray);
    font-style: italic;
    font-size: 0.9rem;
}

.ingredient-text {
    color: var(--dark);
    font-size: 1.1rem;
}

/* Instructions */
.instructions-timeline {
    position: relative;
}

.instructions-timeline::before {
    content: '';
    position: absolute;
    left: 30px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: var(--gray-light);
}

.timeline-step {
    display: flex;
    gap: 30px;
    margin-bottom: 40px;
    position: relative;
}

.timeline-step:last-child {
    margin-bottom: 0;
}

.step-number {
    width: 60px;
    height: 60px;
    background: var(--gradient);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 800;
    font-size: 1.5rem;
    flex-shrink: 0;
    z-index: 1;
    box-shadow: var(--shadow-md);
}

.step-content {
    flex: 1;
    padding-top: 10px;
}

.step-content h3 {
    color: var(--dark);
    margin: 0 0 15px 0;
    font-size: 1.3rem;
    font-weight: 700;
}

.step-content p {
    color: var(--gray);
    line-height: 1.8;
    margin-bottom: 20px;
}

.step-image {
    border-radius: var(--radius-lg);
    overflow: hidden;
    margin-top: 20px;
    box-shadow: var(--shadow-md);
}

.step-image img {
    width: 100%;
    max-height: 300px;
    object-fit: cover;
    display: block;
}

/* Notes */
.notes-content {
    background: linear-gradient(135deg, rgba(6, 214, 160, 0.1), rgba(6, 214, 160, 0.05));
    border-left: 4px solid var(--success);
    padding: 25px;
    border-radius: var(--radius-lg);
}

.notes-content p {
    color: var(--dark);
    font-size: 1.1rem;
    line-height: 1.7;
    margin: 0;
}

/* Comments Section */
.comment-form {
    display: flex;
    gap: 20px;
    margin-bottom: 40px;
    padding-bottom: 40px;
    border-bottom: 1px solid var(--gray-light);
}

.comment-avatar {
    flex-shrink: 0;
}

.comment-input-wrapper {
    flex: 1;
}

.comment-input-wrapper textarea {
    width: 100%;
    padding: 20px;
    border: 2px solid var(--gray-light);
    border-radius: var(--radius-lg);
    font-family: inherit;
    font-size: 1rem;
    resize: vertical;
    min-height: 120px;
    transition: var(--transition);
    color: var(--dark);
}

.comment-input-wrapper textarea:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(255, 107, 53, 0.1);
}

.comment-input-wrapper textarea::placeholder {
    color: var(--gray);
}

.comment-actions {
    margin-top: 15px;
    text-align: right;
}

.submit-comment-btn {
    padding: 12px 30px;
    background: var(--gradient);
    color: white;
    border: none;
    border-radius: var(--radius-md);
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: var(--transition);
}

.submit-comment-btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.login-prompt {
    text-align: center;
    padding: 40px;
    background: var(--light);
    border-radius: var(--radius-lg);
    margin-bottom: 40px;
}

.login-prompt p {
    color: var(--gray);
    font-size: 1.1rem;
    margin: 0;
}

.login-link,
.register-link {
    color: var(--primary);
    text-decoration: none;
    font-weight: 600;
}

.login-link:hover,
.register-link:hover {
    text-decoration: underline;
}

.no-comments {
    text-align: center;
    padding: 60px 20px;
    color: var(--gray);
}

.no-comments i {
    font-size: 4rem;
    margin-bottom: 20px;
    opacity: 0.2;
}

.no-comments h4 {
    color: var(--dark);
    margin-bottom: 10px;
    font-size: 1.4rem;
}

.no-comments p {
    font-size: 1.1rem;
    margin: 0;
}

/* Related Recipes */
.related-recipes {
    margin: 60px 0 40px;
}

.related-recipes .section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.related-recipes h2 {
    color: var(--dark);
    font-size: 2rem;
    margin: 0;
    font-weight: 800;
}

.view-all {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--primary);
    text-decoration: none;
    font-weight: 600;
    transition: var(--transition);
}

.view-all:hover {
    transform: translateX(5px);
    color: var(--primary-light);
}

.related-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 25px;
}

.related-card {
    background: white;
    border-radius: var(--radius-xl);
    overflow: hidden;
    text-decoration: none;
    box-shadow: var(--shadow-md);
    transition: var(--transition);
    border: 1px solid var(--gray-light);
}

.related-card:hover {
    transform: translateY(-10px);
    box-shadow: var(--shadow-xl);
    border-color: var(--primary-light);
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
    transition: transform 0.6s ease;
}

.related-card:hover .card-image img {
    transform: scale(1.05);
}

.image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to bottom, transparent 50%, rgba(0,0,0,0.3));
}

.card-content {
    padding: 25px;
}

.card-content h3 {
    color: var(--dark);
    margin: 0 0 15px 0;
    font-size: 1.2rem;
    line-height: 1.4;
    font-weight: 700;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.card-meta {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 6px;
    color: var(--gray);
    font-size: 0.9rem;
}

.meta-item i {
    color: var(--primary);
    font-size: 0.9rem;
}

.card-footer {
    padding-top: 20px;
    border-top: 1px solid var(--gray-light);
}

.view-recipe {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: var(--primary);
    font-weight: 600;
    font-size: 0.95rem;
    transition: var(--transition);
}

.related-card:hover .view-recipe {
    transform: translateX(5px);
    color: var(--primary-light);
}

/* Responsive */
@media (max-width: 768px) {
    .recipe-hero {
        min-height: 400px;
        border-radius: 0 0 30px 30px;
    }
    
    .hero-overlay {
        min-height: 400px;
        padding: 40px 20px;
    }
    
    .recipe-main-title {
        font-size: 2.5rem;
    }
    
    .author-section {
        flex-direction: column;
        text-align: center;
        gap: 15px;
    }
    
    .author-stats {
        justify-content: center;
        width: 100%;
    }
    
    .action-card {
        grid-template-columns: repeat(4, 1fr);
    }
    
    .content-section {
        padding: 30px 20px;
    }
    
    .section-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
    
    .instructions-timeline::before {
        left: 25px;
    }
    
    .timeline-step {
        gap: 20px;
    }
    
    .step-number {
        width: 50px;
        height: 50px;
        font-size: 1.2rem;
    }
    
    .comment-form {
        flex-direction: column;
        gap: 15px;
    }
    
    .comment-avatar {
        align-self: flex-start;
    }
    
    .related-recipes .section-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
}

/* Animations */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.content-section {
    animation: fadeIn 0.6s ease-out;
}

/* Print Styles */
@media print {
    .recipe-hero,
    .recipe-sidebar,
    .action-card,
    .comments-section,
    .related-recipes,
    .header,
    .footer {
        display: none !important;
    }
    
    .recipe-main-layout {
        grid-template-columns: 1fr;
        margin: 0;
    }
    
    .recipe-content {
        box-shadow: none;
        border: none;
    }
    
    .content-section {
        padding: 20px 0;
        border-bottom: 1px solid #ddd;
    }
    
    body {
        background: white;
        color: black;
        font-size: 12pt;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Like button functionality
    const likeBtn = document.querySelector('.btn-like');
    if (likeBtn) {
        likeBtn.addEventListener('click', function() {
            const icon = this.querySelector('.action-icon i');
            const recipeId = this.getAttribute('data-recipe-id');
            
            if (this.classList.contains('active')) {
                this.classList.remove('active');
                icon.className = 'far fa-heart';
                // In real app: Send AJAX to unlike
                console.log('Unliked recipe', recipeId);
            } else {
                this.classList.add('active');
                icon.className = 'fas fa-heart';
                // In real app: Send AJAX to like
                console.log('Liked recipe', recipeId);
                showNotification('Recipe liked! ❤️', 'success');
            }
        });
    }
    
    // Save button functionality
    const saveBtn = document.querySelector('.btn-save');
    if (saveBtn) {
        saveBtn.addEventListener('click', function() {
            const icon = this.querySelector('.action-icon i');
            
            if (icon.classList.contains('far')) {
                icon.className = 'fas fa-bookmark';
                showNotification('Recipe saved to your favorites! 🔖', 'success');
            } else {
                icon.className = 'far fa-bookmark';
                showNotification('Recipe removed from favorites', 'info');
            }
        });
    }
    
    // Copy ingredients functionality
    const copyIngredientsBtn = document.querySelector('.copy-ingredients-btn');
    if (copyIngredientsBtn) {
        copyIngredientsBtn.addEventListener('click', function() {
            const ingredients = [];
            document.querySelectorAll('.ingredient-item').forEach(item => {
                const quantity = item.querySelector('.ingredient-quantity')?.textContent || '';
                const name = item.querySelector('.ingredient-name')?.textContent || 
                            item.querySelector('.ingredient-text')?.textContent || '';
                const notes = item.querySelector('.ingredient-notes')?.textContent || '';
                
                let ingredient = quantity ? `${quantity} ` : '';
                ingredient += name;
                ingredient += notes ? ` (${notes})` : '';
                
                ingredients.push(ingredient.trim());
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
                    
                    showNotification('Ingredients copied to clipboard! 📋', 'success');
                })
                .catch(err => {
                    console.error('Failed to copy: ', err);
                    showNotification('Failed to copy ingredients', 'error');
                });
        });
    }
    
    // Check/uncheck ingredients
    document.querySelectorAll('.checkbox-container input').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const ingredientItem = this.closest('.ingredient-item');
            if (this.checked) {
                ingredientItem.style.background = 'linear-gradient(135deg, rgba(6, 214, 160, 0.1), rgba(6, 214, 160, 0.05))';
                ingredientItem.style.borderLeft = '4px solid var(--success)';
            } else {
                ingredientItem.style.background = '';
                ingredientItem.style.borderLeft = '';
            }
        });
    });
    
    // Share functionality
    window.shareRecipe = function() {
        const shareData = {
            title: document.querySelector('.recipe-main-title').textContent,
            text: 'Check out this amazing recipe on FoodFusion!',
            url: window.location.href
        };
        
        if (navigator.share && navigator.canShare(shareData)) {
            navigator.share(shareData)
                .then(() => console.log('Shared successfully'))
                .catch(err => console.log('Error sharing:', err));
        } else {
            navigator.clipboard.writeText(window.location.href)
                .then(() => showNotification('Link copied to clipboard! 🔗', 'success'))
                .catch(err => {
                    console.error('Failed to copy: ', err);
                    showNotification('Failed to copy link', 'error');
                });
        }
    };
    
    // Notification function
    function showNotification(message, type = 'success') {
        // Remove existing notifications
        document.querySelectorAll('.notification').forEach(n => n.remove());
        
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                <span>${message}</span>
            </div>
            <button class="notification-close">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        // Add styles
        Object.assign(notification.style, {
            position: 'fixed',
            top: '30px',
            right: '30px',
            background: type === 'success' ? '#06D6A0' : 
                       type === 'error' ? '#EF476F' : 
                       type === 'info' ? '#2196F3' : '#FFD166',
            color: 'white',
            padding: '20px 25px',
            borderRadius: 'var(--radius-lg)',
            boxShadow: 'var(--shadow-xl)',
            zIndex: '9999',
            display: 'flex',
            alignItems: 'center',
            gap: '15px',
            animation: 'slideInRight 0.4s ease',
            backdropFilter: 'blur(10px)',
            border: '1px solid rgba(255, 255, 255, 0.2)',
            maxWidth: '400px',
            fontWeight: '500'
        });
        
        document.body.appendChild(notification);
        
        // Add animation styles
        const style = document.createElement('style');
        if (!document.querySelector('#notification-animations')) {
            style.id = 'notification-animations';
            style.textContent = `
                @keyframes slideInRight {
                    from { transform: translateX(100%); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
                @keyframes slideOutRight {
                    from { transform: translateX(0); opacity: 1; }
                    to { transform: translateX(100%); opacity: 0; }
                }
                @keyframes fadeIn {
                    from { opacity: 0; transform: translateY(-20px); }
                    to { opacity: 1; transform: translateY(0); }
                }
            `;
            document.head.appendChild(style);
        }
        
        // Auto remove after 4 seconds
        const removeTimer = setTimeout(() => {
            notification.style.animation = 'slideOutRight 0.4s ease';
            setTimeout(() => notification.remove(), 400);
        }, 4000);
        
        // Close button
        notification.querySelector('.notification-close').addEventListener('click', () => {
            clearTimeout(removeTimer);
            notification.style.animation = 'slideOutRight 0.4s ease';
            setTimeout(() => notification.remove(), 400);
        });
        
        // Hover to pause auto-remove
        notification.addEventListener('mouseenter', () => {
            clearTimeout(removeTimer);
        });
        
        notification.addEventListener('mouseleave', () => {
            setTimeout(() => {
                notification.style.animation = 'slideOutRight 0.4s ease';
                setTimeout(() => notification.remove(), 400);
            }, 2000);
        });
    }
    
    // Lazy loading images
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    const src = img.getAttribute('data-src') || img.src;
                    
                    // Load image
                    const imageLoader = new Image();
                    imageLoader.src = src;
                    imageLoader.onload = () => {
                        img.src = src;
                        img.classList.add('loaded');
                    };
                    
                    observer.unobserve(img);
                }
            });
        }, { 
            rootMargin: '100px 0px',
            threshold: 0.1
        });
        
        // Observe all images
        document.querySelectorAll('img[loading="lazy"]').forEach(img => {
            if (!img.classList.contains('loaded')) {
                imageObserver.observe(img);
            }
        });
    }
    
    // Add parallax effect to hero background
    window.addEventListener('scroll', () => {
        const hero = document.querySelector('.hero-background');
        if (hero) {
            const scrolled = window.pageYOffset;
            const rate = scrolled * -0.5;
            hero.style.transform = `translate3d(0px, ${rate}px, 0px) scale(1.1)`;
        }
    });
    
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 100,
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // Add hover effects to recipe cards
    document.querySelectorAll('.related-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-10px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
    
    // Initialize tooltips for action buttons
    const tooltipStyle = document.createElement('style');
    tooltipStyle.textContent = `
        .action-btn {
            position: relative;
        }
        
        .action-btn::after {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: var(--dark);
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.85rem;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            pointer-events: none;
            z-index: 1000;
        }
        
        .action-btn::before {
            content: '';
            position: absolute;
            bottom: calc(100% - 5px);
            left: 50%;
            transform: translateX(-50%);
            border-width: 5px;
            border-style: solid;
            border-color: var(--dark) transparent transparent transparent;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            pointer-events: none;
            z-index: 1000;
        }
        
        .action-btn:hover::after,
        .action-btn:hover::before {
            opacity: 1;
            visibility: visible;
        }
    `;
    document.head.appendChild(tooltipStyle);
    
    // Add tooltips to action buttons
    document.querySelectorAll('.action-btn').forEach(btn => {
        const text = btn.querySelector('.action-text').textContent;
        btn.setAttribute('data-tooltip', text);
    });
});
</script>

<?php
// Helper function to check if user liked the recipe
function checkIfLiked($recipe_id, $user_id) {
    // In real app: Check database
    // For demo purposes, return false
    return false;
}
?>

<?php include 'includes/footer.php'; ?>