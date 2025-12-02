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
    $category = $_POST['category'];
    $user_id = $_SESSION['user_id'];
    
    $query = "INSERT INTO community_posts (user_id, title, content, category) VALUES (?, ?, ?, ?)";
    $stmt = $db->prepare($query);
    $stmt->execute([$user_id, $title, $content, $category]);
    
    header('Location: community.php');
    exit;
}
?>

<div class="container main-content">
    <div class="community-page">
        <div class="community-header">
            <h1>FoodFusion Community</h1>
            <p class="subtitle">Connect, share, and learn with fellow food enthusiasts</p>
        </div>

        <?php if (isset($_SESSION['user_id'])): ?>
        <div class="share-section">
            <div class="share-box">
                <div class="share-header">
                    <div class="user-info">
                        <div class="user-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <h3>Start a Discussion</h3>
                            <p>Share your culinary thoughts with the community</p>
                        </div>
                    </div>
                </div>
                <form method="POST" action="" class="post-form">
                    <div class="form-group">
                        <input type="text" name="title" class="form-control" placeholder="What would you like to share?" required>
                    </div>
                    <div class="form-group">
                        <select name="category" class="form-control" required>
                            <option value="">Select Topic</option>
                            <option value="recipe">Recipe Share</option>
                            <option value="tip">Cooking Tip</option>
                            <option value="question">Question</option>
                            <option value="review">Review</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <textarea name="content" class="form-control" rows="4" 
                                  placeholder="Tell us more about your recipe, tip, or question..." required></textarea>
                    </div>
                    <button type="submit" class="btn-post">
                        <i class="fas fa-paper-plane"></i> Post to Community
                    </button>
                </form>
            </div>
        </div>
        <?php else: ?>
        <div class="join-community">
            <div class="join-message">
                <div class="join-content">
                    <h3><i class="fas fa-comments"></i> Join the Conversation</h3>
                    <p>Become part of our growing food community to share recipes, ask questions, and connect with other food lovers.</p>
                    <div class="join-actions">
                        <!-- Login and register links removed -->
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="community-features">
            <h2><i class="fas fa-star"></i> Community Features</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <h3>Recipe Sharing</h3>
                    <p>Share your favorite recipes and discover new ones from community members</p>
                    <ul class="feature-list">
                        <li><i class="fas fa-check"></i> Upload recipes with photos</li>
                        <li><i class="fas fa-check"></i> Rate and review recipes</li>
                        <li><i class="fas fa-check"></i> Save favorites to your cookbook</li>
                    </ul>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h3>Discussion Forums</h3>
                    <p>Engage in food-related discussions and get advice from experienced cooks</p>
                    <ul class="feature-list">
                        <li><i class="fas fa-check"></i> Ask cooking questions</li>
                        <li><i class="fas fa-check"></i> Share cooking tips</li>
                        <li><i class="fas fa-check"></i> Troubleshoot kitchen issues</li>
                    </ul>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <h3>Monthly Challenges</h3>
                    <p>Participate in fun cooking challenges and showcase your skills</p>
                    <ul class="feature-list">
                        <li><i class="fas fa-check"></i> Theme-based challenges</li>
                        <li><i class="fas fa-check"></i> Win community badges</li>
                        <li><i class="fas fa-check"></i> Featured recipe spotlight</li>
                    </ul>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <h3>Virtual Events</h3>
                    <p>Join live cooking classes, Q&A sessions, and food-related webinars</p>
                    <ul class="feature-list">
                        <li><i class="fas fa-check"></i> Live cooking demos</li>
                        <li><i class="fas fa-check"></i> Guest chef sessions</li>
                        <li><i class="fas fa-check"></i> Interactive workshops</li>
                    </ul>
                </div>
                
                <!-- New Feature 1 -->
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-camera"></i>
                    </div>
                    <h3>Food Photography</h3>
                    <p>Showcase your culinary creations with beautiful food photography</p>
                    <ul class="feature-list">
                        <li><i class="fas fa-check"></i> Photo sharing gallery</li>
                        <li><i class="fas fa-check"></i> Photography tips</li>
                        <li><i class="fas fa-check"></i> Styling workshops</li>
                    </ul>
                </div>
                
                <!-- New Feature 2 -->
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-globe-americas"></i>
                    </div>
                    <h3>Global Cuisine</h3>
                    <p>Explore and share authentic recipes from around the world</p>
                    <ul class="feature-list">
                        <li><i class="fas fa-check"></i> Regional cuisine guides</li>
                        <li><i class="fas fa-check"></i> Cultural cooking insights</li>
                        <li><i class="fas fa-check"></i> Ingredient sourcing tips</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="community-guidelines">
            <h2><i class="fas fa-handshake"></i> Community Guidelines</h2>
            <div class="guidelines-content">
                <div class="guideline-item">
                    <i class="fas fa-heart"></i>
                    <h3>Be Respectful</h3>
                    <p>Treat all community members with kindness and respect</p>
                </div>
                <div class="guideline-item">
                    <i class="fas fa-share-alt"></i>
                    <h3>Share Generously</h3>
                    <p>Share your knowledge and recipes with the community</p>
                </div>
                <div class="guideline-item">
                    <i class="fas fa-flag"></i>
                    <h3>Credit Sources</h3>
                    <p>Always give credit when sharing recipes from others</p>
                </div>
                <div class="guideline-item">
                    <i class="fas fa-smile"></i>
                    <h3>Have Fun</h3>
                    <p>Enjoy cooking, learning, and connecting with food lovers</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.community-header {
    text-align: center;
    padding: 3rem 0;
    margin-bottom: 3rem;
    background: linear-gradient(135deg, #FF6B7CFF, #6BFFCEFF);
    color: white;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(255, 107, 107, 0.2);
}

.community-header h1 {
    margin-bottom: 0.5rem;
    font-size: 2.5rem;
    font-weight: 700;
}

.subtitle {
    font-size: 1.2rem;
    opacity: 0.95;
    max-width: 600px;
    margin: 0 auto;
    font-weight: 300;
}

.share-section {
    margin-bottom: 3rem;
}

.share-box {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    border: 1px solid rgba(0,0,0,0.05);
}

.share-header {
    margin-bottom: 2rem;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.user-avatar {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #FF8E53, #FF6B6B);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.user-info h3 {
    margin: 0;
    color: #333;
    font-size: 1.4rem;
}

.user-info p {
    margin: 0.25rem 0 0;
    color: #666;
}

.post-form .form-group {
    margin-bottom: 1.5rem;
}

.post-form .form-control {
    width: 100%;
    padding: 1rem;
    border: 2px solid #E0E0E0;
    border-radius: 10px;
    font-size: 1rem;
    transition: all 0.3s;
    background: #FAFAFA;
}

.post-form .form-control:focus {
    outline: none;
    border-color: #FF8E53;
    background: white;
    box-shadow: 0 0 0 4px rgba(255, 142, 83, 0.1);
}

.post-form textarea.form-control {
    resize: vertical;
    min-height: 120px;
    font-family: inherit;
}

.btn-post {
    background: linear-gradient(135deg, #FF6B6B, #FF8E53);
    color: white;
    border: none;
    padding: 0.9rem 2rem;
    border-radius: 10px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    transition: all 0.3s;
}

.btn-post:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(255, 107, 107, 0.3);
}

.join-community {
    margin-bottom: 3rem;
}

.join-message {
    background: white;
    border-radius: 15px;
    padding: 2.5rem;
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    border: 1px solid rgba(0,0,0,0.05);
    text-align: center;
}

.join-content h3 {
    margin-bottom: 1rem;
    color: #333;
    font-size: 1.6rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
}

.join-content p {
    margin-bottom: 2rem;
    color: #666;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
    font-size: 1.1rem;
    line-height: 1.6;
}

.join-actions {
    display: flex;
    justify-content: center;
    gap: 1.5rem;
    flex-wrap: wrap;
}

.community-features {
    margin-bottom: 3rem;
}

.community-features h2 {
    margin-bottom: 2rem;
    color: #333;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 1.8rem;
    font-weight: 600;
    text-align: center;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
}

.feature-card {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    border: 1px solid rgba(0,0,0,0.05);
    transition: all 0.3s;
    display: flex;
    flex-direction: column;
}

.feature-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.15);
}

.feature-icon {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, #FF8E53, #FF6B6B);
    color: white;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    margin-bottom: 1.5rem;
}

.feature-card:nth-child(2) .feature-icon {
    background: linear-gradient(135deg, #4ECDC4, #44A08D);
}

.feature-card:nth-child(3) .feature-icon {
    background: linear-gradient(135deg, #FFD166, #FFB347);
}

.feature-card:nth-child(4) .feature-icon {
    background: linear-gradient(135deg, #9D50BB, #6A11CB);
}

.feature-card:nth-child(5) .feature-icon {
    background: linear-gradient(135deg, #06D6A0, #05B48A);
}

.feature-card:nth-child(6) .feature-icon {
    background: linear-gradient(135deg, #EF476F, #D74064);
}

.feature-card h3 {
    margin-bottom: 1rem;
    color: #333;
    font-size: 1.4rem;
    font-weight: 600;
}

.feature-card > p {
    color: #666;
    margin-bottom: 1.5rem;
    line-height: 1.6;
}

.feature-list {
    list-style: none;
    padding: 0;
    margin: 0;
    margin-top: auto;
}

.feature-list li {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.5rem 0;
    color: #555;
    font-size: 0.95rem;
}

.feature-list i {
    color: #4ECDC4;
    font-size: 0.9rem;
}

.community-guidelines {
    background: linear-gradient(135deg, #FFD166, #FFB347);
    border-radius: 15px;
    padding: 2.5rem;
    box-shadow: 0 8px 25px rgba(255, 209, 102, 0.2);
}

.community-guidelines h2 {
    margin-bottom: 2rem;
    color: #333;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 1.8rem;
    font-weight: 600;
    text-align: center;
}

.guidelines-content {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 2rem;
}

.guideline-item {
    text-align: center;
    padding: 1.5rem;
    background: white;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    transition: all 0.3s;
}

.guideline-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.guideline-item i {
    font-size: 2.5rem;
    color: #FF6B6B;
    margin-bottom: 1rem;
}

.guideline-item h3 {
    margin-bottom: 0.5rem;
    color: #333;
    font-size: 1.2rem;
}

.guideline-item p {
    color: #666;
    font-size: 0.95rem;
    line-height: 1.5;
}

/* Animation */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.feature-card, .guideline-item {
    animation: fadeInUp 0.6s ease-out;
}

/* Responsive */
@media (max-width: 768px) {
    .community-header {
        padding: 2rem 1rem;
    }
    
    .community-header h1 {
        font-size: 2rem;
    }
    
    .subtitle {
        font-size: 1.1rem;
    }
    
    .share-box,
    .join-message,
    .community-guidelines {
        padding: 1.5rem;
    }
    
    .features-grid {
        grid-template-columns: 1fr;
    }
    
    .guidelines-content {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Smooth animations for elements
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1 });
    
    // Observe feature cards and guideline items
    document.querySelectorAll('.feature-card, .guideline-item').forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = `all 0.6s ease ${index * 0.1}s`;
        observer.observe(card);
    });
});
</script>

<?php include 'includes/footer.php'; ?>