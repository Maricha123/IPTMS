<?php
session_start();

// Ensure the user is logged in before accessing the page
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Details</title>
    <link rel="stylesheet" href="display.css"> <!-- Update the CSS file name -->
</head>
<button type="button" class="btn btn-secondary" onclick="goBack()">Back</button>
<body>
    
    
    <?php include 'display_student.php'; ?>
    
</body>
<script>
    function goBack() {
            window.history.back();
        }
</script>
</html>
    
    
