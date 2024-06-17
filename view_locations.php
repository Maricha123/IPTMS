<?php
session_start();
include 'db.php';

$userId = $_SESSION['user_id'];

// Fetch the username and email of the supervisor from the database based on the user ID
$sql_supervisor_info = "SELECT Name, Email FROM users WHERE UserID = ?";
$stmt_supervisor_info = $conn->prepare($sql_supervisor_info);
$stmt_supervisor_info->bind_param("i", $userId);
$stmt_supervisor_info->execute();
$result_supervisor_info = $stmt_supervisor_info->get_result();

if ($result_supervisor_info->num_rows > 0) {
    $row_supervisor_info = $result_supervisor_info->fetch_assoc();
    $username = $row_supervisor_info["Name"];
    $supervisor_email = $row_supervisor_info["Email"];

    // Fetch the region assigned to the supervisor based on their email
    $sql_supervisor_region = "SELECT region_id FROM supervisors WHERE supervisor_email = ?";
    $stmt_supervisor_region = $conn->prepare($sql_supervisor_region);
    $stmt_supervisor_region->bind_param("s", $supervisor_email);
    $stmt_supervisor_region->execute();
    $result_supervisor_region = $stmt_supervisor_region->get_result();

    if ($result_supervisor_region->num_rows > 0) {
        $row_supervisor_region = $result_supervisor_region->fetch_assoc();
        $supervisor_region_id = $row_supervisor_region["region_id"];

        // Fetch students who have chosen the supervisor's assigned region with their locations
        $sql_students_locations = "
            SELECT UserID, name, latitude, longitude, is_ready
            FROM student_form 
            WHERE region = ?
        ";
        $stmt_students_locations = $conn->prepare($sql_students_locations);
        $stmt_students_locations->bind_param("i", $supervisor_region_id);
        $stmt_students_locations->execute();
        $result_students_locations = $stmt_students_locations->get_result()->fetch_all(MYSQLI_ASSOC);
    } else {
        $supervisor_region_id = null;
        $result_students_locations = [];
    }
} else {
    $username = "Guest";
    $supervisor_region_id = null;
    $result_students_locations = [];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Locations</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <style>
        /* Adjust map container size */
        #map { height: 600px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Student Locations</h1>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div id="map"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <form action="mark.php" method="post">
                                <label for="student">Select Student:</label>
                                <select name="student_id" id="student">
                                    <?php
                                    foreach ($result_students_locations as $student) {
                                        echo "<option value='{$student['UserID']}'>{$student['name']}</option>";
                                    }
                                    ?>
                                </select>
                                <button type="submit">Mark as Ready</button>
                            </form>
                            <?php
                            if (!empty($result_students_locations)) {
                                echo "<div class='card'>";
                                echo "<div class='card-header'><h3 class='card-title'>Student Locations</h3></div>";
                                echo "<div class='card-body p-0'>";
                                echo "<ul class='products-list product-list-in-card pl-2 pr-2'>";
                                foreach ($result_students_locations as $location) {
                                    echo "<li class='item'>";
                                    echo "<div class='product-info'>";
                                    echo "<a href='view_location.php?lat={$location['latitude']}&lng={$location['longitude']}&name=" . urlencode($location['name']) . "' class='product-title'>{$location['name']}</a>";
                                    echo "</div>";
                                    echo "</li>";
                                }
                                echo "</ul>";
                                echo "</div>";
                                echo "</div>";
                            } else {
                                echo "<p>No student locations found for the assigned region.</p>";
                            }
                            ?>
                        </div>
                    </div>
                    <!-- /.row -->
                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->

        <!-- Main Footer -->
        <footer class="main-footer">
            <strong>IPTMS &copy; 2024.</strong> All rights reserved.
        </footer>
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        var map = L.map('map').setView([0, 0], 2); // Set initial map center and zoom level

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        <?php
        // Loop through each student location and add a marker with a different color or icon based on readiness status
        if (!empty($result_students_locations)) {
            foreach ($result_students_locations as $location) {
                $name = htmlspecialchars($location['name'], ENT_QUOTES);
                $latitude = floatval($location['latitude']);
                $longitude = floatval($location['longitude']);
                $is_ready = $location['is_ready'] ? 'ready' : 'not ready';
                
                // Determine marker icon based on readiness status
                $icon_url = $location['is_ready'] ? 'ready.jpg' : 'not_ready.jpg';

                // Create JavaScript for adding marker with popup
                echo "var icon = L.icon({iconUrl: '$icon_url', iconSize: [35, 51], iconAnchor: [12, 41]});
                      L.marker([$latitude, $longitude], {icon: icon}).addTo(map)
                      .bindPopup('$name ($is_ready)')
                      .openPopup();\n";
            }
        }
        ?>
    </script>
</body>
</html>
