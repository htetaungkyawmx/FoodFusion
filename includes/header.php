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
        /* Header Styles - Only changed logo colors */
        .header {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
        }

        /* Updated Logo Styles - FoodFusion text in dark, icon in green */
        .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            font-size: 1.5rem;
            font-weight: bold;
            color: #333; /* Dark color for FoodFusion text */
        }

        .logo i {
            color: #4CAF50; /* Green color for icon */
        }

        .logo span {
            color: #333; /* Dark color for FoodFusion text */
        }

        .logo:hover {
            color: #4CAF50; /* Green on hover */
        }

        .logo:hover span {
            color: #4CAF50; /* Green on hover */
        }

        .nav-links {
            display: flex;
            list-style: none;
            align-items: center;
            gap: 2rem;
        }

        .nav-links a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: #4CAF50; /* Green on hover */
        }

        .btn-login {
            padding: 0.5rem 1.5rem;
            border: 2px solid #4CAF50; /* Green border */
            border-radius: 5px;
            color: #4CAF50; /* Green text */
            transition: all 0.3s;
        }

        .btn-login:hover {
            background: #4CAF50; /* Green background on hover */
            color: white;
        }

        .btn-register {
            padding: 0.5rem 1.5rem;
            background: #4CAF50; /* Green background */
            color: white;
            border-radius: 5px;
            transition: all 0.3s;
        }

        .btn-register:hover {
            background: #45a049; /* Darker green on hover */
        }

        /* Mobile Navigation */
        .hamburger {
            display: none;
            flex-direction: column;
            cursor: pointer;
        }

        .hamburger span {
            width: 25px;
            height: 3px;
            background: #333;
            margin: 2px 0;
            transition: 0.3s;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hamburger {
                display: flex;
            }
            
            .nav-links {
                position: fixed;
                top: 70px;
                left: -100%;
                width: 100%;
                flex-direction: column;
                background: white;
                padding: 2rem;
                transition: 0.3s;
            }
            
            .nav-links.active {
                left: 0;
            }
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
                    <li><a href="login.php" class="btn-login">Login</a></li>
                    <li><a href="register.php" class="btn-register">Register</a></li>
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