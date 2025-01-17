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
$foodpref = $userdata['foodpref'];


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
            <li><a href="mybooking.php">My Bookings</a></li>
            <li><a href="contact.php">Contact Us</a></li>
            <li><a href="#">About Us</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>

        <div class="hamburger">
            <!--icon for mobile meneu-->
        </div>
    </nav>
    <div class="home-container">
    <div class="search-container">
        <form action="" class="searchbar">
           <input type="text" placeholder="Search Chef" name="search-input">
           <button type="submit"><img src="search.jpeg"></button>
        </form>
    </div>
    <!-- creating chef card -->
    <div class="top-rated-chefs">
    <h2 class="top-rated-chef-head">Top Rated Chefs</h2>
    <div class="chef-profiles">
        <!-- Chef Profile -->
        <div class="chef-card">
            <img src="chef1.jpg" alt="Chef Profile Photo" class="chef-photo">
            <div class="chef-info">
                <h3 class="chef-name">Raju Sharma</h3>
                <p class="chef-rating">⭐ 4.9/5</p>
                <p class="chef-specialty">Spl:Rajasthani</p>
            </div>
            <div class="view-hire-buttons">
                <ul class="view-hire-link">
                    <li><a href="#">View</a></li>
                    <li><a href="#">Hire</a></li>
                </ul>
            </div> 
        </div>
        <!-- Add more chef profiles as needed -->
        <div class="chef-card">
            <img src="chef2.jpg" alt="Chef Profile Photo" class="chef-photo">
            <div class="chef-info">
                <h3 class="chef-name">Savitri Sen</h3>
                <p class="chef-rating">⭐ 4.8/5</p>
                <p class="chef-specialty">Spl:Maharashtrian</p>
            </div>
            <div class="view-hire-buttons">
                <ul class="view-hire-link">
                    <li><a href="#">View</a></li>
                    <li><a href="#">Hire</a></li>
                </ul>
            </div> 
        </div>
        <!-- Example Additional Profile -->
        <div class="chef-card">
            <img src="chef3.jpg" alt="Chef Profile Photo" class="chef-photo">
            <div class="chef-info">
                <h3 class="chef-name">Shetty Anna</h3>
                <p class="chef-rating">⭐ 4.7/5</p>
                <p class="chef-specialty">Spl:South Indian</p>
            </div>
            <div class="view-hire-buttons">
                <ul class="view-hire-link">
                    <li><a href="#">View</a></li>
                    <li><a href="#">Hire</a></li>
                </ul>
            </div> 
        </div>
        <!-- adding one more chef -->
        <div class="chef-card">
            <img src="chef4.jpg" alt="Chef Profile Photo" class="chef-photo">
            <div class="chef-info">
                <h3 class="chef-name">Manav Gada</h3>
                <p class="chef-rating">⭐ 4.6/5</p>
                <p class="chef-specialty">Spl:Gujarati</p>
            </div>
            <div class="view-hire-buttons">
                <ul class="view-hire-link">
                    <li><a href="#">View</a></li>
                    <li><a href="#">Hire</a></li>
                </ul>
            </div> 
        </div>
        <!-- adding one more chef -->
        <div class="chef-card">
            <img src="chef5.jpg" alt="Chef Profile Photo" class="chef-photo">
            <div class="chef-info">
                <h3 class="chef-name">Gobind Singh</h3>
                <p class="chef-rating">⭐ 4.5/5</p>
                <p class="chef-specialty">Spl:Punjabi</p>
            </div>
            <div class="view-hire-buttons">
                <ul class="view-hire-link">
                    <li><a href="#">View</a></li>
                    <li><a href="#">Hire</a></li>
                </ul>
            </div> 
        </div>
        
    </div>
</div>
<!-- creating chef card for cusine wise chef-->
<div class="top-rated-chefs">
    <h2 class="top-rated-chef-head">Top Rated Chefs In <?php echo htmlspecialchars($foodpref); ?></h2>
    <div class="chef-profiles">
        <!-- Chef Profile -->
        <div class="chef-card">
            <img src="chef1.jpg" alt="Chef Profile Photo" class="chef-photo">
            <div class="chef-info">
                <h3 class="chef-name">Tulsi Ram</h3>
                <p class="chef-rating">⭐ 4.9/5</p>
            </div>
            <div class="view-hire-buttons">
                <ul class="view-hire-link">
                    <li><a href="#">View</a></li>
                    <li><a href="#">Hire</a></li>
                </ul>
            </div> 
        </div>
        <!-- Add more chef profiles as needed -->
        <div class="chef-card">
            <img src="chef2.jpg" alt="Chef Profile Photo" class="chef-photo">
            <div class="chef-info">
                <h3 class="chef-name">Ganga Ram</h3>
                <p class="chef-rating">⭐ 4.8/5</p>
            </div>
            <div class="view-hire-buttons">
                <ul class="view-hire-link">
                    <li><a href="#">View</a></li>
                    <li><a href="#">Hire</a></li>
                </ul>
            </div> 
        </div>
        <!-- Example Additional Profile -->
        <div class="chef-card">
            <img src="chef3.jpg" alt="Chef Profile Photo" class="chef-photo">
            <div class="chef-info">
                <h3 class="chef-name">Pushkar Jaat</h3>
                <p class="chef-rating">⭐ 4.7/5</p>
            </div>
            <div class="view-hire-buttons">
                <ul class="view-hire-link">
                    <li><a href="#">View</a></li>
                    <li><a href="#">Hire</a></li>
                </ul>
            </div> 
        </div>
        <!-- adding one more chef -->
        <div class="chef-card">
            <img src="chef4.jpg" alt="Chef Profile Photo" class="chef-photo">
            <div class="chef-info">
                <h3 class="chef-name">Manilal Bhati</h3>
                <p class="chef-rating">⭐ 4.5/5</p>
            </div>
            <div class="view-hire-buttons">
                <ul class="view-hire-link">
                    <li><a href="#">View</a></li>
                    <li><a href="#">Hire</a></li>
                </ul>
            </div> 
        </div>
        <!-- adding one more chef -->
        <div class="chef-card">
            <img src="chef5.jpg" alt="Chef Profile Photo" class="chef-photo">
            <div class="chef-info">
                <h3 class="chef-name">Sohan Ved</h3>
                <p class="chef-rating">⭐ 4.4/5</p>
            </div>
            <div class="view-hire-buttons">
                <ul class="view-hire-link">
                    <li><a href="#">View</a></li>
                    <li><a href="#">Hire</a></li>
                </ul>
            </div> 
        </div>
        
    </div>
</div>
</div>   
</div>   
</body>
</html>