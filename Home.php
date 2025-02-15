<?php
// Start session and check if the user is logged in
session_start();
include 'navbar.php'; // Assuming this is a navbar file for your navigation

// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'project1');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch bikes from the database to display
$sql = "SELECT * FROM bikes ORDER BY id DESC";
$result = $conn->query($sql);

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bike Bazaar</title>
    <link rel="stylesheet" href="home.css">
</head>
<body>
    <!-- Hero Section -->
    <div class="hero">
        <div>
            <h1>SECONDHAND BIKE SELLING SERVICE</h1>
            <p>"Certified Secondhand Bikes – Quality You Can Trust!"</p>
        </div>
    </div>

    <!-- Featured Bikes Section -->
    <div class="featured-bikes">
        <h2>Find Your Perfect Secondhand Bike</h2>
        <p>Reliable bikes at affordable prices</p>
        <div class="bike-list">
            <?php if ($result && $result->num_rows > 0): ?>
                <!-- Loop through all the bikes -->
                <?php while ($bike = $result->fetch_assoc()): ?>
                    <div class="bike-item">
                        <!-- Dynamically generate the path to the uploaded image -->
                        <img src="http://localhost/sujal/bikebazaar/<?php echo htmlspecialchars($bike['image']); ?>" 
                             alt="<?php echo htmlspecialchars($bike['bike_name']); ?>" 
                             width="200" 
                             height="150">
                        
                        <h3><?php echo htmlspecialchars($bike['bike_name']); ?></h3>
                        <p><strong>Price:</strong> $<?php echo number_format($bike['price'], 2); ?></p>
                        <p><strong>Model:</strong> <?php echo htmlspecialchars($bike['model']); ?></p>
                        <p><strong>Kilometer:</strong> <?php echo htmlspecialchars($bike['kilometer']); ?> KM</p>
                        <p><strong>Owner:</strong> <?php echo htmlspecialchars($bike['owner']); ?></p>
                        <p><strong>Description:</strong> <?php echo htmlspecialchars($bike['description']); ?></p>
                        
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No bikes available at the moment. Please check back later!</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Contact Section -->
    <div id="contact" class="contact">
        <h2>Contact Us</h2>
        <p>Email: <a href="mailto:support@bikebazaar.com">support@bikebazaar.com</a></p>
        <p>Phone: +1 (800) 123-4567</p>
    </div>

    <!-- Footer Section -->
    <footer>
        <p>&copy; 2024 Bike Bazaar. All rights reserved.</p>
    </footer>
</body>
</html>
