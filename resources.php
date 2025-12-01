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
                        <a href="#" class="btn btn-outline">
                            <i class="fas fa-download"></i> Download PDF
                        </a>
                    </div>
                    
                    <div class="resource-card">
                        <div class="resource-icon">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <h3>Recipe Card Template</h3>
                        <p>Beautiful printable recipe cards for your kitchen</p>
                        <a href="#" class="btn btn-outline">
                            <i class="fas fa-download"></i> Download Template
                        </a>
                    </div>
                    
                    <div class="resource-card">
                        <div class="resource-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <h3>Grocery Shopping List</h3>
                        <p>Organized shopping list template for your kitchen needs</p>
                        <a href="#" class="btn btn-outline">
                            <i class="fas fa-download"></i> Download PDF
                        </a>
                    </div>
                    
                    <div class="resource-card">
                        <div class="resource-icon">
                            <i class="fas fa-balance-scale"></i>
                        </div>
                        <h3>Measurement Conversion Chart</h3>
                        <p>Convert between cups, grams, ounces and more</p>
                        <a href="#" class="btn btn-outline">
                            <i class="fas fa-download"></i> Download Chart
                        </a>
                    </div>
                </div>
            </div>

            <div class="tab-content" id="tutorials">
                <h2><i class="fas fa-video"></i> Cooking Tutorials</h2>
                <div class="tutorials-grid">
                    <div class="tutorial-card">
                        <div class="tutorial-video">
                            <i class="fas fa-play-circle"></i>
                        </div>
                        <h3>Knife Skills 101</h3>
                        <p>Learn proper knife techniques for safe and efficient cooking</p>
                        <button class="btn btn-outline">
                            <i class="fas fa-play"></i> Watch Tutorial
                        </button>
                    </div>
                    
                    <div class="tutorial-card">
                        <div class="tutorial-video">
                            <i class="fas fa-play-circle"></i>
                        </div>
                        <h3>Basic Sauce Making</h3>
                        <p>Master the five mother sauces of French cuisine</p>
                        <button class="btn btn-outline">
                            <i class="fas fa-play"></i> Watch Tutorial
                        </button>
                    </div>
                    
                    <div class="tutorial-card">
                        <div class="tutorial-video">
                            <i class="fas fa-play-circle"></i>
                        </div>
                        <h3>Baking Fundamentals</h3>
                        <p>Understand the science behind perfect baking</p>
                        <button class="btn btn-outline">
                            <i class="fas fa-play"></i> Watch Tutorial
                        </button>
                    </div>
                    
                    <div class="tutorial-card">
                        <div class="tutorial-video">
                            <i class="fas fa-play-circle"></i>
                        </div>
                        <h3>Meat Cooking Guide</h3>
                        <p>Learn how to cook meat to perfection every time</p>
                        <button class="btn btn-outline">
                            <i class="fas fa-play"></i> Watch Tutorial
                        </button>
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
                            <a href="#" class="guide-link">Read Guide →</a>
                        </div>
                    </div>
                    
                    <div class="guide-item">
                        <i class="fas fa-fire"></i>
                        <div class="guide-content">
                            <h3>Cooking Temperature Guide</h3>
                            <p>Safe cooking temperatures for different types of food</p>
                            <a href="#" class="guide-link">Read Guide →</a>
                        </div>
                    </div>
                    
                    <div class="guide-item">
                        <i class="fas fa-clock"></i>
                        <div class="guide-content">
                            <h3>Food Storage Guide</h3>
                            <p>How to properly store different foods to maximize freshness</p>
                            <a href="#" class="guide-link">Read Guide →</a>
                        </div>
                    </div>
                    
                    <div class="guide-item">
                        <i class="fas fa-seedling"></i>
                        <div class="guide-content">
                            <h3>Vegetarian Cooking Guide</h3>
                            <p>Essential nutrients and protein sources for vegetarian cooking</p>
                            <a href="#" class="guide-link">Read Guide →</a>
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
    padding: 2rem 0;
    margin-bottom: 3rem;
}

.resources-header h1 {
    color: #333;
    margin-bottom: 0.5rem;
}

.subtitle {
    color: #666;
    font-size: 1.1rem;
}

.category-tabs {
    display: flex;
    justify-content: center;
    gap: 1rem;
    margin-bottom: 2rem;
}

.tab-btn {
    padding: 0.75rem 2rem;
    background: #f8f9fa;
    border: none;
    border-radius: 25px;
    cursor: pointer;
    font-size: 1rem;
    font-weight: 500;
    transition: all 0.3s;
}

.tab-btn.active {
    background: #e74c3c;
    color: white;
}

.tab-btn:hover:not(.active) {
    background: #e9ecef;
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

.tab-content h2 {
    margin-bottom: 2rem;
    color: #333;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.downloads-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

.resource-card {
    background: white;
    border-radius: 10px;
    padding: 2rem;
    text-align: center;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s;
}

.resource-card:hover {
    transform: translateY(-5px);
}

.resource-icon {
    font-size: 3rem;
    color: #3498db;
    margin-bottom: 1.5rem;
}

.resource-card h3 {
    margin-bottom: 1rem;
    color: #333;
}

.resource-card p {
    color: #666;
    margin-bottom: 1.5rem;
    line-height: 1.6;
}

.tutorials-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

.tutorial-card {
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s;
}

.tutorial-card:hover {
    transform: translateY(-5px);
}

.tutorial-video {
    height: 180px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 4rem;
}

.tutorial-card h3 {
    padding: 1.5rem 1.5rem 0.5rem;
    color: #333;
}

.tutorial-card p {
    padding: 0 1.5rem;
    color: #666;
    margin-bottom: 1.5rem;
    line-height: 1.6;
}

.tutorial-card button {
    margin: 0 1.5rem 1.5rem;
}

.guides-list {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    margin-bottom: 3rem;
}

.guide-item {
    display: flex;
    align-items: flex-start;
    gap: 1.5rem;
    padding: 1.5rem;
    background: white;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s;
}

.guide-item:hover {
    transform: translateY(-3px);
}

.guide-item i {
    font-size: 2rem;
    color: #2ecc71;
    margin-top: 0.25rem;
}

.guide-content h3 {
    margin-bottom: 0.5rem;
    color: #333;
}

.guide-content p {
    color: #666;
    margin-bottom: 0.5rem;
    line-height: 1.6;
}

.guide-link {
    color: #3498db;
    text-decoration: none;
    font-weight: 500;
}

.guide-link:hover {
    text-decoration: underline;
}

.resources-cta {
    background: linear-gradient(135deg, #f39c12, #d35400);
    color: white;
    padding: 3rem;
    border-radius: 10px;
    text-align: center;
    margin-top: 3rem;
}

.cta-content h2 {
    color: white;
    margin-bottom: 1rem;
}

.cta-content p {
    opacity: 0.9;
    margin-bottom: 2rem;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

.btn-large {
    padding: 1rem 2.5rem;
    font-size: 1.1rem;
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
    
    // Simulate resource download
    document.querySelectorAll('.resource-card .btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const resourceName = this.closest('.resource-card').querySelector('h3').textContent;
            alert(`Downloading "${resourceName}"...\n\n(In a real application, this would download the file)`);
        });
    });
    
    // Simulate tutorial play
    document.querySelectorAll('.tutorial-card .btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const tutorialName = this.closest('.tutorial-card').querySelector('h3').textContent;
            alert(`Playing tutorial: "${tutorialName}"\n\n(In a real application, this would play the video)`);
        });
    });
});
</script>

<?php include 'includes/footer.php'; ?>