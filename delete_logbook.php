<?php
session_start();

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
if (isset($_POST['logbook_id']) && is_numeric($_POST['logbook_id'])) {
    $logbookId = $_POST['logbook_id'];

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("DELETE FROM logbooks WHERE logbook_id = ?");
    $stmt->bind_param("i", $logbookId);

    if ($stmt->execute()) {
        // Logbook deleted successfully
        $_SESSION['success_message'] = "Logbook deleted successfully.";
    } else {
        // Error deleting logbook
        $_SESSION['error_message'] = "Error deleting logbook: " . $conn->error;
    }

    $stmt->close();
} else {
    // If logbook_id is not provided or not numeric
    $_SESSION['error_message'] = "Invalid logbook ID.";
}

// Redirect back to view_logbook.php after deletion
header("Location: view_logbook.php");
exit();

