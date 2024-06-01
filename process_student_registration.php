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

// Get data from the form
$email = $_POST['email'];
$regno = $_POST['regno'];
$password = $_POST['password'];

// Hash the password for security
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Check if the email already exists
$checkEmailQuery = "SELECT * FROM students WHERE email = '$email'";
$checkEmailResult = $conn->query($checkEmailQuery);

if ($checkEmailResult->num_rows > 0) {
    echo "Error: Email already exists. Please use a different email.";
} else {
    // Insert data into the database
    $insertQuery = "INSERT INTO students (email, regno, password) VALUES ('$email', '$regno', '$hashedPassword')";

    if ($conn->query($insertQuery) === TRUE) {
        echo "Student registered successfully";
        header("Location: logiin.html");
    } else {
        echo "Error: " . $insertQuery . "<br>" . $conn->error;
    }
}


// Close the database connection
$conn->close();
?>
