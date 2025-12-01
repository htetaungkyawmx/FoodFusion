<?php
$page_title = "About Us - FoodFusion";
include 'includes/header.php';
?>

<div class="container main-content">
    <div class="about-page">
        <div class="about-hero">
            <h1>About FoodFusion</h1>
            <p class="subtitle">Where Food Lovers Connect and Create</p>
        </div>

        <div class="about-content">
            <section class="about-section">
                <h2><i class="fas fa-bullseye"></i> Our Mission</h2>
                <p>At FoodFusion, we believe that great food brings people together. Our mission is to create a global community where food enthusiasts can share recipes, learn new cooking techniques, and connect with like-minded individuals.</p>
            </section>

            <section class="about-section">
                <h2><i class="fas fa-history"></i> Our Story</h2>
                <p>Founded in 2024, FoodFusion started as a small project between friends who loved cooking. What began as a simple recipe sharing platform has grown into a vibrant community of thousands of food lovers from around the world.</p>
            </section>

            <section class="about-section">
                <h2><i class="fas fa-users"></i> Our Community</h2>
                <p>We're proud to have built a diverse and inclusive community. Our members range from professional chefs to home cooks, all united by their passion for food and cooking.</p>
                <div class="community-stats">
                    <div class="stat">
                        <div class="stat-number">10,000+</div>
                        <div class="stat-label">Members</div>
                    </div>
                    <div class="stat">
                        <div class="stat-number">5,000+</div>
                        <div class="stat-label">Recipes</div>
                    </div>
                    <div class="stat">
                        <div class="stat-number">150+</div>
                        <div class="stat-label">Countries</div>
                    </div>
                </div>
            </section>

            <section class="about-section">
                <h2><i class="fas fa-leaf"></i> Our Values</h2>
                <div class="values-grid">
                    <div class="value-card">
                        <i class="fas fa-heart"></i>
                        <h3>Passion</h3>
                        <p>We're passionate about food and community</p>
                    </div>
                    <div class="value-card">
                        <i class="fas fa-share-alt"></i>
                        <h3>Sharing</h3>
                        <p>We believe knowledge grows when shared</p>
                    </div>
                    <div class="value-card">
                        <i class="fas fa-handshake"></i>
                        <h3>Community</h3>
                        <p>We build connections through food</p>
                    </div>
                    <div class="value-card">
                        <i class="fas fa-graduation-cap"></i>
                        <h3>Learning</h3>
                        <p>We're always learning and growing</p>
                    </div>
                </div>
            </section>

            <section class="about-section">
                <h2><i class="fas fa-utensils"></i> What We Offer</h2>
                <div class="features-list">
                    <div class="feature">
                        <i class="fas fa-book"></i>
                        <div>
                            <h3>Recipe Collection</h3>
                            <p>Thousands of recipes from around the world, categorized and easy to search</p>
                        </div>
                    </div>
                    <div class="feature">
                        <i class="fas fa-comments"></i>
                        <div>
                            <h3>Community Forum</h3>
                            <p>Connect with other food lovers, ask questions, share tips</p>
                        </div>
                    </div>
                    <div class="feature">
                        <i class="fas fa-video"></i>
                        <div>
                            <h3>Learning Resources</h3>
                            <p>Tutorials, guides, and cooking tips for all skill levels</p>
                        </div>
                    </div>
                </div>
            </section>

            <div class="join-cta">
                <h2>Ready to Join Our Community?</h2>
                <p>Create your free account and start your culinary journey today</p>
                <a href="register.php" class="btn btn-large">Join Now</a>
            </div>
        </div>
    </div>
</div>

<style>
.about-hero {
    text-align: center;
    padding: 3rem 0;
    background: linear-gradient(135deg, #e74c3c, #f39c12);
    color: white;
    border-radius: 10px;
    margin-bottom: 3rem;
}

.about-hero h1 {
    font-size: 3rem;
    margin-bottom: 1rem;
}

.subtitle {
    font-size: 1.2rem;
    opacity: 0.9;
}

.about-section {
    margin-bottom: 3rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid #eee;
}

.about-section:last-child {
    border-bottom: none;
}

.about-section h2 {
    color: #2c3e50;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.community-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
}

.stat {
    text-align: center;
    padding: 1.5rem;
    background: #f8f9fa;
    border-radius: 10px;
}

.stat-number {
    font-size: 2.5rem;
    font-weight: bold;
    color: #e74c3c;
    margin-bottom: 0.5rem;
}

.stat-label {
    color: #666;
    font-size: 0.9rem;
}

.values-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    margin-top: 1.5rem;
}

.value-card {
    text-align: center;
    padding: 2rem;
    background: white;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s;
}

.value-card:hover {
    transform: translateY(-5px);
}

.value-card i {
    font-size: 3rem;
    color: #e74c3c;
    margin-bottom: 1rem;
}

.value-card h3 {
    margin-bottom: 0.5rem;
    color: #333;
}

.features-list {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.feature {
    display: flex;
    align-items: flex-start;
    gap: 1.5rem;
    padding: 1.5rem;
    background: #f8f9fa;
    border-radius: 10px;
}

.feature i {
    font-size: 2rem;
    color: #3498db;
    margin-top: 0.5rem;
}

.feature h3 {
    margin-bottom: 0.5rem;
    color: #333;
}

.join-cta {
    text-align: center;
    padding: 3rem;
    background: linear-gradient(135deg, #2ecc71, #27ae60);
    color: white;
    border-radius: 10px;
    margin-top: 3rem;
}

.join-cta h2 {
    color: white;
    margin-bottom: 1rem;
}

.join-cta p {
    opacity: 0.9;
    margin-bottom: 2rem;
}

.btn-large {
    padding: 1rem 2.5rem;
    font-size: 1.1rem;
}
</style>

<?php include 'includes/footer.php'; ?>