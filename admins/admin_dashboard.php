<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .dashboard-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .dashboard-header h1 {
            font-size: 24px;
            color: #333;
        }
        .dashboard-header a {
            text-decoration: none;
            color: #fff;
            background-color: #007bff;
            padding: 10px 15px;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .dashboard-header a:hover {
            background-color: #0056b3;
        }
        .dashboard-content {
            font-size: 18px;
            color: #555;
        }
        ul {
            padding: 0;
            list-style-type: none;
        }
        li {
            margin: 10px 0;
        }
        li a {
            color: #007bff;
            text-decoration: none;
            font-size: 18px;
            transition: color 0.3s;
        }
        li a:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['admin_name']); ?></h1>
            <a href="admin_logout.php">Logout</a> <!-- Uncommented logout link -->
        </div>
        <div class="dashboard-content">
            <p>You are logged in as an admin.</p>
            <ul>
                <li><a href="user_details.php">View Users</a></li>
                <li><a href="bike_upload.php">Upload Bikes</a></li> <!-- Fixed the link extension to .php -->
            </ul>
        </div>
    </div>
</body>
</html>
