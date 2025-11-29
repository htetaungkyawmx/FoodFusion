<?php include 'includes/header.php'; ?>

<div class="container">
    <section class="page-header">
        <h1>Recipe Collection</h1>
        <p>Discover delicious recipes from around the world</p>
    </section>

    <!-- Recipe Filters -->
    <div class="filters">
        <form method="GET" action="">
            <select name="cuisine">
                <option value="">All Cuisines</option>
                <option value="Italian">Italian</option>
                <option value="Asian">Asian</option>
                <option value="Mexican">Mexican</option>
                <option value="Indian">Indian</option>
                <option value="Mediterranean">Mediterranean</option>
            </select>
            
            <select name="diet">
                <option value="">All Diets</option>
                <option value="Vegetarian">Vegetarian</option>
                <option value="Vegan">Vegan</option>
                <option value="Non-Vegetarian">Non-Vegetarian</option>
            </select>
            
            <select name="difficulty">
                <option value="">All Levels</option>
                <option value="Easy">Easy</option>
                <option value="Medium">Medium</option>
                <option value="Hard">Hard</option>
            </select>
            
            <button type="submit" class="btn">Filter</button>
        </form>
    </div>

    <!-- Recipes Grid -->
    <div class="recipe-grid">
        <?php
        include 'config/database.php';
        $database = new Database();
        $db = $database->getConnection();
        
        // Build query based on filters
        $query = "SELECT * FROM recipes WHERE 1=1";
        $params = [];
        
        if(isset($_GET['cuisine']) && !empty($_GET['cuisine'])) {
            $query .= " AND cuisine_type = ?";
            $params[] = $_GET['cuisine'];
        }
        
        if(isset($_GET['diet']) && !empty($_GET['diet'])) {
            $query .= " AND dietary_preference = ?";
            $params[] = $_GET['diet'];
        }
        
        if(isset($_GET['difficulty']) && !empty($_GET['difficulty'])) {
            $query .= " AND difficulty_level = ?";
            $params[] = $_GET['difficulty'];
        }
        
        $query .= " ORDER BY created_at DESC";
        
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        
        if($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '
                <div class="recipe-card">
                    <h3>' . htmlspecialchars($row['title']) . '</h3>
                    <div class="recipe-meta">
                        <span class="cuisine">' . htmlspecialchars($row['cuisine_type']) . '</span>
                        <span class="diet">' . htmlspecialchars($row['dietary_preference']) . '</span>
                        <span class="difficulty">' . htmlspecialchars($row['difficulty_level']) . '</span>
                    </div>
                    <p><strong>Time:</strong> ' . htmlspecialchars($row['cooking_time']) . ' minutes</p>
                    <p>' . htmlspecialchars(substr($row['description'], 0, 150)) . '...</p>
                    <div class="recipe-actions">
                        <button class="btn btn-view">View Recipe</button>
                    </div>
                </div>';
            }
        } else {
            echo '<p>No recipes found matching your criteria.</p>';
        }
        ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>