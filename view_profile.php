<?php
session_start();

// Ensure the user is logged in before accessing the page
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

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

        // Fetch students who have chosen the supervisor's assigned region
        $sql_students = "SELECT * 
                         FROM student_form
                         WHERE region = (SELECT region_name FROM regions WHERE region_id = '$supervisor_region_id')";

        $result_students = $conn->query($sql_students);
    } else {
        $supervisor_region_id = null;
        $result_students = null;
    }
} else {
    $username = "Guest";
    $supervisor_region_id = null;
    $result_students = null;
}

// Fetch user profile information from the database based on the logged-in user ID
$sql_profile = "SELECT * FROM users WHERE UserID = '$userId'";
$result_profile = $conn->query($sql_profile);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supervisor Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <style>
        /* Define dark mode styles */
        .dark-mode {
            background-color: #333;
            color: #fff;
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
                    <a href="supdash.php" class="nav-link">Home</a>
                    
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="supdash.php" class="brand-link">
                <span class="brand-text font-weight-light">Home</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="info">
                        <a href="#" class="d-block"><?php echo $username; ?></a>
                    </div>
                </div>
                <a href="view_locations.php" class="nav-link">
            <i class="nav-icon fas fa-map-marked-alt"></i>
            <p>Students Locations</p>
        </a>
    </li>
    <li class="nav-item">
                            <a href="logout.php" class="nav-link">
                                <i class="nav-icon fas fa-sign-out-alt"></i>
                                <p>Logout</p>
                            </a>
                        </li>
                

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-item">
                            <a href="" class="nav-link">
                                <i class=""></i>
                                <p>
                                    
                                </p>
                            </a>
                        </li>
                        <!-- Add other menu items here -->
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
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
                            <h1 class="m-0">Welcome <?php echo $username; ?></h1>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- Small boxes (Stat box) -->
                    <div class="row">

                        <div class="col-lg-3 col-6">
                            <!-- small box -->
                            
                        </div>
                    </div><!-- /.container-fluid -->

                    <!-- Profile Section -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header"><h3 class="card-title">Your Profile</h3></div>
                                <div class="card-body p-0">
                                    <?php if ($result_profile->num_rows > 0): ?>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Role</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while ($row = $result_profile->fetch_assoc()): ?>
                                                    <tr>
                                                        <td><?php echo $row['Name']; ?></td>
                                                        <td><?php echo $row['Email']; ?></td>
                                                        <td><?php echo $row['Role']; ?></td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                    <?php else: ?>
                                        <p>No profile found for this user.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->

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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
        <script>
            $(document).ready(function(){
                // Check if the theme preference is stored in local storage
                var currentTheme = localStorage.getItem('theme');

                // If theme preference exists, apply it
                if(currentTheme) {
                    $('body').addClass(currentTheme);
                    if(currentTheme === 'dark-mode') {
                        $('#themeSwitch').prop('checked', true);
                    }
                }

                // Toggle theme when switch is clicked
                $('#themeSwitch').change(function() {
                    if ($(this).is(':checked')) {
                        $('body').addClass('dark-mode');
                        localStorage.setItem('theme', 'dark-mode');
                    } else {
                        $('body').removeClass('dark-mode');
                        localStorage.setItem('theme', '');
                    }
                });
            });
        </script>

        
    </body>
</html>
