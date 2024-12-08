<?php
include 'config.php';
session_start();


// Get user ID from the session
$user_id = $_SESSION['userdata']['user_id'];

// Fetch user data from the database using user ID
$sql = "SELECT name, mobile, email, city, foodpref FROM users WHERE user_id = $user_id";
$stmt = mysqli_query($conn, $sql);

if (!$stmt) {
    die($conn->error);
}



if (mysqli_num_rows($stmt)>0) {
    $user_data = mysqli_fetch_assoc($stmt); 
} 
else {
    echo "user data data not found";
    }

    // Handle profile update form submission
    if (isset($_POST['update_button'])){
    // Get updated details from the form
    $updated_name = $_POST['name'];
    $updated_city = $_POST['city'];
    $updated_foodpref = $_POST['foodpref'];

    // Update query
    $update_sql = "UPDATE users SET name = '$updated_name', city = '$updated_city', foodpref = '$updated_foodpref' WHERE user_id = '$user_id'";
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
    <div class="profile-container">
        <h2 id="prof-head">My Profile</h2>
        <form method="post" enctype="multipart/form-data">
            <div class="profile-form">
                <!-- Name -->
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user_data['name']); ?>" required>

                <!-- Mobile -->
                <label for="mobile">Mobile</label>
                <input type="tel" id="mobile" name="mobile" value="<?php echo htmlspecialchars($user_data['mobile']); ?>" readonly>

                <!-- Email -->
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user_data['email']); ?>" readonly>

                <!-- city selection -->
                <label for="city">City</label>
                <input id="city" name="city" value="<?php echo htmlspecialchars($user_data['city']);?>" required>

                <!-- Food Preference -->
                <label for="foodpref">Food Preference</label>
                <input type="text" id="foodpref" name="foodpref" value="<?php echo htmlspecialchars($user_data['foodpref']); ?>">

                <!-- Update Button -->
                <button type="submit" class="btn" name="update_button">Update Profile</button>
            </div>
        </form>
    </div>
</body>

</html>
