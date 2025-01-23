<?php
include '../db/config.php'; // Include the database connection file
session_start();

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check user authentication
if (!isset($_SESSION['userdata'])) {
    header('location:login.php');
    exit();
}

$userdata = $_SESSION['userdata'];
$foodpref = $userdata['foodpref'];
$location = $userdata['city'];

// Fetch top 5 chefs sorted by rating
$sql = "SELECT * FROM chefs ORDER BY chef_ratings DESC LIMIT 5";
$chefs = $conn->query($sql)->fetch_all(MYSQLI_ASSOC) ?: [];

// Fetch top 5 chefs by food preference
$stmt = $conn->prepare("SELECT * FROM chefs WHERE speciality = ? ORDER BY chef_ratings DESC LIMIT 5");
$stmt->bind_param("s", $foodpref);
$stmt->execute();
$category_chefs = $stmt->get_result()->fetch_all(MYSQLI_ASSOC) ?: [];
$stmt->close();

// Fetch top 5 chefs by location
$stmt = $conn->prepare("SELECT * FROM chefs WHERE city = ? ORDER BY chef_ratings DESC LIMIT 5");
$stmt->bind_param("s", $location);
$stmt->execute();
$location_chefs = $stmt->get_result()->fetch_all(MYSQLI_ASSOC) ?: [];
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search-input'])) {
    $search_input = "%" . htmlspecialchars($_POST['search-input']) . "%";

    $stmt = $conn->prepare("SELECT chef_name, chef_pic, city, speciality FROM chefs 
                            WHERE chef_name LIKE ? OR city LIKE ? OR speciality LIKE ? 
                            ORDER BY chef_ratings DESC LIMIT 10");
    $stmt->bind_param("sss", $search_input, $search_input, $search_input);
    $stmt->execute();
    $chefs = $stmt->get_result()->fetch_all(MYSQLI_ASSOC) ?: [];
    $stmt->close();

    // Return JSON response for AJAX
    header('Content-Type: application/json');
    echo json_encode(['results' => $chefs]);
    exit();
}

// // Handle search request via POST
// if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search-input'])) {
//     $search_input = "%" . htmlspecialchars($_POST['search-input']) . "%";

//     $stmt = $conn->prepare("SELECT chef_name, chef_pic, city, speciality FROM chefs 
//                             WHERE chef_name LIKE ? OR city LIKE ? OR speciality LIKE ?");
//     $stmt->bind_param("sss", $search_input, $search_input, $search_input);
//     $stmt->execute();
//     $chefs = $stmt->get_result()->fetch_all(MYSQLI_ASSOC) ?: [];
//     $stmt->close();

//     echo json_encode(['results' => $chefs]);
//     exit();
// }

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>home page</title>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
</head>

<body>
    <nav class="navbar">
        <div class="comname"><img src="../assets/web_images/logo.webp" alt="cheflogo">ChefConnect</div>
        <ul class="nav-link">
            <li><a href="home.php">Home</a></li>
            <li><a href="../profile/profile.php">Profile</a></li>
            <li><a href="../bookings/mybooking.php">My Bookings</a></li>
            <li><a href="../contact/contact.php">Contact Us</a></li>
            <li><a href="#">About Us</a></li>
            <li><a href="../logReg/logout.php">Logout</a></li>
        </ul>

        <div class="hamburger">
            <!--icon for mobile meneu-->
        </div>
    </nav>
    <div class="home-container">
        <!-- Search Bar -->
        <div class="search-container">
            <form id="searchbar" method="POST" enctype="multipart/form-data">
                <input type="text" placeholder="Search Chef by Name, City, or Food Cuisine" name="search-input"
                    id="search-input" oninput="showSuggestions()">
                <button type="submit"><img src="../assets/web_images/search.jpeg" alt="Search"></button>
            </form>
        </div>

        <!-- Floating Card Container -->
        <div id="floating-card" class="floating-card">
            <div id="close-card" class="close-card" onclick="closeFloatingCard()">&#10006;</div>
            <div id="results-container" class="results-container">
                <!-- Suggestions will be dynamically added here -->
            </div>
        </div>


        <!-- creating chef card -->
        <div class="top-rated-chefs">
            <h2 class="top-rated-chef-head">Top Rated Chefs</h2>
            <div class="chef-profiles">
                <?php foreach ($chefs as $chef): ?>
                    <!-- Chef Profile -->
                    <div class="chef-card">
                        <img src="<?php if ($chef['chef_pic'] != '') {
                            echo '../assets/uploads/chef_pic/' . htmlspecialchars($chef['chef_pic']);
                        } else {
                            echo '../assets/web_images/default-profile-pic.jpeg';
                        } ?>" alt="Chef Profile Photo" class="chef-photo">
                        <div class="chef-info">
                            <h3 class="chef-name"><?php echo htmlspecialchars($chef['chef_name']); ?></h3>
                            <p class="chef-rating">⭐<?php echo htmlspecialchars($chef['chef_ratings']); ?>/5</p>
                            <p class="chef-specialty">Spl:<?php echo htmlspecialchars($chef['speciality']); ?></p>
                        </div>
                        <div class="view-hire-buttons">
                            <ul class="view-hire-link">
                                <li><a href="#">View</a></li>
                                <li><a href="#">Hire</a></li>
                            </ul>
                        </div>
                    </div>
                <?php endforeach; ?>
                <!-- removing other 4 template -->
            </div>
        </div>
        <!-- creating chef card for cusine wise chef-->
        <div class="top-rated-chefs">
            <h2 class="top-rated-chef-head">Top Rated Chefs In <?php echo htmlspecialchars($foodpref); ?></h2>
            <div class="chef-profiles">
                <?php foreach ($category_chefs as $cat_chef): ?>
                    <!-- Chef Profile -->
                    <div class="chef-card">
                        <img src="<?php if ($cat_chef['chef_pic'] != '') {
                            echo '../assets/uploads/chef_pic/' . htmlspecialchars($cat_chef['chef_pic']);
                        } else {
                            echo '../assets/web_images/default-profile-pic.jpeg';
                        } ?>" alt="Chef Profile Photo" class="chef-photo">
                        <div class="chef-info">
                            <h3 class="chef-name"><?php echo htmlspecialchars($cat_chef['chef_name']); ?></h3>
                            <p class="chef-rating">⭐<?php echo htmlspecialchars($cat_chef['chef_ratings']); ?>/5</p>
                        </div>
                        <div class="view-hire-buttons">
                            <ul class="view-hire-link">
                                <li><a href="#">View</a></li>
                                <li><a href="#">Hire</a></li>
                            </ul>
                        </div>
                    </div>
                <?php endforeach; ?>
                <!-- removing other 4 cards -->

            </div>
        </div>
        <!-- creating top rated chefs in location -->
        <div class="top-rated-chefs">
            <h2 class="top-rated-chef-head">Top Rated Chefs In <?php echo htmlspecialchars($location); ?></h2>
            <div class="chef-profiles">
                <?php foreach ($location_chefs as $loc_chef): ?>
                    <!-- Chef Profile -->
                    <div class="chef-card">
                        <img src="<?php if ($loc_chef['chef_pic'] != '') {
                            echo '../assets/uploads/chef_pic/' . htmlspecialchars($loc_chef['chef_pic']);
                        } else {
                            echo '../assets/web_images/default-profile-pic.jpeg';
                        } ?>" alt="Chef Profile Photo" class="chef-photo">
                        <div class="chef-info">
                            <h3 class="chef-name"><?php echo htmlspecialchars($loc_chef['chef_name']); ?></h3>
                            <p class="chef-rating">⭐<?php echo htmlspecialchars($loc_chef['chef_ratings']); ?>/5</p>
                        </div>
                        <div class="view-hire-buttons">
                            <ul class="view-hire-link">
                                <li><a href="#">View</a></li>
                                <li><a href="#">Hire</a></li>
                            </ul>
                        </div>
                    </div>
                <?php endforeach; ?>
                <!-- removing other 4 cards -->

            </div>
        </div>
    </div>
    </div>
    <!-- Scripts -->
    <script src="home.js"></script>
</body>

</html>