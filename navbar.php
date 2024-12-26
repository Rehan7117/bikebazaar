<?php
// Start session only if not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<style>
    /* Navbar container styles */
    .navbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #333; /* Dark background color */
        color: white; /* Text color */
        padding: 10px 20px; /* Spacing */
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2); /* Subtle shadow */
        font-family: Arial, sans-serif; /* Font */
    }

    /* Navbar title styles */
    .navbar h1 {
        margin: 0;
        font-size: 1.5rem;
    }

    /* Navbar link container */
    .navbar div {
        display: flex;
        align-items: center;
    }

    /* Navbar links */
    .navbar a {
        color: white;
        text-decoration: none;
        margin: 0 10px;
        padding: 5px 10px;
        border-radius: 5px;
        transition: background-color 0.3s, color 0.3s; /* Smooth hover effect */
    }

    /* Hover effect for links */
    .navbar a:hover {
        background-color: #575757;
        color: #f0f0f0;
    }

    /* Welcome text styles */
    .navbar span {
        margin-right: 10px;
        font-size: 0.9rem;
        font-weight: bold;
    }

    /* Responsive styles */
    @media (max-width: 600px) {
        .navbar {
            flex-direction: column;
            align-items: flex-start;
        }

        .navbar div {
            flex-direction: column;
            align-items: flex-start;
        }

        .navbar a {
            margin: 5px 0;
        }
    }
</style>


<div class="navbar">
    <h1>Bike Bazaar</h1>
    <div>
        <a href="home.php">Home</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <!-- <a href="register.php">Register</a> -->
        <?php endif; ?>
    </div>
</div>
