<?php
include 'config.php'; // Include the database connection file
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
    $foodpref = trim($_POST['foodpref']);
    $city = trim($_POST['city']);

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

      // Validate food preference
    if (empty($city)) {
        $errors['city'] = 'Please enter where you live !!';
        $isValid = false;
    }
    // Validate food preference
    if (empty($foodpref)) {
        $errors['foodpref'] = 'Food Preference is required';
        $isValid = false;
    }

    // Check validation status
    if ($isValid) {
        // Check if the email or mobile number already exists in the database
        $check_sql = "SELECT * FROM users WHERE email = '$email' OR mobile = '$mobile'";
        $check_stmt = mysqli_query($conn, $check_sql);

        if (mysqli_num_rows($check_stmt) > 0) {
            // Email or mobile already exists
            $existing_data = mysqli_fetch_assoc($check_stmt);
            if ($existing_data['email'] === $email) {
                $errors['email'] = 'Email already exists';
            }
            if ($existing_data['mobile'] === $mobile) {
                $errors['mobile'] = 'Mobile number already exists';
            }
        } else {
            // If no duplicate, insert the new data
            $sql = "INSERT INTO users (name, email, mobile, password, foodpref, city) 
                    VALUES ('$name', '$email', '$mobile', '$password', '$foodpref', '$city')";
            $stmt = mysqli_query($conn, $sql);

            if ($stmt) {
                header('location:login.php');
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
    <link rel="stylesheet" href="style.css">
</head>

<body class="reglog">
    <div class="container">
        <form id="registerform" enctype="multipart/form-data" action="registration.php" method="POST">
            <div class="registerform">
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

              <!--city selection -->
              <div class="inputbox">
                    <select id="city" name="city" required>
                        <option value="">Lives In</option>
                        <option value="mulund">Mulund</option>
                        <option value="bhandup">Bhandup</option>
                        <option value="ghatkopar">Ghatkopar</option>
                    </select>
                    <span class="error"><?php echo $errors['city'] ?? ''; ?></span>
                </div>

             <!-- Food Preference -->
             <div class="inputbox">
              <select id="foodpref" name="foodpref" required>
                <option value="">Select Food Preference</option>
                <option value="rajasthani">Rajasthani</option>
                <option value="gujarati">Gujarati</option>
                <option value="punjabi">Punjabi</option>
              </select>
              <span class="error"><?php echo $errors['foodpref'] ?? ''; ?></span>
             </div>


                <!-- Login Link -->
                <div class="register-loginlink">
                    <p>Already have an account? <a href="login.php">Login</a></p>
                </div>

                <!-- Submit Button -->
                <button id="registerLoginBtn" type="submit" name="sbmt">Register</button>
            </div>
        </form>
    </div>
</body>
</html>
