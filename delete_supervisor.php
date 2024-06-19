<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['supervisor_id'])) {
    $supervisor_id = $_POST['supervisor_id'];

    // Prepare to delete supervisor from supervisors table
    $sql_supervisor = "DELETE FROM supervisors WHERE supervisor_id = ?";
    $stmt_supervisor = $conn->prepare($sql_supervisor);
    $stmt_supervisor->bind_param("i", $supervisor_id);

    // Prepare to fetch supervisor's email for deleting from users table
    $sql_get_email = "SELECT supervisor_email FROM supervisors WHERE supervisor_id = ?";
    $stmt_get_email = $conn->prepare($sql_get_email);
    $stmt_get_email->bind_param("i", $supervisor_id);
    $stmt_get_email->execute();
    $result = $stmt_get_email->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        $supervisor_email = $row['supervisor_email'];

        // Delete supervisor from users table (where role is supervisor)
        $sql_user = "DELETE FROM users WHERE Email = ? AND role = 'supervisor'";
        $stmt_user = $conn->prepare($sql_user);
        $stmt_user->bind_param("s", $supervisor_email);

        // Execute the deletion queries
        $stmt_supervisor_success = $stmt_supervisor->execute();
        $stmt_user_success = $stmt_user->execute();

        if ($stmt_supervisor_success && $stmt_user_success) {
            $_SESSION['message'] = "Supervisor and associated user deleted successfully!";
        } else {
            $_SESSION['message'] = "Error deleting supervisor or associated user!";
        }

        $stmt_user->close();
    } else {
        $_SESSION['message'] = "Error: Supervisor not found!";
    }

    // Close statements and database connection
    $stmt_supervisor->close();
    $stmt_get_email->close();
    $conn->close();

    // Redirect back to view_supervisors.php
    header("Location: view_supervisors.php");
    exit();
} else {
    $_SESSION['message'] = "Invalid request!";
    header("Location: view_supervisors.php");
    exit();
}
?>
