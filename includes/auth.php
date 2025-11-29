<?php
/**
 * Enhanced Authentication Helper Functions for FoodFusion
 */

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Get current user ID
 */
function getCurrentUserId() {
    return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
}

/**
 * Get current user information
 */
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    include 'config/database.php';
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "SELECT id, first_name, last_name, email, profile_image, bio, created_at, is_admin FROM users WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$_SESSION['user_id']]);
    
    if ($stmt->rowCount() > 0) {
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    return null;
}

/**
 * Check if user is admin
 */
function isAdmin() {
    $user = getCurrentUser();
    return $user && $user['is_admin'];
}

/**
 * Check if account is locked
 */
function isAccountLocked($email) {
    include 'config/database.php';
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "SELECT locked_until FROM users WHERE email = ? AND locked_until > NOW()";
    $stmt = $db->prepare($query);
    $stmt->execute([$email]);
    
    return $stmt->rowCount() > 0;
}

/**
 * Get remaining lock time
 */
function getRemainingLockTime($email) {
    include 'config/database.php';
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "SELECT locked_until FROM users WHERE email = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$email]);
    
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user['locked_until']) {
            $lock_time = strtotime($user['locked_until']);
            $current_time = time();
            return max(0, $lock_time - $current_time);
        }
    }
    
    return 0;
}

/**
 * Increment failed login attempts
 */
function incrementFailedAttempts($email) {
    include 'config/database.php';
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "SELECT failed_attempts FROM users WHERE email = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$email]);
    
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $failed_attempts = $user['failed_attempts'] + 1;
        
        if ($failed_attempts >= 3) {
            $lock_time = date('Y-m-d H:i:s', strtotime('+3 minutes'));
            $query = "UPDATE users SET failed_attempts = ?, locked_until = ? WHERE email = ?";
            $stmt = $db->prepare($query);
            $stmt->execute([$failed_attempts, $lock_time, $email]);
            return true;
        } else {
            $query = "UPDATE users SET failed_attempts = ? WHERE email = ?";
            $stmt = $db->prepare($query);
            $stmt->execute([$failed_attempts, $email]);
            return false;
        }
    }
    
    return false;
}

/**
 * Reset failed login attempts
 */
function resetFailedAttempts($email) {
    include 'config/database.php';
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "UPDATE users SET failed_attempts = 0, locked_until = NULL WHERE email = ?";
    $stmt = $db->prepare($query);
    return $stmt->execute([$email]);
}

/**
 * Verify user credentials
 */
function verifyCredentials($email, $password) {
    include 'config/database.php';
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$email]);
    
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (password_verify($password, $user['password'])) {
            return $user;
        }
    }
    
    return false;
}

/**
 * Check if email exists
 */
function emailExists($email) {
    include 'config/database.php';
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "SELECT id FROM users WHERE email = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$email]);
    
    return $stmt->rowCount() > 0;
}

/**
 * Register new user
 */
function registerUser($first_name, $last_name, $email, $password) {
    include 'config/database.php';
    $database = new Database();
    $db = $database->getConnection();
    
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $query = "INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)";
    $stmt = $db->prepare($query);
    
    if ($stmt->execute([$first_name, $last_name, $email, $hashed_password])) {
        return $db->lastInsertId();
    }
    
    return false;
}

/**
 * Require user to be logged in
 */
function requireLogin($redirect_url = 'login.php') {
    if (!isLoggedIn()) {
        $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
        header("Location: $redirect_url");
        exit;
    }
}

/**
 * Require admin access
 */
function requireAdmin($redirect_url = 'index.php') {
    if (!isAdmin()) {
        setFlashMessage('error', 'Administrator access required.');
        header("Location: $redirect_url");
        exit;
    }
}

/**
 * Require user to be logged out
 */
function requireLogout($redirect_url = 'index.php') {
    if (isLoggedIn()) {
        header("Location: $redirect_url");
        exit;
    }
}

/**
 * Logout user
 */
function logoutUser() {
    $_SESSION = array();
    
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    session_destroy();
}

/**
 * Generate CSRF token
 */
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Validate CSRF token
 */
function validateCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Secure input data
 */
function sanitizeInput($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

/**
 * Update user profile
 */
function updateUserProfile($user_id, $first_name, $last_name, $bio, $profile_image = null) {
    include 'config/database.php';
    $database = new Database();
    $db = $database->getConnection();
    
    if ($profile_image) {
        $query = "UPDATE users SET first_name = ?, last_name = ?, bio = ?, profile_image = ? WHERE id = ?";
        $stmt = $db->prepare($query);
        return $stmt->execute([$first_name, $last_name, $bio, $profile_image, $user_id]);
    } else {
        $query = "UPDATE users SET first_name = ?, last_name = ?, bio = ? WHERE id = ?";
        $stmt = $db->prepare($query);
        return $stmt->execute([$first_name, $last_name, $bio, $user_id]);
    }
}
?>