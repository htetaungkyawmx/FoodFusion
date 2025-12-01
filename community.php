<?php
$page_title = "Community - FoodFusion";
include 'includes/header.php';
include 'config/database.php';

$database = new Database();
$db = $database->getConnection();

// Handle post submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $user_id = $_SESSION['user_id'];
    
    $query = "INSERT INTO community_posts (user_id, title, content) VALUES (?, ?, ?)";
    $stmt = $db->prepare($query);
    $stmt->execute([$user_id, $title, $content]);
    
    header('Location: community.php');
    exit;
}

// Get community posts
$query = "SELECT cp.*, u.first_name, u.last_name 
          FROM community_posts cp 
          JOIN users u ON cp.user_id = u.id 
          ORDER BY cp.created_at DESC 
          LIMIT 20";
$stmt = $db->prepare($query);
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container main-content">
    <div class="community-page">
        <div class="community-header">
            <h1>Community Forum</h1>
            <p class="subtitle">Share recipes, tips, and connect with food lovers</p>
        </div>

        <?php if (isset($_SESSION['user_id'])): ?>
        <div class="create-post">
            <h2>Share Your Thoughts</h2>
            <form method="POST" action="">
                <div class="form-group">
                    <input type="text" name="title" class="form-control" placeholder="Post Title" required>
                </div>
                <div class="form-group">
                    <textarea name="content" class="form-control" rows="4" 
                              placeholder="Share your recipe, cooking tip, or food experience..." required></textarea>
                </div>
                <button type="submit" class="btn">Post to Community</button>
            </form>
        </div>
        <?php else: ?>
        <div class="login-prompt">
            <p><a href="login.php">Login</a> or <a href="register.php">register</a> to participate in the community.</p>
        </div>
        <?php endif; ?>

        <div class="community-posts">
            <h2>Recent Community Posts</h2>
            
            <?php if ($posts): ?>
                <div class="posts-grid">
                    <?php foreach ($posts as $post): ?>
                        <div class="post-card">
                            <div class="post-header">
                                <div class="post-author">
                                    <div class="author-avatar">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="author-info">
                                        <strong><?php echo htmlspecialchars($post['first_name'] . ' ' . $post['last_name']); ?></strong>
                                        <span class="post-date"><?php echo date('M j, Y', strtotime($post['created_at'])); ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="post-content">
                                <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                                <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                            </div>
                            <div class="post-actions">
                                <button class="btn-like"><i class="far fa-heart"></i> Like</button>
                                <button class="btn-comment"><i class="far fa-comment"></i> Comment</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="no-posts">No community posts yet. Be the first to share!</p>
            <?php endif; ?>
        </div>

        <div class="community-features">
            <h2>Community Features</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <i class="fas fa-utensils"></i>
                    <h3>Recipe Exchange</h3>
                    <p>Share and discover recipes from around the world</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-question-circle"></i>
                    <h3>Q&A Forum</h3>
                    <p>Get cooking advice from experienced community members</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-trophy"></i>
                    <h3>Challenges</h3>
                    <p>Participate in monthly cooking challenges</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-calendar-alt"></i>
                    <h3>Events</h3>
                    <p>Join virtual cooking classes and events</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.community-header {
    text-align: center;
    padding: 2rem 0;
    margin-bottom: 2rem;
}

.community-header h1 {
    color: #333;
    margin-bottom: 0.5rem;
}

.subtitle {
    color: #666;
    font-size: 1.1rem;
}

.create-post {
    background: white;
    padding: 2rem;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    margin-bottom: 2rem;
}

.create-post h2 {
    margin-bottom: 1.5rem;
    color: #333;
}

.login-prompt {
    text-align: center;
    padding: 2rem;
    background: #f8f9fa;
    border-radius: 10px;
    margin-bottom: 2rem;
}

.login-prompt a {
    color: #e74c3c;
    text-decoration: none;
    font-weight: 600;
}

.login-prompt a:hover {
    text-decoration: underline;
}

.community-posts {
    margin-bottom: 3rem;
}

.posts-grid {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    margin-top: 1.5rem;
}

.post-card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    padding: 1.5rem;
    transition: transform 0.3s;
}

.post-card:hover {
    transform: translateY(-3px);
}

.post-header {
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #eee;
}

.post-author {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.author-avatar {
    width: 40px;
    height: 40px;
    background: #e74c3c;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.author-info {
    display: flex;
    flex-direction: column;
}

.post-date {
    font-size: 0.9rem;
    color: #666;
}

.post-content h3 {
    margin-bottom: 0.75rem;
    color: #333;
}

.post-content p {
    color: #555;
    line-height: 1.6;
}

.post-actions {
    display: flex;
    gap: 1rem;
    margin-top: 1.5rem;
    padding-top: 1rem;
    border-top: 1px solid #eee;
}

.btn-like, .btn-comment {
    background: none;
    border: none;
    color: #666;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 5px;
    transition: all 0.3s;
}

.btn-like:hover, .btn-comment:hover {
    background: #f8f9fa;
    color: #333;
}

.no-posts {
    text-align: center;
    padding: 3rem;
    color: #666;
    background: #f8f9fa;
    border-radius: 10px;
}

.community-features {
    margin-top: 3rem;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    margin-top: 1.5rem;
}

.feature-card {
    text-align: center;
    padding: 2rem;
    background: white;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s;
}

.feature-card:hover {
    transform: translateY(-5px);
}

.feature-card i {
    font-size: 3rem;
    color: #3498db;
    margin-bottom: 1rem;
}

.feature-card h3 {
    margin-bottom: 0.5rem;
    color: #333;
}

.feature-card p {
    color: #666;
    font-size: 0.9rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Like button functionality
    document.querySelectorAll('.btn-like').forEach(button => {
        button.addEventListener('click', function() {
            const icon = this.querySelector('i');
            if (icon.classList.contains('far')) {
                icon.classList.remove('far');
                icon.classList.add('fas');
                this.style.color = '#e74c3c';
            } else {
                icon.classList.remove('fas');
                icon.classList.add('far');
                this.style.color = '#666';
            }
        });
    });
});
</script>

<?php include 'includes/footer.php'; ?>