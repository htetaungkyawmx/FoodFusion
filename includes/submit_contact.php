<?php
session_start();
include '../config/database.php';

if($_POST) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);
    
    // Basic validation
    if(empty($name) || empty($email) || empty($subject) || empty($message)) {
        $_SESSION['contact_error'] = "All fields are required.";
        header("Location: ../contact.php");
        exit;
    }
    
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['contact_error'] = "Please enter a valid email address.";
        header("Location: ../contact.php");
        exit;
    }
    
    $database = new Database();
    $db = $database->getConnection();
    
    // Insert contact message
    $query = "INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)";
    $stmt = $db->prepare($query);
    
    if($stmt->execute([$name, $email, $subject, $message])) {
        $_SESSION['contact_success'] = "Thank you for your message! We'll get back to you soon.";
    } else {
        $_SESSION['contact_error'] = "Sorry, there was an error sending your message. Please try again.";
    }
    
    header("Location: ../contact.php");
    exit;
}
?>