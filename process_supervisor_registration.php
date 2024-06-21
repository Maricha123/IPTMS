<?php
session_start();

// Ensure the user is logged in before accessing the page
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Connect to the database
$dbHost = "localhost";
$dbUser = "root";
$dbPass = "";
$dbName = "actual";

$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get data from the form
$username = $_POST['username'];
$email = $_POST['email'];
$workno = $_POST['workno'];
$password = $_POST['password'];

// Hash the password for security
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insert data into the database
$sql = "INSERT INTO supervisors (username, email, workno, password) VALUES ('$username', '$email', '$workno', '$hashedPassword')";

if ($conn->query($sql) === TRUE) {
    echo "Supervisor registered successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close the database connection
$conn->close();
?>
