<?php
$page_title = "Register - FoodFusion";
include 'includes/header.php';
include 'includes/auth.php';
include 'includes/functions.php';

// Redirect if already logged in
if (isLoggedIn()) {
    redirect('index.php');
}

$error = '';
$success = '';

if ($_POST) {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $agree_terms = isset($_POST['agree_terms']);
    $csrf_token = $_POST['csrf_token'] ?? '';
    
    // Validate CSRF token
    if (!validateCSRFToken($csrf_token)) {
        $error = "Security token invalid. Please try again.";
    } elseif (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } elseif (!isValidEmail($email)) {
        $error = "Please enter a valid email address.";
    } elseif (!isPasswordStrong($password)) {
        $error = "Password must be at least 6 characters long.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (!$agree_terms) {
        $error = "You must agree to the Terms of Service and Privacy Policy.";
    } else {
        // Check if email exists
        if (emailExists($email)) {
            $error = "Email already registered. Please use a different email or <a href='login.php'>login here</a>.";
        } else {
            // Register new user
            $user_id = registerUser($first_name, $last_name, $email, $password);
            
            if ($user_id) {
                // Auto-login after registration
                $_SESSION['user_id'] = $user_id;
                $_SESSION['user_name'] = $first_name;
                $_SESSION['user_email'] = $email;
                
                // Log activity
                logActivity("New user registered", $user_id);
                
                // Send welcome email (optional)
                // sendEmailNotification($email, "Welcome to FoodFusion!", "Welcome to our culinary community!");
                
                $success = "Registration successful! Welcome to FoodFusion, $first_name!";
                setFlashMessage('success', $success);
                redirect('index.php');
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}
?>

<div class="container">
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h1>Join FoodFusion</h1>
                <p>Create your account and start your culinary journey</p>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="" class="auth-form">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" id="first_name" name="first_name" class="form-control" 
                               value="<?php echo isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : ''; ?>" 
                               required autofocus>
                    </div>
                    
                    <div class="form-group">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" id="last_name" name="last_name" class="form-control" 
                               value="<?php echo isset($_POST['last_name']) ? htmlspecialchars($_POST['last_name']) : ''; ?>" 
                               required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" 
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                           required>
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-control" 
                           required minlength="6">
                    <small class="form-text">Must be at least 6 characters long</small>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label class="checkbox">
                        <input type="checkbox" name="agree_terms" <?php echo isset($_POST['agree_terms']) ? 'checked' : ''; ?>>
                        <span>I agree to the <a href="terms.php" target="_blank">Terms of Service</a> and <a href="privacy.php" target="_blank">Privacy Policy</a></span>
                    </label>
                </div>
                
                <button type="submit" class="btn btn-primary btn-lg btn-block">Create Account</button>
            </form>
            
            <div class="auth-footer">
                <p>Already have an account? <a href="login.php" class="auth-link">Sign in here</a></p>
            </div>
        </div>
        
        <div class="auth-benefits">
            <h2>Why Join FoodFusion?</h2>
            <div class="benefits-list">
                <div class="benefit-item">
                    <i class="fas fa-share-alt"></i>
                    <div>
                        <h3>Share Recipes</h3>
                        <p>Share your culinary creations with a global community</p>
                    </div>
                </div>
                
                <div class="benefit-item">
                    <i class="fas fa-heart"></i>
                    <div>
                        <h3>Save Favorites</h3>
                        <p>Bookmark recipes you love and create personal collections</p>
                    </div>
                </div>
                
                <div class="benefit-item">
                    <i class="fas fa-comments"></i>
                    <div>
                        <h3>Join Discussions</h3>
                        <p>Connect with other food enthusiasts and share tips</p>
                    </div>
                </div>
                
                <div class="benefit-item">
                    <i class="fas fa-trophy"></i>
                    <div>
                        <h3>Earn Badges</h3>
                        <p>Unlock achievements as you explore and contribute</p>
                    </div>
                </div>
                
                <div class="benefit-item">
                    <i class="fas fa-bell"></i>
                    <div>
                        <h3>Get Notifications</h3>
                        <p>Stay updated on new recipes and community activities</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.form-text {
    display: block;
    margin-top: 0.25rem;
    color: var(--gray-color);
    font-size: 0.875rem;
}

.auth-benefits {
    padding: 2rem;
}

.auth-benefits h2 {
    text-align: center;
    margin-bottom: 2rem;
    color: var(--secondary-color);
}

.benefits-list {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.benefit-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1rem;
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
    transition: var(--transition);
}

.benefit-item:hover {
    transform: translateX(5px);
    box-shadow: var(--shadow-lg);
}

.benefit-item i {
    font-size: 1.5rem;
    color: var(--primary-color);
    margin-top: 0.25rem;
}

.benefit-item h3 {
    margin-bottom: 0.5rem;
    font-size: 1.1rem;
}

.benefit-item p {
    margin: 0;
    color: var(--gray-color);
    font-size: 0.9rem;
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .auth-benefits {
        padding: 1rem;
    }
    
    .benefit-item {
        padding: 0.75rem;
    }
}
</style>

<?php include 'includes/footer.php'; ?>