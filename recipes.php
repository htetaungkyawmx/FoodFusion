<?php
$page_title = "Recipes - FoodFusion";
include 'includes/header.php';
include 'includes/functions.php';

// Get filters
$filters = [];
if (isset($_GET['cuisine'])) $filters['cuisine'] = sanitizeInput($_GET['cuisine']);
if (isset($_GET['difficulty'])) $filters['difficulty'] = sanitizeInput($_GET['difficulty']);

$recipes = getAllRecipes($filters);
?>

<div class="container main-content">
    <h1>Recipe Collection</h1>
    
    <!-- Filters -->
    <div class="filters" style="margin-bottom: 2rem;">
        <form method="GET" action="">
            <select name="cuisine" class="form-control" style="display: inline-block; width: auto;">
                <option value="">All Cuisines</option>
                <option value="Italian" <?php echo ($filters['cuisine'] ?? '') == 'Italian' ? 'selected' : ''; ?>>Italian</option>
                <option value="Asian" <?php echo ($filters['cuisine'] ?? '') == 'Asian' ? 'selected' : ''; ?>>Asian</option>
                <option value="Mexican" <?php echo ($filters['cuisine'] ?? '') == 'Mexican' ? 'selected' : ''; ?>>Mexican</option>
            </select>
            
            <select name="difficulty" class="form-control" style="display: inline-block; width: auto;">
                <option value="">All Levels</option>
                <option value="Easy" <?php echo ($filters['difficulty'] ?? '') == 'Easy' ? 'selected' : ''; ?>>Easy</option>
                <option value="Medium" <?php echo ($filters['difficulty'] ?? '') == 'Medium' ? 'selected' : ''; ?>>Medium</option>
                <option value="Hard" <?php echo ($filters['difficulty'] ?? '') == 'Hard' ? 'selected' : ''; ?>>Hard</option>
            </select>
            
            <button type="submit" class="btn">Filter</button>
            <a href="recipes.php" class="btn btn-outline">Clear</a>
        </form>
    </div>
    
    <!-- Recipe Grid -->
    <div class="recipe-grid">
        <?php if ($recipes): ?>
            <?php foreach ($recipes as $recipe): ?>
                <div class="recipe-card">
                    <div class="recipe-image">
                        <img src="<?php echo $recipe['featured_image'] ?: 'assets/images/default-recipe.jpg'; ?>" alt="<?php echo htmlspecialchars($recipe['title']); ?>">
                    </div>
                    <div class="recipe-content">
                        <h3 class="recipe-title"><?php echo htmlspecialchars($recipe['title']); ?></h3>
                        <div class="recipe-meta">
                            <span><i class="fas fa-clock"></i> <?php echo $recipe['cooking_time']; ?> min</span>
                            <span><i class="fas fa-user"></i> <?php echo $recipe['servings']; ?> servings</span>
                            <span><i class="fas fa-fire"></i> <?php echo $recipe['difficulty_level']; ?></span>
                        </div>
                        <p><?php echo htmlspecialchars(substr($recipe['description'], 0, 100)); ?>...</p>
                        <a href="recipe-detail.php?id=<?php echo $recipe['id']; ?>" class="btn">View Recipe</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No recipes found.</p>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>