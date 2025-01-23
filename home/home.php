<?php
include '../db/config.php'; // Include the database connection file
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
$location = $userdata['city'];

// Fetch chefs sorted by rating 
$sql = "SELECT * FROM chefs ORDER BY chef_ratings DESC LIMIT 5";
$result = $conn->query($sql); 
$chefs = array(); 
if ($result->num_rows > 0) {
     while($row = $result->fetch_assoc()) {
     $chefs[] = $row; }
 }

//fetching the top 5 rated from chefs from user food preference
 $sql = "SELECT * FROM chefs WHERE speciality = '$foodpref' ORDER BY chef_ratings DESC LIMIT 5";
 $result = $conn->query($sql); 
 $category_chefs = array(); 
 if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $category_chefs[] = $row; 
    }
  }
 //fetching the top 5 rated chefs from user location
 $sql = "SELECT * FROM chefs WHERE city = '$location' ORDER BY chef_ratings DESC LIMIT 5";
 $result = $conn->query($sql); 
 $location_chefs = array(); 
 if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $location_chefs[] = $row; 
    }
  }
// fetching the search input 


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_data = json_decode(file_get_contents('php://input'), true);
    $search_input = htmlspecialchars($input_data['query']);
    $search_input = "%$search_input%"; // For partial matching


    $sql = "SELECT * FROM chefs WHERE chef_name LIKE ? OR city LIKE ? OR speciality LIKE ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $search_input, $search_input, $search_input);
    $stmt->execute();
    $result = $stmt->get_result();

    $chefs = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $chefs[] = array(
                "chef_name" => $row['chef_name'],
                "chef_pic" => $row['chef_pic'],
                "city" => $row['city'],
                "speciality" => $row['speciality']
            );
        }
    }

    echo json_encode(array("results" => $chefs));
    
    $stmt->close();
}
$conn->close(); // Close the database connection
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
        <!-- creating search bar -->
    <div class="search-container">
    <form id="searchbar" method="POST" action="/search">
        <input type="text" placeholder="Search Chef by Name, City, or Food Cuisine" name="search-input" id="search-input">
        <button type="submit"><img src="../assets/web_images/search.jpeg" alt="Search"></button>
    </form>
</div>

<!-- Floating card container -->
<div id="floating-card" class="floating-card">
    <div id="close-card" class="close-card">X</div>
    <div id="results-container" class="results-container"></div>
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
<script>
document.getElementById('searchbar').addEventListener('submit', function(e) {
    e.preventDefault(); // Prevent the default form submission
    var query = document.getElementById('search-input').value;

    // Fetch results from the PHP script
    fetch('/search', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ query: query })
    })
    .then(response => response.json())
    .then(data => {
        // Display results in the floating card
        var resultsContainer = document.getElementById('results-container');
        resultsContainer.innerHTML = '';
        data.results.forEach(result => {
            resultsContainer.innerHTML += `<div class="chef-cards">
                                               <img src="../assets/uploads/chef_pic/${result.chef_pic}" alt="${result.chef_name}">
                                               <h3>${result.chef_name}</h3>
                                               <p>${result.city} - ${result.speciality}</p>
                                           </div>`;
        });

        // Show the floating card
        document.getElementById('floating-card').style.display = 'block';
    })
    .catch(error => console.error('Error:', error));
});

// Close button functionality
document.getElementById('close-card').addEventListener('click', function() {
    document.getElementById('floating-card').style.display = 'none';
});
</script>

</body>
</html>