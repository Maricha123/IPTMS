<?php
session_start();

// Ensure the user is logged in before accessing the page
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $region_id = $_POST['region_id'];

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Delete associated districts first (if not using ON DELETE CASCADE)
        $sql_delete_districts = "DELETE FROM districts WHERE region_id = ?";
        $stmt_districts = $conn->prepare($sql_delete_districts);
        $stmt_districts->bind_param("i", $region_id);
        $stmt_districts->execute();
        $stmt_districts->close();

        // Delete the region
        $sql_delete_region = "DELETE FROM regions WHERE region_id = ?";
        $stmt_region = $conn->prepare($sql_delete_region);
        $stmt_region->bind_param("i", $region_id);
        $stmt_region->execute();
        $stmt_region->close();

        // Commit transaction
        $conn->commit();

        $_SESSION['message'] = "Region and its associated districts deleted successfully.";
        $_SESSION['msg_type'] = "success";

    } catch (Exception $e) {
        // Rollback transaction if any error occurs
        $conn->rollback();
        
        $_SESSION['message'] = "Error deleting region: " . $e->getMessage();
        $_SESSION['msg_type'] = "danger";
    }

    // Close the database connection
    $conn->close();

    // Redirect back to the regions page
    header("Location: view_regions.php");
    exit();
} else {
    $_SESSION['message'] = "Invalid request method.";
    $_SESSION['msg_type'] = "danger";
    
    // Redirect back to the regions page
    header("Location: view_regions.php");
    exit();
}
?>
