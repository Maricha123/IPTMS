<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['supervisor_id']) && isset($_POST['UserID'])) {
    $supervisor_id = $_POST['supervisor_id'];
    $user_id = $_POST['UserID'];

    // Delete supervisor from supervisors table
    $sql_supervisor = "DELETE FROM supervisors WHERE supervisor_id = ?";
    $stmt_supervisor = $conn->prepare($sql_supervisor);
    $stmt_supervisor->bind_param("i", $supervisor_id);

    // Check if the user has the role of supervisor before deleting from users table
    $sql_check_role = "SELECT role FROM users WHERE UserID = ?";
    $stmt_check_role = $conn->prepare($sql_check_role);
    $stmt_check_role->bind_param("i", $user_id);
    $stmt_check_role->execute();
    $result = $stmt_check_role->get_result();
    $user = $result->fetch_assoc();

    if ($user && $user['role'] == 'supervisor') {
        // Delete user from users table
        $sql_user = "DELETE FROM users WHERE UserID = ?";
        $stmt_user = $conn->prepare($sql_user);
        $stmt_user->bind_param("i", $user_id);

        if ($stmt_supervisor->execute() && $stmt_user->execute()) {
            $_SESSION['message'] = "Supervisor deleted successfully!";
        } else {
            $_SESSION['message'] = "Error deleting supervisor!";
        }

        $stmt_user->close();
    } else {
        $_SESSION['message'] = "Error: User is not a supervisor!";
    }

    $stmt_supervisor->close();
    $stmt_check_role->close();
    $conn->close();

    header("Location: view_supervisors.php");
    exit();
} else {
    $_SESSION['message'] = "Invalid request!";
    header("Location: view_supervisors.php");
    exit();
}
?>
