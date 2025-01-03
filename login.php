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
    
    $email = $_POST['email'];
    $password = $_POST['password']; // Securely hash the password

    // fetch data from the database
    $sql = "SELECT * FROM users WHERE email = '$email' and password = '$password' " or die('query failed');
    $stmt = mysqli_query($conn, $sql);

    if(mysqli_num_rows($stmt)){
        $row = mysqli_fetch_assoc($stmt);
        $_SESSION['userdata'] = $row;
        header('location:home.php');
    }
    else{
        echo "<script>alert('Login failed');</script>";
    }
}

$conn->close(); // Close the database connection
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body class="reglog">
    <div class="container">
        <form action="" enctype="multipart/form-data" action="login.php" method="POST">
            <div class="loginform">
                <h1 id="loginheader">Login</h1>
                <div class="inputbox">
                    <input type="text" name="email" placeholder="Enter Email" required>
                </div>
                <div class="inputbox">
                    <input type="password" name="password" placeholder="Enter Password" required>
                </div>
                <div class="register-loginlink">
                   <p> Don't have a account?<a href="registration.php">Register</a>
                </div>
                <button type="submit" id="registerLoginBtn" name="sbmt">Login</button>
            </div>

        </form>


    </div>
</body>

</html> 
