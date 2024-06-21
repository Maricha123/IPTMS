<?php
session_start();

// Ensure the user is logged in before accessing the page
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Include your database connection code here
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project2";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the region is set in the POST request
if (isset($_POST['region'])) {
    $selectedRegion = $_POST['region'];

    // Assuming you have a region_supervisor table with supervisor_id, region_id columns
    $query = "SELECT s.supervisor_id, s.supervisor_name FROM supervisors s
              INNER JOIN region_supervisor rs ON s.supervisor_id = rs.supervisor_id
              WHERE rs.region_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $selectedRegion);
    $stmt->execute();
    $result = $stmt->get_result();

    // Build the supervisor options
    $options = '<option value="">Select Supervisor</option>';
    while ($row = $result->fetch_assoc()) {
        $options .= '<option value="' . $row['supervisor_id'] . '">' . $row['supervisor_name'] . '</option>';
    }

    echo $options;

    $stmt->close();
}

$conn->close();
?>
