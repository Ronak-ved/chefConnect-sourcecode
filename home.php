<?php
include 'config.php'; // Include the database connection file
session_start();

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if (!isset($_SESSION['userdata'])) {
    header('location:login.php');
}
$userdata = $_SESSION['userdata'];
$name = $userdata['name'];
echo($name);

$conn->close(); // Close the database connection
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>home page</title>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <nav class="navbar"> 
        <div class="comname"><img src="logo.webp" alt="cheflogo">ChefConnect</div>
        <ul class="nav-link">
            <li><a href="index.php">Home</a></li>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="#">My Bookings</a></li>
            <li><a href="#">Contact Us</a></li>
            <li><a href="#">About Us</a></li>
            <li><a href="logout.php">Logout</li>
        </ul>

        <div class="hamburger">
            <!--icon for mobile meneu-->
        </div>
    </nav>
</body>
</html>