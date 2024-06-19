<?php
include 'db.php';

if(isset($_POST['region_id'])) {
    $region_id = $_POST['region_id'];
    $sql = "SELECT district_id, district_name FROM districts WHERE region_id = '$region_id'";
    $result = $conn->query($sql);
    $districts = array();
    while($row = $result->fetch_assoc()) {
        $districts[] = $row;
    }
    echo json_encode($districts);
}
?>
