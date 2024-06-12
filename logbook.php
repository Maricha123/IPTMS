<?php
session_start();

$userId = $_SESSION['user_id'];

?>

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
            color: rgb(3, 3, 3);
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
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="time-section">
                        <p id="currentDateTime"></p>
                    </div>
                    <marquee behavior="alternate" direction="right" class="marquee-header">Student Daily Logbook</marquee>
                    <div class="card">
                        <div class="card-body">
                            <!-- Form for uploading a file -->
                            <form action="submits.php" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="uploadedFile" class="form-label">Upload File:</label>
                                    <input type="file" name="uploadedFile" class="form-control">
                                </div>
                                <button type="submit" name="uploadBtn" class="btn btn-primary">Upload File</button>
                            </form>

                            <!-- Form for submitting logbook data -->
                            <form action="submits.php" method="post">
                                <div class="form-group">
                                    <label for="date" class="form-label">Date:</label>
                                    <input type="date" id="date" name="date" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="workspace" class="form-label">Workspace:</label>
                                    <textarea id="workspace" name="workspace" rows="4" class="form-control" required></textarea>
                                </div>
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
