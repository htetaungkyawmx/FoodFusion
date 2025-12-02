<?php
// Start output buffering at the very beginning
ob_start();

$page_title = "Register - FoodFusion";

// Include required files
require_once 'includes/auth.php';
require_once 'config/database.php';

// If already logged in, redirect to home
if (isLoggedIn()) {
    header('Location: index.php');
    exit();
}

// Initialize variables
$error = '';
$success = '';
$first_name = '';
$last_name = '';
$email = '';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate inputs
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validate all fields
    $valid = true;
    
    if (empty($first_name)) {
        $error = "First name is required.";
        $valid = false;
    } elseif (empty($last_name)) {
        $error = "Last name is required.";
        $valid = false;
    } elseif (empty($email)) {
        $error = "Email is required.";
        $valid = false;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
        $valid = false;
    } elseif (empty($password)) {
        $error = "Password is required.";
        $valid = false;
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
        $valid = false;
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
        $valid = false;
    }
    
    // If validation passed, check database
    if ($valid) {
        try {
            // Connect to database
            $database = new Database();
            $db = $database->getConnection();
            
            // Check if email already exists
            $query = "SELECT id FROM users WHERE email = :email";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                $error = "Email already registered. Please use a different email.";
            } else {
                // Hash the password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                // Insert new user
                $query = "INSERT INTO users (first_name, last_name, email, password, created_at) 
                          VALUES (:first_name, :last_name, :email, :password, NOW())";
                
                $stmt = $db->prepare($query);
                $stmt->bindParam(':first_name', $first_name);
                $stmt->bindParam(':last_name', $last_name);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $hashed_password);
                
                if ($stmt->execute()) {
                    // Registration successful
                    $success = "Registration successful! Redirecting to login page...";
                    
                    // Clear output buffer and redirect
                    ob_end_clean();
                    
                    // Store success message in session for login page
                    session_start();
                    $_SESSION['registration_success'] = true;
                    $_SESSION['success_message'] = "Registration successful! You can now login.";
                    
                    header('Location: login.php');
                    exit();
                } else {
                    $error = "Registration failed. Please try again.";
                }
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}

// Include header after all processing
include 'includes/header.php';
?>

<div class="container" style="max-width: 500px; margin: 50px auto;">
    <div class="card" style="background: white; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); padding: 40px;">
        <h2 style="text-align: center; margin-bottom: 30px; color: #333;">Create Account</h2>
        
        <?php if ($error): ?>
            <div class="alert" style="background: #ffebee; color: #c62828; padding: 12px 15px; border-radius: 5px; margin-bottom: 20px; border-left: 4px solid #c62828;">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert" style="background: #e8f5e9; color: #2e7d32; padding: 12px 15px; border-radius: 5px; margin-bottom: 20px; border-left: 4px solid #2e7d32;">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #555;">First Name</label>
                <input type="text" name="first_name" 
                       style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px;"
                       value="<?php echo htmlspecialchars($first_name); ?>"
                       required>
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #555;">Last Name</label>
                <input type="text" name="last_name" 
                       style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px;"
                       value="<?php echo htmlspecialchars($last_name); ?>"
                       required>
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #555;">Email Address</label>
                <input type="email" name="email" 
                       style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px;"
                       value="<?php echo htmlspecialchars($email); ?>"
                       required>
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #555;">Password</label>
                <input type="password" name="password" 
                       style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px;"
                       required
                       minlength="6">
                <small style="display: block; margin-top: 5px; color: #777;">Must be at least 6 characters</small>
            </div>
            
            <div style="margin-bottom: 30px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #555;">Confirm Password</label>
                <input type="password" name="confirm_password" 
                       style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px;"
                       required>
            </div>
            
            <button type="submit" 
                    style="width: 100%; padding: 14px; background: linear-gradient(135deg, #FF6B6B, #4ECDC4); 
                           color: white; border: none; border-radius: 5px; font-size: 16px; 
                           font-weight: 600; cursor: pointer; transition: all 0.3s;">
                Register
            </button>
            
            <div style="text-align: center; margin-top: 25px; padding-top: 25px; border-top: 1px solid #eee;">
                <p style="color: #666; margin: 0;">
                    Already have an account? 
                    <a href="login.php" style="color: #FF6B6B; text-decoration: none; font-weight: 500;">
                        Login here
                    </a>
                </p>
            </div>
        </form>
    </div>
</div>

<?php 
// End output buffering and flush content
ob_end_flush();
include 'includes/footer.php'; 
?>