<?php
$page_title = "Privacy Policy - FoodFusion";
include 'includes/header.php';
?>

<div class="container">
    <div class="legal-header">
        <h1>Privacy Policy</h1>
        <p class="last-updated">Last updated: March 1, 2024</p>
    </div>

    <div class="legal-content">
        <section class="legal-section">
            <h2>1. Information We Collect</h2>
            <p>At FoodFusion, we collect information to provide better services to all our users. We collect information in the following ways:</p>
            
            <h3>Information you give us</h3>
            <ul>
                <li><strong>Personal Information:</strong> When you create an account, we collect your name, email address, and profile information.</li>
                <li><strong>Content:</strong> We collect the content you create, upload, or receive from others when using our services, such as recipes, reviews, and community posts.</li>
                <li><strong>Communication:</strong> When you contact us, we collect information about your communication and any information you choose to provide.</li>
            </ul>
            
            <h3>Information we get from your use of our services</h3>
            <ul>
                <li><strong>Usage Information:</strong> We collect information about how you use our services, such as the types of content you view or engage with.</li>
                <li><strong>Device Information:</strong> We collect device-specific information such as your hardware model, operating system version, and browser type.</li>
                <li><strong>Log Information:</strong> When you use our services, we automatically collect and store certain information in server logs.</li>
            </ul>
        </section>

        <section class="legal-section" id="cookies">
            <h2>2. Cookies and Similar Technologies</h2>
            <p>We use cookies and similar technologies to provide, protect, and improve our services. Cookies help us:</p>
            <ul>
                <li>Remember your preferences and settings</li>
                <li>Understand how you use our services</li>
                <li>Personalize your experience</li>
                <li>Show you relevant content and advertisements</li>
            </ul>
            
            <p>You can control cookies through your browser settings and other tools. However, if you disable cookies, some features of our services may not function properly.</p>
        </section>

        <section class="legal-section">
            <h2>3. How We Use Information</h2>
            <p>We use the information we collect to:</p>
            <ul>
                <li>Provide, maintain, and improve our services</li>
                <li>Develop new services and features</li>
                <li>Personalize content and make suggestions for you</li>
                <li>Measure performance and understand how our services are used</li>
                <li>Communicate with you about our services</li>
                <li>Protect FoodFusion, our users, and the public</li>
            </ul>
        </section>

        <section class="legal-section">
            <h2>4. Information Sharing</h2>
            <p>We do not share personal information with companies, organizations, or individuals outside of FoodFusion except in the following cases:</p>
            <ul>
                <li><strong>With your consent:</strong> We will share personal information with third parties when we have your consent to do so.</li>
                <li><strong>For legal reasons:</strong> We will share personal information if we believe that access, use, preservation, or disclosure of the information is reasonably necessary.</li>
                <li><strong>For external processing:</strong> We provide personal information to our affiliates or other trusted businesses or persons to process it for us.</li>
            </ul>
        </section>

        <section class="legal-section">
            <h2>5. Information Security</h2>
            <p>We work hard to protect FoodFusion and our users from unauthorized access to or unauthorized alteration, disclosure, or destruction of information we hold. We implement security measures including:</p>
            <ul>
                <li>Encryption of data in transit and at rest</li>
                <li>Regular security assessments and testing</li>
                <li>Access controls and authentication procedures</li>
                <li>Secure development practices</li>
            </ul>
        </section>

        <section class="legal-section">
            <h2>6. Your Rights</h2>
            <p>You have the right to access, correct, or delete your personal information. You can:</p>
            <ul>
                <li>Access and update your personal information through your account settings</li>
                <li>Request deletion of your personal information</li>
                <li>Object to the processing of your personal information</li>
                <li>Request portability of your personal information</li>
            </ul>
        </section>

        <section class="legal-section">
            <h2>7. Changes to This Policy</h2>
            <p>We may change this privacy policy from time to time. We will post any privacy policy changes on this page and, if the changes are significant, we will provide a more prominent notice.</p>
        </section>

        <section class="legal-section">
            <h2>8. Contact Us</h2>
            <p>If you have any questions about this Privacy Policy, please contact us at:</p>
            <div class="contact-info">
                <p><strong>Email:</strong> privacy@foodfusion.com</p>
                <p><strong>Address:</strong> 123 Culinary Street, Foodville, FK 12345</p>
            </div>
        </section>
    </div>
</div>

<style>
.legal-header {
    text-align: center;
    margin-bottom: 3rem;
    padding: 2rem;
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
}

.legal-header h1 {
    margin-bottom: 0.5rem;
    color: var(--secondary-color);
}

.last-updated {
    color: var(--gray-color);
    font-size: 1.1rem;
}

.legal-content {
    background: white;
    padding: 3rem;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
}

.legal-section {
    margin-bottom: 3rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid var(--border-color);
}

.legal-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.legal-section h2 {
    color: var(--primary-color);
    margin-bottom: 1rem;
    font-size: 1.5rem;
}

.legal-section h3 {
    color: var(--secondary-color);
    margin: 1.5rem 0 0.5rem 0;
    font-size: 1.2rem;
}

.legal-section p {
    margin-bottom: 1rem;
    line-height: 1.7;
}

.legal-section ul {
    margin: 1rem 0;
    padding-left: 2rem;
}

.legal-section li {
    margin-bottom: 0.5rem;
    line-height: 1.6;
}

.contact-info {
    background: var(--light-gray);
    padding: 1.5rem;
    border-radius: var(--border-radius);
    margin-top: 1rem;
}

.contact-info p {
    margin-bottom: 0.5rem;
}

@media (max-width: 768px) {
    .legal-content {
        padding: 2rem;
    }
    
    .legal-header {
        padding: 1.5rem;
    }
}
</style>

<?php include 'includes/footer.php'; ?>