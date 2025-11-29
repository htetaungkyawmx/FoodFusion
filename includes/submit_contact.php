<?php
session_start();
include '../config/database.php';
include 'functions.php';

if($_POST) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);
    $csrf_token = $_POST['csrf_token'] ?? '';
    
    // Validate CSRF token
    if (!validateCSRFToken($csrf_token)) {
        $_SESSION['contact_error'] = "Security token invalid. Please try again.";
        header("Location: ../contact.php");
        exit;
    }
    
    // Basic validation
    if(empty($name) || empty($email) || empty($subject) || empty($message)) {
        $_SESSION['contact_error'] = "All fields are required.";
        header("Location: ../contact.php");
        exit;
    }
    
    if(!isValidEmail($email)) {
        $_SESSION['contact_error'] = "Please enter a valid email address.";
        header("Location: ../contact.php");
        exit;
    }
    
    // Validate message length
    if(strlen($message) < 10) {
        $_SESSION['contact_error'] = "Message must be at least 10 characters long.";
        header("Location: ../contact.php");
        exit;
    }
    
    $database = new Database();
    $db = $database->getConnection();
    
    // Insert contact message
    $query = "INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)";
    $stmt = $db->prepare($query);
    
    if($stmt->execute([$name, $email, $subject, $message])) {
        // Log activity
        logActivity("Contact form submitted by: $email");
        
        // Send email notification (optional)
        $email_body = "
            <h2>New Contact Form Submission</h2>
            <p><strong>Name:</strong> $name</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Subject:</strong> $subject</p>
            <p><strong>Message:</strong></p>
            <p>$message</p>
        ";
        
        // sendEmailNotification('admin@foodfusion.com', 'New Contact Form Submission', $email_body);
        
        $_SESSION['contact_success'] = "Thank you for your message! We'll get back to you within 24 hours.";
    } else {
        $_SESSION['contact_error'] = "Sorry, there was an error sending your message. Please try again.";
    }
    
    header("Location: ../contact.php");
    exit;
} else {
    header("Location: ../contact.php");
    exit;
}
?>