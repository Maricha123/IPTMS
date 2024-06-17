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
        // Retrieve latitude and longitude from the URL parameters
        $lat = $_GET['lat'];
        $lng = $_GET['lng'];
    ?>
    <div id="map"></div>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        var map = L.map('map').setView([<?php echo $lat; ?>, <?php echo $lng; ?>], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors'
        }).addTo(map);

        L.marker([<?php echo $lat; ?>, <?php echo $lng; ?>]).addTo(map)
            .bindPopup('Student Location')
            .openPopup();
    </script>
</body>
</html>
