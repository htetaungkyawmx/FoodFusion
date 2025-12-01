<?php
// includes/header.php ရဲ့အစမှာ
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) . ' - FoodFusion' : 'FoodFusion'; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* Temporary fix - remove any problematic CSS */
        .nav-links a {
            cursor: pointer !important;
            pointer-events: auto !important;
        }
    </style>
</head>
<body>
    <header class="header">
        <nav class="navbar container">
            <a href="index.php" class="logo">
                <i class="fas fa-utensils"></i>
                <span>FoodFusion</span>
            </a>
            
            <ul class="nav-links" id="navLinks">
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="recipes.php">Recipes</a></li>
                <li><a href="community.php">Community</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="resources.php">Resources</a></li>
                <li><a href="education.php">Educational</a></li>
                
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li class="user-menu">
                        <a href="profile.php">
                            <i class="fas fa-user"></i>
                            <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                        </a>
                    </li>
                <?php else: ?>
                    <li><a href="login.php" class="btn-login" onclick="return true;">Login</a></li>
                    <li><a href="register.php" class="btn-register" onclick="return true;">Register</a></li>
                <?php endif; ?>
            </ul>
            
            <div class="hamburger" id="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </nav>
    </header>

    <script>
    // Simple JavaScript without interfering with links
    document.addEventListener('DOMContentLoaded', function() {
        const hamburger = document.getElementById('hamburger');
        const navLinks = document.getElementById('navLinks');
        
        if (hamburger && navLinks) {
            hamburger.addEventListener('click', function() {
                navLinks.classList.toggle('active');
            });
        }
        
        // Make sure all links work
        document.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function(e) {
                // Allow all links to work normally
                return true;
            });
        });
    });
    </script>
    