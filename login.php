<?php
$page_title = "Login - FoodFusion";
include 'includes/header.php';
include 'includes/auth.php';
include 'includes/functions.php';

// Redirect if already logged in
if (isLoggedIn()) {
    redirect('index.php');
}

$error = '';

if ($_POST) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $csrf_token = $_POST['csrf_token'] ?? '';
    
    // Validate CSRF token
    if (!validateCSRFToken($csrf_token)) {
        $error = "Security token invalid. Please try again.";
    } elseif (empty($email) || empty($password)) {
        $error = "Please enter both email and password.";
    } else {
        // Check if account is locked
        if (isAccountLocked($email)) {
            $remaining_time = getRemainingLockTime($email);
            $minutes = ceil($remaining_time / 60);
            $error = "Account locked due to multiple failed attempts. Try again in $minutes minutes.";
        } else {
            // Verify credentials
            $user = verifyCredentials($email, $password);
            
            if ($user) {
                // Successful login
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['first_name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['is_admin'] = $user['is_admin'];
                
                // Reset failed attempts
                resetFailedAttempts($email);
                
                // Log activity
                logActivity("User logged in", $user['id']);
                
                // Redirect to intended page or home
                $redirect_url = isset($_SESSION['redirect_url']) ? $_SESSION['redirect_url'] : 'index.php';
                unset($_SESSION['redirect_url']);
                
                setFlashMessage('success', 'Welcome back, ' . $user['first_name'] . '!');
                redirect($redirect_url);
            } else {
                // Failed login
                $locked = incrementFailedAttempts($email);
                
                if ($locked) {
                    $error = "Account locked due to multiple failed attempts. Try again in 3 minutes.";
                } else {
                    $error = "Invalid email or password. Please try again.";
                }
                
                logActivity("Failed login attempt for email: $email");
            }
        }
    }
}
?>

<div class="container">
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h1>Welcome Back</h1>
                <p>Sign in to your FoodFusion account</p>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="" class="auth-form">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" 
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                           required autofocus>
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                
                <div class="form-options">
                    <label class="checkbox">
                        <input type="checkbox" name="remember">
                        <span>Remember me</span>
                    </label>
                    <a href="forgot-password.php" class="forgot-link">Forgot password?</a>
                </div>
                
                <button type="submit" class="btn btn-primary btn-lg btn-block">Sign In</button>
            </form>
            
            <div class="auth-footer">
                <p>Don't have an account? <a href="register.php" class="auth-link">Create one here</a></p>
            </div>
            
            <div class="auth-divider">
                <span>or continue with</span>
            </div>
            
            <div class="social-auth">
                <button class="btn btn-social btn-google">
                    <i class="fab fa-google"></i>
                    Google
                </button>
                <button class="btn btn-social btn-facebook">
                    <i class="fab fa-facebook"></i>
                    Facebook
                </button>
            </div>
        </div>
        
        <div class="auth-features">
            <div class="feature-card">
                <i class="fas fa-utensils"></i>
                <h3>Discover Recipes</h3>
                <p>Explore thousands of recipes from around the world</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-users"></i>
                <h3>Join Community</h3>
                <p>Connect with food enthusiasts and share your creations</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-star"></i>
                <h3>Save Favorites</h3>
                <p>Bookmark your favorite recipes and cooking tips</p>
            </div>
        </div>
    </div>
</div>

<style>
.auth-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4rem;
    align-items: center;
    min-height: 80vh;
    padding: 2rem 0;
}

.auth-card {
    background: white;
    padding: 3rem;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-lg);
}

.auth-header {
    text-align: center;
    margin-bottom: 2rem;
}

.auth-header h1 {
    font-size: 2.5rem;
    margin-bottom: 0.5rem;
}

.auth-form .form-group {
    margin-bottom: 1.5rem;
}

.form-options {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.checkbox {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
}

.forgot-link {
    color: var(--primary-color);
    text-decoration: none;
}

.forgot-link:hover {
    text-decoration: underline;
}

.btn-block {
    width: 100%;
}

.auth-footer {
    text-align: center;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid var(--border-color);
}

.auth-link {
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 500;
}

.auth-link:hover {
    text-decoration: underline;
}

.auth-divider {
    text-align: center;
    margin: 2rem 0;
    position: relative;
}

.auth-divider::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 1px;
    background: var(--border-color);
}

.auth-divider span {
    background: white;
    padding: 0 1rem;
    color: var(--gray-color);
}

.social-auth {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.btn-social {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    border: 2px solid var(--border-color);
    background: white;
    color: var(--dark-color);
}

.btn-social:hover {
    border-color: var(--primary-color);
    background: var(--light-gray);
}

.btn-google:hover {
    border-color: #db4437;
}

.btn-facebook:hover {
    border-color: #4267B2;
}

.auth-features {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.feature-card {
    text-align: center;
    padding: 2rem;
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
}

.feature-card i {
    font-size: 3rem;
    color: var(--primary-color);
    margin-bottom: 1rem;
}

@media (max-width: 768px) {
    .auth-container {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .auth-card {
        padding: 2rem;
    }
    
    .social-auth {
        grid-template-columns: 1fr;
    }
}
</style>

<?php include 'includes/footer.php'; ?>