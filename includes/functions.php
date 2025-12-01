<?php
// Include database connection
include 'config/database.php';

function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function displayError($message) {
    return '<div class="alert alert-error">' . $message . '</div>';
}

function displaySuccess($message) {
    return '<div class="alert alert-success">' . $message . '</div>';
}

function getFeaturedRecipes($limit = 6) {
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "SELECT * FROM recipes WHERE is_featured = 1 ORDER BY created_at DESC LIMIT ?";
    $stmt = $db->prepare($query);
    $stmt->bindParam(1, $limit, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllRecipes($filters = []) {
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "SELECT * FROM recipes WHERE 1=1";
    $params = [];
    
    if (isset($filters['cuisine']) && $filters['cuisine']) {
        $query .= " AND cuisine_type = ?";
        $params[] = $filters['cuisine'];
    }
    
    if (isset($filters['difficulty']) && $filters['difficulty']) {
        $query .= " AND difficulty_level = ?";
        $params[] = $filters['difficulty'];
    }
    
    $query .= " ORDER BY created_at DESC";
    
    $stmt = $db->prepare($query);
    $stmt->execute($params);
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>