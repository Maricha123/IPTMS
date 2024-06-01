<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Student Form
    if (isset($_POST['name'])) {
        $name = $_POST['name'];
        $regNo = $_POST['regNo'];
        $academicYear = $_POST['academicYear'];
        $region = $_POST['region'];
        $district = $_POST['district'];
        $organization = $_POST['organization'];
        $supervisorName = $_POST['supervisorName'];
        $supervisorNo = $_POST['supervisorNo'];

        // Check if regNo already exists
        $checkStmt = $conn->prepare("SELECT * FROM studentss_form WHERE regNo = ?");
        $checkStmt->bind_param("s", $regNo);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        $checkStmt->close();

        if ($result->num_rows > 0) {
            echo "Error: Student with registration number '$regNo' already exists.";
        } else {
            // Insert new student record
            $insertStmt = $conn->prepare("INSERT INTO studentss_form (name, regNo, academicYear, region, district, organization, supervisorName, supervisorNo)
                                          VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $insertStmt->bind_param("ssssssss", $name, $regNo, $academicYear, $region, $district, $organization, $supervisorName, $supervisorNo);

            if ($insertStmt->execute()) {
                echo "Student form submitted successfully.";
            } else {
                echo "Error: " . $insertStmt->error;
            }

            $insertStmt->close();
        }
    }
    if (isset($_POST['date'])) {
        $date = $_POST['date'];
        $workspace = $_POST['workspace'];

        // Insert logbook entry
        $logbookStmt = $conn->prepare("INSERT INTO logbookss (date, workspace) VALUES (?, ?)");
        $logbookStmt->bind_param("ss", $date, $workspace);

        if ($logbookStmt->execute()) {
            echo "Logbook entry submitted successfully.";
        } else {
            echo "Error: " . $logbookStmt->error;
        }

        $logbookStmt->close();
    }
    if (isset($_POST['weekNo'])) {
        $weekNo = $_POST['weekNo'];
        $work = $_POST['work'];
        $problems = $_POST['problems'];

        // Insert report entry
        $reportStmt = $conn->prepare("INSERT INTO reportss (weekNo, work, problems) VALUES (?, ?, ?)");
        $reportStmt->bind_param("sss", $weekNo, $work, $problems);

        if ($reportStmt->execute()) {
            echo "Report submitted successfully.";
        } else {
            echo "Error: " . $reportStmt->error;
        }

        $reportStmt->close();
    }
    $conn->close();
}
?>
