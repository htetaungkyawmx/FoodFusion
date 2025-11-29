<?php include 'includes/header.php'; ?>

<div class="container">
    <section class="page-header">
        <h1>Contact Us</h1>
        <p>We'd love to hear from you! Send us your questions, feedback, or recipe requests.</p>
    </section>

    <div class="contact-container">
        <div class="contact-form">
            <h2>Send us a Message</h2>
            
            <?php
            if(isset($_SESSION['contact_success'])) {
                echo '<div class="alert alert-success">' . $_SESSION['contact_success'] . '</div>';
                unset($_SESSION['contact_success']);
            }
            ?>
            
            <form action="includes/submit_contact.php" method="POST">
                <div class="form-group">
                    <input type="text" name="name" placeholder="Your Name" required 
                           value="<?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <input type="email" name="email" placeholder="Your Email" required
                           value="<?php echo isset($_SESSION['user_email']) ? htmlspecialchars($_SESSION['user_email']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <input type="text" name="subject" placeholder="Subject" required>
                </div>
                
                <div class="form-group">
                    <textarea name="message" placeholder="Your Message" rows="6" required></textarea>
                </div>
                
                <button type="submit" class="btn">Send Message</button>
            </form>
        </div>
        
        <div class="contact-info">
            <h2>Get in Touch</h2>
            <div class="contact-item">
                <h3><i class="fas fa-envelope"></i> Email</h3>
                <p>hello@foodfusion.com</p>
            </div>
            <div class="contact-item">
                <h3><i class="fas fa-phone"></i> Phone</h3>
                <p>+1 (555) 123-4567</p>
            </div>
            <div class="contact-item">
                <h3><i class="fas fa-map-marker-alt"></i> Address</h3>
                <p>123 Culinary Street<br>Foodville, FK 12345</p>
            </div>
            <div class="contact-item">
                <h3><i class="fas fa-clock"></i> Response Time</h3>
                <p>We typically respond within 24 hours</p>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>