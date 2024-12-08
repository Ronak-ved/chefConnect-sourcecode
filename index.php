<?php
include 'config.php'; // Include the database connection file
session_start();
if (isset($_SESSION['userdata'])) {
    header('location:home.php');
}

if (isset($_SESSION['chefdata'])) {
    header('location:chef_home.php');
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>landingPage/ChefConnect</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="reglog">
    <div class="landing-container">
    <h1 id="landh1">Welcome to ChefConnect!</h1>
    <p id="landp">What describes you the best?</p>
        <div class="buttons">
            <a href="registration.php" class="button user-btn">I am a User</a>
            <a href="chef_registration.php" class="button chef-btn">I am a Chef</a>
        </div>

    </div>
    
</body>
</html>