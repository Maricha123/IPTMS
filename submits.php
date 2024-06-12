<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include your database connection code here
    include 'db.php';

    // Assuming you have stored user_id in the session during login or registration
    $UserID = $_SESSION['user_id'];

    // Handle file upload if a file is selected
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
    }

    // Check if all fields are filled out for the Student Form
    if (isset($_POST['name'], $_POST['regNo'], $_POST['academicYear'], $_POST['region'], $_POST['district'], $_POST['organization'], $_POST['supervisorName'], $_POST['supervisorNo'], $_POST['latitude'], $_POST['longitude'])) {
        // Retrieve form data
        $name = $_POST['name'];
        $regNo = $_POST['regNo'];
        $academicYear = $_POST['academicYear'];
        $region = $_POST['region'];
        $district = $_POST['district'];
        $organization = $_POST['organization'];
        $supervisorName = $_POST['supervisorName'];
        $supervisorNo = $_POST['supervisorNo'];
        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];

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
            $insertQuery = "INSERT INTO student_form (UserID, name, registration_number, academic_year, region, district, organization, supervisor_name, supervisor_number, latitude, longitude) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param("issssssssss", $UserID, $name, $regNo, $academicYear, $region, $district, $organization, $supervisorName, $supervisorNo, $latitude, $longitude);
            $stmt->execute();
            $stmt->close();
            header("Location: homee.php");
            exit();
        }

        $checkStmt->close();
    }

    // Handle form submission for Logbook
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

    // Handle form submission for Report Form
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Submission</title>
    <script>
        function fetchLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(setPosition, showError);
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }

        function setPosition(position) {
            document.getElementById('latitude').value = position.coords.latitude;
            document.getElementById('longitude').value = position.coords.longitude;
            document.getElementById('submitBtn').disabled = false;
        }

        function showError(error) {
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    alert("User denied the request for Geolocation.");
                    break;
                case error.POSITION_UNAVAILABLE:
                    alert("Location information is unavailable.");
                    break;
                case error.TIMEOUT:
                    alert("The request to get user location timed out.");
                    break;
                case error.UNKNOWN_ERROR:
                    alert("An unknown error occurred.");
                    break;
            }
        }

        function checkLocationAndSubmit() {
            const lat = document.getElementById('latitude').value;
            const lon = document.getElementById('longitude').value;
            if (!lat || !lon) {
                alert("Please allow location access to submit the form.");
                return false;
            }
            return true;
        }

        document.addEventListener('DOMContentLoaded', (event) => {
            document.getElementById('submitBtn').disabled = true;
            fetchLocation();
        });
    </script>
</head>
<body>
    <form method="POST" enctype="multipart/form-data" onsubmit="return checkLocationAndSubmit();">
        <!-- Add your form fields here -->
        <input type="text" id="latitude" name="latitude" hidden>
        <input type="text" id="longitude" name="longitude" hidden>

        <!-- Example fields for student form -->
        <input type="text" name="name" placeholder="Name" required>
        <input type="text" name="regNo" placeholder="Registration Number" required>
        <input type="text" name="academicYear" placeholder="Academic Year" required>
        <input type="text" name="region" placeholder="Region" required>
        <input type="text" name="district" placeholder="District" required>
        <input type="text" name="organization" placeholder="Organization" required>
        <input type="text" name="supervisorName" placeholder="Supervisor Name" required>
        <input type="text" name="supervisorNo" placeholder="Supervisor Number" required>

        <button type="submit" id="submitBtn">Submit</button>
    </form>
</body>
</html>
