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
            <a href="#" class="brand-link">
                <span class="brand-text font-weight-light">Supervisor Dashboard</span>
            </a>
            

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="info">
                        <a href="view_profile.php" class="d-block"><?php echo $username; ?></a>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item">
         <a href="view_locations.php" class="nav-link">
            <i class="nav-icon fas fa-map-marked-alt"></i>
            <p>Students Locations</p>
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
                                <div class="small-box bg-info" >
                                    <div class="inner" style="background-color:orange">
                                        <h3><?php echo ($result_students !== null) ? $result_students->num_rows : 0; ?></h3>
                                        <p >Students</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-user-graduate"></i>
                                    </div>
                                </div>
                            </div>
                            <!-- ./col -->
                        </div>
                        <!-- /.row -->
                        <div class="row">
                            <div class="col-12">
                                <?php
                                if ($result_students !== null && $result_students->num_rows > 0) {
                                    echo "<div class='card'>";
                                    echo "<div class='card-header'><h3 class='card-title'>Students</h3></div>";
                                    echo "<div class='card-body p-0'>";
                                    echo "<ul class='products-list product-list-in-card pl-2 pr-2'>";
                                    $counter = 1;
                                    while ($row_student = $result_students->fetch_assoc()) {
                                        echo "<li class='item'>";
                                        echo "<div class='product-info'>";
                                        echo "<a href='displays.php?student_id={$row_student['UserID']}' class='product-title'>{$counter}. {$row_student['name']}</a>";
                                        echo "</div>";
                                        echo "</li>";
                                        $counter++;
                                    }
                                    echo "</ul>";
                                    echo "</div>";
                                    echo "</div>";
                                } else {
                                    echo "<p>No students found for the assigned region.</p>";
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
>
        </div>
        <!-- ./wrapper -->

        <!-- REQUIRED SCRIPTS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
        <script>
            function updateDateTime() {
                const currentDateTimeElement = document.getElementById('currentDateTime');
                const now = new Date();
                const options = {
                    weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', 
                    hour: 'numeric', minute: 'numeric', second: 'numeric', timeZone: 'Africa/Nairobi'
                };
                const currentDateTime = now.toLocaleString('en-US', options);
                currentDateTimeElement.textContent = `${currentDateTime}`;
            }
            setInterval(updateDateTime, 1000);
            updateDateTime();
        </script>

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
