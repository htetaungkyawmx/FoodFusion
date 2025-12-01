<?php
$page_title = "Resources - FoodFusion";
include 'includes/header.php';
?>

<div class="container main-content">
    <div class="resources-page">
        <div class="resources-header">
            <h1>Culinary Resources</h1>
            <p class="subtitle">Download recipe cards, watch tutorials, and improve your cooking skills</p>
        </div>

        <div class="resources-categories">
            <div class="category-tabs">
                <button class="tab-btn active" data-tab="downloads">Downloads</button>
                <button class="tab-btn" data-tab="tutorials">Tutorials</button>
                <button class="tab-btn" data-tab="guides">Guides</button>
            </div>

            <div class="tab-content active" id="downloads">
                <h2><i class="fas fa-download"></i> Recipe Cards & Templates</h2>
                <div class="downloads-grid">
                    <div class="resource-card">
                        <div class="resource-icon">
                            <i class="fas fa-file-pdf"></i>
                        </div>
                        <h3>Weekly Meal Planner</h3>
                        <p>Plan your meals for the week with this printable template</p>
                        <a href="downloads/meal-planner.pdf" class="btn btn-outline" download>
                            <i class="fas fa-download"></i> Download PDF
                        </a>
                    </div>
                    
                    <div class="resource-card">
                        <div class="resource-icon">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <h3>Recipe Card Template</h3>
                        <p>Beautiful printable recipe cards for your kitchen</p>
                        <a href="downloads/recipe-card-template.pdf" class="btn btn-outline" download>
                            <i class="fas fa-download"></i> Download Template
                        </a>
                    </div>
                    
                    <div class="resource-card">
                        <div class="resource-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <h3>Grocery Shopping List</h3>
                        <p>Organized shopping list template for your kitchen needs</p>
                        <a href="downloads/grocery-list.pdf" class="btn btn-outline" download>
                            <i class="fas fa-download"></i> Download PDF
                        </a>
                    </div>
                    
                    <div class="resource-card">
                        <div class="resource-icon">
                            <i class="fas fa-balance-scale"></i>
                        </div>
                        <h3>Measurement Conversion Chart</h3>
                        <p>Convert between cups, grams, ounces and more</p>
                        <a href="downloads/conversion-chart.pdf" class="btn btn-outline" download>
                            <i class="fas fa-download"></i> Download Chart
                        </a>
                    </div>
                    
                    <!-- Additional Download 1 -->
                    <div class="resource-card">
                        <div class="resource-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <h3>30-Day Healthy Eating Challenge</h3>
                        <p>Complete guide with daily recipes and meal plans</p>
                        <a href="downloads/30-day-challenge.pdf" class="btn btn-outline" download>
                            <i class="fas fa-download"></i> Download Guide
                        </a>
                    </div>
                    
                    <!-- Additional Download 2 -->
                    <div class="resource-card">
                        <div class="resource-icon">
                            <i class="fas fa-heartbeat"></i>
                        </div>
                        <h3>Nutrition Facts Journal</h3>
                        <p>Track your daily nutrition intake and health goals</p>
                        <a href="downloads/nutrition-journal.pdf" class="btn btn-outline" download>
                            <i class="fas fa-download"></i> Download Journal
                        </a>
                    </div>
                </div>
            </div>

            <div class="tab-content" id="tutorials">
                <h2><i class="fas fa-video"></i> Cooking Tutorials</h2>
                <div class="tutorials-grid">
                    <!-- Professional Cooking Techniques -->
                    <div class="tutorial-card">
                        <div class="tutorial-video">
                            <img src="https://img.youtube.com/vi/CozmWJS0JZY/maxresdefault.jpg" alt="15 Cooking Tricks Chefs Reveal Only at Culinary Schools" class="video-thumbnail">
                            <div class="play-overlay">
                                <i class="fas fa-play-circle"></i>
                            </div>
                        </div>
                        <h3>15 Cooking Tricks Chefs Reveal</h3>
                        <p>Professional secrets only taught in culinary schools</p>
                        <a href="https://www.youtube.com/watch?v=CozmWJS0JZY" target="_blank" class="btn btn-outline">
                            <i class="fas fa-play"></i> Watch Tutorial
                        </a>
                    </div>
                    
                    <!-- Jamie Oliver Cooking Skills -->
                    <div class="tutorial-card">
                        <div class="tutorial-video">
                            <img src="https://img.youtube.com/vi/PPmI-vZMfSU/maxresdefault.jpg" alt="Jamie Oliver's Quick Cooking Skills" class="video-thumbnail">
                            <div class="play-overlay">
                                <i class="fas fa-play-circle"></i>
                            </div>
                        </div>
                        <h3>Jamie Oliver's Quick Cooking</h3>
                        <p>Essential skills for quick and delicious meals</p>
                        <a href="https://www.youtube.com/watch?v=PPmI-vZMfSU" target="_blank" class="btn btn-outline">
                            <i class="fas fa-play"></i> Watch Tutorial
                        </a>
                    </div>
                    
                    <!-- Gordon Ramsay Ultimate Cooking Guide -->
                    <div class="tutorial-card">
                        <div class="tutorial-video">
                            <img src="https://img.youtube.com/vi/kkHN_BC9mdg/maxresdefault.jpg" alt="Gordon Ramsay's Ultimate Cooking Guide" class="video-thumbnail">
                            <div class="play-overlay">
                                <i class="fas fa-play-circle"></i>
                            </div>
                        </div>
                        <h3>Gordon Ramsay Ultimate Guide</h3>
                        <p>Master cooking techniques with Gordon Ramsay</p>
                        <a href="https://www.youtube.com/watch?v=kkHN_BC9mdg" target="_blank" class="btn btn-outline">
                            <i class="fas fa-play"></i> Watch Tutorial
                        </a>
                    </div>
                    
                    <!-- Italian Pasta Making Masterclass -->
                    <div class="tutorial-card">
                        <div class="tutorial-video">
                            <img src="https://img.youtube.com/vi/sv0BkQmaI3M/maxresdefault.jpg" alt="Pasta Making Masterclass" class="video-thumbnail">
                            <div class="play-overlay">
                                <i class="fas fa-play-circle"></i>
                            </div>
                        </div>
                        <h3>Pasta Making Masterclass</h3>
                        <p>Learn authentic Italian pasta making from scratch</p>
                        <a href="https://www.youtube.com/watch?v=sv0BkQmaI3M" target="_blank" class="btn btn-outline">
                            <i class="fas fa-play"></i> Watch Tutorial
                        </a>
                    </div>
                    
                    <!-- Sushi Making at Home -->
                    <div class="tutorial-card">
                        <div class="tutorial-video">
                            <img src="https://img.youtube.com/vi/lyHlv-c3o7w/maxresdefault.jpg" alt="How to Make Perfect Sushi at Home" class="video-thumbnail">
                            <div class="play-overlay">
                                <i class="fas fa-play-circle"></i>
                            </div>
                        </div>
                        <h3>Perfect Sushi at Home</h3>
                        <p>Professional sushi techniques you can do at home</p>
                        <a href="https://www.youtube.com/watch?v=lyHlv-c3o7w" target="_blank" class="btn btn-outline">
                            <i class="fas fa-play"></i> Watch Tutorial
                        </a>
                    </div>
                    
                    <!-- French Baking Masterclass -->
                    <div class="tutorial-card">
                        <div class="tutorial-video">
                            <img src="https://img.youtube.com/vi/lp8RsLQ1P8Q/maxresdefault.jpg" alt="French Baking Masterclass" class="video-thumbnail">
                            <div class="play-overlay">
                                <i class="fas fa-play-circle"></i>
                            </div>
                        </div>
                        <h3>French Baking Masterclass</h3>
                        <p>Learn the secrets of classic French pastry making</p>
                        <a href="https://www.youtube.com/watch?v=lp8RsLQ1P8Q" target="_blank" class="btn btn-outline">
                            <i class="fas fa-play"></i> Watch Tutorial
                        </a>
                    </div>
                </div>
            </div>

            <div class="tab-content" id="guides">
                <h2><i class="fas fa-book-open"></i> Cooking Guides</h2>
                <div class="guides-list">
                    <div class="guide-item">
                        <i class="fas fa-leaf"></i>
                        <div class="guide-content">
                            <h3>Seasonal Cooking Guide</h3>
                            <p>Learn what fruits and vegetables are in season each month</p>
                            <a href="guides/seasonal-cooking.pdf" class="guide-link" download>
                                <i class="fas fa-download"></i> Download PDF
                            </a>
                        </div>
                    </div>
                    
                    <div class="guide-item">
                        <i class="fas fa-fire"></i>
                        <div class="guide-content">
                            <h3>Cooking Temperature Guide</h3>
                            <p>Safe cooking temperatures for different types of food</p>
                            <a href="guides/cooking-temperatures.pdf" class="guide-link" download>
                                <i class="fas fa-download"></i> Download PDF
                            </a>
                        </div>
                    </div>
                    
                    <div class="guide-item">
                        <i class="fas fa-clock"></i>
                        <div class="guide-content">
                            <h3>Food Storage Guide</h3>
                            <p>How to properly store different foods to maximize freshness</p>
                            <a href="guides/food-storage.pdf" class="guide-link" download>
                                <i class="fas fa-download"></i> Download PDF
                            </a>
                        </div>
                    </div>
                    
                    <div class="guide-item">
                        <i class="fas fa-seedling"></i>
                        <div class="guide-content">
                            <h3>Vegetarian Cooking Guide</h3>
                            <p>Essential nutrients and protein sources for vegetarian cooking</p>
                            <a href="guides/vegetarian-cooking.pdf" class="guide-link" download>
                                <i class="fas fa-download"></i> Download PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="resources-cta">
            <div class="cta-content">
                <h2>Want More Resources?</h2>
                <p>Create a free account to access our full library of resources, save your favorites, and track your progress.</p>
                <a href="register.php" class="btn btn-large">
                    <i class="fas fa-user-plus"></i> Join Free
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.resources-header {
    text-align: center;
    padding: 2.5rem 0;
    margin-bottom: 3rem;
    background: linear-gradient(135deg, #FF6B6B, #FF8E53);
    color: white;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(255, 107, 107, 0.3);
}

.resources-header h1 {
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

.category-tabs {
    display: flex;
    justify-content: center;
    gap: 1rem;
    margin-bottom: 3rem;
    flex-wrap: wrap;
}

.tab-btn {
    padding: 0.85rem 2.5rem;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border: none;
    border-radius: 30px;
    cursor: pointer;
    font-size: 1rem;
    font-weight: 600;
    transition: all 0.3s;
    color: #555;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.tab-btn.active {
    background: linear-gradient(135deg, #FF6B6B, #FF8E53);
    color: white;
    box-shadow: 0 6px 20px rgba(255, 107, 107, 0.4);
}

.tab-btn:hover:not(.active) {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.15);
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

.tab-content h2 {
    margin-bottom: 2.5rem;
    color: #333;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 1.8rem;
    font-weight: 600;
}

.tab-content h2 i {
    color: #FF6B6B;
}

.downloads-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

.resource-card {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    text-align: center;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    transition: all 0.3s;
    border: 1px solid rgba(0,0,0,0.05);
}

.resource-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}

.resource-icon {
    font-size: 3.5rem;
    color: #FF6B6B;
    margin-bottom: 1.5rem;
    background: linear-gradient(135deg, rgba(255, 107, 107, 0.1), rgba(255, 142, 83, 0.1));
    width: 90px;
    height: 90px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
}

.resource-card h3 {
    margin-bottom: 1rem;
    color: #333;
    font-size: 1.3rem;
    font-weight: 600;
}

.resource-card p {
    color: #666;
    margin-bottom: 1.5rem;
    line-height: 1.6;
    font-size: 0.95rem;
}

.btn-outline {
    background: transparent;
    color: #FF6B6B;
    border: 2px solid #FF6B6B;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s;
}

.btn-outline:hover {
    background: linear-gradient(135deg, #FF6B6B, #FF8E53);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(255, 107, 107, 0.3);
}

.tutorials-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

.tutorial-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    transition: all 0.3s;
    border: 1px solid rgba(0,0,0,0.05);
}

.tutorial-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}

.tutorial-video {
    height: 200px;
    position: relative;
    overflow: hidden;
    background: linear-gradient(135deg, #667eea, #764ba2);
}

.video-thumbnail {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s;
}

.tutorial-card:hover .video-thumbnail {
    transform: scale(1.05);
}

.play-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 4rem;
    opacity: 0.9;
    transition: opacity 0.3s;
}

.play-overlay i {
    text-shadow: 0 4px 8px rgba(0,0,0,0.3);
}

.tutorial-card h3 {
    padding: 1.5rem 1.5rem 0.5rem;
    color: #333;
    font-size: 1.2rem;
    font-weight: 600;
}

.tutorial-card p {
    padding: 0 1.5rem;
    color: #666;
    margin-bottom: 1.5rem;
    line-height: 1.6;
    font-size: 0.95rem;
}

.tutorial-card .btn-outline {
    margin: 0 1.5rem 1.5rem;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border: none;
}

.tutorial-card .btn-outline:hover {
    background: linear-gradient(135deg, #5a6fd8, #6a4190);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
}

.guides-list {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    margin-bottom: 3rem;
}

.guide-item {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    padding: 1.5rem;
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    transition: all 0.3s;
    border: 1px solid rgba(0,0,0,0.05);
}

.guide-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.15);
}

.guide-item i {
    font-size: 2.5rem;
    color: #FF6B6B;
    background: linear-gradient(135deg, rgba(255, 107, 107, 0.1), rgba(255, 142, 83, 0.1));
    width: 70px;
    height: 70px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.guide-content h3 {
    margin-bottom: 0.5rem;
    color: #333;
    font-size: 1.2rem;
    font-weight: 600;
}

.guide-content p {
    color: #666;
    margin-bottom: 0.5rem;
    line-height: 1.6;
    font-size: 0.95rem;
}

.guide-link {
    color: #FF6B6B;
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s;
}

.guide-link:hover {
    color: #FF8E53;
    gap: 0.75rem;
}

.resources-cta {
    background: linear-gradient(135deg, #4ECDC4, #44A08D);
    color: white;
    padding: 3rem;
    border-radius: 15px;
    text-align: center;
    margin-top: 4rem;
    box-shadow: 0 10px 30px rgba(78, 205, 196, 0.3);
}

.cta-content h2 {
    color: white;
    margin-bottom: 1rem;
    font-size: 2rem;
    font-weight: 700;
}

.cta-content p {
    opacity: 0.95;
    margin-bottom: 2rem;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
    font-size: 1.1rem;
    line-height: 1.6;
}

.btn-large {
    background: white;
    color: #4ECDC4;
    border: none;
    padding: 1rem 2.5rem;
    font-size: 1.1rem;
    font-weight: 600;
    border-radius: 10px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    text-decoration: none;
    transition: all 0.3s;
    box-shadow: 0 6px 20px rgba(255, 255, 255, 0.3);
}

.btn-large:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(255, 255, 255, 0.4);
}

/* Color variations for resource cards */
.resource-card:nth-child(1) .resource-icon { 
    color: #FF6B6B; 
    background: linear-gradient(135deg, rgba(255, 107, 107, 0.1), rgba(255, 142, 83, 0.1));
}
.resource-card:nth-child(2) .resource-icon { 
    color: #4ECDC4; 
    background: linear-gradient(135deg, rgba(78, 205, 196, 0.1), rgba(68, 160, 141, 0.1));
}
.resource-card:nth-child(3) .resource-icon { 
    color: #FFD166; 
    background: linear-gradient(135deg, rgba(255, 209, 102, 0.1), rgba(255, 179, 71, 0.1));
}
.resource-card:nth-child(4) .resource-icon { 
    color: #06D6A0; 
    background: linear-gradient(135deg, rgba(6, 214, 160, 0.1), rgba(5, 184, 138, 0.1));
}
.resource-card:nth-child(5) .resource-icon { 
    color: #EF476F; 
    background: linear-gradient(135deg, rgba(239, 71, 111, 0.1), rgba(215, 64, 100, 0.1));
}
.resource-card:nth-child(6) .resource-icon { 
    color: #118AB2; 
    background: linear-gradient(135deg, rgba(17, 138, 178, 0.1), rgba(15, 123, 160, 0.1));
}

/* Different colors for guide items */
.guide-item:nth-child(1) i { 
    color: #06D6A0; 
    background: linear-gradient(135deg, rgba(6, 214, 160, 0.1), rgba(5, 184, 138, 0.1));
}
.guide-item:nth-child(2) i { 
    color: #EF476F; 
    background: linear-gradient(135deg, rgba(239, 71, 111, 0.1), rgba(215, 64, 100, 0.1));
}
.guide-item:nth-child(3) i { 
    color: #FFD166; 
    background: linear-gradient(135deg, rgba(255, 209, 102, 0.1), rgba(255, 179, 71, 0.1));
}
.guide-item:nth-child(4) i { 
    color: #118AB2; 
    background: linear-gradient(135deg, rgba(17, 138, 178, 0.1), rgba(15, 123, 160, 0.1));
}

/* Animation for cards */
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

.resource-card,
.tutorial-card,
.guide-item {
    animation: fadeInUp 0.6s ease-out;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .resources-header h1 {
        font-size: 2.2rem;
    }
    
    .subtitle {
        font-size: 1.1rem;
        padding: 0 1rem;
    }
    
    .tab-btn {
        padding: 0.75rem 1.5rem;
        font-size: 0.95rem;
    }
    
    .tutorials-grid {
        grid-template-columns: 1fr;
    }
    
    .guide-item {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .guide-item i {
        margin: 0 auto;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab switching functionality
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const tabId = this.dataset.tab;
            
            // Update active tab button
            tabBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            // Show corresponding tab content
            tabContents.forEach(content => {
                content.classList.remove('active');
                if (content.id === tabId) {
                    content.classList.add('active');
                }
            });
        });
    });
    
    // Handle download links
    document.querySelectorAll('a[download]').forEach(link => {
        link.addEventListener('click', function(e) {
            const resourceName = this.closest('.resource-card, .guide-item').querySelector('h3').textContent;
            console.log(`Downloading: ${resourceName}`);
            // In real application, this would trigger file download
        });
    });
    
    // Video thumbnail hover effect
    document.querySelectorAll('.tutorial-video').forEach(video => {
        video.addEventListener('mouseenter', function() {
            const overlay = this.querySelector('.play-overlay');
            overlay.style.opacity = '1';
        });
        
        video.addEventListener('mouseleave', function() {
            const overlay = this.querySelector('.play-overlay');
            overlay.style.opacity = '0.9';
        });
    });
    
    // Animate cards on scroll
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1 });
    
    // Observe resource cards
    document.querySelectorAll('.resource-card, .tutorial-card, .guide-item').forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = `all 0.6s ease ${index * 0.1}s`;
        observer.observe(card);
    });
});
</script>

<?php include 'includes/footer.php'; ?>