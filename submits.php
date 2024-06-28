<?php
session_start();

// Ensure the user is logged in before accessing the page
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Include your database connection
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
    echo "<script>
                alert('submitted successfuly!');
                window.location.href = 'homee.php';
              </script>";
    
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
        echo "<script>
        alert('Registration Number Already Exist!');
        window.location.href = 'forms.php';
      </script>";
    } else {
        // Insert into student_form table
        $insertQuery = "INSERT INTO student_form (UserID, name, registration_number, academic_year, region, district, organization, supervisor_name, supervisor_number, latitude, longitude) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("issssssssss", $UserID, $name, $regNo, $academicYear, $region, $district, $organization, $supervisorName, $supervisorNo, $latitude, $longitude);
        $stmt->execute();
        $stmt->close();
        echo "<script>
        alert('successfully submitted!');
        window.location.href = 'forms.php';
      </script>";
        exit();
      
    }

    $checkStmt->close();
}

// Handle logbook data submission
if (isset($_POST['date'], $_POST['content'])) {
    $date = $_POST['date'];
    $content = $_POST['content'];

    // Sanitize content to remove HTML tags
    $content = strip_tags($content);

    $stmt = $conn->prepare("INSERT INTO logbooks (UserID, date, workspace) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $UserID, $date, $content);

    if ($stmt->execute()) {
        echo "<script>
                alert('submitted successfully!!');
                window.location.href = 'logbook.php';
              </script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Handle form submission for Report Form
if (isset($_POST["weekNo"], $_POST["content"]) && !empty($_POST["weekNo"]) && !empty($_POST["content"])) {
    // Sanitize user input to prevent SQL injection or other attacks
    $weekNo = $_POST["weekNo"];
    $content = $_POST["content"];
    
    // Sanitize content to remove HTML tags
    $content = strip_tags($content);

    // Prepare and bind SQL statement for report form with file
    $stmt = $conn->prepare("INSERT INTO reports (UserID, week_number, works) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $UserID, $weekNo, $content);

    // Execute the statement
    if ($stmt->execute() === TRUE) {
        echo "<script>
        alert('submitted successfully!');
        window.location.href = 'report.php';
      </script>";
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    // If any field is not set or empty, display an error message
   
    exit();
}

// If the form is not submitted, redirect to the home page

exit();
?>
