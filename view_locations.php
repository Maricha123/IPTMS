<?php
session_start();
include 'db.php';

$userId = $_SESSION['user_id'];

// Fetch the username and email of the supervisor from the database based on the user ID
$sql_supervisor_info = "SELECT Name, Email FROM users WHERE UserID = '$userId'";
$result_supervisor_info = $conn->query($sql_supervisor_info);

if ($result_supervisor_info->num_rows > 0) {
    $row_supervisor_info = $result_supervisor_info->fetch_assoc();
    $username = $row_supervisor_info["Name"];
    $supervisor_email = $row_supervisor_info["Email"];

    // Fetch the region assigned to the supervisor based on their email
    $sql_supervisor_region = "SELECT region_id FROM supervisors WHERE supervisor_email = '$supervisor_email'";
    $result_supervisor_region = $conn->query($sql_supervisor_region);

    if ($result_supervisor_region->num_rows > 0) {
        $row_supervisor_region = $result_supervisor_region->fetch_assoc();
        $supervisor_region_id = $row_supervisor_region["region_id"];

        // Fetch students who have chosen the supervisor's assigned region with their locations
        $sql_students_locations = "
            SELECT name, latitude, longitude 
            FROM student_form 
            WHERE region = (SELECT region_name FROM regions WHERE region_id = '$supervisor_region_id')
        ";

        $result_students_locations = $conn->query($sql_students_locations)->fetch_all(MYSQLI_ASSOC);
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
                            <?php
                            if (!empty($result_students_locations)) {
                                echo "<div class='card'>";
                                echo "<div class='card-header'><h3 class='card-title'>Student Locations</h3></div>";
                                echo "<div class='card-body p-0'>";
                                echo "<ul class='products-list product-list-in-card pl-2 pr-2'>";
                                foreach ($result_students_locations as $location) {
                                    // This loop is just for reference; we won't display names, latitude, or longitude here
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
            <div class="float-right d-none d-sm-inline">
                <!-- Theme switch toggle button -->
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="themeSwitch">
                    <label class="form-check-label" for="themeSwitch">Dark Mode</label>
                </div>
            </div>
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
        // Loop through each student location and add a marker with popup
        if (!empty($result_students_locations)) {
            foreach ($result_students_locations as $location) {
                $name = addslashes($location['name']);
                $latitude = floatval($location['latitude']);
                $longitude = floatval($location['longitude']);

                // Create JavaScript for adding marker with popup
                echo "L.marker([$latitude, $longitude]).addTo(map).bindPopup('<b>$name</b><br>Latitude: $latitude<br>Longitude: $longitude');";
            }
        }
        ?>

        // Optional: Toggle Dark Mode
        document.getElementById('themeSwitch').addEventListener('change', function() {
            document.body.classList.toggle('dark-mode');
        });
    </script>
</body>
</html>
