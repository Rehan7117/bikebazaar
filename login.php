<?php include 'navbar.php'; ?>
<?php
// Start session only if not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'project1'); // Change 'project1' to your database name

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize the input to avoid SQL injection
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    // Check if the username/email exists in the database
    $sql = "SELECT * FROM users WHERE username='$username' OR email='$username' LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch the user data
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Store user data in the session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];

            // Redirect to the home page
            header("Location: home.php");
            exit(); // Ensure no further code is executed
        } else {
            $error_message = "Invalid password.";
        }
    } else {
        $error_message = "No user found with that username/email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- CSS Styling Inside the Same Page -->
    <style>
        /* General styles */
        body {
            font-family: Arial, sans-serif;
            background: url('backgroundbikeimage.jpg') no-repeat center center/cover;
            background-color: #f4f4f9;
            height: 100vh;  /* Full viewport height */
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            padding-top: 60px;  /* Space for fixed navbar */
        }

        .navbar {
            width: 100%;         /* Full-width navbar */
            position: fixed;
                            /* Fix navbar at the top */
            top: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #00274d;
            color: white;
            padding: 10px 20px;
            font-family: Arial, sans-serif;
            box-sizing: border-box; /* Ensures padding doesn't shrink the width */
        }

        .container {
            background-color: rgba(255, 255, 255, 0.9); /* Semi-transparent form background */
            border-radius: 12px;
            padding: 30px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            text-align: center;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        input[type="text"],
        input[type="password"],
        button {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            box-sizing: border-box;
        }

        button {
            background-color: #4CAF50;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
        }

        .error-message {
            color: red;
            margin-top: 10px;
        }

        a {
            text-decoration: none;
            color: #007bff;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>

        <!-- Error Message -->
        <?php if (isset($error_message)): ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <!-- Login Form -->
        <form method="POST">
            <label for="username">Username or Email:</label>
            <input type="text" id="username" placeholder="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" placeholder="password" name="password" required>

            <button type="submit">Login</button>
        </form>

        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>