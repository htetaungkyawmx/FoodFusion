<?php
// includes/functions.php

// Include database configuration
if (!class_exists('Database')) {
    include_once __DIR__ . '/../config/database.php';
}

// Initialize database connection
function getDBConnection() {
    static $db = null;
    if ($db === null) {
        $database = new Database();
        $db = $database->getConnection();
    }
    return $db;
}

function sanitizeInput($data) {
    if (is_array($data)) {
        return array_map('sanitizeInput', $data);
    }
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    return $data;
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function getFeaturedRecipes($limit = 6) {
    $db = getDBConnection();
    
    try {
        $query = "SELECT r.*, u.first_name, u.last_name 
                  FROM recipes r 
                  LEFT JOIN users u ON r.user_id = u.id 
                  WHERE r.featured = 1 
                  ORDER BY r.created_at DESC 
                  LIMIT ?";
        $stmt = $db->prepare($query);
        $stmt->bindParam(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Database error in getFeaturedRecipes: " . $e->getMessage());
        return [];
    }
}

function getUserRecipes($user_id) {
    $db = getDBConnection();
    
    try {
        $query = "SELECT * FROM recipes WHERE user_id = ? ORDER BY created_at DESC";
        $stmt = $db->prepare($query);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Database error in getUserRecipes: " . $e->getMessage());
        return [];
    }
}

function getAllRecipes($filters = [], $limit = 12) {
    $db = getDBConnection();
    
    try {
        $query = "SELECT r.*, 
                         u.first_name, 
                         u.last_name,
                         u.profile_image
                  FROM recipes r 
                  LEFT JOIN users u ON r.user_id = u.id 
                  WHERE 1=1";
        
        $params = [];
        
        if (!empty($filters['cuisine'])) {
            $query .= " AND r.cuisine = ?";
            $params[] = $filters['cuisine'];
        }
        
        if (!empty($filters['difficulty'])) {
            $query .= " AND r.difficulty_level = ?";
            $params[] = $filters['difficulty'];
        }
        
        if (!empty($filters['category'])) {
            $query .= " AND r.category = ?";
            $params[] = $filters['category'];
        }
        
        if (!empty($filters['search'])) {
            $query .= " AND (r.title LIKE ? OR r.description LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        $query .= " ORDER BY r.created_at DESC LIMIT ?";
        $params[] = $limit;
        
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Database error in getAllRecipes: " . $e->getMessage());
        return [];
    }
}

function getRecipeById($id) {
    $db = getDBConnection();
    
    try {
        $query = "SELECT r.*, 
                         u.first_name, 
                         u.last_name,
                         u.profile_image,
                         u.bio as user_bio
                  FROM recipes r 
                  LEFT JOIN users u ON r.user_id = u.id 
                  WHERE r.id = ?";
        
        $stmt = $db->prepare($query);
        $stmt->execute([$id]);
        $recipe = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($recipe) {
            // Get recipe ingredients
            $ingQuery = "SELECT * FROM recipe_ingredients WHERE recipe_id = ? ORDER BY step_order";
            $ingStmt = $db->prepare($ingQuery);
            $ingStmt->execute([$id]);
            $recipe['ingredients'] = $ingStmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Get recipe instructions
            $instQuery = "SELECT * FROM recipe_instructions WHERE recipe_id = ? ORDER BY step_number";
            $instStmt = $db->prepare($instQuery);
            $instStmt->execute([$id]);
            $recipe['instructions'] = $instStmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        return $recipe;
    } catch (PDOException $e) {
        error_log("Database error in getRecipeById: " . $e->getMessage());
        return null;
    }
}

function redirect($url, $message = '') {
    if ($message) {
        $_SESSION['flash_message'] = $message;
    }
    header('Location: ' . $url);
    exit();
}

function getFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return $message;
    }
    return '';
}

function generateSlug($string) {
    $string = preg_replace('/[^a-zA-Z0-9\s]/', '', $string);
    $string = strtolower(trim($string));
    $string = preg_replace('/\s+/', '-', $string);
    return $string;
}

function formatDate($date) {
    return date('F j, Y', strtotime($date));
}

function truncateText($text, $length = 100) {
    if (strlen($text) <= $length) {
        return $text;
    }
    return substr($text, 0, $length) . '...';
}

function timeAgo($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

function getCategories() {
    $db = getDBConnection();
    
    try {
        $query = "SELECT DISTINCT category FROM recipes WHERE category IS NOT NULL AND category != '' ORDER BY category";
        $stmt = $db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    } catch (PDOException $e) {
        error_log("Database error in getCategories: " . $e->getMessage());
        return [];
    }
}

function getCuisines() {
    $db = getDBConnection();
    
    try {
        $query = "SELECT DISTINCT cuisine FROM recipes WHERE cuisine IS NOT NULL AND cuisine != '' ORDER BY cuisine";
        $stmt = $db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    } catch (PDOException $e) {
        error_log("Database error in getCuisines: " . $e->getMessage());
        return [];
    }
}

function getDifficultyLevels() {
    return ['Easy', 'Medium', 'Hard'];
}

function getRecipeCount() {
    $db = getDBConnection();
    
    try {
        $query = "SELECT COUNT(*) as count FROM recipes";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] ?? 0;
    } catch (PDOException $e) {
        error_log("Database error in getRecipeCount: " . $e->getMessage());
        return 0;
    }
}

function increaseRecipeViews($recipe_id) {
    $db = getDBConnection();
    
    try {
        $query = "UPDATE recipes SET views = views + 1 WHERE id = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$recipe_id]);
        return true;
    } catch (PDOException $e) {
        error_log("Database error in increaseRecipeViews: " . $e->getMessage());
        return false;
    }
}

function saveRecipe($user_id, $recipe_id) {
    $db = getDBConnection();
    
    try {
        // Check if already saved
        $checkQuery = "SELECT COUNT(*) as count FROM saved_recipes WHERE user_id = ? AND recipe_id = ?";
        $checkStmt = $db->prepare($checkQuery);
        $checkStmt->execute([$user_id, $recipe_id]);
        $result = $checkStmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result['count'] > 0) {
            // Already saved, so unsave it
            $deleteQuery = "DELETE FROM saved_recipes WHERE user_id = ? AND recipe_id = ?";
            $deleteStmt = $db->prepare($deleteQuery);
            $deleteStmt->execute([$user_id, $recipe_id]);
            return 'unsaved';
        } else {
            // Save the recipe
            $insertQuery = "INSERT INTO saved_recipes (user_id, recipe_id, saved_at) VALUES (?, ?, NOW())";
            $insertStmt = $db->prepare($insertQuery);
            $insertStmt->execute([$user_id, $recipe_id]);
            return 'saved';
        }
    } catch (PDOException $e) {
        error_log("Database error in saveRecipe: " . $e->getMessage());
        return false;
    }
}

function isRecipeSaved($user_id, $recipe_id) {
    $db = getDBConnection();
    
    try {
        $query = "SELECT COUNT(*) as count FROM saved_recipes WHERE user_id = ? AND recipe_id = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$user_id, $recipe_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    } catch (PDOException $e) {
        error_log("Database error in isRecipeSaved: " . $e->getMessage());
        return false;
    }
}

function getUserSavedRecipes($user_id) {
    $db = getDBConnection();
    
    try {
        $query = "SELECT r.*, 
                         u.first_name, 
                         u.last_name,
                         sr.saved_at
                  FROM saved_recipes sr
                  JOIN recipes r ON sr.recipe_id = r.id
                  JOIN users u ON r.user_id = u.id
                  WHERE sr.user_id = ?
                  ORDER BY sr.saved_at DESC";
        
        $stmt = $db->prepare($query);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Database error in getUserSavedRecipes: " . $e->getMessage());
        return [];
    }
}

function likeRecipe($user_id, $recipe_id) {
    $db = getDBConnection();
    
    try {
        // Check if already liked
        $checkQuery = "SELECT COUNT(*) as count FROM recipe_likes WHERE user_id = ? AND recipe_id = ?";
        $checkStmt = $db->prepare($checkQuery);
        $checkStmt->execute([$user_id, $recipe_id]);
        $result = $checkStmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result['count'] > 0) {
            // Already liked, so unlike it
            $deleteQuery = "DELETE FROM recipe_likes WHERE user_id = ? AND recipe_id = ?";
            $deleteStmt = $db->prepare($deleteQuery);
            $deleteStmt->execute([$user_id, $recipe_id]);
            return 'unliked';
        } else {
            // Like the recipe
            $insertQuery = "INSERT INTO recipe_likes (user_id, recipe_id, created_at) VALUES (?, ?, NOW())";
            $insertStmt = $db->prepare($insertQuery);
            $insertStmt->execute([$user_id, $recipe_id]);
            return 'liked';
        }
    } catch (PDOException $e) {
        error_log("Database error in likeRecipe: " . $e->getMessage());
        return false;
    }
}

function getRecipeLikesCount($recipe_id) {
    $db = getDBConnection();
    
    try {
        $query = "SELECT COUNT(*) as count FROM recipe_likes WHERE recipe_id = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$recipe_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] ?? 0;
    } catch (PDOException $e) {
        error_log("Database error in getRecipeLikesCount: " . $e->getMessage());
        return 0;
    }
}

function isRecipeLiked($user_id, $recipe_id) {
    $db = getDBConnection();
    
    try {
        $query = "SELECT COUNT(*) as count FROM recipe_likes WHERE user_id = ? AND recipe_id = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$user_id, $recipe_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    } catch (PDOException $e) {
        error_log("Database error in isRecipeLiked: " . $e->getMessage());
        return false;
    }
}

function addRecipeComment($user_id, $recipe_id, $comment, $parent_id = null) {
    $db = getDBConnection();
    
    try {
        $query = "INSERT INTO recipe_comments (recipe_id, user_id, parent_id, comment, created_at) 
                  VALUES (?, ?, ?, ?, NOW())";
        $stmt = $db->prepare($query);
        $stmt->execute([$recipe_id, $user_id, $parent_id, $comment]);
        return $db->lastInsertId();
    } catch (PDOException $e) {
        error_log("Database error in addRecipeComment: " . $e->getMessage());
        return false;
    }
}

function getRecipeComments($recipe_id) {
    $db = getDBConnection();
    
    try {
        $query = "SELECT c.*, 
                         u.first_name, 
                         u.last_name,
                         u.profile_image
                  FROM recipe_comments c
                  JOIN users u ON c.user_id = u.id
                  WHERE c.recipe_id = ? AND c.parent_id IS NULL
                  ORDER BY c.created_at DESC";
        
        $stmt = $db->prepare($query);
        $stmt->execute([$recipe_id]);
        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Get replies for each comment
        foreach ($comments as &$comment) {
            $replyQuery = "SELECT rc.*, 
                                  u.first_name, 
                                  u.last_name,
                                  u.profile_image
                           FROM recipe_comments rc
                           JOIN users u ON rc.user_id = u.id
                           WHERE rc.parent_id = ?
                           ORDER BY rc.created_at ASC";
            
            $replyStmt = $db->prepare($replyQuery);
            $replyStmt->execute([$comment['id']]);
            $comment['replies'] = $replyStmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        return $comments;
    } catch (PDOException $e) {
        error_log("Database error in getRecipeComments: " . $e->getMessage());
        return [];
    }
}

function getRecipeCommentCount($recipe_id) {
    $db = getDBConnection();
    
    try {
        $query = "SELECT COUNT(*) as count FROM recipe_comments WHERE recipe_id = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$recipe_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] ?? 0;
    } catch (PDOException $e) {
        error_log("Database error in getRecipeCommentCount: " . $e->getMessage());
        return 0;
    }
}

function countRecipes() {
    $db = getDBConnection();
    
    try {
        $query = "SELECT COUNT(*) as total FROM recipes";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    } catch (PDOException $e) {
        error_log("Database error in countRecipes: " . $e->getMessage());
        return 0;
    }
}

function countUsers() {
    $db = getDBConnection();
    
    try {
        $query = "SELECT COUNT(*) as total FROM users";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    } catch (PDOException $e) {
        error_log("Database error in countUsers: " . $e->getMessage());
        return 0;
    }
}
?>