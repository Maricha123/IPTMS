<?php
include 'db.php';

// Handle form submissions to add new supervisors
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_supervisor'])) {
    $supervisor_name = $_POST['supervisor_name'];
    $region_id = $_POST['region_id'];
    $insertQuery = "INSERT INTO supervisors (supervisor_name, region_id) VALUES (?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("si", $supervisor_name, $region_id);
    $stmt->execute();
    $stmt->close();
}

// Handle form submissions to delete supervisors
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_supervisor'])) {
    $supervisor_id = $_POST['supervisor_id'];
    $deleteQuery = "DELETE FROM supervisors WHERE supervisor_id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $supervisor_id);
    $stmt->execute();
    $stmt->close();
}

// Retrieve and display existing supervisors
$selectQuery = "SELECT * FROM supervisors JOIN regions ON supervisors.region_id = regions.region_id";
$result = $conn->query($selectQuery);

// Retrieve existing regions for the dropdown
$regionsQuery = "SELECT * FROM regions";
$regionsResult = $conn->query($regionsQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Supervisors</title>
    <link rel="stylesheet" href="sty.css">
</head>
<body>
    <!-- <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color:grey;
        } -->
    </style>
    <h1>Manage Supervisors</h1>
    <form method="post" action="">
        <label for="supervisor_name">Add New Supervisor:</label>
        <input type="text" name="supervisor_name" required>
        <label for="region_id">Select Region:</label>
        <select name="region_id" required>
            <?php
            while ($region = $regionsResult->fetch_assoc()) {
                echo "<option value='{$region['region_id']}'>{$region['region_name']}</option>";
            }
            ?>
        </select>
        <button type="submit" name="add_supervisor">Add Supervisor</button>
    </form>

    <ul>
        <?php
        while ($row = $result->fetch_assoc()) {
            echo "<li>{$row['supervisor_name']} ({$row['region_name']})
                  <form method='post' action=''>
                    <input type='hidden' name='supervisor_id' value='{$row['supervisor_id']}'>
                    <button type='submit' name='delete_supervisor'>Delete</button>
                  </form></li>";
        }
        ?>
    </ul>
</body>
</html>
