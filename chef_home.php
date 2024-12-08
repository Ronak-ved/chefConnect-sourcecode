<?php
include 'config.php'; // Include the database connection file
session_start();

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if (!isset($_SESSION['chefdata'])) {
    header('location:chef_login.php');
}
$userdata = $_SESSION['chefdata'];
$name = $userdata['chef_name'];
echo($name);

$conn->close(); // Close the database connection
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>chef home page</title>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <nav class="navbar chef-nav"> 
        <div class="comname"><img src="logo.webp" alt="cheflogo">ChefConnect</div>
        <ul class="nav-link">
            <li><a href="chef_home.php">Home</a></li>
            <li><a href="chef_profile.php">Profile</a></li>
            <li><a href="#">Orders</a></li>
            <li><a href="#">Contact Us</a></li>
            <li><a href="#">About Us</a></li>
            <li><a href="chef_logout.php">Logout</li>
        </ul>

        <div class="hamburger">
            <!--icon for mobile meneu-->
        </div>
    </nav>
</body>
</html>