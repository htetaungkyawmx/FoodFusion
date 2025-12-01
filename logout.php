<?php
// logout.php with confirmation option
session_start();

// Check if confirmation was given
if (!isset($_GET['confirm']) || $_GET['confirm'] !== 'true') {
    // Show confirmation page
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Confirm Logout - FoodFusion</title>
        <link rel="stylesheet" href="assets/css/style.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    </head>
    <body>
        <div class="container" style="display: flex; justify-content: center; align-items: center; min-height: 100vh;">
            <div class="form-container" style="text-align: center;">
                <h2><i class="fas fa-sign-out-alt"></i> Confirm Logout</h2>
                <p style="margin: 1.5rem 0;">Are you sure you want to logout?</p>
                <div style="display: flex; gap: 1rem; justify-content: center;">
                    <a href="index.php" class="btn" style="background: #6c757d;">Cancel</a>
                    <a href="logout.php?confirm=true" class="btn btn-logout">Yes, Logout</a>
                </div>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit();
}

// If confirmed, proceed with logout
$_SESSION = array();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();

// Redirect with success message
header('Location: index.php?message=logout_success');
exit();
?>