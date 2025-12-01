<?php
$page_title = "FoodFusion - Home";
include 'includes/header.php';
include 'includes/functions.php';

$featured_recipes = getFeaturedRecipes();
?>

<section class="hero">
    <div class="container">
        <h1>Welcome to FoodFusion</h1>
        <p>Discover, Share, and Enjoy Amazing Recipes</p>
        <a href="recipes.php" class="btn">Explore Recipes</a>
        <a href="register.php" class="btn btn-outline">Join Community</a>
    </div>
</section>

<section class="container main-content">
    <h2>Featured Recipes</h2>
    
    <div class="recipe-grid">
        <?php if ($featured_recipes): ?>
            <?php foreach ($featured_recipes as $recipe): ?>
                <div class="recipe-card">
                    <div class="recipe-image">
                        <img src="<?php echo $recipe['featured_image'] ?: 'assets/images/default-recipe.jpg'; ?>" alt="<?php echo htmlspecialchars($recipe['title']); ?>">
                    </div>
                    <div class="recipe-content">
                        <h3 class="recipe-title"><?php echo htmlspecialchars($recipe['title']); ?></h3>
                        <div class="recipe-meta">
                            <span><i class="fas fa-clock"></i> <?php echo $recipe['cooking_time']; ?> min</span>
                            <span><i class="fas fa-user"></i> <?php echo $recipe['servings']; ?> servings</span>
                        </div>
                        <p><?php echo htmlspecialchars(substr($recipe['description'], 0, 100)); ?>...</p>
                        <a href="recipe-detail.php?id=<?php echo $recipe['id']; ?>" class="btn">View Recipe</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No featured recipes available.</p>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>