<?php
// logout.php
session_start();

// Destroy all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Delete session cookie if it exists
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Redirect to home page
header('Location: index.php');
exit();
?>