<?php
/**
 * General Utility Functions for FoodFusion
 * Provides common functions used throughout the application
 */

/**
 * Redirect to specified URL
 * @param string $url URL to redirect to
 * @param int $status_code HTTP status code for redirect
 */
function redirect($url, $status_code = 302) {
    header("Location: $url", true, $status_code);
    exit;
}

/**
 * Set flash message for next request
 * @param string $type Message type (success, error, warning, info)
 * @param string $message Message content
 */
function setFlashMessage($type, $message) {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['flash_messages'][$type][] = $message;
}

/**
 * Get and clear flash messages
 * @return array Array of flash messages
 */
function getFlashMessages() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    $messages = isset($_SESSION['flash_messages']) ? $_SESSION['flash_messages'] : [];
    unset($_SESSION['flash_messages']);
    return $messages;
}

/**
 * Display flash messages
 * @param string $type Specific message type to display (optional)
 */
function displayFlashMessages($type = null) {
    $messages = getFlashMessages();
    
    if (empty($messages)) {
        return;
    }
    
    if ($type) {
        if (isset($messages[$type])) {
            foreach ($messages[$type] as $message) {
                echo '<div class="alert alert-' . htmlspecialchars($type) . '">' . htmlspecialchars($message) . '</div>';
            }
        }
    } else {
        foreach ($messages as $msg_type => $type_messages) {
            foreach ($type_messages as $message) {
                echo '<div class="alert alert-' . htmlspecialchars($msg_type) . '">' . htmlspecialchars($message) . '</div>';
            }
        }
    }
}

/**
 * Validate email address
 * @param string $email Email to validate
 * @return bool True if email is valid, false otherwise
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate password strength
 * @param string $password Password to validate
 * @param int $min_length Minimum password length
 * @return bool True if password meets requirements, false otherwise
 */
function isPasswordStrong($password, $min_length = 6) {
    return strlen($password) >= $min_length;
}

/**
 * Generate random string
 * @param int $length Length of random string
 * @return string Random string
 */
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $characters_length = strlen($characters);
    $random_string = '';
    
    for ($i = 0; $i < $length; $i++) {
        $random_string .= $characters[rand(0, $characters_length - 1)];
    }
    
    return $random_string;
}

/**
 * Format date for display
 * @param string $date_string Date string
 * @param string $format Date format
 * @return string Formatted date
 */
function formatDate($date_string, $format = 'F j, Y') {
    $date = new DateTime($date_string);
    return $date->format($format);
}

/**
 * Truncate text to specified length
 * @param string $text Text to truncate
 * @param int $length Maximum length
 * @param string $suffix Suffix to add if truncated
 * @return string Truncated text
 */
function truncateText($text, $length = 100, $suffix = '...') {
    if (strlen($text) <= $length) {
        return $text;
    }
    
    $truncated = substr($text, 0, $length);
    $last_space = strrpos($truncated, ' ');
    
    if ($last_space !== false) {
        $truncated = substr($truncated, 0, $last_space);
    }
    
    return $truncated . $suffix;
}

/**
 * Get client IP address
 * @return string Client IP address
 */
function getClientIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

/**
 * Log activity to file
 * @param string $activity Activity description
 * @param string $user_id User ID (optional)
 */
function logActivity($activity, $user_id = null) {
    $log_file = __DIR__ . '/../logs/activity.log';
    $log_dir = dirname($log_file);
    
    // Create logs directory if it doesn't exist
    if (!is_dir($log_dir)) {
        mkdir($log_dir, 0755, true);
    }
    
    $timestamp = date('Y-m-d H:i:s');
    $ip = getClientIP();
    $user_info = $user_id ? "User: $user_id" : "Guest";
    
    $log_entry = "[$timestamp] [$ip] [$user_info] $activity" . PHP_EOL;
    
    file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX);
}

/**
 * Upload file with validation
 * @param array $file $_FILES array element
 * @param string $upload_dir Upload directory
 * @param array $allowed_types Allowed file types
 * @param int $max_size Maximum file size in bytes
 * @return array Result with 'success' boolean and 'message' or 'file_path'
 */
function uploadFile($file, $upload_dir, $allowed_types = ['jpg', 'jpeg', 'png', 'gif'], $max_size = 2097152) {
    $result = ['success' => false, 'message' => ''];
    
    // Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $result['message'] = 'File upload error: ' . $file['error'];
        return $result;
    }
    
    // Check file size
    if ($file['size'] > $max_size) {
        $result['message'] = 'File is too large. Maximum size: ' . ($max_size / 1024 / 1024) . 'MB';
        return $result;
    }
    
    // Check file type
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($file_extension, $allowed_types)) {
        $result['message'] = 'File type not allowed. Allowed types: ' . implode(', ', $allowed_types);
        return $result;
    }
    
    // Create upload directory if it doesn't exist
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    // Generate unique filename
    $filename = uniqid() . '_' . time() . '.' . $file_extension;
    $file_path = $upload_dir . '/' . $filename;
    
    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $file_path)) {
        $result['success'] = true;
        $result['file_path'] = $file_path;
        $result['message'] = 'File uploaded successfully';
    } else {
        $result['message'] = 'Failed to move uploaded file';
    }
    
    return $result;
}

/**
 * Send email
 * @param string $to Recipient email
 * @param string $subject Email subject
 * @param string $body Email body
 * @param string $from Sender email
 * @return bool True if email sent successfully, false otherwise
 */
function sendEmail($to, $subject, $body, $from = 'noreply@foodfusion.com') {
    $headers = "From: $from\r\n";
    $headers .= "Reply-To: $from\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    
    return mail($to, $subject, $body, $headers);
}

/**
 * Get pagination parameters
 * @param int $total_items Total number of items
 * @param int $current_page Current page number
 * @param int $items_per_page Number of items per page
 * @return array Pagination data
 */
function getPagination($total_items, $current_page = 1, $items_per_page = 10) {
    $total_pages = ceil($total_items / $items_per_page);
    $current_page = max(1, min($current_page, $total_pages));
    $offset = ($current_page - 1) * $items_per_page;
    
    return [
        'total_items' => $total_items,
        'total_pages' => $total_pages,
        'current_page' => $current_page,
        'items_per_page' => $items_per_page,
        'offset' => $offset,
        'has_previous' => $current_page > 1,
        'has_next' => $current_page < $total_pages
    ];
}

/**
 * Generate pagination HTML
 * @param array $pagination Pagination data from getPagination()
 * @param string $base_url Base URL for pagination links
 * @return string Pagination HTML
 */
function generatePagination($pagination, $base_url) {
    if ($pagination['total_pages'] <= 1) {
        return '';
    }
    
    $html = '<div class="pagination">';
    
    // Previous link
    if ($pagination['has_previous']) {
        $html .= '<a href="' . $base_url . '?page=' . ($pagination['current_page'] - 1) . '" class="page-link prev">Previous</a>';
    }
    
    // Page links
    for ($i = 1; $i <= $pagination['total_pages']; $i++) {
        if ($i == $pagination['current_page']) {
            $html .= '<span class="page-link current">' . $i . '</span>';
        } else {
            $html .= '<a href="' . $base_url . '?page=' . $i . '" class="page-link">' . $i . '</a>';
        }
    }
    
    // Next link
    if ($pagination['has_next']) {
        $html .= '<a href="' . $base_url . '?page=' . ($pagination['current_page'] + 1) . '" class="page-link next">Next</a>';
    }
    
    $html .= '</div>';
    return $html;
}

/**
 * Escape string for database query
 * @param string $string String to escape
 * @return string Escaped string
 */
function escapeString($string) {
    include 'config/database.php';
    $database = new Database();
    $db = $database->getConnection();
    
    return $db->quote($string);
}

/**
 * Get database connection
 * @return PDO Database connection
 */
function getDBConnection() {
    include 'config/database.php';
    $database = new Database();
    return $database->getConnection();
}

/**
 * Check if request is AJAX
 * @return bool True if AJAX request, false otherwise
 */
function isAjaxRequest() {
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

/**
 * JSON response helper
 * @param array $data Data to encode as JSON
 * @param int $status_code HTTP status code
 */
function jsonResponse($data, $status_code = 200) {
    http_response_code($status_code);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

/**
 * Get current URL
 * @return string Current URL
 */
function getCurrentUrl() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $uri = $_SERVER['REQUEST_URI'];
    return $protocol . '://' . $host . $uri;
}

/**
 * Generate SEO-friendly slug
 * @param string $string String to convert to slug
 * @return string SEO-friendly slug
 */
function generateSlug($string) {
    $slug = strtolower(trim($string));
    $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
    $slug = preg_replace('/-+/', '-', $slug);
    $slug = trim($slug, '-');
    return $slug;
}

/**
 * Calculate recipe rating
 * @param int $recipe_id Recipe ID
 * @return array Rating data
 */
function calculateRecipeRating($recipe_id) {
    $db = getDBConnection();
    
    $query = "SELECT AVG(rating) as average_rating, COUNT(*) as total_ratings 
              FROM recipe_ratings 
              WHERE recipe_id = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$recipe_id]);
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return [
        'average' => $result['average_rating'] ? round($result['average_rating'], 1) : 0,
        'total' => $result['total_ratings'] ? $result['total_ratings'] : 0
    ];
}
?>