<?php
/**
 * Enhanced Utility Functions for FoodFusion
 */

/**
 * Set flash message
 */
function setFlashMessage($type, $message) {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['flash_messages'][$type][] = $message;
}

/**
 * Get flash messages
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
 * Validate email
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate password strength
 */
function isPasswordStrong($password, $min_length = 6) {
    return strlen($password) >= $min_length;
}

/**
 * Redirect to URL
 */
function redirect($url, $status_code = 302) {
    header("Location: $url", true, $status_code);
    exit;
}

/**
 * Generate random string
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
 * Format date
 */
function formatDate($date_string, $format = 'F j, Y') {
    $date = new DateTime($date_string);
    return $date->format($format);
}

/**
 * Truncate text
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
 * Get client IP
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
 * Upload file with validation
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
        $result['filename'] = $filename;
        $result['message'] = 'File uploaded successfully';
    } else {
        $result['message'] = 'Failed to move uploaded file';
    }
    
    return $result;
}

/**
 * Get pagination parameters
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
 */
function generatePagination($pagination, $base_url) {
    if ($pagination['total_pages'] <= 1) {
        return '';
    }
    
    $html = '<div class="pagination">';
    
    // Previous link
    if ($pagination['has_previous']) {
        $html .= '<a href="' . $base_url . '?page=' . ($pagination['current_page'] - 1) . '" class="page-link prev"><i class="fas fa-chevron-left"></i> Previous</a>';
    }
    
    // Page links
    $start_page = max(1, $pagination['current_page'] - 2);
    $end_page = min($pagination['total_pages'], $pagination['current_page'] + 2);
    
    for ($i = $start_page; $i <= $end_page; $i++) {
        if ($i == $pagination['current_page']) {
            $html .= '<span class="page-link current">' . $i . '</span>';
        } else {
            $html .= '<a href="' . $base_url . '?page=' . $i . '" class="page-link">' . $i . '</a>';
        }
    }
    
    // Next link
    if ($pagination['has_next']) {
        $html .= '<a href="' . $base_url . '?page=' . ($pagination['current_page'] + 1) . '" class="page-link next">Next <i class="fas fa-chevron-right"></i></a>';
    }
    
    $html .= '</div>';
    return $html;
}

/**
 * Get database connection
 */
function getDBConnection() {
    include 'config/database.php';
    $database = new Database();
    return $database->getConnection();
}

/**
 * Check if request is AJAX
 */
function isAjaxRequest() {
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

/**
 * JSON response helper
 */
function jsonResponse($data, $status_code = 200) {
    http_response_code($status_code);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

/**
 * Generate SEO-friendly slug
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
 */
function calculateRecipeRating($recipe_id) {
    $db = getDBConnection();
    
    $query = "SELECT AVG(rating) as average_rating, COUNT(*) as total_ratings 
              FROM recipe_reviews 
              WHERE recipe_id = ? AND rating IS NOT NULL";
    $stmt = $db->prepare($query);
    $stmt->execute([$recipe_id]);
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return [
        'average' => $result['average_rating'] ? round($result['average_rating'], 1) : 0,
        'total' => $result['total_ratings'] ? $result['total_ratings'] : 0
    ];
}

/**
 * Log activity
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
 * Send email notification
 */
function sendEmailNotification($to, $subject, $body, $from = 'noreply@foodfusion.com') {
    $headers = "From: $from\r\n";
    $headers .= "Reply-To: $from\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    
    return mail($to, $subject, $body, $headers);
}

/**
 * Get featured recipes
 */
function getFeaturedRecipes($limit = 6) {
    $db = getDBConnection();
    
    $query = "SELECT r.*, u.first_name, u.last_name 
              FROM recipes r 
              JOIN users u ON r.user_id = u.id 
              WHERE r.is_featured = 1 
              ORDER BY r.created_at DESC 
              LIMIT ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$limit]);
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Get recent community posts
 */
function getRecentCommunityPosts($limit = 10) {
    $db = getDBConnection();
    
    $query = "SELECT cp.*, u.first_name, u.last_name 
              FROM community_posts cp 
              JOIN users u ON cp.user_id = u.id 
              ORDER BY cp.created_at DESC 
              LIMIT ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$limit]);
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>