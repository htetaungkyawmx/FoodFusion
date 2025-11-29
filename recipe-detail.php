<?php
include 'includes/header.php';
include 'includes/auth.php';
include 'includes/functions.php';

// Get recipe ID from URL
$recipe_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($recipe_id <= 0) {
    setFlashMessage('error', 'Invalid recipe ID.');
    redirect('recipes.php');
}

$db = getDBConnection();

// Get recipe details
$query = "SELECT r.*, u.first_name, u.last_name, u.profile_image, c.name as category_name
          FROM recipes r 
          JOIN users u ON r.user_id = u.id 
          LEFT JOIN categories c ON r.category_id = c.id 
          WHERE r.id = ?";
$stmt = $db->prepare($query);
$stmt->execute([$recipe_id]);
$recipe = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$recipe) {
    setFlashMessage('error', 'Recipe not found.');
    redirect('recipes.php');
}

// Increment view count
$update_query = "UPDATE recipes SET views = views + 1 WHERE id = ?";
$update_stmt = $db->prepare($update_query);
$update_stmt->execute([$recipe_id]);

// Get recipe rating
$rating = calculateRecipeRating($recipe_id);

// Get recipe reviews
$reviews_query = "SELECT rr.*, u.first_name, u.last_name, u.profile_image 
                  FROM recipe_reviews rr 
                  JOIN users u ON rr.user_id = u.id 
                  WHERE rr.recipe_id = ? AND rr.rating IS NOT NULL 
                  ORDER BY rr.created_at DESC 
                  LIMIT 10";
$reviews_stmt = $db->prepare($reviews_query);
$reviews_stmt->execute([$recipe_id]);
$reviews = $reviews_stmt->fetchAll(PDO::FETCH_ASSOC);

// Get similar recipes
$similar_query = "SELECT r.*, u.first_name, u.last_name 
                  FROM recipes r 
                  JOIN users u ON r.user_id = u.id 
                  WHERE r.cuisine_type = ? AND r.id != ? 
                  ORDER BY RAND() 
                  LIMIT 3";
$similar_stmt = $db->prepare($similar_query);
$similar_stmt->execute([$recipe['cuisine_type'], $recipe_id]);
$similar_recipes = $similar_stmt->fetchAll(PDO::FETCH_ASSOC);

$page_title = $recipe['title'] . " - FoodFusion";

// Handle review submission
if ($_POST && isset($_POST['submit_review']) && isLoggedIn()) {
    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
    $comment = trim($_POST['comment']);
    $csrf_token = $_POST['csrf_token'] ?? '';
    
    if (validateCSRFToken($csrf_token) && $rating >= 1 && $rating <= 5) {
        // Check if user already reviewed this recipe
        $check_query = "SELECT id FROM recipe_reviews WHERE recipe_id = ? AND user_id = ?";
        $check_stmt = $db->prepare($check_query);
        $check_stmt->execute([$recipe_id, $_SESSION['user_id']]);
        
        if ($check_stmt->rowCount() > 0) {
            // Update existing review
            $update_query = "UPDATE recipe_reviews SET rating = ?, comment = ? WHERE recipe_id = ? AND user_id = ?";
            $update_stmt = $db->prepare($update_query);
            $update_stmt->execute([$rating, $comment, $recipe_id, $_SESSION['user_id']]);
            setFlashMessage('success', 'Review updated successfully!');
        } else {
            // Insert new review
            $insert_query = "INSERT INTO recipe_reviews (recipe_id, user_id, rating, comment) VALUES (?, ?, ?, ?)";
            $insert_stmt = $db->prepare($insert_query);
            $insert_stmt->execute([$recipe_id, $_SESSION['user_id'], $rating, $comment]);
            setFlashMessage('success', 'Review submitted successfully!');
        }
        
        redirect("recipe-detail.php?id=$recipe_id");
    } else {
        setFlashMessage('error', 'Please provide a valid rating (1-5 stars).');
    }
}
?>

<div class="container">
    <!-- Recipe Header -->
    <div class="recipe-header">
        <div class="recipe-image">
            <img src="assets/images/recipes/<?php echo $recipe['featured_image'] ?: 'default-recipe.jpg'; ?>" 
                 alt="<?php echo htmlspecialchars($recipe['title']); ?>">
        </div>
        
        <div class="recipe-info">
            <div class="recipe-meta-badge">
                <span class="badge <?php echo strtolower($recipe['difficulty_level']); ?>">
                    <?php echo $recipe['difficulty_level']; ?>
                </span>
                <span class="badge cuisine"><?php echo $recipe['cuisine_type']; ?></span>
                <span class="badge diet"><?php echo $recipe['dietary_preference']; ?></span>
            </div>
            
            <h1><?php echo htmlspecialchars($recipe['title']); ?></h1>
            <p class="recipe-description"><?php echo htmlspecialchars($recipe['description']); ?></p>
            
            <div class="recipe-stats">
                <div class="stat">
                    <i class="fas fa-clock"></i>
                    <span><?php echo $recipe['cooking_time']; ?> min</span>
                </div>
                <div class="stat">
                    <i class="fas fa-users"></i>
                    <span><?php echo $recipe['servings']; ?> servings</span>
                </div>
                <div class="stat">
                    <i class="fas fa-eye"></i>
                    <span><?php echo $recipe['views']; ?> views</span>
                </div>
                <div class="stat">
                    <i class="fas fa-star"></i>
                    <span><?php echo $rating['average']; ?> (<?php echo $rating['total']; ?> reviews)</span>
                </div>
            </div>
            
            <div class="recipe-author">
                <div class="author-avatar">
                    <?php if ($recipe['profile_image']): ?>
                        <img src="assets/uploads/profiles/<?php echo $recipe['profile_image']; ?>" 
                             alt="<?php echo htmlspecialchars($recipe['first_name'] . ' ' . $recipe['last_name']); ?>">
                    <?php else: ?>
                        <div class="avatar-placeholder-sm">
                            <i class="fas fa-user"></i>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="author-info">
                    <span class="author-name">By <?php echo htmlspecialchars($recipe['first_name'] . ' ' . $recipe['last_name']); ?></span>
                    <span class="recipe-date">Posted on <?php echo formatDate($recipe['created_at']); ?></span>
                </div>
            </div>
            
            <div class="recipe-actions">
                <button class="btn btn-primary">
                    <i class="fas fa-print"></i> Print Recipe
                </button>
                <button class="btn btn-outline">
                    <i class="far fa-heart"></i> Save Recipe
                </button>
                <button class="btn btn-outline">
                    <i class="fas fa-share-alt"></i> Share
                </button>
            </div>
        </div>
    </div>
    
    <!-- Recipe Content -->
    <div class="recipe-content">
        <div class="ingredients-section">
            <h2>Ingredients</h2>
            <div class="ingredients-list">
                <?php
                $ingredients = explode("\n", $recipe['ingredients']);
                foreach ($ingredients as $ingredient) {
                    if (trim($ingredient)) {
                        echo '<div class="ingredient-item">';
                        echo '<input type="checkbox" id="ing' . uniqid() . '">';
                        echo '<label for="ing' . uniqid() . '">' . htmlspecialchars(trim($ingredient)) . '</label>';
                        echo '</div>';
                    }
                }
                ?>
            </div>
        </div>
        
        <div class="instructions-section">
            <h2>Instructions</h2>
            <div class="instructions-list">
                <?php
                $instructions = explode("\n", $recipe['instructions']);
                $step = 1;
                foreach ($instructions as $instruction) {
                    if (trim($instruction)) {
                        echo '<div class="instruction-step">';
                        echo '<div class="step-number">' . $step . '</div>';
                        echo '<div class="step-content">' . nl2br(htmlspecialchars(trim($instruction))) . '</div>';
                        echo '</div>';
                        $step++;
                    }
                }
                ?>
            </div>
        </div>
    </div>
    
    <!-- Reviews Section -->
    <div class="reviews-section">
        <div class="reviews-header">
            <h2>Reviews & Ratings</h2>
            <div class="rating-summary">
                <div class="average-rating">
                    <span class="rating-number"><?php echo $rating['average']; ?></span>
                    <div class="stars">
                        <?php
                        $full_stars = floor($rating['average']);
                        $half_star = ($rating['average'] - $full_stars) >= 0.5;
                        $empty_stars = 5 - $full_stars - ($half_star ? 1 : 0);
                        
                        for ($i = 0; $i < $full_stars; $i++) {
                            echo '<i class="fas fa-star"></i>';
                        }
                        if ($half_star) {
                            echo '<i class="fas fa-star-half-alt"></i>';
                        }
                        for ($i = 0; $i < $empty_stars; $i++) {
                            echo '<i class="far fa-star"></i>';
                        }
                        ?>
                    </div>
                    <span class="rating-count"><?php echo $rating['total']; ?> reviews</span>
                </div>
            </div>
        </div>
        
        <!-- Add Review Form -->
        <?php if (isLoggedIn()): ?>
            <div class="add-review-form">
                <h3>Add Your Review</h3>
                <form method="POST" action="">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                    
                    <div class="form-group">
                        <label class="form-label">Your Rating</label>
                        <div class="star-rating">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <input type="radio" id="star<?php echo $i; ?>" name="rating" value="<?php echo $i; ?>">
                                <label for="star<?php echo $i; ?>"><i class="fas fa-star"></i></label>
                            <?php endfor; ?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="comment" class="form-label">Your Review (Optional)</label>
                        <textarea id="comment" name="comment" class="form-control" rows="4" 
                                  placeholder="Share your thoughts about this recipe..."></textarea>
                    </div>
                    
                    <button type="submit" name="submit_review" class="btn btn-primary">Submit Review</button>
                </form>
            </div>
        <?php else: ?>
            <div class="login-prompt">
                <p>Please <a href="login.php">login</a> to leave a review.</p>
            </div>
        <?php endif; ?>
        
        <!-- Reviews List -->
        <div class="reviews-list">
            <?php if (!empty($reviews)): ?>
                <?php foreach ($reviews as $review): ?>
                    <div class="review-item">
                        <div class="review-header">
                            <div class="reviewer-info">
                                <div class="reviewer-avatar">
                                    <?php if ($review['profile_image']): ?>
                                        <img src="assets/uploads/profiles/<?php echo $review['profile_image']; ?>" 
                                             alt="<?php echo htmlspecialchars($review['first_name'] . ' ' . $review['last_name']); ?>">
                                    <?php else: ?>
                                        <div class="avatar-placeholder-xs">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <span class="reviewer-name"><?php echo htmlspecialchars($review['first_name'] . ' ' . $review['last_name']); ?></span>
                                    <span class="review-date"><?php echo formatDate($review['created_at']); ?></span>
                                </div>
                            </div>
                            <div class="review-rating">
                                <?php
                                for ($i = 1; $i <= 5; $i++) {
                                    if ($i <= $review['rating']) {
                                        echo '<i class="fas fa-star"></i>';
                                    } else {
                                        echo '<i class="far fa-star"></i>';
                                    }
                                }
                                ?>
                            </div>
                        </div>
                        
                        <?php if ($review['comment']): ?>
                            <div class="review-comment">
                                <?php echo nl2br(htmlspecialchars($review['comment'])); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-reviews">
                    <p>No reviews yet. Be the first to review this recipe!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Similar Recipes -->
    <?php if (!empty($similar_recipes)): ?>
        <div class="similar-recipes">
            <h2>You Might Also Like</h2>
            <div class="recipe-grid">
                <?php foreach ($similar_recipes as $similar_recipe): ?>
                    <div class="recipe-card">
                        <div class="recipe-image">
                            <img src="assets/images/recipes/<?php echo $similar_recipe['featured_image'] ?: 'default-recipe.jpg'; ?>" 
                                 alt="<?php echo htmlspecialchars($similar_recipe['title']); ?>">
                        </div>
                        <div class="recipe-content">
                            <h3 class="recipe-title"><?php echo htmlspecialchars($similar_recipe['title']); ?></h3>
                            <div class="recipe-meta">
                                <span><i class="fas fa-clock"></i> <?php echo $similar_recipe['cooking_time']; ?> min</span>
                                <span><i class="fas fa-user"></i> <?php echo htmlspecialchars($similar_recipe['first_name']); ?></span>
                            </div>
                            <p class="recipe-description"><?php echo htmlspecialchars(substr($similar_recipe['description'], 0, 100)); ?>...</p>
                            <div class="recipe-actions">
                                <a href="recipe-detail.php?id=<?php echo $similar_recipe['id']; ?>" class="btn btn-primary btn-sm">View Recipe</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
.recipe-header {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3rem;
    margin-bottom: 3rem;
}

.recipe-image {
    border-radius: var(--border-radius);
    overflow: hidden;
    box-shadow: var(--shadow-lg);
}

.recipe-image img {
    width: 100%;
    height: 400px;
    object-fit: cover;
}

.recipe-meta-badge {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1rem;
    flex-wrap: wrap;
}

.badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
    text-transform: capitalize;
}

.badge.easy { background: #27ae60; color: white; }
.badge.medium { background: #f39c12; color: white; }
.badge.hard { background: #e74c3c; color: white; }
.badge.cuisine { background: #3498db; color: white; }
.badge.diet { background: #9b59b6; color: white; }

.recipe-stats {
    display: flex;
    gap: 2rem;
    margin: 1.5rem 0;
    padding: 1.5rem 0;
    border-top: 1px solid var(--border-color);
    border-bottom: 1px solid var(--border-color);
}

.stat {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--gray-color);
}

.stat i {
    color: var(--primary-color);
}

.recipe-author {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.author-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    overflow: hidden;
}

.author-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-placeholder-sm {
    width: 100%;
    height: 100%;
    background: var(--light-gray);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--gray-color);
}

.avatar-placeholder-xs {
    width: 100%;
    height: 100%;
    background: var(--light-gray);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--gray-color);
    font-size: 0.8rem;
}

.author-info {
    display: flex;
    flex-direction: column;
}

.author-name {
    font-weight: 500;
    color: var(--dark-color);
}

.recipe-date {
    font-size: 0.9rem;
    color: var(--gray-color);
}

.recipe-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.recipe-content {
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: 3rem;
    margin-bottom: 3rem;
}

.ingredients-section,
.instructions-section {
    background: white;
    padding: 2rem;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
}

.ingredients-list {
    margin-top: 1rem;
}

.ingredient-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.5rem 0;
    border-bottom: 1px solid var(--border-color);
}

.ingredient-item:last-child {
    border-bottom: none;
}

.ingredient-item input[type="checkbox"] {
    width: 18px;
    height: 18px;
}

.ingredient-item label {
    cursor: pointer;
    flex: 1;
}

.instructions-list {
    margin-top: 1rem;
}

.instruction-step {
    display: flex;
    gap: 1rem;
    margin-bottom: 1.5rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid var(--border-color);
}

.instruction-step:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.step-number {
    width: 40px;
    height: 40px;
    background: var(--primary-color);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    flex-shrink: 0;
}

.step-content {
    flex: 1;
    line-height: 1.6;
}

.reviews-section {
    background: white;
    padding: 2rem;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
    margin-bottom: 3rem;
}

.reviews-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid var(--light-gray);
}

.rating-summary {
    text-align: center;
}

.average-rating {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
}

.rating-number {
    font-size: 3rem;
    font-weight: bold;
    color: var(--primary-color);
}

.stars {
    color: #f39c12;
}

.rating-count {
    color: var(--gray-color);
    font-size: 0.9rem;
}

.add-review-form {
    background: var(--light-gray);
    padding: 1.5rem;
    border-radius: var(--border-radius);
    margin-bottom: 2rem;
}

.star-rating {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
    gap: 0.25rem;
}

.star-rating input {
    display: none;
}

.star-rating label {
    cursor: pointer;
    color: #ddd;
    font-size: 1.5rem;
    transition: var(--transition);
}

.star-rating label:hover,
.star-rating label:hover ~ label,
.star-rating input:checked ~ label {
    color: #f39c12;
}

.reviews-list {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.review-item {
    padding: 1.5rem;
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
}

.review-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.reviewer-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.reviewer-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    overflow: hidden;
}

.reviewer-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.reviewer-name {
    font-weight: 500;
    color: var(--dark-color);
}

.review-date {
    font-size: 0.9rem;
    color: var(--gray-color);
}

.review-rating {
    color: #f39c12;
}

.review-comment {
    line-height: 1.6;
    color: #555;
}

.login-prompt {
    text-align: center;
    padding: 2rem;
    background: var(--light-gray);
    border-radius: var(--border-radius);
    margin-bottom: 2rem;
}

.no-reviews {
    text-align: center;
    padding: 3rem;
    color: var(--gray-color);
}

.similar-recipes {
    margin-bottom: 3rem;
}

.similar-recipes h2 {
    margin-bottom: 2rem;
    text-align: center;
}

@media (max-width: 768px) {
    .recipe-header {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .recipe-content {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .recipe-stats {
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .reviews-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .review-header {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }
}
</style>

<?php include 'includes/footer.php'; ?>