<?php
// Include navbar, which already starts the session
include 'navbar.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Initialize the database connection
$conn = new mysqli('localhost', 'root', '', 'project1');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user details
$user_details = [];
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT username, email, phone FROM users WHERE id = '$user_id'"; // Fetching phone number as well
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $user_details = $result->fetch_assoc();
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bike_name = $conn->real_escape_string($_POST['bike_name']);
    $bike_number = $conn->real_escape_string($_POST['bike_number']);
    $username = $user_details['username'];
    $email = $user_details['email'];
    $phone = $conn->real_escape_string($_POST['phone']);  // Capturing the additional phone number

    // Insert booking details into the database
    $sql = "INSERT INTO bike_bookings (user_id, bike_name, bike_number, username, email, phone) 
            VALUES ('$user_id', '$bike_name', '$bike_number', '$username', '$email', '$phone')";
    
    if ($conn->query($sql) === TRUE) {
        $success_message = "Bike booking successful!";
    } else {
        $error_message = "Error: " . $conn->error;
    }
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bike Booking</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .booking-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .booking-container h2 {
            color: #333;
        }
        .booking-container form {
            margin-top: 20px;
        }
        .booking-container label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }
        .booking-container input, .booking-container select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .booking-container button {
            padding: 10px 15px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .booking-container button:hover {
            background-color: #0056b3;
        }
        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .message.success {
            background-color: #d4edda;
            color: #155724;
        }
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="booking-container">
        <h2>Book a Bike</h2>

        <!-- Show logged-in user's name and email -->
        <p>Welcome, <?php echo htmlspecialchars($user_details['username']); ?> (<?php echo htmlspecialchars($user_details['email']); ?>)</p>

        <?php if (isset($success_message)): ?>
            <div class="message success"><?php echo $success_message; ?></div>
        <?php elseif (isset($error_message)): ?>
            <div class="message error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <!-- Booking form -->
        <form method="POST" action="">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($user_details['username']); ?>" readonly>

            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user_details['email']); ?>" readonly>

            <label for="phone">Phone Number</label>
            <input type="text" name="phone" id="phone" value="<?php echo htmlspecialchars($user_details['phone']); ?>" required>

            <label for="bike_name">Enter Bike Name</label>
            <input type="text" name="bike_name" id="bike_name" placeholder="Enter Bike Name" required>

            <label for="bike_number">Bike Number</label>
            <input type="text" name="bike_number" id="bike_number" placeholder="Enter Bike Number" required>
            
            <button type="submit">Book Now</button>
        </form>
    </div>
</body>
</html>
