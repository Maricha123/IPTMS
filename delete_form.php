<?php
session_start();

// Ensure the user is logged in before accessing the page
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}


// Database connection details
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$database = "project2"; 

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if logbook_id is provided and is numeric
if (isset($_POST['student_form_id']) && is_numeric($_POST['student_form_id'])) {
    $student_form_id = $_POST['student_form_id'];

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("DELETE FROM student_form WHERE student_form_id = ?");
    $stmt->bind_param("i", $student_form_id);

    if ($stmt->execute()) {
        // Logbook deleted successfully
        $_SESSION['success_message'] = " deleted successfully.";
    } else {
        // Error deleting logbook
        $_SESSION['error_message'] = "Error deleting: " . $conn->error;
    }

    $stmt->close();
} else {
    // If logbook_id is not provided or not numeric
    $_SESSION['error_message'] = "Invalid form ID.";
}

// Redirect back to view_logbook.php after deletion
header("Location: view_form.php");
exit();



