<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supervisor Dashboard</title>
    <link rel="stylesheet" href="stylessss.css">
</head>
<body>
    <div class="caption">
        <p>Hello!</p><br><p>Our aim is to simplify the whole process of finding places to conduct field work as well as being supervised.</p><br><p>This platform enables supervisors to:</p> <br>
      
            <p>View student forms.</p><br>
            <p>Access student logbooks.</p><br>
            <p>Review student reports.</p><br>
       
        <p>Thank you!</p>
    </div>
    <!-- display_students.php -->
<?php
include 'db.php';

$sql = "SELECT * FROM students_form";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $studentId = $row['id']; // Assuming 'id' is the primary key of your students table
        $studentName = $row['name'];

        // Display clickable link for each student
        echo "<a href='display_form.php?student_id={$studentId}'>";
        echo "Student Name: {$studentName}";
        echo "</a><br>";
    }
} else {
    echo "No students found.";
}

$conn->close();
?>
   
    <script src="app.js"></script>
</body>
</html>
