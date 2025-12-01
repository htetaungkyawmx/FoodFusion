<?php
$page_title = "Contact Us - FoodFusion";
include 'includes/header.php';
include 'config/database.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    
    // Basic validation
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        $database = new Database();
        $db = $database->getConnection();
        
        // Save to database
        $query = "INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)";
        $stmt = $db->prepare($query);
        
        if ($stmt->execute([$name, $email, $subject, $message])) {
            $success = "Thank you for your message! We'll get back to you within 24 hours.";
            
            // Clear form
            $_POST = [];
        } else {
            $error = "Sorry, there was an error sending your message. Please try again.";
        }
    }
}
?>

<div class="container main-content">
    <div class="contact-page">
        <div class="contact-header">
            <h1>Contact Us</h1>
            <p class="subtitle">We'd love to hear from you! Send us your questions, feedback, or recipe requests.</p>
        </div>

        <div class="contact-content">
            <div class="contact-form-section">
                <h2><i class="fas fa-envelope"></i> Send us a Message</h2>
                
                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <form method="POST" action="" class="contact-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Your Name *</label>
                            <input type="text" name="name" class="form-control" 
                                   value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" 
                                   required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Your Email *</label>
                            <input type="email" name="email" class="form-control" 
                                   value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                                   required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Subject *</label>
                        <input type="text" name="subject" class="form-control" 
                               value="<?php echo isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : ''; ?>" 
                               required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Message *</label>
                        <textarea name="message" class="form-control" rows="6" required><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                    </div>
                    
                    <button type="submit" class="btn">
                        <i class="fas fa-paper-plane"></i> Send Message
                    </button>
                </form>
            </div>
            
            <div class="contact-info-section">
                <div class="info-card">
                    <h2><i class="fas fa-info-circle"></i> Contact Information</h2>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-details">
                            <h3>Email</h3>
                            <p>hello@foodfusion.com</p>
                            <p>support@foodfusion.com</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="contact-details">
                            <h3>Phone</h3>
                            <p>+1 (555) 123-4567</p>
                            <p>Mon-Fri, 9AM-6PM EST</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="contact-details">
                            <h3>Address</h3>
                            <p>123 Culinary Street</p>
                            <p>Foodville, FK 12345</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="contact-details">
                            <h3>Response Time</h3>
                            <p>We typically respond within 24 hours</p>
                            <p>Emergency: contact@foodfusion.com</p>
                        </div>
                    </div>
                </div>
                
                <div class="faq-preview">
                    <h3><i class="fas fa-question-circle"></i> Frequently Asked Questions</h3>
                    <div class="faq-item">
                        <h4>How do I reset my password?</h4>
                        <p>Click "Forgot Password" on the login page or contact support.</p>
                    </div>
                    <div class="faq-item">
                        <h4>Can I submit my own recipes?</h4>
                        <p>Yes! Registered users can submit recipes through their profile.</p>
                    </div>
                    <a href="faq.php" class="view-all-faq">View All FAQs →</a>
                </div>
            </div>
        </div>

        <div class="contact-extra">
            <div class="social-contact">
                <h2>Connect With Us</h2>
                <div class="social-links">
                    <a href="#" class="social-link facebook">
                        <i class="fab fa-facebook-f"></i>
                        <span>Facebook</span>
                    </a>
                    <a href="#" class="social-link twitter">
                        <i class="fab fa-twitter"></i>
                        <span>Twitter</span>
                    </a>
                    <a href="#" class="social-link instagram">
                        <i class="fab fa-instagram"></i>
                        <span>Instagram</span>
                    </a>
                    <a href="#" class="social-link pinterest">
                        <i class="fab fa-pinterest-p"></i>
                        <span>Pinterest</span>
                    </a>
                </div>
            </div>
            
            <div class="contact-map">
                <h2>Find Us</h2>
                <div class="map-placeholder">
                    <i class="fas fa-map"></i>
                    <p>Interactive Map</p>
                    <small>(Map would be embedded here)</small>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.contact-header {
    text-align: center;
    padding: 2rem 0;
    margin-bottom: 3rem;
}

.contact-header h1 {
    color: #333;
    margin-bottom: 0.5rem;
}

.subtitle {
    color: #666;
    font-size: 1.1rem;
    max-width: 600px;
    margin: 0 auto;
}

.contact-content {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 3rem;
    margin-bottom: 3rem;
}

@media (max-width: 992px) {
    .contact-content {
        grid-template-columns: 1fr;
    }
}

.contact-form-section {
    background: white;
    padding: 2rem;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.contact-form-section h2 {
    color: #333;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

@media (max-width: 576px) {
    .form-row {
        grid-template-columns: 1fr;
    }
}

.contact-info-section {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.info-card {
    background: white;
    padding: 2rem;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.info-card h2 {
    color: #333;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.contact-item {
    display: flex;
    gap: 1rem;
    padding: 1rem 0;
    border-bottom: 1px solid #eee;
}

.contact-item:last-child {
    border-bottom: none;
}

.contact-icon {
    width: 50px;
    height: 50px;
    background: #e74c3c;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    flex-shrink: 0;
}

.contact-details h3 {
    margin-bottom: 0.25rem;
    color: #333;
    font-size: 1rem;
}

.contact-details p {
    color: #666;
    margin-bottom: 0.25rem;
    font-size: 0.9rem;
}

.faq-preview {
    background: white;
    padding: 1.5rem;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.faq-preview h3 {
    color: #333;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.faq-item {
    padding: 0.75rem 0;
    border-bottom: 1px solid #eee;
}

.faq-item:last-child {
    border-bottom: none;
}

.faq-item h4 {
    color: #555;
    margin-bottom: 0.25rem;
    font-size: 0.95rem;
}

.faq-item p {
    color: #666;
    font-size: 0.9rem;
}

.view-all-faq {
    display: inline-block;
    margin-top: 1rem;
    color: #3498db;
    text-decoration: none;
    font-weight: 500;
}

.view-all-faq:hover {
    text-decoration: underline;
}

.contact-extra {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3rem;
    margin-top: 3rem;
}

@media (max-width: 768px) {
    .contact-extra {
        grid-template-columns: 1fr;
    }
}

.social-contact, .contact-map {
    background: white;
    padding: 2rem;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.social-contact h2, .contact-map h2 {
    color: #333;
    margin-bottom: 1.5rem;
    text-align: center;
}

.social-links {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 1rem;
}

.social-link {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 1rem;
    border-radius: 8px;
    text-decoration: none;
    color: white;
    transition: transform 0.3s;
}

.social-link:hover {
    transform: translateY(-3px);
}

.social-link i {
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
}

.social-link span {
    font-size: 0.9rem;
    font-weight: 500;
}

.facebook { background: #3b5998; }
.twitter { background: #1da1f2; }
.instagram { background: #e4405f; }
.pinterest { background: #bd081c; }

.map-placeholder {
    height: 200px;
    background: #f8f9fa;
    border-radius: 8px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #666;
}

.map-placeholder i {
    font-size: 3rem;
    color: #ccc;
    margin-bottom: 1rem;
}

.map-placeholder p {
    margin-bottom: 0.5rem;
    font-weight: 500;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form submission animation
    const contactForm = document.querySelector('.contact-form');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            // Add loading animation
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
            submitBtn.disabled = true;
            
            // Simulate sending (in real app, this would be AJAX)
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 2000);
        });
    }
    
    // Social link animations
    document.querySelectorAll('.social-link').forEach(link => {
        link.addEventListener('mouseenter', function() {
            this.style.boxShadow = '0 5px 15px rgba(0,0,0,0.2)';
        });
        
        link.addEventListener('mouseleave', function() {
            this.style.boxShadow = 'none';
        });
    });
});
</script>

<?php include 'includes/footer.php'; ?>