<?php
session_start();

// Database connection details (same as before)
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

// Check if report_id is provided and is numeric
if (isset($_POST['report_id']) && is_numeric($_POST['report_id'])) {
    $reportId = $_POST['report_id'];

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("DELETE FROM reports WHERE report_id = ?");
    $stmt->bind_param("i", $reportId);

    if ($stmt->execute()) {
        // Report deleted successfully
        $_SESSION['success_message'] = "Report deleted successfully.";
    } else {
        // Failed to delete report
        $_SESSION['error_message'] = "Failed to delete report.";
    }

    $stmt->close();
} else {
    $_SESSION['error_message'] = "Invalid report ID.";
}

// Redirect back to view_report.php after deletion
header("Location: view_report.php");
exit();

