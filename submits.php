<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include your database connection code here
    include 'db.php';

    // Assuming you have stored user_id in the session during login or registration
    $UserID = $_SESSION['user_id'];
    
    // Handle file upload
    if (isset($_FILES['uploadedFile']) && $_FILES['uploadedFile']['error'] === UPLOAD_ERR_OK) {
        // Process file upload
        $fileName = $_FILES['uploadedFile']['name'];
        $fileTmpName = $_FILES['uploadedFile']['tmp_name'];

        // Read file content
        $fileContent = file_get_contents($fileTmpName);

        // Insert file into database
        $insertQuery = "INSERT INTO file_uploads (UserID, file_name, file_content, time_submitted) VALUES (?, ?, ?, NOW())";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("iss", $UserID, $fileName, $fileContent);
        $stmt->execute();
        $stmt->close();
        header("Location: homee.php");
        exit();
    } else {
        echo "Error uploading file.";
    }

    // Form submission for Student Form
    if (isset($_POST['name'], $_POST['regNo'], $_POST['academicYear'], $_POST['region'], $_POST['district'], $_POST['organization'], $_POST['supervisorName'], $_POST['supervisorNo'])) {
        // Retrieve form data
        $name = $_POST['name'];
        $regNo = $_POST['regNo'];
        $academicYear = $_POST['academicYear'];
        $region = $_POST['region'];
        $district = $_POST['district'];
        $organization = $_POST['organization'];
        $supervisorName = $_POST['supervisorName'];
        $supervisorNo = $_POST['supervisorNo'];

        // Check if the registration number already exists
        $checkQuery = "SELECT * FROM student_form WHERE registration_number = ?";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bind_param("s", $regNo);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            // Registration number already exists, reject the submission
            echo "Registration number already exists. Please use a different registration number.";
        } else {
            // Insert into student_form table
            $insertQuery = "INSERT INTO student_form (UserID, name, registration_number, academic_year, region, district, organization, supervisor_name, supervisor_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param("issssssss", $UserID, $name, $regNo, $academicYear, $region, $district, $organization, $supervisorName, $supervisorNo);
            $stmt->execute();
            $stmt->close();
            echo "Student form submitted successfully";
            header("Location: homee.php");
            exit();
        }

        $checkStmt->close();
    }

    // Form submission for Logbook
    if (isset($_POST["date"], $_POST["workspace"]) && !empty($_POST["date"]) && !empty($_POST["workspace"])) {
        // Sanitize user input to prevent SQL injection or other attacks
        $date = htmlspecialchars($_POST["date"]);
        $workspace = htmlspecialchars($_POST["workspace"]);

        // Prepare and bind SQL statement for logbook
        $stmt = $conn->prepare("INSERT INTO logbooks (UserID, date, workspace) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $UserID, $date, $workspace);

        // Execute the statement
        if ($stmt->execute() === TRUE) {
            echo "Logbook entry submitted successfully.";
            header("Location: homee.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    // Form submission for Report Form
    if (isset($_POST["weekNo"], $_POST["work"], $_POST["problems"]) && !empty($_POST["weekNo"]) && !empty($_POST["work"]) && !empty($_POST["problems"])) {
        // Sanitize user input to prevent SQL injection or other attacks
        $weekNo = htmlspecialchars($_POST["weekNo"]);
        $work = htmlspecialchars($_POST["work"]);
        $problems = htmlspecialchars($_POST["problems"]);

        // Prepare and bind SQL statement for report form with file
        $stmt = $conn->prepare("INSERT INTO reports (UserID, week_number, works, problems) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $UserID, $weekNo, $work, $problems);

        // Execute the statement
        if ($stmt->execute() === TRUE) {
            echo "Report submitted successfully.";
            header("Location: homee.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        // If any field is not set or empty, display an error message
        echo "All fields are required for the Report Form.";
    }
} else {
    // If the form is not submitted, redirect to the home page
    header("Location: homee.php");
    exit();
}
?>
