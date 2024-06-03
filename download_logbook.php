<?php
include 'db.php';

// Check if the logbook ID is provided in the URL
if(isset($_GET['logbook_id'])) {
    // Get the logbook ID from the URL
    $logbookID = $_GET['logbook_id'];

    // Retrieve the logbook details from the database
    $query = "SELECT date, workspace, uploaded_at FROM logbooks WHERE logbook_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $logbookID);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $fileName = "logbook_" . $row['date'] . ".txt"; // You can adjust the file name as needed
        $fileContent = $row['workspace'];

        // Set the appropriate headers for file download
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"$fileName\"");
        
        // Output the file content
        echo $fileContent;
    } else {
        echo "Logbook not found.";
    }

    // Close the statement
    $stmt->close();
} else {
    echo "Invalid request. Please provide logbook ID.";
}

// Close the database connection
$conn->close();
?>
