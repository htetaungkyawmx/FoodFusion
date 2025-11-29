<?php include 'includes/header.php'; ?>

<div class="container">
    <section class="page-header">
        <h1>Community Cookbook</h1>
        <p>Share your recipes, tips, and culinary experiences with our community</p>
    </section>

    <?php if(isset($_SESSION['user_id'])): ?>
        <!-- Add New Post Form -->
        <div class="post-form">
            <h3>Share with Community</h3>
            <form action="includes/submit_post.php" method="POST">
                <div class="form-group">
                    <input type="text" name="title" placeholder="Post Title" required>
                </div>
                <div class="form-group">
                    <select name="post_type" required>
                        <option value="">Select Post Type</option>
                        <option value="recipe">Recipe</option>
                        <option value="tip">Cooking Tip</option>
                        <option value="experience">Culinary Experience</option>
                    </select>
                </div>
                <div class="form-group">
                    <textarea name="content" placeholder="Share your recipe, tip, or experience..." rows="5" required></textarea>
                </div>
                <button type="submit" class="btn">Share Post</button>
            </form>
        </div>
    <?php else: ?>
        <div class="alert alert-error">
            Please <a href="login.php">login</a> to share with the community.
        </div>
    <?php endif; ?>

    <!-- Community Posts -->
    <div class="community-posts">
        <h2>Recent Community Posts</h2>
        <?php
        include 'config/database.php';
        $database = new Database();
        $db = $database->getConnection();
        
        $query = "SELECT cp.*, u.first_name, u.last_name 
                 FROM community_posts cp 
                 JOIN users u ON cp.user_id = u.id 
                 ORDER BY cp.created_at DESC 
                 LIMIT 10";
        $stmt = $db->prepare($query);
        $stmt->execute();
        
        if($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '
                <div class="post-card">
                    <div class="post-header">
                        <h3>' . htmlspecialchars($row['title']) . '</h3>
                        <span class="post-type">' . ucfirst($row['post_type']) . '</span>
                    </div>
                    <div class="post-meta">
                        <span>By ' . htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) . '</span>
                        <span>on ' . date('M j, Y', strtotime($row['created_at'])) . '</span>
                    </div>
                    <div class="post-content">
                        ' . nl2br(htmlspecialchars($row['content'])) . '
                    </div>
                </div>';
            }
        } else {
            echo '<p>No community posts yet. Be the first to share!</p>';
        }
        ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>