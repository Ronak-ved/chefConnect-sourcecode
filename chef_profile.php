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



if (mysqli_num_rows($stmt)>0) {
    $chef_data = mysqli_fetch_assoc($stmt); 
} 
else {
    echo "Chef data data not found";
    }

    // Handle profile update form submission
    if (isset($_POST['update_button'])){
    // Get updated details from the form
    $updated_name = $_POST['name'];
    $updated_bio = $_POST['bio'];
    $updated_city = $_POST['city'];
    $updated_splfood = $_POST['splfood'];
    $updated_experience = $_POST['experience'];
    $updated_charges = $_POST['charges'];

    // Update query
    $update_sql = "UPDATE chefs SET chef_name = '$updated_name', bio = '$updated_bio', city = '$updated_city', speciality = '$updated_splfood', experience = '$updated_experience', charges = '$updated_charges' WHERE chef_id = '$chef_id'";
    $update_stmt = mysqli_query($conn, $update_sql);

    if ($update_stmt) {
        echo "<script>alert('Profile updated successfully!');</script>";
        // Optionally, refresh the page to reflect updated data
        header("Refresh:0");
        exit;
    }

    else {
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
    <div class="profile-container chef-profile">
        <h2 id="prof-head">My Profile</h2>
        <form method="post" enctype="multipart/form-data">
            <div class="profile-form">
                <!-- Name -->
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($chef_data['chef_name']); ?>" required>

                <!-- Bio -->
                <label for="bio">Bio</label>
                <textarea id="bio" name="bio" rows="3"><?php echo htmlspecialchars($chef_data['bio']); ?></textarea>

                <!-- Mobile -->
                <label for="mobile">Mobile</label>
                <input type="tel" id="mobile" name="mobile" value="<?php echo htmlspecialchars($chef_data['chef_mobile']); ?>" readonly>

                <!-- Email -->
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($chef_data['chef_email']); ?>" readonly>

                <!-- City -->
                <label for="city">City</label>
                <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($chef_data['city']); ?>" required>


                <!-- Food speciality -->
                <label for="splfood">Speciality IN</label>
                <input type="text" id="splfood" name="splfood" value="<?php echo htmlspecialchars($chef_data['speciality']); ?>" required>
                
                <!-- gender -->
                <label for="gender">Gender</label>
                <input type="text" id="gender" name="gender" value="<?php echo htmlspecialchars($chef_data['gender']); ?>" readonly>

                <!-- experience  -->
                <label for="experience">Experience</label>
                <input type="text" id="experience" name="experience" value="<?php echo htmlspecialchars($chef_data['experience']); ?>">
                
                <!-- charges -->
                <label for="charges">Charges</label>
                <input type="integer" id="charges" name="charges" value="<?php echo htmlspecialchars($chef_data['charges']); ?>">

                <!-- Update Button -->
                <button type="submit" class="btn" name="update_button">Update Profile</button>
            </div>
        </form>
    </div>
</body>

</html>
