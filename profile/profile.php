<?php
include '../db/config.php';
session_start();

// Get user ID from the session
$user_id = $_SESSION['userdata']['user_id'];

// Fetch user data from the database using user ID
$sql = "SELECT name, mobile, email, city, foodpref, user_pic, user_gender FROM users WHERE user_id = $user_id";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die($conn->error);
}

if (mysqli_num_rows($result) > 0) {
    $user_data = mysqli_fetch_assoc($result);
} else {
    echo "User data not found";
}

// Handle profile update form submission
if (isset($_POST['update_button'])) {
    // Get updated details from the form
    $updated_name = $_POST['name'];
    $updated_city = $_POST['city'];
    $updated_foodpref = $_POST['foodpref'];
    $updated_gender = $_POST['gender'];
    $profile_pic = $_POST['profile-pic']; // Default to existing profile pic

    // Handle file upload
    if (isset($_FILES['profile-pic']) && $_FILES['profile-pic']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = '../assets/uploads/user_pic/';
        $file_name = basename($_FILES['profile-pic']['name']);
        $target_file = $upload_dir . $file_name;
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

        // Check file size (e.g., max 2MB)
        if ($_FILES['profile-pic']['size'] > 2 * 1024 * 1024) {
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
        if (move_uploaded_file($_FILES['profile-pic']['tmp_name'], $target_file)) {
            $profile_pic = $file_name;
            echo "The file " . htmlspecialchars($file_name) . " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        echo 'error';
    }
    // Update query
    $update_sql = "UPDATE users SET name = '$updated_name', city = '$updated_city', foodpref = '$updated_foodpref', user_pic = '$profile_pic', user_gender = '$updated_gender' WHERE user_id = '$user_id'";
    if (mysqli_query($conn, $update_sql)) {
        echo "<script>alert('Profile updated successfully!');</script>";
        // Refresh the page to reflect updated data
        header("Refresh:0");
        exit;
    } else {
        echo "<script>alert('Failed to update profile. Please try again.');</script>";
    }
}

// Close the connection
mysqli_close($conn);
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
    <nav class="navbar">
        <div class="comname"><img src="../assets/web_images/logo.webp" alt="cheflogo">ChefConnect</div>
        <ul class="nav-link">
            <li><a href="../home/home.php">Home</a></li>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="../bookings/mybooking.php">My Bookings</a></li>
            <li><a href="../contact/contact.php">Contact Us</a></li>
            <li><a href="#">About Us</a></li>
            <li><a href="../logReg/logout.php">Logout</a></li>
        </ul>
        <div class="hamburger">
            <!--icon for mobile menu-->
        </div>
    </nav>

    <div class="profile-container">
        <h2 id="prof-head">My Profile</h2>

        <form method="post" enctype="multipart/form-data">
            <div class="profile-pic-container">
                <label for="profile-pic" class="profile-pic-label">
                    <div id="profile-pic-preview">
                        <img id="img-preview"
                            src="<?php if ($user_data['user_pic'] != '') {
                                echo '../assets/uploads/user_pic/' . htmlspecialchars($user_data['user_pic']);
                            } else {
                                echo '../assets/web_images/default-profile-pic.jpeg';
                            } ?>"
                            alt="Profile Picture" />
                    </div>
                </label>
                <input type="file" id="profile-pic" name="profile-pic" accept="image/*" style="display: none;" />
            </div>
            <div class="profile-form">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user_data['name']); ?>"
                        required>
                </div>
                <div class="form-group">
                    <label for="mobile">Mobile</label>
                    <input type="tel" id="mobile" name="mobile"
                        value="<?php echo htmlspecialchars($user_data['mobile']); ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email"
                        value="<?php echo htmlspecialchars($user_data['email']); ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="city">City</label>
                    <select id="city" name="city" required>
                        <option value="" disabled>Select your city</option>
                        <option value="Mulund" <?php if ($user_data['city'] == 'Mulund')
                            echo 'selected'; ?>>Mulund
                        </option>
                        <option value="Bhandup" <?php if ($user_data['city'] == 'Bhandup')
                            echo 'selected'; ?>>Bhandup
                        </option>
                        <option value="Ghatkopar" <?php if ($user_data['city'] == 'Ghatkopar')
                            echo 'selected'; ?>>
                            Ghatkopar</option>
                        <!-- Add more cities as needed -->
                    </select>
                </div>
                <div class="form-group">
                    <label for="foodpref">Food Preference</label>
                    <select id="foodpref" name="foodpref" required>
                        <option value="" disabled>Select your food preference</option>
                        <option value="Rajasthani" <?php if ($user_data['foodpref'] == 'Rajasthani')
                            echo 'selected'; ?>>
                            Rajasthani</option>
                        <option value="Gujarati" <?php if ($user_data['foodpref'] == 'Gujarati')
                            echo 'selected'; ?>>
                            Gujarati</option>
                        <option value="Punjabi" <?php if ($user_data['foodpref'] == 'Punjabi')
                            echo 'selected'; ?>>Punjabi
                        </option>
                        <option value="Maharashtrian" <?php if ($user_data['foodpref'] == 'Maharashtrian')
                            echo 'selected'; ?>>Maharashtrian</option>
                        <!-- Add more food preferences as needed -->
                    </select>
                </div>
                <div class="form-group">
                    <label for="user_gender">Gender</label>
                    <select id="user_gender" name="gender" required>
                        <option value="" disabled>Select your gender</option>
                        <option value="Male" <?php if ($user_data['user_gender'] == 'Male')
                            echo 'selected'; ?>>Male
                        </option>
                        <option value="Female" <?php if ($user_data['user_gender'] == 'Female')
                            echo 'selected'; ?>>Female
                        </option>
                        <option value="Other" <?php if ($user_data['user_gender'] == 'Other')
                            echo 'selected'; ?>>Other
                        </option>
                    </select>
                </div>
                <button id="profile-update-button" type="submit" class="btn" name="update_button">Update
                    Profile</button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('profile-pic').addEventListener('change', function (event) {
            const [file] = event.target.files;
            if (file) {
                document.getElementById('img-preview').src = URL.createObjectURL(file);
            }
        });
    </script>
</body>

</html>