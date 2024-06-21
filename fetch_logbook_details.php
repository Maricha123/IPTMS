<?php
session_start();

// Ensure the user is logged in before accessing the page
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Example fetch_logbook_details.php
include 'db.php'; // Include your database connection file

if (isset($_GET['student_id'])) {
    $student_id = $_GET['student_id'];

    // Query to retrieve logbook details for the student
    $logbookQuery = "SELECT * FROM Logbooks WHERE UserID = ?";
    $logbookStmt = $conn->prepare($logbookQuery);
    $logbookStmt->bind_param("i", $student_id);
    $logbookStmt->execute();
    $logbookResult = $logbookStmt->get_result();

    // Fetch logbook details into an array
    $logbookDetails = [];
    while ($row = $logbookResult->fetch_assoc()) {
        $logbookDetails[] = $row;
    }

    // Close statement and database connection
    $logbookStmt->close();
    $conn->close();

    // Return logbook details as JSON response
    header('Content-Type: application/json');
    echo json_encode($logbookDetails);
    exit;
} else {
    // Handle case where student_id is not provided
    http_response_code(400); // Bad request
    echo json_encode(array('error' => 'Student ID is required.'));
    exit;
}
?>
