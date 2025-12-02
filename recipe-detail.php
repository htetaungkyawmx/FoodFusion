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

// Get recipe details
$query = "SELECT r.*, u.first_name, u.last_name 
          FROM recipes r 
          LEFT JOIN users u ON r.user_id = u.id 
          WHERE r.id = ?";
$stmt = $db->prepare($query);
$stmt->execute([$recipe_id]);
$recipe = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$recipe) {
    echo '<div class="container">
            <div class="recipe-not-found">
                <i class="fas fa-utensils"></i>
                <h2>Recipe Not Found</h2>
                <p>The recipe you\'re looking for doesn\'t exist.</p>
                <a href="recipes.php" class="btn-primary">Browse Recipes</a>
            </div>
          </div>';
    include 'includes/footer.php';
    exit();
}

// Update view count
$update_query = "UPDATE recipes SET views = views + 1 WHERE id = ?";
$update_stmt = $db->prepare($update_query);
$update_stmt->execute([$recipe_id]);
?>

<div class="container">
    <!-- Recipe Header -->
    <div class="recipe-header">
        <div class="breadcrumb">
            <a href="index.php"><i class="fas fa-home"></i> Home</a> / 
            <a href="recipes.php">Recipes</a> / 
            <span><?php echo htmlspecialchars($recipe['title']); ?></span>
        </div>
        
        <h1 class="recipe-title"><?php echo htmlspecialchars($recipe['title']); ?></h1>
        
        <div class="recipe-meta">
            <div class="meta-item">
                <i class="fas fa-clock"></i>
                <span><?php echo $recipe['cooking_time'] ?? '--'; ?> minutes</span>
            </div>
            <div class="meta-item">
                <i class="fas fa-users"></i>
                <span><?php echo $recipe['servings'] ?? '--'; ?> servings</span>
            </div>
            <div class="meta-item">
                <i class="fas fa-signal"></i>
                <span><?php echo $recipe['difficulty_level'] ?? 'Medium'; ?></span>
            </div>
            <div class="meta-item">
                <i class="fas fa-user"></i>
                <span><?php echo htmlspecialchars($recipe['first_name'] ?? 'Unknown'); ?></span>
            </div>
        </div>
    </div>

    <!-- Recipe Image -->
    <div class="recipe-image">
        <?php if (!empty($recipe['featured_image'])): ?>
            <img src="<?php echo htmlspecialchars($recipe['featured_image']); ?>" 
                 alt="<?php echo htmlspecialchars($recipe['title']); ?>">
        <?php else: ?>
            <img src="assets/images/default-recipe.jpg" 
                 alt="<?php echo htmlspecialchars($recipe['title']); ?>">
        <?php endif; ?>
    </div>

    <!-- Recipe Content -->
    <div class="recipe-content">
        <!-- Quick Actions -->
        <div class="quick-actions">
            <button class="action-btn" onclick="printRecipe()">
                <i class="fas fa-print"></i> Print
            </button>
            <button class="action-btn" onclick="saveRecipe()">
                <i class="far fa-bookmark"></i> Save
            </button>
            <button class="action-btn" onclick="shareRecipe()">
                <i class="fas fa-share-alt"></i> Share
            </button>
        </div>

        <!-- Description -->
        <section class="section">
            <h2><i class="fas fa-info-circle"></i> Description</h2>
            <p class="description"><?php echo nl2br(htmlspecialchars($recipe['description'])); ?></p>
        </section>

        <!-- Ingredients & Instructions Side by Side -->
        <div class="ingredients-instructions">
            <!-- Ingredients -->
            <section class="section ingredients-section">
                <div class="section-header">
                    <h2><i class="fas fa-shopping-basket"></i> Ingredients</h2>
                    <button class="copy-btn" onclick="copyIngredients()">
                        <i class="fas fa-copy"></i> Copy
                    </button>
                </div>
                
                <div class="ingredients-list">
                    <?php
                    $ingredients = $recipe['ingredients'] ?? '';
                    if (is_string($ingredients) && json_decode($ingredients) !== null) {
                        $ingredients = json_decode($ingredients, true);
                        if (is_array($ingredients)) {
                            foreach ($ingredients as $ing):
                                if (is_array($ing)):
                    ?>
                    <div class="ingredient-item">
                        <input type="checkbox" id="ing-<?php echo $ing['id'] ?? rand(); ?>">
                        <label for="ing-<?php echo $ing['id'] ?? rand(); ?>">
                            <span class="quantity"><?php echo htmlspecialchars($ing['quantity'] ?? ''); ?></span>
                            <span class="name"><?php echo htmlspecialchars($ing['name'] ?? ''); ?></span>
                        </label>
                    </div>
                    <?php
                                endif;
                            endforeach;
                        }
                    } else {
                        $ingredient_lines = explode("\n", $ingredients);
                        foreach ($ingredient_lines as $line):
                            if (trim($line)):
                    ?>
                    <div class="ingredient-item">
                        <input type="checkbox" id="ing-<?php echo rand(); ?>">
                        <label for="ing-<?php echo rand(); ?>">
                            <?php echo htmlspecialchars(trim($line)); ?>
                        </label>
                    </div>
                    <?php
                            endif;
                        endforeach;
                    }
                    ?>
                </div>
            </section>

            <!-- Instructions -->
            <section class="section instructions-section">
                <h2><i class="fas fa-book-open"></i> Instructions</h2>
                <div class="instructions-list">
                    <?php
                    $instructions = $recipe['instructions'] ?? '';
                    if (is_string($instructions) && json_decode($instructions) !== null) {
                        $instructions = json_decode($instructions, true);
                        if (is_array($instructions)) {
                            $step = 1;
                            foreach ($instructions as $inst):
                                if (is_array($inst)):
                    ?>
                    <div class="instruction-step">
                        <div class="step-number"><?php echo $step; ?></div>
                        <div class="step-content">
                            <p><?php echo nl2br(htmlspecialchars($inst['description'] ?? '')); ?></p>
                        </div>
                    </div>
                    <?php
                                    $step++;
                                endif;
                            endforeach;
                        }
                    } else {
                        $instruction_lines = explode("\n", $instructions);
                        $step = 1;
                        foreach ($instruction_lines as $line):
                            if (trim($line)):
                    ?>
                    <div class="instruction-step">
                        <div class="step-number"><?php echo $step; ?></div>
                        <div class="step-content">
                            <p><?php echo htmlspecialchars(trim($line)); ?></p>
                        </div>
                    </div>
                    <?php
                                $step++;
                            endif;
                        endforeach;
                    }
                    ?>
                </div>
            </section>
        </div>

        <!-- Notes -->
        <?php if (!empty($recipe['notes'])): ?>
        <section class="section notes-section">
            <h2><i class="fas fa-lightbulb"></i> Chef's Notes</h2>
            <div class="notes-content">
                <p><?php echo nl2br(htmlspecialchars($recipe['notes'])); ?></p>
            </div>
        </section>
        <?php endif; ?>
    </div>

    <!-- Related Recipes (Search from 10 recipes) -->
    <section class="related-recipes">
        <h2>You Might Also Like</h2>
        <div class="related-grid">
            <?php
            // Get 10 random recipes (excluding current one)
            $related_query = "SELECT * FROM recipes 
                             WHERE id != ? 
                             ORDER BY RAND() 
                             LIMIT 4";
            $related_stmt = $db->prepare($related_query);
            $related_stmt->execute([$recipe_id]);
            $related_recipes = $related_stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($related_recipes as $related):
            ?>
            <a href="recipe-detail.php?id=<?php echo $related['id']; ?>" class="related-card">
                <div class="card-image">
                    <?php if (!empty($related['featured_image'])): ?>
                        <img src="<?php echo htmlspecialchars($related['featured_image']); ?>" 
                             alt="<?php echo htmlspecialchars($related['title']); ?>">
                    <?php else: ?>
                        <img src="assets/images/default-recipe.jpg" 
                             alt="<?php echo htmlspecialchars($related['title']); ?>">
                    <?php endif; ?>
                </div>
                <div class="card-content">
                    <h3><?php echo htmlspecialchars($related['title']); ?></h3>
                    <div class="card-meta">
                        <span><i class="fas fa-clock"></i> <?php echo $related['cooking_time'] ?? '--'; ?> min</span>
                        <span><i class="fas fa-users"></i> <?php echo $related['servings'] ?? '--'; ?> servings</span>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </section>
</div>

<style>
/* Recipe Details Styles */
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

/* Recipe Header */
.recipe-header {
    margin: 30px 0 20px;
}

.breadcrumb {
    color: #666;
    font-size: 0.9rem;
    margin-bottom: 15px;
}

.breadcrumb a {
    color: #4CAF50;
    text-decoration: none;
}

.breadcrumb a:hover {
    text-decoration: underline;
}

.breadcrumb span {
    color: #333;
}

.recipe-title {
    font-size: 2.5rem;
    color: #333;
    margin: 0 0 20px 0;
    font-weight: 700;
}

.recipe-meta {
    display: flex;
    gap: 25px;
    flex-wrap: wrap;
    margin-bottom: 25px;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #555;
    font-size: 0.95rem;
}

.meta-item i {
    color: #4CAF50;
    font-size: 1.1rem;
}

/* Recipe Image */
.recipe-image {
    width: 100%;
    height: 400px;
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 30px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.recipe-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Recipe Content */
.quick-actions {
    display: flex;
    gap: 15px;
    margin-bottom: 40px;
    flex-wrap: wrap;
}

.action-btn {
    padding: 12px 25px;
    background: white;
    border: 2px solid #4CAF50;
    color: #4CAF50;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
    font-size: 1rem;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.action-btn:hover {
    background: #4CAF50;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(76, 175, 80, 0.3);
}

/* Sections */
.section {
    margin-bottom: 40px;
}

.section h2 {
    font-size: 1.5rem;
    color: #333;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.section h2 i {
    color: #4CAF50;
}

.description {
    color: #555;
    line-height: 1.8;
    font-size: 1.1rem;
    margin: 0;
}

/* Ingredients & Instructions Side by Side */
.ingredients-instructions {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
    margin-bottom: 40px;
}

@media (max-width: 992px) {
    .ingredients-instructions {
        grid-template-columns: 1fr;
        gap: 30px;
    }
}

/* Ingredients Section */
.ingredients-section .section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.copy-btn {
    padding: 8px 20px;
    background: #f0f9f0;
    color: #4CAF50;
    border: 1px solid #4CAF50;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 5px;
    transition: all 0.3s;
}

.copy-btn:hover {
    background: #4CAF50;
    color: white;
}

.ingredients-list {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
}

.ingredient-item {
    padding: 15px;
    border-bottom: 1px solid #e9ecef;
    display: flex;
    align-items: center;
    gap: 15px;
}

.ingredient-item:last-child {
    border-bottom: none;
}

.ingredient-item input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.ingredient-item label {
    flex: 1;
    color: #333;
    font-size: 1rem;
    cursor: pointer;
    display: flex;
    gap: 10px;
    align-items: center;
}

.quantity {
    font-weight: 700;
    color: #4CAF50;
    min-width: 60px;
}

/* Instructions Section */
.instructions-list {
    background: white;
    border-radius: 10px;
    padding: 20px;
    border: 1px solid #e9ecef;
}

.instruction-step {
    display: flex;
    gap: 20px;
    margin-bottom: 25px;
    padding-bottom: 25px;
    border-bottom: 1px solid #f0f0f0;
}

.instruction-step:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: none;
}

.step-number {
    width: 40px;
    height: 40px;
    background: #4CAF50;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.1rem;
    flex-shrink: 0;
}

.step-content {
    flex: 1;
}

.step-content p {
    color: #555;
    line-height: 1.6;
    margin: 0;
    font-size: 1rem;
}

/* Notes Section */
.notes-content {
    background: #f0f9f0;
    border-left: 4px solid #4CAF50;
    padding: 25px;
    border-radius: 8px;
}

.notes-content p {
    color: #333;
    line-height: 1.7;
    margin: 0;
    font-size: 1rem;
}

/* Related Recipes */
.related-recipes {
    margin: 60px 0 40px;
    padding-top: 40px;
    border-top: 1px solid #e9ecef;
}

.related-recipes h2 {
    font-size: 1.8rem;
    color: #333;
    margin-bottom: 30px;
    text-align: center;
}

.related-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 25px;
}

.related-card {
    background: white;
    border-radius: 10px;
    overflow: hidden;
    text-decoration: none;
    box-shadow: 0 3px 15px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    border: 1px solid #eee;
}

.related-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    border-color: #4CAF50;
}

.card-image {
    height: 160px;
    overflow: hidden;
}

.card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.related-card:hover .card-image img {
    transform: scale(1.05);
}

.card-content {
    padding: 20px;
}

.card-content h3 {
    font-size: 1.1rem;
    color: #333;
    margin: 0 0 15px 0;
    line-height: 1.4;
    height: 3em;
    overflow: hidden;
}

.card-meta {
    display: flex;
    gap: 15px;
    color: #666;
    font-size: 0.9rem;
}

.card-meta span {
    display: flex;
    align-items: center;
    gap: 5px;
}

.card-meta i {
    color: #4CAF50;
}

/* Responsive */
@media (max-width: 768px) {
    .recipe-title {
        font-size: 1.8rem;
    }
    
    .recipe-image {
        height: 250px;
    }
    
    .recipe-meta {
        gap: 15px;
    }
    
    .meta-item {
        font-size: 0.9rem;
    }
    
    .quick-actions {
        gap: 10px;
    }
    
    .action-btn {
        padding: 10px 15px;
        font-size: 0.9rem;
    }
    
    .related-grid {
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
    }
    
    .section h2 {
        font-size: 1.3rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Print recipe
    window.printRecipe = function() {
        window.print();
    };
    
    // Save recipe (localStorage demo)
    window.saveRecipe = function() {
        const recipeTitle = document.querySelector('.recipe-title').textContent;
        const savedRecipes = JSON.parse(localStorage.getItem('savedRecipes') || '[]');
        const recipeId = new URLSearchParams(window.location.search).get('id');
        
        const existingIndex = savedRecipes.findIndex(r => r.id === recipeId);
        
        if (existingIndex === -1) {
            savedRecipes.push({
                id: recipeId,
                title: recipeTitle,
                date: new Date().toISOString().split('T')[0]
            });
            localStorage.setItem('savedRecipes', JSON.stringify(savedRecipes));
            showNotification('Recipe saved to favorites!', 'success');
        } else {
            savedRecipes.splice(existingIndex, 1);
            localStorage.setItem('savedRecipes', JSON.stringify(savedRecipes));
            showNotification('Recipe removed from favorites', 'info');
        }
    };
    
    // Share recipe
    window.shareRecipe = function() {
        if (navigator.share) {
            navigator.share({
                title: document.title,
                text: 'Check out this amazing recipe on FoodFusion!',
                url: window.location.href
            }).catch(console.error);
        } else {
            navigator.clipboard.writeText(window.location.href).then(() => {
                showNotification('Link copied to clipboard!', 'success');
            }).catch(() => {
                showNotification('Failed to copy link', 'error');
            });
        }
    };
    
    // Copy ingredients
    window.copyIngredients = function() {
        const ingredients = Array.from(document.querySelectorAll('.ingredient-item label'))
            .map(label => {
                const quantity = label.querySelector('.quantity')?.textContent || '';
                const name = label.querySelector('.name')?.textContent || label.textContent;
                return (quantity + ' ' + name).trim();
            })
            .join('\n');
        
        navigator.clipboard.writeText(ingredients).then(() => {
            const btn = document.querySelector('.copy-btn');
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check"></i> Copied!';
            btn.disabled = true;
            
            setTimeout(() => {
                btn.innerHTML = originalHTML;
                btn.disabled = false;
            }, 2000);
            
            showNotification('Ingredients copied to clipboard!', 'success');
        }).catch(() => {
            showNotification('Failed to copy ingredients', 'error');
        });
    };
    
    // Toggle ingredient checkbox styling
    document.querySelectorAll('.ingredient-item input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const label = this.closest('label');
            if (this.checked) {
                label.style.textDecoration = 'line-through';
                label.style.opacity = '0.6';
            } else {
                label.style.textDecoration = 'none';
                label.style.opacity = '1';
            }
        });
    });
    
    // Notification system
    function showNotification(message, type = 'success') {
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
            background: type === 'success' ? '#4CAF50' : 
                       type === 'error' ? '#f44336' : '#2196F3',
            color: 'white',
            padding: '15px 20px',
            borderRadius: '8px',
            boxShadow: '0 4px 15px rgba(0,0,0,0.2)',
            zIndex: '9999',
            display: 'flex',
            alignItems: 'center',
            gap: '10px',
            animation: 'slideInRight 0.3s ease'
        });
        
        document.body.appendChild(notification);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            notification.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
        
        // Close button
        notification.querySelector('.notification-close').addEventListener('click', () => {
            notification.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        });
        
        // Add animation styles
        if (!document.querySelector('#notification-animations')) {
            const style = document.createElement('style');
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
            `;
            document.head.appendChild(style);
        }
    }
    
    // Check if recipe is already saved
    const recipeId = new URLSearchParams(window.location.search).get('id');
    const savedRecipes = JSON.parse(localStorage.getItem('savedRecipes') || '[]');
    const isSaved = savedRecipes.some(r => r.id === recipeId);
    
    if (isSaved) {
        const saveBtn = document.querySelector('button[onclick="saveRecipe()"]');
        if (saveBtn) {
            saveBtn.innerHTML = '<i class="fas fa-bookmark"></i> Saved';
        }
    }
});
</script>

<?php include 'includes/footer.php'; ?>