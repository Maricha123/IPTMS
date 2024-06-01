
<?php
include 'db.php';

if (isset($_GET['student_id'])) {
    $UserID = $_GET['student_id'];

   // Retrieve and display the student's form
    $formQuery = "SELECT * FROM student_form WHERE UserID = ?";
    $formStmt = $conn->prepare($formQuery);
    $formStmt->bind_param("i", $UserID);
    $formStmt->execute();
    $formResult = $formStmt->get_result();

    // Store form data in a variable
    $formData = $formResult->fetch_assoc();

    // Retrieve and display the student's logbook
    $logbookQuery = "SELECT * FROM Logbooks WHERE UserID = ?";
    $logbookStmt = $conn->prepare($logbookQuery);
    $logbookStmt->bind_param("i", $UserID);
    $logbookStmt->execute();
    $logbookResult = $logbookStmt->get_result();

    // Retrieve and display the student's report
    $reportQuery = "SELECT * FROM Reports WHERE UserID = ?";
    $reportStmt = $conn->prepare($reportQuery);
    $reportStmt->bind_param("i", $UserID);
    $reportStmt->execute();
    $reportResult = $reportStmt->get_result();

    // Display form data in a table
    if ($formResult->num_rows > 0) {
        echo "<div class='data-table'>";
        echo "<table>";
        echo "<tr><th colspan='2'>Student Form</th></tr>";
        echo "<tr><td>Name</td><td>{$formData['name']}</td></tr>";
        echo "<tr><td>Registration Number</td><td>{$formData['registration_number']}</td></tr>";
        echo "<tr><td>Academic Year</td><td>{$formData['academic_year']}</td></tr>";
        echo "<tr><td>Region</td><td>{$formData['region']}</td></tr>";
        echo "<tr><td>District</td><td>{$formData['district']}</td></tr>";
        echo "<tr><td>Organization</td><td>{$formData['organization']}</td></tr>";
        echo "<tr><td>SupervisorName</td><td>{$formData['supervisor_name']}</td></tr>";
        echo "<tr><td>SupervisorNumber</td><td>{$formData['supervisor_number']}</td></tr>";
        echo "<td>{$formData['uploaded_at']}<td>submitted_time</td></td>"; // Display the date submitted
        echo "</table>";
        echo "</div>";
    } else {
    echo "<div class='center-message'>";
    echo "<p>No form data found for the student.</p>";
    echo "</div>";
}

    // Display logbook data in a table
    if ($logbookResult->num_rows > 0) {
        echo "<div class='data-table'>";
        echo "<table>";
        echo "<tr><th colspan='3'>Student Logbook</th></tr>";
        echo "<tr><th>Date</th><th>Workspace</th><th>Submitted_time</th></tr>";
        while ($logbookRow = $logbookResult->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$logbookRow['date']}</td>";
            echo "<td>{$logbookRow['workspace']}</td>";
            // echo "<td><a href='view_file.php?type=logbook&logbook_id={$logbookRow['logbook_id']}'>View File</a></td>"; // Update 'logbook_id' to the correct column name
            echo "<td>{$logbookRow['uploaded_at']}</td>"; // Display the date submitted
            echo "</tr>";
        }
        echo "</table>";
        echo "</div>";
    } else {
        echo "<div class='center-message'>";
        echo "<p>No logbook data found for the student.</p>";
        echo "</div>";
    }

    // Display report data in a table
    if ($reportResult->num_rows > 0) {
        echo "<div class='data-table'>";
        echo "<table>";
        echo "<tr><th colspan='4' >Student Report</th></tr>";
        echo "<tr><th>Week Number</th><th>Works</th><th>Problems</th><th>Submitted_time</th></tr>";
        while ($reportRow = $reportResult->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$reportRow['week_number']}</td>";
            echo "<td>{$reportRow['works']}</td>";
            echo "<td>{$reportRow['problems']}</td>";
            // echo "<td><a href='view_file.php?type=report&report_id={$reportRow['report_id']}'>View File</a></td>"; // Update 'report_id' to the correct column name
            echo "<td>{$reportRow['uploaded_at']}</td>"; // Display the date submitted
            echo "</tr>";
        }
        echo "</table>";
        echo "</div>";
    } else {
        echo "<div class='center-message'>";
        echo "<p>No report data found for the student.</p>";
        echo "</div>";
    }

    // Close the prepared statements
    $formStmt->close();
    $logbookStmt->close();
    $reportStmt->close();
} else {
    echo "<p>Invalid request. Please select a student.</p>";
}
 // Retrieve and display the student's files
 $fileQuery = "SELECT * FROM file_uploads WHERE UserID = ?";
 $fileStmt = $conn->prepare($fileQuery);
 $fileStmt->bind_param("i", $UserID);
 $fileStmt->execute();
 $fileResult = $fileStmt->get_result();

 // Display files data in a table
 if ($fileResult->num_rows > 0) {
     echo "<div class='data-table'>";
     echo "<table>";
     echo "<tr><th colspan='3'>Student Files</th></tr>";
     echo "<tr><th>File Name</th><th>Submitted Time</th><th>Action</th></tr>";
     while ($fileRow = $fileResult->fetch_assoc()) {
         echo "<tr>";
         echo "<td>{$fileRow['file_name']}</td>";
         echo "<td>{$fileRow['time_submitted']}</td>";
         echo "<td><a href='view_file.php?id={$fileRow['id']}'>Download</a></td>";
         echo "</tr>";
     }
     echo "</table>";
     echo "</div>";
 } else {
    echo "<div class='center-message'>";
    echo "<p>No file found for the student.</p>";
    echo "</div>";
 }

?>
