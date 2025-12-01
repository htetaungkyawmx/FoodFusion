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
                <div class="form-container">
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
            </div>
            
            <div class="contact-info-section">
                <div class="info-container">
                    <div class="contact-item address-item">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="contact-details">
                            <h3>Find us in</h3>
                            <p>141 Walton Rd, Walton</p>
                            <p>Liverpool L4 4AH</p>
                            <p>United Kingdom</p>
                        </div>
                    </div>
                    
                    <div class="map-container">
                        <div class="map-wrapper">
                            <!-- Google Maps Embed -->
                            <iframe 
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2378.571472055433!2d-2.9680543!3d53.4291534!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x487b21636a7fbfc9%3A0x3f2a9be8e4fa6dac!2s141%20Walton%20Rd%2C%20Walton%2C%20Liverpool%20L4%204AH%2C%20UK!5e0!3m2!1sen!2s!4v1700000000000!5m2!1sen!2s" 
                                width="100%" 
                                height="250" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy" 
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                    </div>
                    
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
                            <p>+44 151 555 1234</p>
                            <p>Mon-Fri, 9AM-6PM GMT</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="contact-details">
                            <h3>Response Time</h3>
                            <p>We typically respond within 24 hours</p>
                        </div>
                    </div>
                </div>
                
                <div class="social-connect-section">
                    <h3><i class="fas fa-share-alt"></i> Connect with us</h3>
                    <div class="social-links-mini">
                        <a href="#" class="social-link-mini facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-link-mini twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="social-link-mini instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.contact-header {
    text-align: center;
    padding: 2.5rem 0;
    margin-bottom: 3rem;
    background: linear-gradient(135deg, #FF6B6B, #53FF61FF);
    color: white;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(255, 107, 107, 0.3);
}

.contact-header h1 {
    margin-bottom: 0.5rem;
    font-size: 2.8rem;
    font-weight: 700;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
}

.subtitle {
    font-size: 1.3rem;
    opacity: 0.95;
    max-width: 700px;
    margin: 0 auto;
    font-weight: 300;
    letter-spacing: 0.5px;
}

.contact-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
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
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    overflow: hidden;
    transition: transform 0.3s ease;
}

.contact-form-section:hover {
    transform: translateY(-5px);
}

.form-container {
    padding: 2.5rem;
    border-left: 6px solid #FF6B6B;
}

.contact-form-section h2 {
    color: #333;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 1.6rem;
    font-weight: 600;
}

.contact-form-section h2 i {
    color: #FF6B6B;
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

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: block;
    margin-bottom: 0.5rem;
    color: #555;
    font-weight: 500;
    font-size: 0.95rem;
}

.form-control {
    width: 100%;
    padding: 0.85rem;
    border: 2px solid #E0E0E0;
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s;
    background: #FAFAFA;
}

.form-control:focus {
    outline: none;
    border-color: #FF6B6B;
    background: white;
    box-shadow: 0 0 0 4px rgba(255, 107, 107, 0.1);
}

textarea.form-control {
    resize: vertical;
    min-height: 150px;
}

.btn {
    background: linear-gradient(135deg, #FF6B6B, #FF8E53);
    color: white;
    border: none;
    padding: 0.9rem 2.5rem;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s;
    letter-spacing: 0.5px;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(255, 107, 107, 0.4);
}

.contact-info-section {
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    overflow: hidden;
    transition: transform 0.3s ease;
}

.contact-info-section:hover {
    transform: translateY(-5px);
}

.info-container {
    padding: 2.5rem;
}

.contact-item {
    display: flex;
    gap: 1.25rem;
    padding: 1.5rem 0;
    border-bottom: 1px solid #F0F0F0;
    align-items: flex-start;
}

.contact-item:last-child {
    border-bottom: none;
}

.address-item {
    border-left: 4px solid #4ECDC4;
    padding-left: 1rem;
    background: linear-gradient(135deg, #F8F9FA, #FFFFFF);
    border-radius: 10px;
    margin: -1.5rem -1.5rem 1.5rem;
    padding: 1.5rem;
}

.contact-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #4ECDC4, #44A08D);
    color: white;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
    box-shadow: 0 5px 15px rgba(78, 205, 196, 0.3);
}

.address-item .contact-icon {
    background: linear-gradient(135deg, #FFD166, #FFB347);
}

.contact-details h3 {
    margin-bottom: 0.5rem;
    color: #333;
    font-size: 1.2rem;
    font-weight: 600;
}

.contact-details p {
    color: #555;
    margin-bottom: 0.25rem;
    line-height: 1.6;
    font-size: 0.95rem;
}

.map-container {
    margin: 1.5rem -2.5rem;
    border-radius: 0;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.map-wrapper {
    width: 100%;
    height: 250px;
}

.map-wrapper iframe {
    width: 100%;
    height: 100%;
    border: none;
}

.alert {
    padding: 1rem 1.5rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    font-weight: 500;
}

.alert-error {
    background: linear-gradient(135deg, #FFE8E8, #FFD1D1);
    color: #D32F2F;
    border-left: 4px solid #D32F2F;
}

.alert-success {
    background: linear-gradient(135deg, #E8F5E9, #C8E6C9);
    color: #2E7D32;
    border-left: 4px solid #2E7D32;
}

.social-connect-section {
    background: linear-gradient(135deg, #6A89CC, #4ABD65FF);
    padding: 1.5rem;
    border-radius: 10px;
    margin-top: 2rem;
    text-align: center;
}

.social-connect-section h3 {
    color: white;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    font-size: 1.2rem;
}

.social-links-mini {
    display: flex;
    justify-content: center;
    gap: 1rem;
}

.social-link-mini {
    width: 45px;
    height: 45px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    text-decoration: none;
    font-size: 1.2rem;
    transition: all 0.3s;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.social-link-mini:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-3px) scale(1.1);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .contact-header h1 {
        font-size: 2.2rem;
    }
    
    .subtitle {
        font-size: 1.1rem;
        padding: 0 1rem;
    }
    
    .form-container,
    .info-container {
        padding: 1.5rem;
    }
    
    .address-item {
        margin: -1rem -1rem 1rem;
    }
    
    .map-container {
        margin: 1rem -1.5rem;
    }
}

/* Animation for form elements */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.contact-form-section,
.contact-info-section {
    animation: fadeInUp 0.6s ease-out;
}

.contact-info-section {
    animation-delay: 0.2s;
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
    
    // Add animation to form inputs
    const formInputs = document.querySelectorAll('.form-control');
    formInputs.forEach((input, index) => {
        input.style.animationDelay = `${index * 0.1}s`;
        input.classList.add('animate-input');
    });
    
    // Social link animations
    document.querySelectorAll('.social-link-mini').forEach(link => {
        link.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px) scale(1.1)';
        });
        
        link.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
    
    // Animate contact items on scroll
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1 });
    
    // Observe contact items
    document.querySelectorAll('.contact-item').forEach((item, index) => {
        item.style.opacity = '0';
        item.style.transform = 'translateY(20px)';
        item.style.transition = `all 0.5s ease ${index * 0.1}s`;
        observer.observe(item);
    });
});
</script>

<?php include 'includes/footer.php'; ?>