<?php
session_start();

// Ensure the user is logged in before accessing the page
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// reset_password.php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Database connection (update with your connection details)
    $conn = new mysqli("localhost", "root", "", "project2");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Validate token and update password
    $sql = "SELECT * FROM password_resets WHERE token = ? AND expiry > NOW()";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $email = $row['email'];

        // Update password
        $sql = "UPDATE users SET password = ? WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $password, $email);
        $stmt->execute();

        // Delete the token
        $sql = "DELETE FROM password_resets WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        header("location: index.php");
    } else {
        echo "Invalid or expired token.";
    }

    $stmt->close();
    $conn->close();
}
