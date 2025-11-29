<?php
session_start();
include 'config/database.php';

$error = '';

if($_POST) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    if(empty($email) || empty($password)) {
        $error = "Please enter both email and password.";
    } else {
        $database = new Database();
        $db = $database->getConnection();
        
        // Check if account is locked
        $query = "SELECT * FROM users WHERE email = ? AND locked_until > NOW()";
        $stmt = $db->prepare($query);
        $stmt->execute([$email]);
        
        if($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $lock_time = strtotime($user['locked_until']);
            $current_time = time();
            $remaining_time = ceil(($lock_time - $current_time) / 60);
            $error = "Account locked. Try again in " . $remaining_time . " minutes.";
        } else {
            // Verify user
            $query = "SELECT * FROM users WHERE email = ?";
            $stmt = $db->prepare($query);
            $stmt->execute([$email]);
            
            if($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if(password_verify($password, $user['password'])) {
                    // Successful login
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['first_name'];
                    
                    // Reset failed attempts
                    $query = "UPDATE users SET failed_attempts = 0, locked_until = NULL WHERE id = ?";
                    $stmt = $db->prepare($query);
                    $stmt->execute([$user['id']]);
                    
                    header("Location: index.php");
                    exit;
                } else {
                    // Failed login
                    $failed_attempts = $user['failed_attempts'] + 1;
                    
                    if($failed_attempts >= 3) {
                        // Lock account for 3 minutes
                        $lock_time = date('Y-m-d H:i:s', strtotime('+3 minutes'));
                        $query = "UPDATE users SET failed_attempts = ?, locked_until = ? WHERE id = ?";
                        $stmt = $db->prepare($query);
                        $stmt->execute([$failed_attempts, $lock_time, $user['id']]);
                        $error = "Account locked due to multiple failed attempts. Try again in 3 minutes.";
                    } else {
                        $query = "UPDATE users SET failed_attempts = ? WHERE id = ?";
                        $stmt = $db->prepare($query);
                        $stmt->execute([$failed_attempts, $user['id']]);
                        $error = "Invalid password. Attempts: " . $failed_attempts . "/3";
                    }
                }
            } else {
                $error = "No account found with this email.";
            }
        }
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="container">
    <div class="form-container">
        <h2>Login to FoodFusion</h2>
        
        <?php if($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <input type="email" name="email" placeholder="Email Address" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
            </div>
            
            <div class="form-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            
            <button type="submit" class="btn">Login</button>
        </form>
        
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>