<?php
session_start();
include 'db.php';

// Check if the region ID is set in the POST request
if (isset($_POST['region_id'])) {
    $region_id = $_POST['region_id'];

    // Delete the region from the database
    $sql = "DELETE FROM regions WHERE region_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $region_id);

    if ($stmt->execute()) {
        // Redirect back to view_regions.php with a success message
        $_SESSION['message'] = "Region deleted successfully!";
        $_SESSION['message_type'] = "success";
    } else {
        // Redirect back to view_regions.php with an error message
        $_SESSION['message'] = "Error deleting region!";
        $_SESSION['message_type'] = "danger";
    }

    // Close the prepared statement
    $stmt->close();
} else {
    // Redirect back to view_regions.php with an error message
    $_SESSION['message'] = "Invalid request!";
    $_SESSION['message_type'] = "danger";
}

// Close the database connection
$conn->close();

// Redirect back to view_regions.php
header("Location: manage regions.php");
exit();
?>
