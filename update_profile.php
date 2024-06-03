<?php
session_start();
include 'db.php';

$userId = $_SESSION['user_id'];

if(isset($_POST['update_profile'])) {
    // Get the new email and name from the form
    $new_email = $_POST['new_email'];
    $new_name = $_POST['new_name'];
    
    // Update the user's email and name in the database
    $update_sql = "UPDATE users SET Email = '$new_email', Name = '$new_name' WHERE UserID = '$userId'";
    if ($conn->query($update_sql) === TRUE) {
        echo "Profile updated successfully.";
    } else {
        echo "Error updating profile: " . $conn->error;
    }
}

$conn->close();
?>
