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
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        .navbar {
            background-color: #00274d;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar a {
            color: #fff;
            text-decoration: none;
            margin: 0 10px;
            font-weight: bold;
            transition: color 0.3s;
        }

        .navbar a:hover {
            color: #00bcd4;
        }

        .hero {
            height: 400px;
            background: url('bg1.jpg') no-repeat center center/cover;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            text-align: center;
        }

        .hero h1 {
            font-size: 3rem;
            margin-bottom: 10px;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
        }

        .featured-bikes {
            padding: 40px 20px;
            background-color: #e3f2fd;
            text-align: center;
        }

        .featured-bikes h2 {
            font-size: 2.5rem;
            color: #00274d;
            margin-bottom: 20px;
        }

        .featured-bikes p {
            font-size: 1.2rem;
            margin-bottom: 30px;
            color: #555;
        }

        .bike-list {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .bike-item {
            background: #ffffff;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            width: 280px;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .bike-item img {
            width: 100%;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .bike-item h3 {
            font-size: 1.4rem;
            color: #00274d;
            margin-bottom: 10px;
        }

        .bike-item p {
            font-size: 1rem;
            color: #555;
        }

        .bike-item:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            border-color: #00bcd4;
        }

        .contact {
            background-color: #00274d;
            color: white;
            padding: 40px 20px;
            text-align: center;
        }

        footer {
            background-color: #001f3f;
            color: white;
            text-align: center;
            padding: 15px 0;
            font-size: 0.9rem;
        }
    </style>
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
                        <!-- Link to the booking page with the bike's details as URL parameters -->
                        <a href="bikebooking.php?bike_id=<?php echo $bike['id']; ?>&bike_name=<?php echo urlencode($bike['bike_name']); ?>&price=<?php echo $bike['price']; ?>&model=<?php echo urlencode($bike['model']); ?>&kilometer=<?php echo urlencode($bike['kilometer']); ?>&owner=<?php echo urlencode($bike['owner']); ?>&description=<?php echo urlencode($bike['description']); ?>">
                            <img src="http://localhost/shivam/bikebazaar/<?php echo htmlspecialchars($bike['image']); ?>" 
                                 alt="<?php echo htmlspecialchars($bike['bike_name']); ?>" 
                                 width="200" 
                                 height="150">
                            <h3><?php echo htmlspecialchars($bike['bike_name']); ?></h3>
                            <p><strong>Price:</strong> <?php echo number_format($bike['price'], 2); ?></p>
                            <p><strong>Model:</strong> <?php echo htmlspecialchars($bike['model']); ?></p>
                            <p><strong>Kilometer:</strong> <?php echo htmlspecialchars($bike['kilometer']); ?> KM</p>
                            <p><strong>Owner:</strong> <?php echo htmlspecialchars($bike['owner']); ?></p>
                            <p><strong>Description:</strong> <?php echo htmlspecialchars($bike['description']); ?></p>
                        </a>
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
        <p>Phone: +91 8668216117</p>
    </div>

    <!-- Footer Section -->
    <footer>
        <p>&copy; 2024 Bike Bazaar. All rights reserved.</p>
    </footer>
</body>
</html>