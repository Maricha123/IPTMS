<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Form Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <style>
        /* Define dark mode styles */
        .dark-mode {
            background-color: #333;
            color: #fff;
        }
        .time-section {
            text-align: center;
            margin-bottom: 20px;
        }
        .marquee-header {

            background-color: #f8d775;
            padding: 10px;
            text-align: start;
            font-size: 1.5rem;
            font-weight: bold;
            color: black;
        }
        .form-label {
            font-weight: bold;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="homee.php" class="nav-link">Home</a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="homee.php" class="brand-link">
                <span class="brand-text font-weight-light">Student Dashboard</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-item" style="background-color:#0eacb8; margin-top:10px">
                            <a href="forms.php" class="nav-link">
                                <i class="nav-icon fas fa-edit"></i>
                                <p>Arrival Form</p>
                            </a>
                        </li>
                        <li class="nav-item" style="background-color:#0eacb8;margin-top:10px">
                            <a href="logbook.php" class="nav-link">
                                <i class="nav-icon fas fa-book"></i>
                                <p>Logbook</p>
                            </a>
                        </li>
                        <li class="nav-item" style="background-color:#0eacb8; margin-top:10px">
                            <a href="report.php" class="nav-link">
                                <i class="nav-icon fas fa-file-alt"></i>
                                <p>Report</p>
                            </a>
                        </li>
                        <li class="nav-item" style="background-color:#0eacb8; margin-top:10px">
                            <a href="view_logbook.php" class="nav-link">
                                <i class="nav-icon fas fa-book"></i>
                                <p>View Logbooks</p>
                            </a>
                        </li>
                        <li class="nav-item" style="background-color:#0eacb8; margin-top:10px">
                            <a href="view_report.php" class="nav-link">
                                <i class="nav-icon fas fa-file-alt"></i>
                                <p>View Reports</p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0"></h1>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!--            // Update hidden input fields
            document.getElementById("latitude").value = latitude;
            document.getElementById("longitude").value = longitude;
            alert("Location fetched successfully:\nLatitude: " + latitude + "\nLongitude: " + longitude);
        }

        // Function to handle errors in geolocation
        function showError(error) {
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    alert("User denied the request for Geolocation.");
                    break;
                case error.POSITION_UNAVAILABLE:
                    alert("Location information is unavailable.");
                    break;
                case error.TIMEOUT:
                    alert("The request to get user location timed out.");
                    break;
                case error.UNKNOWN_ERROR:
                    alert("An unknown error occurred.");
                    break;
            }
        }

        function goBack() {
            window.history.back();
        }

        function updateDateTime() {
            const currentDateTimeElement = document.getElementById('currentDateTime');
            const now = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric', second: 'numeric', timeZone: 'Africa/Nairobi' };

            // Display the time, date, and day
            const currentDateTime = new Date().toLocaleString('en-US', options);
            currentDateTimeElement.textContent = `${currentDateTime}`;
        }

        // Update current time, date, and day every second
        setInterval(updateDateTime, 1000);

        // Initial update
        updateDateTime();
    </script>
</body>
</html>
 Main content -->
            <section class="content" >
                <div class="container-fluid" >
                    <div class="time-section" >
                        <p id="currentDateTime"></p>
                    </div>
                    <marquee behavior="alternate" direction="right" class="marquee-header">Student Arrival Form</marquee>
                <h3 style="color: red">*Remember to fetch the location before submitting the form*</h3>
                    <div class="card">
                        <div class="card-body">
                            <form id="studentForm" action="submits.php" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="name" class="form-label">Name:</label>
                                    <input type="text" id="name" name="name" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="regNo" class="form-label">Registration Number:</label>
                                    <input type="text" id="regNo" name="regNo" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="academicYear" class="form-label">Academic Year:</label>
                                    <input type="text" id="academicYear" name="academicYear" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="region" class="form-label">Region:</label>
                                    <select id="region" name="region" class="form-control" required>
                                        <option value="" disabled selected>Select Region</option>
                                        <?php
                                            include 'db.php';
                                            $sql = "SELECT * FROM regions";
                                            $result = $conn->query($sql);
                                            while($row = $result->fetch_assoc()) {
                                                echo "<option value='" . $row['region_name'] . "'>" . $row['region_name'] . "</option>";
                                            }
                                            $conn->close();
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="district" class="form-label">District:</label>
                                    <input type="text" id="district" name="district" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="organization" class="form-label">Organization:</label>
                                    <input type="text" id="organization" name="organization" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="supervisorName" class="form-label">Onsite Supervisor Name:</label>
                                    <input type="text" id="supervisorName" name="supervisorName" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="supervisorNo" class="form-label">Onsite Supervisor Number:</label>
                                    <input type="text" id="supervisorNo" name="supervisorNo" class="form-control" required>
                                </div>
                                <!-- Hidden fields for latitude and longitude -->
                                <input type="hidden" id="latitude" name="latitude">
                                <input type="hidden" id="longitude" name="longitude">
                                <!-- Button to fetch location -->
                                <button type="button" class="btn btn-primary" onclick="getLocation()">Fetch Location</button>
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <button type="button" class="btn btn-secondary" onclick="goBack()">Back</button>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <script>
        // Function to get user's geolocation
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }

        // Function to show position and update hidden input fields
        function showPosition(position) {
            const latitude = position.coords.latitude;
            const longitude = position.coords.longitude;
            // Update hidden input fields
            document.getElementById("latitude").value = latitude;
            document.getElementById("longitude").value = longitude;
          
            alert("Location fetched successfully:\nLatitude: " + latitude + "\nLongitude: " + longitude);
        }

        // Function to handle errors in geolocation
        function showError(error) {
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    alert("User denied the request for Geolocation.");
                    break;
                case error.POSITION_UNAVAILABLE:
                    alert("Location information is unavailable.");
                    break;
                case error.TIMEOUT:
                    alert("The request to get user location timed out.");
                    break;
                case error.UNKNOWN_ERROR:
                    alert("An unknown error occurred.");
                    break;
            }
        }

        function goBack() {
            window.history.back();
        }

        function updateDateTime() {
            const currentDateTimeElement = document.getElementById('currentDateTime');
            const now = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric', second: 'numeric', timeZone: 'Africa/Nairobi' };

            // Display the time, date, and day
            const currentDateTime = new Date().toLocaleString('en-US', options);
            currentDateTimeElement.textContent = `${currentDateTime}`;
        }

        // Update current time, date, and day every second
        setInterval(updateDateTime, 1000);

        // Initial update
        updateDateTime();
    </script>
</body>
</html>
