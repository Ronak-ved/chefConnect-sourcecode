<?php
include 'config.php';
session_start();

// Get user ID from the session
$chef_id = $_SESSION['chefdata']['chef_id'];

// Fetch chef data from the database using user ID
$sql = "SELECT chef_name, bio, chef_mobile, chef_email, city, speciality, gender, experience, charges  FROM chefs WHERE chef_id = $chef_id";
$stmt = mysqli_query($conn, $sql);

if (!$stmt) {
    die($conn->error);
}

if (mysqli_num_rows($stmt) > 0) {
    $chef_data = mysqli_fetch_assoc($stmt);
} else {
    echo "Chef data not found";
}

// Handle profile update form submission
if (isset($_POST['update_button'])) {
    // Get updated details from the form
    $updated_name = $_POST['name'];
    $updated_bio = $_POST['bio'];
    $updated_city = $_POST['city'];
    $updated_speciality = $_POST['speciality'];
    $updated_experience = $_POST['experience'];
    $updated_charges = $_POST['charges'];

    // Update query
    $update_sql = "UPDATE chefs SET chef_name = '$updated_name', bio = '$updated_bio', city = '$updated_city', speciality = '$updated_speciality', experience = '$updated_experience', charges = '$updated_charges' WHERE chef_id = '$chef_id'";
    $update_stmt = mysqli_query($conn, $update_sql);

    if ($update_stmt) {
        echo "<script>alert('Profile updated successfully!');</script>";
        // Optionally, refresh the page to reflect updated data
        header("Refresh:0");
        exit;
    } else {
        echo "<script>alert('Failed to update profile. Please try again.');</script>";
    }
    $update_stmt->close();
}

// Close the statement
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
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
            <li><a href="chef_logout.php">Logout</a></li>
        </ul>

        <div class="hamburger">
            <!--icon for mobile menu-->
        </div>
    </nav>
    <div class="chef-profile-container">
        <h2 id="chef-prof-head">My Profile</h2>
        <div class="photo-upload-center">
            <div class="profile-photo-wrapper">
                <img id="profile-photo-preview" src="default-profile.png" alt="Profile Photo">
                <input type="file" id="photo-upload" name="photo-upload" accept="image/*" onchange="previewPhoto()">
            </div>
        </div>
        <form method="post" enctype="multipart/form-data">
            <div class="chef-profile-form">
                <div class="chef-profile-row">
                    <div class="chef-profile-item">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" required value="<?php echo htmlspecialchars($chef_data['chef_name']); ?>">
                    </div>
                    <div class="chef-profile-item">
                        <label for="bio">Bio</label>
                        <textarea id="bio" name="bio" rows="3"><?php echo htmlspecialchars($chef_data['bio']); ?></textarea>
                    </div>
                    <div class="chef-profile-item">
                        <label for="mobile">Mobile</label>
                        <input type="tel" id="mobile" name="mobile" readonly value="<?php echo htmlspecialchars($chef_data['chef_mobile']); ?>">
                    </div>
                </div>
                <div class="chef-profile-row">
                    <div class="chef-profile-item">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" readonly value="<?php echo htmlspecialchars($chef_data['chef_email']); ?>">
                    </div>
                    <div class="chef-profile-item">
                        <label for="city">City</label>
                        <select id="city" name="city" required>
                            <option value="" disabled>Select your city</option>
                            <option value="Mulund" <?php if ($chef_data['city'] == 'Mulund') echo 'selected'; ?>>Mulund</option>
                            <option value="Bhandup" <?php if ($chef_data['city'] == 'Bhandup') echo 'selected'; ?>>Bhandup</option>
                            <option value="Ghatkopar" <?php if ($chef_data['city'] == 'Ghatkopar') echo 'selected'; ?>>Ghatkopar</option>
                            <!-- Add more cities as needed -->
                        </select>
                    </div>
                    <div class="chef-profile-item">
                        <label for="speciality">Specialized In</label>
                        <select id="speciality" name="speciality" required>
                            <option value="" disabled>Select your food preference</option>
                            <option value="Rajasthani" <?php if ($chef_data['speciality'] == 'Rajasthani') echo 'selected'; ?>>Rajasthani</option>
                            <option value="Gujarati" <?php if ($chef_data['speciality'] == 'Gujarati') echo 'selected'; ?>>Gujarati</option>
                            <option value="Punjabi" <?php if ($chef_data['speciality'] == 'Punjabi') echo 'selected'; ?>>Punjabi</option>
                            <option value="Maharashtrian" <?php if ($chef_data['speciality'] == 'Maharashtrian') echo 'selected'; ?>>Maharashtrian</option>
                            <!-- Add more food preferences as needed -->
                        </select>
                    </div>
                </div>
                <div class="chef-profile-row">
                    <div class="chef-profile-item">
                        <label for="chef_gender">Gender</label>
                        <select id="chef_gender" name="chef_gender" readonly>
                            <option value="" disabled>Select your gender</option>
                            <option value="Male" <?php if ($chef_data['gender'] == 'Male') echo 'selected'; ?>>Male</option>
                            <option value="Female" <?php if ($chef_data['gender'] == 'Female') echo 'selected'; ?>>Female</option>
                            <option value="Other" <?php if ($chef_data['gender'] == 'Other') echo 'selected'; ?>>Other</option>
                        </select>
                    </div>
                    <div class="chef-profile-item">
                        <label for="experience">Experience</label>
                        <input type="text" id="experience" name="experience" value="<?php echo htmlspecialchars($chef_data['experience']); ?>">
                    </div>
                    <div class="chef-profile-item">
                        <label for="charges">Charges</label>
                        <input type="number" id="charges" name="charges" value="<?php echo htmlspecialchars($chef_data['charges']); ?>">
                    </div>
                </div>
                <button id="chef-profile-update-button" type="submit" name="update_button">Update Profile</button>
            </div>
        </form>
    </div>
    <script>
        function previewPhoto() {
            const photoUpload = document.getElementById('photo-upload');
            const photoPreview = document.getElementById('profile-photo-preview');

            const file = photoUpload.files[0];
            const reader = new FileReader();

            reader.onloadend = function () {
                photoPreview.src = reader.result;
            }

            if (file) {
                reader.readAsDataURL(file);
            } else {
                photoPreview.src = 'default-profile.png';
            }
        }
    </script>
</body>
</html>
