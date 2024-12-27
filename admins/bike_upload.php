<?php
// Start session and check if the user is an admin
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'project1');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission for bike upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['bike_image'])) {
    $bike_name = $conn->real_escape_string($_POST['bike_name']);
    $price = $conn->real_escape_string($_POST['price']);
    $description = $conn->real_escape_string($_POST['description']);

    // Handle the image upload
    $image_name = $_FILES['bike_image']['name'];
    $image_tmp_name = $_FILES['bike_image']['tmp_name'];
    $image_size = $_FILES['bike_image']['size'];
    $image_error = $_FILES['bike_image']['error'];

    // Check if there are no errors in the upload
    if ($image_error === 0) {
        // Ensure the 'uploads' directory exists
        $upload_directory = 'uploads/';
        if (!is_dir($upload_directory)) {
            mkdir($upload_directory, 0777, true); // Create 'uploads' directory if it doesn't exist
        }

        // Generate a unique name for the image to avoid conflicts
        $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
        $image_new_name = uniqid('', true) . "." . $image_ext;
        $image_destination = $upload_directory . $image_new_name;

        // Check if the image is a valid file type
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($image_ext, $allowed_extensions)) {
            // Move the uploaded file to the 'uploads' directory
            if (move_uploaded_file($image_tmp_name, $image_destination)) {
                // Insert bike details and image path into the database
                $sql = "INSERT INTO bikes (bike_name, price, description, image) 
                        VALUES ('$bike_name', '$price', '$description', '$image_destination')";

                if ($conn->query($sql) === TRUE) {
                    $success_message = "Bike details and image uploaded successfully!";
                } else {
                    $error_message = "Error: " . $conn->error;
                }
            } else {
                $error_message = "Failed to upload image.";
            }
        } else {
            $error_message = "Invalid file type. Only JPG, JPEG, PNG, GIF are allowed.";
        }
    } else {
        $error_message = "There was an error uploading the image.";
    }
}

// Handle bike deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Fetch the bike's image path from the database
    $sql = "SELECT image FROM bikes WHERE id = $delete_id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $bike = $result->fetch_assoc();
        $image_path = $bike['image'];

        // Delete the bike from the database
        $delete_sql = "DELETE FROM bikes WHERE id = $delete_id";
        if ($conn->query($delete_sql) === TRUE) {
            // Delete the image from the server
            if (file_exists($image_path)) {
                unlink($image_path);  // Delete the image file
            }
            $success_message = "Bike deleted successfully!";
        } else {
            $error_message = "Error: " . $conn->error;
        }
    }
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
    <title>Admin - Upload Bike Details</title>
    <link rel="stylesheet" href="bikeupload.css">
</head>
<body>
    <h2>Admin - Upload Bike Details</h2>

    <!-- Show success or error messages -->
    <?php if (isset($success_message)): ?>
        <div class="message success"><?php echo $success_message; ?></div>
    <?php elseif (isset($error_message)): ?>
        <div class="message error"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <!-- Bike upload form -->
    <form method="POST" action="" enctype="multipart/form-data">
        <label for="bike_name">Bike Name</label>
        <input type="text" name="bike_name" id="bike_name" required>

        <label for="price">Price ($)</label>
        <input type="number" name="price" id="price" step="0.01" required>

        <label for="description">Description</label>
        <textarea name="description" id="description" rows="4" required></textarea>

        <label for="bike_image">Bike Image</label>
        <input type="file" name="bike_image" id="bike_image" accept="image/*" required>

        <button type="submit">Upload Bike</button>
    </form>

    <!-- Display all uploaded bikes -->
    <div class="bike-list">
        <h3>Uploaded Bikes</h3>
        <?php if ($result->num_rows > 0): ?>
            <!-- Loop through all the bikes -->
            <?php while ($bike = $result->fetch_assoc()): ?>
                <div class="bike-item">
                    <!-- Display bike image -->
                    <img src="<?php echo $bike['image']; ?>" alt="<?php echo htmlspecialchars($bike['bike_name']); ?>" width="150">
                    <h3><?php echo htmlspecialchars($bike['bike_name']); ?></h3>
                    <p>Price: $<?php echo number_format($bike['price'], 2); ?></p>
                    <p>Description: <?php echo htmlspecialchars($bike['description']); ?></p>
                    <!-- Delete button -->
                    <a href="?delete_id=<?php echo $bike['id']; ?>" onclick="return confirm('Are you sure you want to delete this bike?');">Delete</a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No bikes available at the moment. Please check back later!</p>
        <?php endif; ?>
    </div>

    <footer>
        <p>&copy; 2024 Bike Bazaar. All rights reserved.</p>
    </footer>
</body>
</html>
