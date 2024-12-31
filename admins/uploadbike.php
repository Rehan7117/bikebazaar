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
    // Sanitize and validate input
    $bike_name = htmlspecialchars($conn->real_escape_string($_POST['bike_name']));
    $price = htmlspecialchars($conn->real_escape_string($_POST['price']));
    $description = htmlspecialchars($conn->real_escape_string($_POST['description']));

    // Handle the image upload
    $target_dir = "C:/xampp/htdocs/sujal/bikebazaar/images/";
    $image_name = $_FILES['bike_image']['name'];
    $image_tmp_name = $_FILES['bike_image']['tmp_name'];
    $image_size = $_FILES['bike_image']['size'];
    $image_error = $_FILES['bike_image']['error'];

    // Check for upload errors
    if ($image_error === 0) {
        // Ensure the 'images' directory exists
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true); // Create 'images' directory if it doesn't exist
        }

        // Generate a unique name for the image to avoid conflicts
        $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
        $image_new_name = uniqid('', true) . "." . $image_ext;
        $target_file = $target_dir . $image_new_name;

        // Validate the file type
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($image_ext, $allowed_extensions)) {
            // Validate the file size (e.g., 5MB max)
            if ($image_size <= 5 * 1024 * 1024) { // 5MB limit
                // Move the uploaded file to the 'images' directory
                if (move_uploaded_file($image_tmp_name, $target_file)) {
                    // Save relative path to the database
                    $image_relative_path = 'images/' . $image_new_name;

                    // Insert bike details and image path into the database using prepared statements
                    $stmt = $conn->prepare("INSERT INTO bikes (bike_name, price, description, image) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("ssss", $bike_name, $price, $description, $image_relative_path);

                    if ($stmt->execute()) {
                        $success_message = "Bike details and image uploaded successfully!";
                    } else {
                        $error_message = "Error: " . $stmt->error;
                    }

                    // Close the statement
                    $stmt->close();
                } else {
                    $error_message = "Failed to upload image.";
                }
            } else {
                $error_message = "File size exceeds the 5MB limit.";
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
    $sql = "SELECT image FROM bikes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $bike = $result->fetch_assoc();
        $image_path = 'C:/xampp/htdocs/sujal/bikebazaar/' . $bike['image'];

        // Delete the bike from the database
        $delete_sql = "DELETE FROM bikes WHERE id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("i", $delete_id);

        if ($delete_stmt->execute()) {
            // Delete the image from the server
            if (file_exists($image_path)) {
                unlink($image_path);  // Delete the image file
            }
            $success_message = "Bike deleted successfully!";
        } else {
            $error_message = "Error: " . $delete_stmt->error;
        }

        // Close statements
        $delete_stmt->close();
    } else {
        $error_message = "Bike not found.";
    }

    // Close the result and statement
    $stmt->close();
}

// Fetch bikes from the database to display
$sql = "SELECT * FROM bikes ORDER BY id DESC";
$result = $conn->query($sql);

// Close the database connection
$conn->close();
?>

<!-- Display success or error messages -->
<?php
if (isset($success_message)) {
    echo "<div style='color: green;'>" . $success_message . "</div>";
}
if (isset($error_message)) {
    echo "<div style='color: red;'>" . $error_message . "</div>";
}
?>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="uploadbike.css"> <!-- Link to your external CSS file -->
    </head>
    <body>
        <form method="POST" enctype="multipart/form-data">
            <label for="bike_name">Bike Name:</label>
            <input type="text" name="bike_name" required><br>

            <label for="price">Price:</label>
            <input type="text" name="price" required><br>

            <label for="description">Description:</label>
            <textarea name="description" required></textarea><br>

            <label for="bike_image">Upload Bike Image:</label>
            <input type="file" name="bike_image" required><br>

            <input type="submit" value="Upload Bike">
        </form>

        <!-- Display the list of bikes with images and delete option -->
        <h2>Uploaded Bikes</h2>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div style='border: 1px solid #ddd; padding: 10px; margin-bottom: 10px;'>";
                echo "<h3>" . htmlspecialchars($row['bike_name']) . "</h3>";
                echo "<p>Price: " . htmlspecialchars($row['price']) . "</p>";
                echo "<p>Description: " . htmlspecialchars($row['description']) . "</p>";
                echo "<img src='/sujal/bikebazaar/" . htmlspecialchars($row['image']) . "' alt='" . htmlspecialchars($row['bike_name']) . "' style='max-width: 200px;'><br>";
                echo "<a href='?delete_id=" . $row['id'] . "' style='color: red;'>Delete</a>";
                echo "</div>";
            }
        } else {
            echo "No bikes uploaded yet.";
        }
        ?>
    </body>
</html>
