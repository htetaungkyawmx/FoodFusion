<?php
session_start();
include '../config/database.php';
include 'auth.php';
include 'functions.php';

// Require login
requireLogin();

if($_POST) {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $post_type = $_POST['post_type'];
    $csrf_token = $_POST['csrf_token'] ?? '';
    
    // Validate CSRF token
    if (!validateCSRFToken($csrf_token)) {
        $_SESSION['post_error'] = "Security token invalid. Please try again.";
        header("Location: ../community.php");
        exit;
    }
    
    // Validation
    if(empty($title) || empty($content) || empty($post_type)) {
        $_SESSION['post_error'] = "All fields are required.";
        header("Location: ../community.php");
        exit;
    }
    
    if(strlen($title) < 5) {
        $_SESSION['post_error'] = "Title must be at least 5 characters long.";
        header("Location: ../community.php");
        exit;
    }
    
    if(strlen($content) < 10) {
        $_SESSION['post_error'] = "Content must be at least 10 characters long.";
        header("Location: ../community.php");
        exit;
    }
    
    $user_id = $_SESSION['user_id'];
    
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "INSERT INTO community_posts (user_id, title, content, post_type) VALUES (?, ?, ?, ?)";
    $stmt = $db->prepare($query);
    
    if($stmt->execute([$user_id, $title, $content, $post_type])) {
        // Log activity
        logActivity("Community post created: $title", $user_id);
        
        $_SESSION['post_success'] = "Your post has been shared with the community!";
    } else {
        $_SESSION['post_error'] = "Sorry, there was an error sharing your post. Please try again.";
    }
    
    header("Location: ../community.php");
    exit;
} else {
    header("Location: ../community.php");
    exit;
}
?>