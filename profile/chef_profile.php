<?php
include '../db/config.php';
session_start();

// Get user ID from the session
$chef_id = $_SESSION['chefdata']['chef_id'];

// Fetch chef data from the database using user ID
$sql = "SELECT chef_name, bio, chef_mobile, chef_email, city, speciality, gender, experience, charges, chef_pic  FROM chefs WHERE chef_id = $chef_id";
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
    $profile_pic = $_POST['chef-profile-pic']; // Default to existing profile pic

    // Handle file upload
 if (isset($_FILES['chef-profile-pic']) && $_FILES['chef-profile-pic']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = '../assets/uploads/chef_pic/';
        $file_name = basename($_FILES['chef-profile-pic']['name']);
        $target_file = $upload_dir . $file_name;
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

        // Check file size (e.g., max 2MB)
        if ($_FILES['chef-profile-pic']['size'] > 2 * 1024 * 1024) {
            echo "Sorry, your file is too large.";
            exit;
        }

        // Allow only certain file formats
        if (!in_array($file_type, $allowed_types)) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            exit;
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            exit;
        }

        // Move uploaded file
        if (move_uploaded_file($_FILES['chef-profile-pic']['tmp_name'], $target_file)) {
            $profile_pic = $file_name;
            echo "The file " . htmlspecialchars($file_name) . " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        echo 'error';
    }
    // Update query
    $update_sql = "UPDATE chefs SET chef_name = '$updated_name', bio = '$updated_bio', city = '$updated_city', speciality = '$updated_speciality', experience = '$updated_experience', charges = '$updated_charges', chef_pic = '$profile_pic' WHERE chef_id = '$chef_id'";
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
    <link rel="stylesheet" href="../style.css">
</head>

<body>
<nav class="navbar chef-nav">
        <div class="comname"><img src="../assets/web_images/logo.webp" alt="cheflogo">ChefConnect</div>
        <ul class="nav-link">
            <li><a href="../home/chef_home.php">Home</a></li>
            <li><a href="chef_profile.php">Profile</a></li>
            <li><a href="#">Orders</a></li>
            <li><a href="#">Contact Us</a></li>
            <li><a href="#">About Us</a></li>
            <li><a href="../logReg/chef_logout.php">Logout</a></li>
        </ul>

        <div class="hamburger">
            <!--icon for mobile menu-->
        </div>
    </nav>
    <div class="chef-profile-container">
        <h2 id="chef-prof-head">My Profile</h2>
        <form method="post" enctype="multipart/form-data">
        <div class="chef-profile-pic-container">
        <label for="chef-profile-pic" class="chef-profile-pic-label">
            <div id="chef-profile-pic-preview">
            <img id="chef-img-preview" src="<?php if ($chef_data['chef_pic'] != '') {
                                echo '../assets/uploads/chef_pic/' . htmlspecialchars($chef_data['chef_pic']);
                            } else {
                                echo '../assets/web_images/default-profile-pic.jpeg';
                            } ?>"
                            alt="Profile Picture" />
            </div>
            </label>
            <input type="file" id="chef-profile-pic" name="chef-profile-pic" accept="image/*" style="display: none;" />
        </div>
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
                        <select id="experience" name="experience">
                            <option value="" disabled>Select your Experience</option>
                            <option value="1 Year+" <?php if ($chef_data['experience'] == '1 Year+') echo 'selected'; ?>>1 Year+</option>
                            <option value="2 Year+" <?php if ($chef_data['experience'] == '2 Year+') echo 'selected'; ?>>2 Year+</option>
                            <option value="3 Year+" <?php if ($chef_data['experience'] == '3 Year+') echo 'selected'; ?>>3 Year+</option>
                            <option value="5 Year+" <?php if ($chef_data['experience'] == '5 Year+') echo 'selected'; ?>>5 Year+</option>
                            <option value="10 Year+" <?php if ($chef_data['experience'] == '10 Year+') echo 'selected'; ?>>10 Year+</option>
                            <option value="15 Year+" <?php if ($chef_data['experience'] == '15 Year+') echo 'selected'; ?>>15 Year+</option>
                        </select>
                    </div>
                    <div class="chef-profile-item">
                        <label for="charges">Charges Per Hour</label>
                        <input type="number" id="charges" name="charges" value="<?php echo htmlspecialchars($chef_data['charges']); ?>">
                    </div>
                </div>
                <button id="chef-profile-update-button" type="submit" name="update_button">Update Profile</button>
            </div>
        </form>
    </div>
    <script>
        document.getElementById('chef-profile-pic').addEventListener('change', function (event) {
            const [file] = event.target.files;
            if (file) {
                document.getElementById('chef-img-preview').src = URL.createObjectURL(file);
            }
        });
    </script>
</body>
</html>
