<?php include 'includes/header.php'; ?>

<!-- Hero Section -->
<section class="hero">
    <h1>Welcome to FoodFusion</h1>
    <p>Discover, Create, and Share Culinary Masterpieces with Our Global Community</p>
    <button class="join-btn" onclick="openJoinForm()">Join Us Today</button>
</section>

<!-- Featured Recipes -->
<section class="featured-section">
    <h2>Featured Recipes</h2>
    <div class="recipe-grid">
        <?php
        include 'config/database.php';
        $database = new Database();
        $db = $database->getConnection();
        
        $query = "SELECT * FROM recipes ORDER BY created_at DESC LIMIT 3";
        $stmt = $db->prepare($query);
        $stmt->execute();
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '
            <div class="recipe-card">
                <h3>' . htmlspecialchars($row['title']) . '</h3>
                <p><strong>Cuisine:</strong> ' . htmlspecialchars($row['cuisine_type']) . '</p>
                <p><strong>Difficulty:</strong> ' . htmlspecialchars($row['difficulty_level']) . '</p>
                <p><strong>Time:</strong> ' . htmlspecialchars($row['cooking_time']) . ' minutes</p>
                <p>' . htmlspecialchars(substr($row['description'], 0, 100)) . '...</p>
            </div>';
        }
        ?>
    </div>
</section>

<!-- Upcoming Events Carousel -->
<section class="events-section">
    <h2>Upcoming Cooking Events</h2>
    <div class="events-carousel">
        <div class="event-card">
            <h3>Italian Pasta Masterclass</h3>
            <p>March 15, 2024 | 6:00 PM</p>
            <p>Learn authentic pasta making from Chef Marco</p>
        </div>
        <div class="event-card">
            <h3>Asian Street Food Festival</h3>
            <p>March 22, 2024 | 5:00 PM</p>
            <p>Explore the flavors of Asian street cuisine</p>
        </div>
        <div class="event-card">
            <h3>Baking Basics Workshop</h3>
            <p>March 29, 2024 | 3:00 PM</p>
            <p>Perfect your baking skills with our experts</p>
        </div>
    </div>
</section>

<!-- Join Us Modal -->
<div id="joinModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeJoinForm()">&times;</span>
        <h2>Join FoodFusion Community</h2>
        <form action="register.php" method="POST" onsubmit="return validateForm(this)">
            <div class="form-group">
                <input type="text" name="first_name" placeholder="First Name" required>
            </div>
            <div class="form-group">
                <input type="text" name="last_name" placeholder="Last Name" required>
            </div>
            <div class="form-group">
                <input type="email" name="email" placeholder="Email Address" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Password" required minlength="6">
            </div>
            <button type="submit" class="btn">Create Account</button>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>