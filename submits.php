<?php
session_start();

// Include your database connection code here
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You are not logged in.";
    exit();
}

// Assuming you have stored user_id in the session during login or registration
$UserID = $_SESSION['user_id'];

// Handle file upload
if (isset($_FILES['uploadedFile'])) {
    if ($_FILES['uploadedFile']['error'] === UPLOAD_ERR_OK) {
        // Process file upload
        $fileName = $_FILES['uploadedFile']['name'];
        $fileTmpName = $_FILES['uploadedFile']['tmp_name'];
        $fileContent = file_get_contents($fileTmpName);

        // Insert file into database
        $insertQuery = "INSERT INTO file_uploads (UserID, file_name, file_content, time_submitted) VALUES (?, ?, ?, NOW())";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("iss", $UserID, $fileName, $fileContent);
        if ($stmt->execute()) {
            header("Location: homee.php");
            exit();
        } else {
            echo "Error uploading file.";
        }
        $stmt->close();
    } else {
        echo "Error uploading file.";
    }
}

// Handle Student Form submission
if (isset($_POST['name'], $_POST['regNo'], $_POST['academicYear'], $_POST['region'], $_POST['district'], $_POST['organization'], $_POST['supervisorName'], $_POST['supervisorNo'])) {
    $name = $_POST['name'];
    $regNo = $_POST['regNo'];
    $academicYear = $_POST['academicYear'];
    $region = $_POST['region'];
    $district = $_POST['district'];
    $organization = $_POST['organization'];
    $supervisorName = $_POST['supervisorName'];
    $supervisorNo = $_POST['supervisorNo'];

    $checkQuery = "SELECT * FROM student_form WHERE registration_number = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("s", $regNo);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        echo "Registration number already exists. Please use a different registration number.";
    } else {
        $insertQuery = "INSERT INTO student_form (UserID, name, registration_number, academic_year, region, district, organization, supervisor_name, supervisor_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("issssssss", $UserID, $name, $regNo, $academicYear, $region, $district, $organization, $supervisorName, $supervisorNo);
        if ($stmt->execute()) {
            header("Location: homee.php");
            exit();
        } else {
            echo "Error submitting student form.";
        }
        $stmt->close();
    }
    $checkStmt->close();
}

// Handle Logbook submission
if (isset($_POST["date"], $_POST["workspace"]) && !empty($_POST["date"]) && !empty($_POST["workspace"])) {
    $date = htmlspecialchars($_POST["date"]);
    $workspace = htmlspecialchars($_POST["workspace"]);

    $stmt = $conn->prepare("INSERT INTO logbooks (UserID, date, workspace) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $UserID, $date, $workspace);

    if ($stmt->execute()) {
        header("Location: homee.php");
        exit();
    } else {
        echo "Error submitting logbook entry.";
    }
    $stmt->close();
}

// Handle Report Form submission
if (isset($_POST["weekNo"], $_POST["work"], $_POST["problems"]) && !empty($_POST["weekNo"]) && !empty($_POST["work"]) && !empty($_POST["problems"])) {
    $weekNo = htmlspecialchars($_POST["weekNo"]);
    $work = htmlspecialchars($_POST["work"]);
    $problems = htmlspecialchars($_POST["problems"]);

    $stmt = $conn->prepare("INSERT INTO reports (UserID, week_number, works, problems) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $UserID, $weekNo, $work, $problems);

    if ($stmt->execute()) {
        header("Location: homee.php");
        exit();
    } else {
        echo "Error submitting report.";
    }
    $stmt->close();
}

// If no specific form submission is detected, redirect to home page
header("Location: homee.php");
exit();
?>
