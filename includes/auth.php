<?php
/**
 * Authentication Helper Functions for FoodFusion
 * Provides user authentication and authorization functions
 */

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if user is logged in
 * @return bool True if user is logged in, false otherwise
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Get current user ID
 * @return int|null User ID if logged in, null otherwise
 */
function getCurrentUserId() {
    return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
}

/**
 * Get current user information
 * @return array|null User data if logged in, null otherwise
 */
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    include 'config/database.php';
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "SELECT id, first_name, last_name, email, created_at FROM users WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$_SESSION['user_id']]);
    
    if ($stmt->rowCount() > 0) {
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    return null;
}

/**
 * Check if user account is locked
 * @param string $email User email
 * @return bool True if account is locked, false otherwise
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
 * Get remaining lock time for an account
 * @param string $email User email
 * @return int Remaining lock time in seconds
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
 * @param string $email User email
 * @return bool True if account is now locked, false otherwise
 */
function incrementFailedAttempts($email) {
    include 'config/database.php';
    $database = new Database();
    $db = $database->getConnection();
    
    // Get current failed attempts
    $query = "SELECT failed_attempts FROM users WHERE email = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$email]);
    
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $failed_attempts = $user['failed_attempts'] + 1;
        
        if ($failed_attempts >= 3) {
            // Lock account for 3 minutes
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
 * @param string $email User email
 * @return bool Success status
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
 * @param string $email User email
 * @param string $password User password
 * @return array|false User data if credentials are valid, false otherwise
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
 * Check if email already exists
 * @param string $email Email to check
 * @return bool True if email exists, false otherwise
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
 * @param string $first_name User first name
 * @param string $last_name User last name
 * @param string $email User email
 * @param string $password User password (plain text)
 * @return int|false New user ID if successful, false otherwise
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
 * @param string $redirect_url URL to redirect to if not logged in
 */
function requireLogin($redirect_url = 'login.php') {
    if (!isLoggedIn()) {
        $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
        header("Location: $redirect_url");
        exit;
    }
}

/**
 * Require user to be logged out
 * @param string $redirect_url URL to redirect to if logged in
 */
function requireLogout($redirect_url = 'index.php') {
    if (isLoggedIn()) {
        header("Location: $redirect_url");
        exit;
    }
}

/**
 * Logout user and clear session
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
 * @return string CSRF token
 */
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Validate CSRF token
 * @param string $token Token to validate
 * @return bool True if token is valid, false otherwise
 */
function validateCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Secure input data
 * @param string $data Input data
 * @return string Sanitized data
 */
function sanitizeInput($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}
?>