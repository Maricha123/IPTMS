<?php
include 'db.php';

// Handle form submissions to add new companies
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_company'])) {
    $company_name = $_POST['company_name'];
    $region_id = $_POST['region_id'];
    $insertQuery = "INSERT INTO companies (company_name, region_id) VALUES (?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("si", $company_name, $region_id);
    $stmt->execute();
    $stmt->close();
}

// Handle form submissions to delete companies
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_company'])) {
    $company_id = $_POST['company_id'];
    $deleteQuery = "DELETE FROM companies WHERE company_id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $company_id);
    $stmt->execute();
    $stmt->close();
}

// Retrieve and display existing companies
$selectQuery = "SELECT * FROM companies JOIN regions ON companies.region_id = regions.region_id";
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
    <title>Manage Companies</title>
    <link rel="stylesheet" href="sty.css">
</head>
<body>
    <h2>Manage Companies</h2>
    <form method="post" action="">
        <label for="company_name">Add New Company:</label>
        <input type="text" name="company_name" required>
        <label for="region_id">Select Region:</label>
        <select name="region_id" required>
            <?php
            while ($region = $regionsResult->fetch_assoc()) {
                echo "<option value='{$region['region_id']}'>{$region['region_name']}</option>";
            }
            ?>
        </select>
        <button type="submit" name="add_company">Add Company</button>
    </form>

    <ul>
        <?php
        while ($row = $result->fetch_assoc()) {
            echo "<li>{$row['company_name']} ({$row['region_name']})
                  <form method='post' action=''>
                    <input type='hidden' name='company_id' value='{$row['company_id']}'>
                    <button type='submit' name='delete_company'>Delete</button>
                  </form></li>";
        }
        ?>
    </ul>
</body>
</html>
