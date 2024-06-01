<?php
include 'db.php';

// Handle form submissions to add new regions
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_region'])) {
    $region_name = $_POST['region_name'];
    $insertQuery = "INSERT INTO regions (region_name) VALUES (?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("s", $region_name);
    $stmt->execute();
    $stmt->close();
}

// Handle form submissions to delete regions
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_region'])) {
    $region_id = $_POST['region_id'];
    $deleteQuery = "DELETE FROM regions WHERE region_id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $region_id);
    $stmt->execute();
    $stmt->close();
}

// Retrieve and display existing regions
$selectQuery = "SELECT * FROM regions";
$result = $conn->query($selectQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Regions</title>
    <link rel="stylesheet" href="sty.css">
</head>
<body>
    <h1>Manage Regions</h1>
    <form method="post" action="">
        <label for="region_name">Add New Region:</label>
        <input type="text" name="region_name" required>
        <button type="submit" name="add_region">Add Region</button>
    </form>

    <ul>
        <?php
        while ($row = $result->fetch_assoc()) {
            echo "<li>{$row['region_name']} 
                  <form method='post' action=''>
                    <input type='hidden' name='region_id' value='{$row['region_id']}'>
                    <button type='submit' name='delete_region'>Delete</button>
                  </form></li>";
        }
        ?>
    </ul>
</body>
</html>
