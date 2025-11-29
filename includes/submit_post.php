<?php
session_start();
include '../config/database.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

if($_POST) {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $post_type = $_POST['post_type'];
    $user_id = $_SESSION['user_id'];
    
    if(empty($title) || empty($content) || empty($post_type)) {
        $_SESSION['post_error'] = "All fields are required.";
        header("Location: ../community.php");
        exit;
    }
    
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "INSERT INTO community_posts (user_id, title, content, post_type) VALUES (?, ?, ?, ?)";
    $stmt = $db->prepare($query);
    
    if($stmt->execute([$user_id, $title, $content, $post_type])) {
        $_SESSION['post_success'] = "Your post has been shared with the community!";
    } else {
        $_SESSION['post_error'] = "Sorry, there was an error sharing your post. Please try again.";
    }
    
    header("Location: ../community.php");
    exit;
}
?>