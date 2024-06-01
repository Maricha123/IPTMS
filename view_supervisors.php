<?php
session_start();
include 'db.php';

// Fetch supervisors with their assigned regions and emails
$sql = "SELECT supervisors.supervisor_name, supervisors.supervisor_email, regions.region_name 
        FROM supervisors 
        LEFT JOIN regions ON supervisors.region_id = regions.region_id";
$result = $conn->query($sql);

$supervisor_regions = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $supervisor_regions[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Supervisors</title>
    <link rel="stylesheet" href="supdash1.css"> <!-- Update the CSS file name -->
</head>
<body>
    <div class="container">
        <h2>Supervisors and Their Assigned Regions</h2>
        <table>
            <thead>
                <tr>
                    <th>Supervisor</th>
                    <th>Email</th>
                    <th>Assigned Region</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($supervisor_regions as $supervisor_region): ?>
                    <tr>
                        <td><?php echo $supervisor_region['supervisor_name']; ?></td>
                        <td><?php echo $supervisor_region['supervisor_email']; ?></td>
                        <td><?php echo $supervisor_region['region_name'] ?? 'Not Assigned'; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
