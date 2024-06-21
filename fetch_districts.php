<?php
session_start();

// Ensure the user is logged in before accessing the page
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
require 'db.php';

if(isset($_POST['region_id'])) {
    $region_id = $_POST['region_id'];

    $query = "SELECT district_id, district_name FROM districts WHERE region_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $region_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo '<div class="form-check">';
            echo '<input class="form-check-input" type="checkbox" name="district_ids[]" value="'.$row['district_id'].'">';
            echo '<label class="form-check-label">'.$row['district_name'].'</label>';
            echo '</div>';
        }
    } else {
        echo 'No districts found for this region.';
    }

    $stmt->close();
    $conn->close();
}
?>
