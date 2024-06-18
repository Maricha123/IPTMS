<?php
session_start();
include 'db.php';

// Check if the region ID is set in the POST request
if (isset($_POST['region_id'])) {
    $region_id = $_POST['region_id'];

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Delete all districts that reference this region
        $sql_districts = "DELETE FROM districts WHERE region_id = ?";
        $stmt_districts = $conn->prepare($sql_districts);
        $stmt_districts->bind_param("i", $region_id);
        $stmt_districts->execute();
        $stmt_districts->close();

        // Delete the region
        $sql_region = "DELETE FROM regions WHERE region_id = ?";
        $stmt_region = $conn->prepare($sql_region);
        $stmt_region->bind_param("i", $region_id);
        $stmt_region->execute();
        $stmt_region->close();

        // Commit the transaction
        $conn->commit();

        // Redirect back to view_regions.php with a success message
        $_SESSION['message'] = "Region deleted successfully!";
        $_SESSION['message_type'] = "success";
    } catch (Exception $e) {
        // Rollback the transaction if anything failed
        $conn->rollback();

        // Redirect back to view_regions.php with an error message
        $_SESSION['message'] = "Error deleting region!";
        $_SESSION['message_type'] = "danger";
    }
} else {
    // Redirect back to view_regions.php with an error message
    $_SESSION['message'] = "Invalid request!";
    $_SESSION['message_type'] = "danger";
}

// Close the database connection
$conn->close();

// Redirect back to view_regions.php
header("Location: view_regions.php");
exit();
?>
