<?php
ob_start(); // Start output buffering at the very beginning

$page_title = "Login - FoodFusion";

// Include header first (header.php already has session_start())
include 'includes/header.php';

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$error = '';
$success = '';

// Check for registration success from session
if (isset($_SESSION['registration_success']) && $_SESSION['registration_success']) {
    $success = $_SESSION['success_message'] ?? 'Registration successful! You can now login.';
    unset($_SESSION['registration_success']);
    unset($_SESSION['success_message']);
}

// Check for success message from URL parameter
if (isset($_GET['message']) && $_GET['message'] === 'login_success') {
    $success = "Login successful! Welcome back.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'config/database.php';
    
    // Get and sanitize inputs
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';
    
    // Validation
    if (empty($email) || empty($password)) {
        $error = "Please enter both email and password.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        try {
            // Connect to database
            $database = new Database();
            $db = $database->getConnection();
            
            // Prepare query to get user by email
            $query = "SELECT * FROM users WHERE email = :email";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            
            // Check if user exists
            if ($stmt->rowCount() === 1) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // Verify password
                if (password_verify($password, $user['password'])) {
                    // Set session variables
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_first_name'] = $user['first_name'];
                    $_SESSION['user_last_name'] = $user['last_name'];
                    $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                    $_SESSION['user_email'] = $user['email'];
                    
                    // Clear output buffer and redirect
                    ob_end_clean();
                    header('Location: index.php?message=login_success');
                    exit();
                } else {
                    $error = "Invalid email or password.";
                }
            } else {
                $error = "Invalid email or password.";
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}
?>

<div class="container" style="max-width: 500px; margin: 50px auto;">
    <div class="card" style="background: white; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); padding: 40px;">
        <h2 style="text-align: center; margin-bottom: 30px; color: #333;">Login to FoodFusion</h2>
        
        <?php if ($success): ?>
            <div class="alert" style="background: #e8f5e9; color: #2e7d32; padding: 12px 15px; border-radius: 5px; margin-bottom: 20px; border-left: 4px solid #2e7d32;">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert" style="background: #ffebee; color: #c62828; padding: 12px 15px; border-radius: 5px; margin-bottom: 20px; border-left: 4px solid #c62828;">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #555;">Email Address</label>
                <input type="email" name="email" 
                       style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px;"
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                       required>
            </div>
            
            <div style="margin-bottom: 30px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #555;">Password</label>
                <input type="password" name="password" 
                       style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px;"
                       required>
            </div>
            
            <button type="submit" 
                    style="width: 100%; padding: 14px; background: linear-gradient(135deg, #FF6B6B, #4ECDC4); 
                           color: white; border: none; border-radius: 5px; font-size: 16px; 
                           font-weight: 600; cursor: pointer; transition: all 0.3s;">
                Login
            </button>
            
            <div style="text-align: center; margin-top: 25px; padding-top: 25px; border-top: 1px solid #eee;">
                <p style="color: #666; margin: 0;">
                    Don't have an account? 
                    <a href="register.php" style="color: #FF6B6B; text-decoration: none; font-weight: 500;">
                        Register here
                    </a>
                </p>
            </div>
        </form>
    </div>
</div>

<?php 
ob_end_flush();
include 'includes/footer.php'; 
?>