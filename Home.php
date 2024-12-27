<?php
// Start session and check if the user is logged in
session_start();
include 'navbar.php'; // Assuming this is a navbar file for your navigation

// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'project1');

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch bikes from the database
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
            <?php if ($result->num_rows > 0): ?>
                <!-- Loop through all the bikes -->
                <?php while ($bike = $result->fetch_assoc()): ?>
                    <div class="bike-item">
                        <!-- Display bike image -->
                        <?php if (!empty($bike['image'])): ?>
                            <!-- Dynamically generate the path to the uploaded image -->
                            <img src="uploads/<?php echo $bike['image']; ?>" alt="Bike Image" width="200">
                        <?php else: ?>
                            <!-- Fallback image if no image is uploaded -->
                            <img src="default-image.jpg" alt="No image available" width="200">
                        <?php endif; ?>
                        
                        <h3><?php echo htmlspecialchars($bike['bike_name']); ?></h3>
                        <p>Price: $<?php echo number_format($bike['price'], 2); ?></p>
                        <p>Description: <?php echo htmlspecialchars($bike['description']); ?></p>
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
