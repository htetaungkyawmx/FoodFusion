<footer class="footer-simple">
    <div class="container">
        <div class="footer-main">
            <!-- Left: Brand -->
            <div class="footer-left">
                <div class="brand-logo">
                    <i class="fas fa-utensils"></i>
                    <span>FoodFusion</span>
                </div>
                <p class="tagline">Where food lovers connect</p>
            </div>
            
            <!-- Right: Legal Links -->
            <div class="footer-right">
                <div class="legal-links">
                    <a href="privacy.php">Privacy</a>
                    <a href="terms.php">Terms</a>
                    <a href="disclaimer.php">Disclaimer</a>
                    <a href="contact.php">Contact</a>
                </div>
                
                <div class="social-icons">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-pinterest"></i></a>
                </div>
            </div>
        </div>
        
        <!-- Copyright -->
        <div class="copyright-bar">
            <p>&copy; <?php echo date('Y'); ?> FoodFusion. All rights reserved.</p>
        </div>
    </div>
</footer>

<style>
/* Simple & Clean Footer */
.footer-simple {
    background: #f8f9fa;
    padding: 30px 0 15px;
    margin-top: 50px;
    border-top: 1px solid #e9ecef;
}

.footer-main {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    flex-wrap: wrap;
    gap: 20px;
}

.footer-left {
    flex: 1;
    min-width: 200px;
}

.brand-logo {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 5px;
}

.brand-logo i {
    font-size: 1.5rem;
    color: #4CAF50;
}

.brand-logo span {
    font-size: 1.4rem;
    font-weight: 600;
    color: #333;
}

.tagline {
    color: #666;
    font-size: 0.9rem;
    margin: 0;
    font-style: italic;
}

.footer-right {
    text-align: right;
}

.legal-links {
    display: flex;
    gap: 20px;
    margin-bottom: 15px;
    justify-content: flex-end;
    flex-wrap: wrap;
}

.legal-links a {
    color: #666;
    text-decoration: none;
    font-size: 0.9rem;
    transition: color 0.3s;
}

.legal-links a:hover {
    color: #4CAF50;
}

.social-icons {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
}

.social-icons a {
    color: #666;
    font-size: 1rem;
    transition: color 0.3s;
}

.social-icons a:hover {
    color: #4CAF50;
}

.copyright-bar {
    text-align: center;
    padding-top: 15px;
    border-top: 1px solid #e9ecef;
    color: #888;
    font-size: 0.85rem;
}

.copyright-bar p {
    margin: 0;
}

/* Responsive */
@media (max-width: 768px) {
    .footer-main {
        flex-direction: column;
        text-align: center;
        gap: 25px;
    }
    
    .footer-right {
        text-align: center;
        width: 100%;
    }
    
    .legal-links {
        justify-content: center;
    }
    
    .social-icons {
        justify-content: center;
    }
}
</style>