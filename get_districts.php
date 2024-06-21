<?php
session_start();

// Ensure the user is logged in before accessing the page
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

include 'db.php'; // Connect to your database

if (isset($_GET['region_id'])) {
    $regionId = $_GET['region_id'];

    // Prepare SQL query to fetch districts for the selected region
    $sql = "SELECT * FROM districts WHERE region_id = $regionId";
    $result = $conn->query($sql);

    $districts = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $districts[] = array(
                'district_id' => $row['district_id'],
                'district_name' => $row['district_name']
            );
        }
    }

    // Output districts as JSON
    echo json_encode($districts);
} else {
    // Handle invalid request
    echo json_encode(array('error' => 'Invalid request'));
}

$conn->close();
?>
