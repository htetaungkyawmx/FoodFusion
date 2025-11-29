<?php include 'includes/header.php'; ?>

<div class="container">
    <section class="page-header">
        <h1>Culinary Resources</h1>
        <p>Download recipe cards, watch tutorials, and improve your cooking skills</p>
    </section>

    <div class="resources-grid">
        <?php
        include 'config/database.php';
        $database = new Database();
        $db = $database->getConnection();
        
        $query = "SELECT * FROM resources ORDER BY created_at DESC";
        $stmt = $db->prepare($query);
        $stmt->execute();
        
        if($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '
                <div class="resource-card">
                    <div class="resource-icon">
                        <i class="fas fa-' . ($row['resource_type'] == 'video' ? 'video' : 'file-download') . '"></i>
                    </div>
                    <h3>' . htmlspecialchars($row['title']) . '</h3>
                    <p>' . htmlspecialchars($row['description']) . '</p>
                    <div class="resource-type">' . ucfirst(str_replace('_', ' ', $row['resource_type'])) . '</div>
                    <button class="btn">Download</button>
                </div>';
            }
        } else {
            echo '<p>No resources available at the moment.</p>';
        }
        ?>
    </div>

    <!-- Cooking Tutorials Section -->
    <section class="tutorials-section">
        <h2>Cooking Tutorials</h2>
        <div class="tutorials-grid">
            <div class="tutorial-card">
                <h3>Knife Skills 101</h3>
                <p>Learn proper knife techniques for safe and efficient cooking</p>
                <button class="btn">Watch Tutorial</button>
            </div>
            <div class="tutorial-card">
                <h3>Sauce Making Basics</h3>
                <p>Master the five mother sauces of French cuisine</p>
                <button class="btn">Watch Tutorial</button>
            </div>
            <div class="tutorial-card">
                <h3>Baking Fundamentals</h3>
                <p>Understand the science behind perfect baking</p>
                <button class="btn">Watch Tutorial</button>
            </div>
        </div>
    </section>
</div>

<?php include 'includes/footer.php'; ?>