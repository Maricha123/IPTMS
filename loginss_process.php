<?php
session_start(); // Start a session to store user information

// Assuming $loggedInUserEmail is the email of the logged-in user
$_SESSION['user_email'] = $loggedInUserEmail;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include your database connection code here
    // Example: include 'db_connection.php';
    include'db.php';

    // Retrieve form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the email and password match a user in the database
    $servername = "localhost";
    $username = "root";
    $password_db = "";
    $dbname = "project2";

    // Create connection
    $conn = new mysqli($servername, $username, $password_db, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to retrieve user information based on email
    $sql = "SELECT * FROM supervisors WHERE Email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // User found, check the password
        $row = $result->fetch_assoc();
        $hashed_password = $row['password']; 

        if (password_verify($password, $hashed_password)) {
            // Password is correct, set session variables
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_email'] = $row['email'];

            // Redirect to a dashboard or home page
            header("Location: supdash.php");
            exit();
        } else {
            // Password is incorrect
            echo "Invalid password. Please try again.";
        }
    } else {
        // User not found
        echo "Invalid email. Please try again.";
    }

    // Close the database connection
    $conn->close();
}
?>
