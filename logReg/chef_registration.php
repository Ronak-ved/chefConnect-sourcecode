<?php
include '../db/config.php'; // Include the database connection file
session_start();

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if (isset($_POST['sbmt'])) { // Check if the form has been submitted
    // Collect data from the form
    $name = trim($_POST['name']);
    $mobile = trim($_POST['mobile']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $gender = trim($_POST['gender']);
    $city = trim($_POST['city']);
    $charges = trim($_POST['charges']);
    $splfood = trim($_POST['splfood']);

    // Validation flags and errors array
    $errors = [];
    $isValid = true;

    // Validate name
    if (empty($name)) {
        $errors['name'] = 'Username is required';
        $isValid = false;
    }

    // Validate mobile
    $mobilePattern = '/^(\+91)?[6-9]\d{9}$/';
    if (empty($mobile)) {
        $errors['mobile'] = 'Mobile number is required';
        $isValid = false;
    } elseif (!preg_match($mobilePattern, $mobile)) {
        $errors['mobile'] = 'Please provide a valid mobile number';
        $isValid = false;
    }

    // Validate email
    if (empty($email)) {
        $errors['email'] = 'Email is required';
        $isValid = false;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Provide a valid email address';
        $isValid = false;
    }

    // Validate password
    if (empty($password)) {
        $errors['password'] = 'Password is required';
        $isValid = false;
    } elseif (strlen($password) < 8) {
        $errors['password'] = 'Password must be at least 8 characters';
        $isValid = false;
    }

    // Validate gender
    if (empty($gender)) {
        $errors['gender'] = 'Gender is required';
        $isValid = false;
    }

    // Validate city
    if (empty($city)) {
        $errors['city'] = 'Locality is required';
        $isValid = false;
    }

    // Validate charges
    if (empty($charges)) {
        $errors['charges'] = 'Charges are required';
        $isValid = false;
    }

    // Validate speciality
    if (empty($splfood)) {
        $errors['splfood'] = 'Speciality is required';
        $isValid = false;
    }

    // Check validation status
    if ($isValid) {
        // Check if the email or mobile number already exists in the database
        $check_sql = "SELECT * FROM chefs WHERE chef_email = '$email' OR chef_mobile = '$mobile'";
        $check_stmt = mysqli_query($conn, $check_sql);

        if (mysqli_num_rows($check_stmt) > 0) {
            // Email or mobile already exists
            $existing_data = mysqli_fetch_assoc($check_stmt);
            if ($existing_data['chef_email'] === $email) {
                $errors['email'] = 'Email already exists';
            }
            if ($existing_data['chef_mobile'] === $mobile) {
                $errors['mobile'] = 'Mobile number already exists';
            }
        } else {
            // If no duplicate, insert the new data
            $sql = "INSERT INTO chefs (chef_name, chef_email, chef_mobile, chef_password, gender, city, charges, speciality) 
                    VALUES ('$name', '$email', '$mobile', '$password', '$gender', '$city', '$charges', '$splfood')";
            $stmt = mysqli_query($conn, $sql);

            if ($stmt) {
                echo "<script>alert('Registration Successful!');</script>";
                header('location:chef_login.php');
                exit;
            } else {
                echo "Error: " . mysqli_error($conn); // Display database error if query fails
            }
        }
    }
}
$conn->close(); // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body class="reglog">
    <div class="container">
        <form id="registerform" enctype="multipart/form-data" action="chef_registration.php" method="POST">
            <div class="chef-registerform">
                <h1>Registration</h1>

                <!-- Name -->
                <div class="inputbox">
                    <input id="name" name="name" type="text" placeholder="Enter Your Name" required>
                    <span class="error"><?php echo $errors['name'] ?? ''; ?></span>
                </div>

                <!-- Mobile -->
                <div class="inputbox">
                    <input id="mobile" name="mobile" type="text" placeholder="Enter Your Mobile" required>
                    <span class="error"><?php echo $errors['mobile'] ?? ''; ?></span>
                </div>

                <!-- Email -->
                <div class="inputbox">
                    <input id="email" name="email" type="email" placeholder="Enter Your Email" required>
                  <span class="error"><?php echo $errors['email'] ?? ''; ?></span>
                </div>

                <!-- Password -->
                <div class="inputbox">
                    <input id="password" name="password" type="password" placeholder="Enter Password" required>
                    <span class="error"><?php echo $errors['password'] ?? ''; ?></span>
                </div>

                <!-- Gender -->
                <div class="inputbox">
                    <select id="gender" name="gender" required>
                        <option value="">Select Your Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>

                    </select>
                    <span class="error"><?php echo $errors['gender'] ?? ''; ?></span>
                </div>

                <!--city selection -->
                <div class="inputbox">
                    <select id="city" name="city" required>
                        <option value="">Lives In</option>
                        <option value="Mulund">Mulund</option>
                        <option value="Bhandup">Bhandup</option>
                        <option value="Ghatkopar">Ghatkopar</option>
                    </select>
                    <span class="error"><?php echo $errors['city'] ?? ''; ?></span>
                </div>

                <!-- charges per hour -->
                <div class="inputbox">
                    <input id="charges" name="charges" type="integer" placeholder="Enter Your Charges/hour" required>
                    <span class="error"><?php echo $errors['charges'] ?? ''; ?></span>
                </div>

                <!-- speciality in food -->
                <div class="inputbox">
                    <select id="splfood" name="splfood" required>
                        <option value="">Speciality In</option>
                        <option value="Rajasthani">Rajasthani</option>
                        <option value="Gujarati">Gujarati</option>
                        <option value="Punjabi">Punjabi</option>
                        <option value="Maharashtrian">Maharashtrian</option>
                    </select>
                    <span class="error"><?php echo $errors['splfoof'] ?? ''; ?></span>
                </div>

                <!-- Login Link -->
                <div class="register-loginlink">
                    <p>Already have an account? <a href="chef_login.php">Login</a></p>
                </div>

                <!-- Submit Button -->
                <button id="registerLoginBtn" type="submit" name="sbmt">Register</button>
            </div>
        </form>
    </div>
</body>
</html>
