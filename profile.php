<?php
$page_title = "My Profile - FoodFusion";
include 'includes/header.php';
include 'includes/auth.php';
include 'config/database.php';

// Require login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$database = new Database();
$db = $database->getConnection();

$user_id = $_SESSION['user_id'];
$success = '';
$error = '';

// Get user information
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $db->prepare($query);
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Get user's recipes
$recipes_query = "SELECT * FROM recipes WHERE user_id = ? ORDER BY created_at DESC";
$recipes_stmt = $db->prepare($recipes_query);
$recipes_stmt->execute([$user_id]);
$user_recipes = $recipes_stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $bio = $_POST['bio'];
    
    // Update user
    $update_query = "UPDATE users SET first_name = ?, last_name = ?, bio = ? WHERE id = ?";
    $update_stmt = $db->prepare($update_query);
    
    if ($update_stmt->execute([$first_name, $last_name, $bio, $user_id])) {
        $_SESSION['user_name'] = $first_name;
        $success = "Profile updated successfully!";
        
        // Refresh user data
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        $error = "Failed to update profile. Please try again.";
    }
}
?>

<div class="container main-content">
    <div class="profile-page">
        <div class="profile-header">
            <div class="profile-avatar">
                <?php if ($user['profile_image']): ?>
                    <img src="uploads/profiles/<?php echo $user['profile_image']; ?>" alt="<?php echo htmlspecialchars($user['first_name']); ?>">
                <?php else: ?>
                    <div class="avatar-placeholder">
                        <i class="fas fa-user"></i>
                    </div>
                <?php endif; ?>
            </div>
            <div class="profile-info">
                <h1><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h1>
                <p class="profile-email"><?php echo htmlspecialchars($user['email']); ?></p>
                <p class="profile-join-date">
                    <i class="fas fa-calendar-alt"></i>
                    Member since <?php echo date('F Y', strtotime($user['created_at'])); ?>
                </p>
            </div>
        </div>

        <div class="profile-stats">
            <div class="stat-card">
                <div class="stat-number"><?php echo count($user_recipes); ?></div>
                <div class="stat-label">Recipes</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">0</div>
                <div class="stat-label">Followers</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">0</div>
                <div class="stat-label">Following</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">0</div>
                <div class="stat-label">Reviews</div>
            </div>
        </div>

        <div class="profile-content">
            <div class="profile-tabs">
                <button class="tab-btn active" data-tab="edit-profile">Edit Profile</button>
                <button class="tab-btn" data-tab="my-recipes">My Recipes</button>
                <button class="tab-btn" data-tab="saved-recipes">Saved Recipes</button>
                <button class="tab-btn" data-tab="settings">Settings</button>
            </div>

            <div class="tab-content active" id="edit-profile">
                <h2>Edit Profile</h2>
                
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <form method="POST" action="" class="profile-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">First Name</label>
                            <input type="text" name="first_name" class="form-control" 
                                   value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="last_name" class="form-control" 
                                   value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <input type="email" class="form-control" 
                               value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                        <small class="form-text">Email cannot be changed</small>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Profile Picture</label>
                        <input type="file" class="form-control" accept="image/*">
                        <small class="form-text">Max file size: 2MB. Supported formats: JPG, PNG, GIF</small>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Bio</label>
                        <textarea name="bio" class="form-control" rows="4" 
                                  placeholder="Tell us about yourself and your culinary interests..."><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>
                    </div>
                    
                    <button type="submit" class="btn">Update Profile</button>
                </form>
            </div>

            <div class="tab-content" id="my-recipes">
                <h2>My Recipes</h2>
                
                <a href="add-recipe.php" class="btn" style="margin-bottom: 1.5rem;">
                    <i class="fas fa-plus"></i> Add New Recipe
                </a>
                
                <?php if ($user_recipes): ?>
                    <div class="recipes-grid">
                        <?php foreach ($user_recipes as $recipe): ?>
                            <div class="recipe-card">
                                <div class="recipe-image">
                                    <img src="<?php echo $recipe['featured_image'] ?: 'assets/images/default-recipe.jpg'; ?>" 
                                         alt="<?php echo htmlspecialchars($recipe['title']); ?>">
                                </div>
                                <div class="recipe-content">
                                    <h3 class="recipe-title"><?php echo htmlspecialchars($recipe['title']); ?></h3>
                                    <div class="recipe-meta">
                                        <span><i class="fas fa-clock"></i> <?php echo $recipe['cooking_time']; ?> min</span>
                                        <span><i class="fas fa-fire"></i> <?php echo $recipe['difficulty_level']; ?></span>
                                    </div>
                                    <p><?php echo htmlspecialchars(substr($recipe['description'], 0, 100)); ?>...</p>
                                    <div class="recipe-actions">
                                        <a href="recipe-detail.php?id=<?php echo $recipe['id']; ?>" class="btn btn-sm">View</a>
                                        <a href="edit-recipe.php?id=<?php echo $recipe['id']; ?>" class="btn btn-sm btn-outline">Edit</a>
                                        <button class="btn btn-sm btn-danger" onclick="deleteRecipe(<?php echo $recipe['id']; ?>)">Delete</button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-utensils"></i>
                        <h3>No Recipes Yet</h3>
                        <p>You haven't created any recipes yet. Share your first recipe!</p>
                        <a href="add-recipe.php" class="btn">Create Your First Recipe</a>
                    </div>
                <?php endif; ?>
            </div>

            <div class="tab-content" id="saved-recipes">
                <h2>Saved Recipes</h2>
                <div class="empty-state">
                    <i class="fas fa-bookmark"></i>
                    <h3>No Saved Recipes</h3>
                    <p>You haven't saved any recipes yet. Browse recipes and click the heart icon to save them.</p>
                    <a href="recipes.php" class="btn">Browse Recipes</a>
                </div>
            </div>

            <div class="tab-content" id="settings">
                <h2>Account Settings</h2>
                
                <div class="settings-section">
                    <h3><i class="fas fa-key"></i> Change Password</h3>
                    <form class="password-form">
                        <div class="form-group">
                            <label class="form-label">Current Password</label>
                            <input type="password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">New Password</label>
                            <input type="password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn">Update Password</button>
                    </form>
                </div>
                
                <div class="settings-section">
                    <h3><i class="fas fa-bell"></i> Notifications</h3>
                    <div class="notification-settings">
                        <label class="checkbox-label">
                            <input type="checkbox" checked>
                            <span>Email notifications for new comments</span>
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" checked>
                            <span>Weekly recipe recommendations</span>
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox">
                            <span>Community activity updates</span>
                        </label>
                    </div>
                </div>
                
                <div class="settings-section danger-zone">
                    <h3><i class="fas fa-exclamation-triangle"></i> Danger Zone</h3>
                    <div class="danger-actions">
                        <button class="btn btn-danger" onclick="exportData()">
                            <i class="fas fa-download"></i> Export My Data
                        </button>
                        <button class="btn btn-danger" onclick="deleteAccount()">
                            <i class="fas fa-trash"></i> Delete Account
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function deleteRecipe(recipeId) {
    if (confirm('Are you sure you want to delete this recipe? This action cannot be undone.')) {
        // In a real application, this would make an AJAX request to delete the recipe
        alert('Recipe deleted! (In a real application, this would delete the recipe from the database)');
        // Reload the page
        location.reload();
    }
}

function exportData() {
    alert('Exporting your data...\n\n(In a real application, this would generate and download a file with your data)');
}

function deleteAccount() {
    if (confirm('Are you absolutely sure you want to delete your account? This will permanently delete all your data and cannot be undone.')) {
        if (confirm('This is your last chance to cancel. Click OK to permanently delete your account.')) {
            alert('Account deletion requested.\n\n(In a real application, this would delete your account from the database)');
            // Redirect to logout
            window.location.href = 'logout.php';
        }
    }
}
</script>

<style>
.profile-header {
    display: flex;
    align-items: center;
    gap: 2rem;
    margin-bottom: 3rem;
    padding: 2rem;
    background: white;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.profile-avatar {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    overflow: hidden;
    border: 4px solid #e74c3c;
}

.profile-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-placeholder {
    width: 100%;
    height: 100%;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #999;
    font-size: 3rem;
}

.profile-info h1 {
    margin-bottom: 0.5rem;
    color: #333;
}

.profile-email {
    color: #666;
    margin-bottom: 0.5rem;
}

.profile-join-date {
    color: #888;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.profile-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    padding: 1.5rem;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    text-align: center;
    transition: transform 0.3s;
}

.stat-card:hover {
    transform: translateY(-3px);
}

.stat-number {
    font-size: 2.5rem;
    font-weight: bold;
    color: #e74c3c;
    margin-bottom: 0.5rem;
}

.stat-label {
    color: #666;
    font-weight: 500;
}

.profile-tabs {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 2rem;
    overflow-x: auto;
    padding-bottom: 0.5rem;
}

.tab-btn {
    padding: 0.75rem 1.5rem;
    background: #f8f9fa;
    border: none;
    border-radius: 25px;
    cursor: pointer;
    font-size: 1rem;
    font-weight: 500;
    white-space: nowrap;
    transition: all 0.3s;
}

.tab-btn.active {
    background: #e74c3c;
    color: white;
}

.tab-btn:hover:not(.active) {
    background: #e9ecef;
}

.tab-content {
    display: none;
    background: white;
    padding: 2rem;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.tab-content.active {
    display: block;
}

.tab-content h2 {
    margin-bottom: 1.5rem;
    color: #333;
}

.profile-form .form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

@media (max-width: 576px) {
    .profile-form .form-row {
        grid-template-columns: 1fr;
    }
}

.form-text {
    display: block;
    margin-top: 0.25rem;
    color: #888;
    font-size: 0.9rem;
}

.recipes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
}

.recipe-card {
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.recipe-image {
    height: 150px;
    overflow: hidden;
}

.recipe-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.recipe-content {
    padding: 1rem;
}

.recipe-title {
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
    color: #333;
}

.recipe-meta {
    display: flex;
    gap: 1rem;
    color: #666;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

.recipe-actions {
    display: flex;
    gap: 0.5rem;
    margin-top: 1rem;
}

.btn-sm {
    padding: 0.25rem 0.75rem;
    font-size: 0.9rem;
}

.btn-danger {
    background: #e74c3c;
    color: white;
}

.btn-danger:hover {
    background: #c0392b;
}

.empty-state {
    text-align: center;
    padding: 3rem;
    color: #666;
}

.empty-state i {
    font-size: 4rem;
    color: #ddd;
    margin-bottom: 1rem;
}

.empty-state h3 {
    margin-bottom: 0.5rem;
    color: #333;
}

.empty-state p {
    margin-bottom: 1.5rem;
}

.settings-section {
    margin-bottom: 2rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid #eee;
}

.settings-section:last-child {
    border-bottom: none;
}

.settings-section h3 {
    margin-bottom: 1rem;
    color: #333;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.password-form {
    max-width: 400px;
}

.notification-settings {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
}

.danger-zone {
    border-color: #e74c3c;
}

.danger-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab switching
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const tabId = this.textContent.toLowerCase().replace(' ', '-');
            
            // Update active tab button
            tabBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            // Show corresponding tab content
            tabContents.forEach(content => {
                content.classList.remove('active');
                if (content.id === tabId) {
                    content.classList.add('active');
                }
            });
        });
    });
    
    // Handle form submissions
    document.querySelector('.password-form').addEventListener('submit', function(e) {
        e.preventDefault();
        alert('Password updated successfully!');
        this.reset();
    });
});
</script>

<?php include 'includes/footer.php'; ?>