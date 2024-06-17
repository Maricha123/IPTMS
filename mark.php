<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'];

    // Update the student's status in the database
    $sql_update_status = "UPDATE student_form SET is_ready = 1 WHERE UserID = ?";
    $stmt_update_status = $conn->prepare($sql_update_status);
    $stmt_update_status->bind_param("i", $student_id);
    
    if ($stmt_update_status->execute()) {
        // Successful update
        echo "Student marked as ready successfully.";
    } else {
        // Error in update
        echo "Error: " . $stmt_update_status->error;
    }

    $stmt_update_status->close();
    $conn->close();

    // Redirect back to the main page
    header("Location: view_locations.php");
    exit();
}
?>
