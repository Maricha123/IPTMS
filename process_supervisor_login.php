<?php
// Assuming you have a database connection
// Replace 'your_database_host', 'your_database_user', 'your_database_password', and 'your_database_name' with your actual database details
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'actual';

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Retrieve username and password from the form
$username = $_POST['username'];
$password = $_POST['password'];

// Perform SQL query to check if the user exists
$query = "SELECT * FROM supervisors WHERE username = '$username' AND password = '$password'";
$result = mysqli_query($conn, $query);

// Check if the query was successful and if a user was found
if ($result && mysqli_num_rows($result) > 0) {
    // User is authenticated, you can redirect to the supervisor dashboard or perform other actions
    header("Location: supervisor_dashboard.php");
} else {
    // Authentication failed, you can redirect back to the login page with an error message
    header("Location: supervisor_login.html?error=1");
}

// Close the database connection
mysqli_close($conn);
?>
