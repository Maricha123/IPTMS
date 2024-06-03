<?php
// Include database connection
include 'db.php';

// Check if the report ID is provided in the URL
if(isset($_GET['report_id'])) {
    // Get the report ID from the URL
    $reportID = $_GET['report_id'];

    // Retrieve the report details from the database
    $query = "SELECT works, week_number FROM reports WHERE report_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $reportID);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $fileName = "Report_Week_" . $row['week_number'] . ".txt"; // Example: Report_Week_1.txt
        $fileContent = $row['works'];

        // Set the appropriate headers for file download
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"$fileName\"");
        
        // Output the file content
        echo $fileContent;
    } else {
        echo "Report not found.";
    }

    // Close the statement
    $stmt->close();
} else {
    echo "Invalid request. Please provide report ID.";
}

// Close the database connection
$conn->close();
?>
