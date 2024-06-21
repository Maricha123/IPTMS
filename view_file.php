<?php
session_start();

// Ensure the user is logged in before accessing the page
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

include 'db.php';

// Check if the file ID is provided in the URL
if(isset($_GET['id'])) {
    // Get the file ID from the URL
    $fileID = $_GET['id'];

    // Retrieve the file details from the database
    $query = "SELECT file_name, file_content FROM file_uploads WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $fileID);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $fileName = $row['file_name'];
        $fileContent = $row['file_content'];

        // Set the appropriate headers for file download
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"$fileName\"");
        
        // Output the file content
        echo $fileContent;
    } else {
        echo "File not found.";
    }

    // Close the statement
    $stmt->close();
} else {
    echo "Invalid request. Please provide file ID.";
}

// Close the database connection
$conn->close();
?>
