<?php
$page_title = "My Profile - FoodFusion";
include 'includes/header.php';
include 'includes/auth.php';
include 'includes/functions.php';

// Require login
requireLogin();

$user = getCurrentUser();
$error = '';
$success = '';

if ($_POST) {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $bio = trim($_POST['bio']);
    $csrf_token = $_POST['csrf_token'] ?? '';
    
    // Validate CSRF token
    if (!validateCSRFToken($csrf_token)) {
        $error = "Security token invalid. Please try again.";
    } elseif (empty($first_name) || empty($last_name)) {
        $error = "First name and last name are required.";
    } else {
        $profile_image = null;
        
        // Handle profile image upload
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
            $upload_result = uploadFile(
                $_FILES['profile_image'], 
                'assets/uploads/profiles',
                ['jpg', 'jpeg', 'png', 'gif'],
                2097152 // 2MB
            );
            
            if ($upload_result['success']) {
                $profile_image = $upload_result['filename'];
                
                // Delete old profile image if exists
                if ($user['profile_image']) {
                    $old_image_path = 'assets/uploads/profiles/' . $user['profile_image'];
                    if (file_exists($old_image_path)) {
                        unlink($old_image_path);
                    }
                }
            } else {
                $error = $upload_result['message'];
            }
        }
        
        if (!$error) {
            // Update user profile
            if (updateUserProfile($user['id'], $first_name, $last_name, $bio, $profile_image)) {
                $success = "Profile updated successfully!";
                
                // Update session data
                $_SESSION['user_name'] = $first_name;
                
                // Refresh user data
                $user = getCurrentUser();
                
                logActivity("Profile updated", $user['id']);
            } else {
                $error = "Failed to update profile. Please try again.";
            }
        }
    }
}

// Get user stats
$db = getDBConnection();
$query = "SELECT 
            COUNT(*) as recipe_count,
            (SELECT COUNT(*) FROM community_posts WHERE user_id = ?) as post_count,
            (SELECT COUNT(*) FROM recipe_reviews WHERE user_id = ?) as review_count
          FROM recipes WHERE user_id = ?";
$stmt = $db->prepare($query);
$stmt->execute([$user['id'], $user['id'], $user['id']]);
$user_stats = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<div class="container">
    <div class="profile-header">
        <div class="profile-avatar">
            <?php if ($user['profile_image']): ?>
                <img src="assets/uploads/profiles/<?php echo $user['profile_image']; ?>" alt="<?php echo htmlspecialchars($user['first_name']); ?>">
            <?php else: ?>
                <div class="avatar-placeholder">
                    <i class="fas fa-user"></i>
                </div>
            <?php endif; ?>
        </div>
        <div class="profile-info">
            <h1><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h1>
            <p class="profile-email"><?php echo htmlspecialchars($user['email']); ?></p>
            <p class="profile-join-date">Member since <?php echo formatDate($user['created_at']); ?></p>
        </div>
    </div>

    <div class="profile-stats">
        <div class="stat-card">
            <div class="stat-number"><?php echo $user_stats['recipe_count']; ?></div>
            <div class="stat-label">Recipes</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?php echo $user_stats['post_count']; ?></div>
            <div class="stat-label">Posts</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?php echo $user_stats['review_count']; ?></div>
            <div class="stat-label">Reviews</div>
        </div>
    </div>

    <div class="profile-content">
        <div class="profile-form-section">
            <h2>Edit Profile</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="" enctype="multipart/form-data" class="profile-form">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" id="first_name" name="first_name" class="form-control" 
                               value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" id="last_name" name="last_name" class="form-control" 
                               value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" id="email" class="form-control" 
                           value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                    <small class="form-text">Email cannot be changed</small>
                </div>
                
                <div class="form-group">
                    <label for="profile_image" class="form-label">Profile Picture</label>
                    <input type="file" id="profile_image" name="profile_image" class="form-control" 
                           accept="image/jpeg,image/png,image/gif">
                    <small class="form-text">Max file size: 2MB. Supported formats: JPG, PNG, GIF</small>
                </div>
                
                <div class="form-group">
                    <label for="bio" class="form-label">Bio</label>
                    <textarea id="bio" name="bio" class="form-control" rows="4" 
                              placeholder="Tell us about yourself and your culinary interests..."><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary">Update Profile</button>
            </form>
        </div>
        
        <div class="profile-actions">
            <div class="action-card">
                <h3>Account Settings</h3>
                <div class="action-links">
                    <a href="change-password.php" class="action-link">
                        <i class="fas fa-key"></i>
                        Change Password
                    </a>
                    <a href="my-recipes.php" class="action-link">
                        <i class="fas fa-utensils"></i>
                        My Recipes
                    </a>
                    <a href="saved-recipes.php" class="action-link">
                        <i class="fas fa-bookmark"></i>
                        Saved Recipes
                    </a>
                    <a href="privacy.php" class="action-link">
                        <i class="fas fa-shield-alt"></i>
                        Privacy Settings
                    </a>
                </div>
            </div>
            
            <?php if (isAdmin()): ?>
                <div class="action-card admin-card">
                    <h3>Admin Tools</h3>
                    <div class="action-links">
                        <a href="admin/dashboard.php" class="action-link">
                            <i class="fas fa-tachometer-alt"></i>
                            Admin Dashboard
                        </a>
                        <a href="admin/recipes.php" class="action-link">
                            <i class="fas fa-utensils"></i>
                            Manage Recipes
                        </a>
                        <a href="admin/users.php" class="action-link">
                            <i class="fas fa-users"></i>
                            Manage Users
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.profile-header {
    display: flex;
    align-items: center;
    gap: 2rem;
    margin-bottom: 3rem;
    padding: 2rem;
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
}

.profile-avatar {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    overflow: hidden;
    border: 4px solid var(--primary-color);
}

.profile-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-placeholder {
    width: 100%;
    height: 100%;
    background: var(--light-gray);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--gray-color);
    font-size: 3rem;
}

.profile-info h1 {
    margin-bottom: 0.5rem;
    color: var(--secondary-color);
}

.profile-email {
    color: var(--gray-color);
    margin-bottom: 0.25rem;
}

.profile-join-date {
    color: var(--gray-color);
    font-size: 0.9rem;
}

.profile-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
    margin-bottom: 3rem;
}

.stat-card {
    background: white;
    padding: 1.5rem;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
    text-align: center;
    transition: var(--transition);
}

.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-lg);
}

.stat-number {
    font-size: 2.5rem;
    font-weight: bold;
    color: var(--primary-color);
    margin-bottom: 0.5rem;
}

.stat-label {
    color: var(--gray-color);
    font-weight: 500;
}

.profile-content {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 3rem;
}

.profile-form-section {
    background: white;
    padding: 2rem;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
}

.profile-actions {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.action-card {
    background: white;
    padding: 1.5rem;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
}

.action-card h3 {
    margin-bottom: 1rem;
    color: var(--secondary-color);
    border-bottom: 2px solid var(--light-gray);
    padding-bottom: 0.5rem;
}

.action-links {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.action-link {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    color: var(--dark-color);
    text-decoration: none;
    border-radius: var(--border-radius);
    transition: var(--transition);
}

.action-link:hover {
    background: var(--light-gray);
    color: var(--primary-color);
}

.action-link i {
    width: 20px;
    text-align: center;
}

.admin-card {
    border-left: 4px solid var(--accent-color);
}

@media (max-width: 768px) {
    .profile-header {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .profile-content {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .profile-stats {
        grid-template-columns: repeat(3, 1fr);
    }
}
</style>

<?php include 'includes/footer.php'; ?>