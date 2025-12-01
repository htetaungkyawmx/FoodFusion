<?php
$page_title = "Recipe Details - FoodFusion";
include 'includes/header.php';
include 'config/database.php';

$recipe_id = $_GET['id'] ?? 0;

$database = new Database();
$db = $database->getConnection();

// Get recipe details
$query = "SELECT r.*, u.first_name, u.last_name FROM recipes r 
          JOIN users u ON r.user_id = u.id 
          WHERE r.id = ?";
$stmt = $db->prepare($query);
$stmt->execute([$recipe_id]);
$recipe = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$recipe) {
    echo '<div class="container main-content"><h2>Recipe not found</h2></div>';
    include 'includes/footer.php';
    exit;
}

// Increment view count
$update_query = "UPDATE recipes SET views = views + 1 WHERE id = ?";
$update_stmt = $db->prepare($update_query);
$update_stmt->execute([$recipe_id]);

$page_title = $recipe['title'] . " - FoodFusion";
?>

<div class="container main-content">
    <div class="recipe-detail">
        <div class="recipe-header">
            <h1><?php echo htmlspecialchars($recipe['title']); ?></h1>
            <div class="recipe-meta">
                <span><i class="fas fa-user"></i> By <?php echo htmlspecialchars($recipe['first_name'] . ' ' . $recipe['last_name']); ?></span>
                <span><i class="fas fa-clock"></i> <?php echo $recipe['cooking_time']; ?> minutes</span>
                <span><i class="fas fa-users"></i> <?php echo $recipe['servings']; ?> servings</span>
                <span><i class="fas fa-fire"></i> <?php echo $recipe['difficulty_level']; ?></span>
                <span><i class="fas fa-globe"></i> <?php echo $recipe['cuisine_type']; ?></span>
            </div>
        </div>

        <div class="recipe-image-large">
            <img src="<?php echo $recipe['featured_image'] ?: 'assets/images/default-recipe.jpg'; ?>" 
                 alt="<?php echo htmlspecialchars($recipe['title']); ?>">
        </div>

        <div class="recipe-content-grid">
            <div class="ingredients-section">
                <h2><i class="fas fa-list"></i> Ingredients</h2>
                <ul class="ingredients-list">
                    <?php
                    $ingredients = explode("\n", $recipe['ingredients']);
                    foreach ($ingredients as $ingredient):
                        if (trim($ingredient)):
                    ?>
                    <li><?php echo htmlspecialchars(trim($ingredient)); ?></li>
                    <?php
                        endif;
                    endforeach;
                    ?>
                </ul>
            </div>

            <div class="instructions-section">
                <h2><i class="fas fa-book-open"></i> Instructions</h2>
                <ol class="instructions-list">
                    <?php
                    $instructions = explode("\n", $recipe['instructions']);
                    $step = 1;
                    foreach ($instructions as $instruction):
                        if (trim($instruction)):
                    ?>
                    <li>
                        <h3>Step <?php echo $step; ?></h3>
                        <p><?php echo htmlspecialchars(trim($instruction)); ?></p>
                    </li>
                    <?php
                            $step++;
                        endif;
                    endforeach;
                    ?>
                </ol>
            </div>
        </div>

        <div class="recipe-info">
            <div class="info-box">
                <h3><i class="fas fa-info-circle"></i> Recipe Information</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <strong>Dietary Preference:</strong>
                        <span><?php echo $recipe['dietary_preference']; ?></span>
                    </div>
                    <div class="info-item">
                        <strong>Created:</strong>
                        <span><?php echo date('F j, Y', strtotime($recipe['created_at'])); ?></span>
                    </div>
                    <div class="info-item">
                        <strong>Views:</strong>
                        <span><?php echo $recipe['views']; ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="recipe-actions">
            <button onclick="window.print()" class="btn btn-outline">
                <i class="fas fa-print"></i> Print Recipe
            </button>
            <button class="btn btn-outline" id="saveRecipeBtn">
                <i class="far fa-heart"></i> Save Recipe
            </button>
            <button class="btn btn-outline" onclick="shareRecipe()">
                <i class="fas fa-share-alt"></i> Share
            </button>
        </div>
    </div>
</div>

<script>
function shareRecipe() {
    if (navigator.share) {
        navigator.share({
            title: '<?php echo addslashes($recipe['title']); ?>',
            text: 'Check out this amazing recipe on FoodFusion!',
            url: window.location.href
        });
    } else {
        navigator.clipboard.writeText(window.location.href);
        alert('Link copied to clipboard!');
    }
}

document.getElementById('saveRecipeBtn').addEventListener('click', function() {
    const icon = this.querySelector('i');
    if (icon.classList.contains('far')) {
        icon.classList.remove('far');
        icon.classList.add('fas');
        this.innerHTML = '<i class="fas fa-heart"></i> Saved';
        alert('Recipe saved to your favorites!');
    } else {
        icon.classList.remove('fas');
        icon.classList.add('far');
        this.innerHTML = '<i class="far fa-heart"></i> Save Recipe';
        alert('Recipe removed from favorites!');
    }
});
</script>

<style>
.recipe-detail {
    background: white;
    border-radius: 10px;
    padding: 2rem;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.recipe-header {
    margin-bottom: 2rem;
}

.recipe-header h1 {
    color: #333;
    margin-bottom: 1rem;
}

.recipe-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 1.5rem;
    color: #666;
    font-size: 0.9rem;
}

.recipe-meta i {
    color: #e74c3c;
    margin-right: 0.5rem;
}

.recipe-image-large {
    width: 100%;
    max-height: 500px;
    overflow: hidden;
    border-radius: 10px;
    margin-bottom: 2rem;
}

.recipe-image-large img {
    width: 100%;
    height: auto;
    object-fit: cover;
}

.recipe-content-grid {
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: 3rem;
    margin-bottom: 2rem;
}

@media (max-width: 768px) {
    .recipe-content-grid {
        grid-template-columns: 1fr;
    }
}

.ingredients-section, .instructions-section {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 10px;
}

.ingredients-section h2, .instructions-section h2 {
    color: #333;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.ingredients-list {
    list-style: none;
    padding: 0;
}

.ingredients-list li {
    padding: 0.5rem 0;
    border-bottom: 1px solid #ddd;
    display: flex;
    align-items: center;
}

.ingredients-list li:last-child {
    border-bottom: none;
}

.ingredients-list li:before {
    content: "•";
    color: #e74c3c;
    font-weight: bold;
    margin-right: 0.5rem;
}

.instructions-list {
    list-style: none;
    padding: 0;
    counter-reset: step-counter;
}

.instructions-list li {
    counter-increment: step-counter;
    margin-bottom: 1.5rem;
    padding-left: 2rem;
    position: relative;
}

.instructions-list li:before {
    content: counter(step-counter);
    position: absolute;
    left: 0;
    top: 0;
    background: #e74c3c;
    color: white;
    width: 25px;
    height: 25px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
}

.instructions-list h3 {
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
    color: #333;
}

.recipe-info {
    margin-bottom: 2rem;
}

.info-box {
    background: #f0f7ff;
    border-left: 4px solid #3498db;
    padding: 1.5rem;
    border-radius: 5px;
}

.info-box h3 {
    color: #2c3e50;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid #ddd;
}

.info-item:last-child {
    border-bottom: none;
}

.recipe-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}
</style>

<?php include 'includes/footer.php'; ?>