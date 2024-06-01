<?php
// Connect to the database
$dbHost = "localhost";
$dbUser = "root";
$dbPass = "";
$dbName = "project2";

$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get data from the login form
$email = $_POST['email'];
$password = $_POST['password'];

// Check if the email exists in the database
$checkEmailQuery = "SELECT * FROM students WHERE email = '$email'";
$checkEmailResult = $conn->query($checkEmailQuery);

if ($checkEmailResult->num_rows > 0) {
    // Email exists, now check the password
    $user = $checkEmailResult->fetch_assoc();
    if (password_verify($password, $user['password'])) {
        // Password is correct

        // Set session variable for the logged-in user's email
        session_start();
        $_SESSION['email'] = $email; // Use 'email' for the session variable

        // If "Remember Me" is checked, set a cookie (you might want to implement a more secure way)
        if (isset($_POST['rememberMe']) && $_POST['rememberMe'] == 'on') {
            setcookie('email', $email, time() + (86400 * 30), "/");
        }

        // Redirect to the user's dashboard or another page
        header("Location: std.php");
        exit();
    } else {
        // Password is incorrect
        echo "Incorrect password. Please try again.";
    }
} else {
    // Email does not exist
    echo "Email not found. Please register first.";
}

// Close the database connection
$conn->close();
?>
