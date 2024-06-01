<?php
// Database connection
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle file upload
    if (isset($_FILES['uploadedFile']) && $_FILES['uploadedFile']['error'] === UPLOAD_ERR_OK) {
        // Process file upload
        $fileName = $_FILES['uploadedFile']['name'];
        $fileTmpName = $_FILES['uploadedFile']['tmp_name'];

        // Read file content
        $fileContent = file_get_contents($fileTmpName);

        // Insert file into database
        $insertQuery = "INSERT INTO files (file_name, file_content) VALUES (?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("sb", $fileName, $fileContent);
        $stmt->execute();
        $stmt->close();

        echo "File uploaded and stored in the database.";
    } else {
        echo "Error uploading file.";
    }
}
?>
