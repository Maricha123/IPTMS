<?php
session_start();

// Ensure the user is logged in before accessing the page
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Location</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        #map {
            height: 600px;
        }
    </style>
</head>
<body>
    <h1>Location Details</h1>
    <?php
        // Retrieve latitude, longitude, and name from the URL parameters and sanitize them
        $lat = htmlspecialchars($_GET['lat']);
        $lng = htmlspecialchars($_GET['lng']);
        $name = htmlspecialchars($_GET['name']);
    ?>
    <div id="map"></div>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        var map = L.map('map').setView([<?php echo $lat; ?>, <?php echo $lng; ?>], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 20,
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors'
        }).addTo(map);

        L.marker([<?php echo $lat; ?>, <?php echo $lng; ?>]).addTo(map)
            .bindPopup('<?php echo $name; ?>\'s Location')
            .openPopup();

        function goBack() {
            window.history.back();
        }
    </script>

    <button type="button" class="btn btn-secondary" onclick="goBack()">Back</button>
</body>
</html>
