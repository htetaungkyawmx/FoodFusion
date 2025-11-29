<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - FoodFusion' : 'FoodFusion - Culinary Community'; ?></title>
    <meta name="description" content="FoodFusion - Your culinary community for sharing recipes, cooking tips, and food experiences">
    
    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.ico">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <nav class="navbar">
                <div class="nav-brand">
                    <a href="index.php" class="logo">
                        <i class="fas fa-utensils"></i>
                        <span>FoodFusion</span>
                    </a>
                </div>
                
                <ul class="nav-links" id="navLinks">
                    <li><a href="index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">Home</a></li>
                    <li><a href="about.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : ''; ?>">About</a></li>
                    <li><a href="recipes.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'recipes.php' ? 'active' : ''; ?>">Recipes</a></li>
                    <li><a href="community.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'community.php' ? 'active' : ''; ?>">Community</a></li>
                    <li><a href="resources.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'resources.php' ? 'active' : ''; ?>">Resources</a></li>
                    <li><a href="education.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'education.php' ? 'active' : ''; ?>">Education</a></li>
                    <li><a href="contact.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : ''; ?>">Contact</a></li>
                    
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <li class="user-menu dropdown">
                            <a href="#" class="user-toggle">
                                <i class="fas fa-user-circle"></i>
                                <span><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                                <i class="fas fa-chevron-down"></i>
                            </a>
                            <div class="dropdown-menu">
                                <a href="profile.php"><i class="fas fa-user"></i> Profile</a>
                                <a href="#"><i class="fas fa-bookmark"></i> Saved Recipes</a>
                                <a href="#"><i class="fas fa-cog"></i> Settings</a>
                                <div class="dropdown-divider"></div>
                                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                            </div>
                        </li>
                    <?php else: ?>
                        <li class="auth-buttons">
                            <a href="login.php" class="btn-login">Login</a>
                            <a href="register.php" class="btn-register">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
                
                <div class="nav-actions">
                    <button class="search-toggle" id="searchToggle">
                        <i class="fas fa-search"></i>
                    </button>
                    <div class="hamburger" id="hamburger">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            </nav>
            
            <!-- Search Bar -->
            <div class="search-bar" id="searchBar">
                <form action="recipes.php" method="GET" class="search-form">
                    <input type="text" name="search" placeholder="Search recipes, ingredients, or techniques..." class="search-input">
                    <button type="submit" class="search-btn">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
                <button class="search-close" id="searchClose">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </header>

    <!-- Flash Messages -->
    <?php
    if(isset($_SESSION['flash_messages'])) {
        foreach($_SESSION['flash_messages'] as $type => $messages) {
            foreach($messages as $message) {
                echo '<div class="alert alert-' . $type . '">' . htmlspecialchars($message) . '</div>';
            }
        }
        unset($_SESSION['flash_messages']);
    }
    ?>