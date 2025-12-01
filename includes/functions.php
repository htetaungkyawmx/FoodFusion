<?php
// includes/functions.php

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
    include 'config/database.php';
    $database = new Database();
    $db = $database->getConnection();
    
    try {
        $query = "SELECT * FROM recipes WHERE featured = 1 ORDER BY created_at DESC LIMIT ?";
        $stmt = $db->prepare($query);
        $stmt->bindParam(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return [];
    }
}

function getUserRecipes($user_id) {
    include 'config/database.php';
    $database = new Database();
    $db = $database->getConnection();
    
    try {
        $query = "SELECT * FROM recipes WHERE user_id = ? ORDER BY created_at DESC";
        $stmt = $db->prepare($query);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return [];
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
?>