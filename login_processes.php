<?php
session_start();
include 'db.php';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the supervisor exists in the database
    $sql = "SELECT * FROM supervisors WHERE supervisor_email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            // Password is correct, set session variables and redirect to dashboard
            $_SESSION['supervisor_id'] = $row['supervisor_id'];
            $_SESSION['supervisor_name'] = $row['supervisor_name'];
            $_SESSION['email'] = $row['supervisor_email'];
            header("location: dashboards.php");
        } else {
            // Password is incorrect
            echo "Ino";
        }
    } else {
        // Supervisor not found
        echo "mmm.";
    }
}

$conn->close();
?>
