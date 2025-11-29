    </div>

    <!-- Cookie Consent -->
    <div id="cookieConsent" class="cookie-consent">
        <div class="cookie-content">
            <div class="cookie-text">
                <p>We use cookies to enhance your experience, analyze site traffic, and for marketing purposes. By continuing to visit this site you agree to our use of cookies.</p>
            </div>
            <div class="cookie-buttons">
                <button onclick="acceptCookies()" class="btn btn-primary btn-sm">Accept All</button>
                <button onclick="customizeCookies()" class="btn btn-outline btn-sm">Customize</button>
                <a href="privacy.php#cookies" class="cookie-link">Learn More</a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <div class="logo">
                        <i class="fas fa-utensils"></i>
                        <span>FoodFusion</span>
                    </div>
                    <p>Connecting food lovers worldwide through shared culinary experiences and delicious recipes.</p>
                    <div class="social-links">
                        <a href="#" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
                        <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" aria-label="Pinterest"><i class="fab fa-pinterest"></i></a>
                        <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h3>Explore</h3>
                    <ul class="footer-links">
                        <li><a href="recipes.php">All Recipes</a></li>
                        <li><a href="recipes.php?category=1">Italian Cuisine</a></li>
                        <li><a href="recipes.php?category=2">Asian Delights</a></li>
                        <li><a href="recipes.php?category=3">Mexican Flavors</a></li>
                        <li><a href="recipes.php?category=4">Healthy Options</a></li>
                        <li><a href="resources.php">Cooking Resources</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Community</h3>
                    <ul class="footer-links">
                        <li><a href="community.php">Community Cookbook</a></li>
                        <li><a href="events.php">Cooking Events</a></li>
                        <li><a href="forum.php">Discussion Forum</a></li>
                        <li><a href="chefs.php">Featured Chefs</a></li>
                        <li><a href="blog.php">Food Blog</a></li>
                        <li><a href="contests.php">Cooking Contests</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Support</h3>
                    <ul class="footer-links">
                        <li><a href="help.php">Help Center</a></li>
                        <li><a href="contact.php">Contact Us</a></li>
                        <li><a href="faq.php">FAQ</a></li>
                        <li><a href="feedback.php">Feedback</a></li>
                        <li><a href="sitemap.php">Site Map</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Legal</h3>
                    <ul class="footer-links">
                        <li><a href="privacy.php">Privacy Policy</a></li>
                        <li><a href="terms.php">Terms of Service</a></li>
                        <li><a href="cookies.php">Cookie Policy</a></li>
                        <li><a href="disclaimer.php">Disclaimer</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <div class="footer-bottom-content">
                    <p>&copy; 2024 FoodFusion. All rights reserved. | Crafted with <i class="fas fa-heart" style="color: #e74c3c;"></i> for food lovers worldwide</p>
                    <div class="footer-badges">
                        <span class="badge">100% Secure</span>
                        <span class="badge">Vegetarian Friendly</span>
                        <span class="badge">Community Driven</span>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button class="back-to-top" id="backToTop">
        <i class="fas fa-chevron-up"></i>
    </button>

    <!-- Scripts -->
    <script src="assets/js/script.js"></script>
    
    <!-- Additional Scripts for Specific Pages -->
    <?php if (basename($_SERVER['PHP_SELF']) == 'recipes.php'): ?>
        <script src="assets/js/recipes.js"></script>
    <?php endif; ?>
    
    <?php if (basename($_SERVER['PHP_SELF']) == 'community.php'): ?>
        <script src="assets/js/community.js"></script>
    <?php endif; ?>
    
    <!-- Analytics Script (Example) -->
    <script>
        // Google Analytics (example)
        // window.dataLayer = window.dataLayer || [];
        // function gtag(){dataLayer.push(arguments);}
        // gtag('js', new Date());
        // gtag('config', 'GA_MEASUREMENT_ID');
    </script>
</body>
</html>