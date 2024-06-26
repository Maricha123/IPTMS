<?php
session_start();

// Ensure the user is logged in before accessing the page
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

include 'db.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';

$userId = $_SESSION['user_id'];

// Fetch the username from the database based on the user ID
$sql = "SELECT Name FROM users WHERE UserID = '$userId'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $username = $row["Name"];
    }
} else {
    $username = "Guest"; // Default to Guest if user not found
}

// Get total number of regions
$sql_regions = "SELECT COUNT(*) AS total_regions FROM regions";
$result_regions = $conn->query($sql_regions);
$row_regions = $result_regions->fetch_assoc();
$total_regions = $row_regions['total_regions'];

// Get total number of supervisors
$sql_supervisors = "SELECT COUNT(*) AS total_supervisors FROM supervisors";
$result_supervisors = $conn->query($sql_supervisors);
$row_supervisors = $result_supervisors->fetch_assoc();
$total_supervisors = $row_supervisors['total_supervisors'];

// Fetch supervisors for selection
$sql_supervisor_options = "SELECT * FROM supervisors";
$result_supervisor_options = $conn->query($sql_supervisor_options);

// Fetch regions for selection
$sql_region_options = "SELECT * FROM regions";
$result_region_options = $conn->query($sql_region_options);

// Count total number of students
$sql_total_students = "SELECT COUNT(*) AS total_students FROM users WHERE Role = 'student'";
$result_total_students = $conn->query($sql_total_students);
$row_total_students = $result_total_students->fetch_assoc();
$total_students = $row_total_students['total_students'];



// Function to generate random password
function generateRandomPassword($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $password = '';
    for ($i = 0; $length > $i; $i++) {
        $password .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $password;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- AdminLTE JS -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js"></script>
    <style>
        body.dark-mode {
            background-color: #121212;
            color: #ffffff;
        }
        body.dark-mode .main-header.navbar {
            background-color: #1f1f1f;
            border-color: #1f1f1f;
        }
        body.dark-mode .main-sidebar {
            background-color: #1f1f1f;
        }
        body.dark-mode .content-wrapper {
            background-color: #121212;
        }
        body.dark-mode .card {
            background-color: #1e1e1e;
            color: #ffffff;
        }
        body.dark-mode .card-header {
            border-bottom: 1px solid #333;
        }
        body.dark-mode .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        body.dark-mode .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        body.dark-mode .small-box {
            background-color: #333;
            color: #ffffff;
        }
        body.dark-mode .small-box .icon {
            color: rgba(255, 255, 255, 0.5);
        }
        body.dark-mode .custom-switch .custom-control-label::before {
            background-color: #6c757d;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars" style="color:#0eacb8"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="admin.php" class="nav-link" style="color:#0eacb8">Home</a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <h5><a class="nav-link" href="view_admin.php" role="button" style="color:#0eacb8"><?php echo $username; ?></a></h5>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="admin.php" class="brand-link">
            <span class="brand-text font-weight-light">Home</span>
        </a>
        <div class="sidebar">
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item">
                        <a href="view_supervisors.php" class="nav-link">
                            <i class="fas fa-user-plus"></i>
                            <p style="color:#0eacb8;">SUPERVISORS</p>
                        </a>
                        <a href="view_regions.php" class="nav-link">
                            <i class="fas fa-map-marker-alt"></i>
                            <p style="color:#0eacb8;">REGIONS</p>
                        </a>
                    </li>
                    <li class="nav-item">
                               <a href="logout.php" class="nav-link">
                                  <i class="nav-icon fas fa-sign-out-alt"></i>
                                  <p>Logout</p>
                               </a>
                            </li>
                </ul>
            </nav>
        </div>
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Manage Supervisors</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="admin.php"></a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-6">
                        <div class="small-box bg-warning">
                            <div class="inner" style="color:white">
                                <h3><?php echo $total_supervisors; ?></h3>
                                <p>Supervisors</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-user-plus"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Assign Supervisor to Region</h3>
                            </div>
                            <div class="card-body">
                                <form action="assign_supervisor.php" method="POST">
                                    <div class="form-group">
                                        <label for="supervisor_id">Select Supervisor:</label>
                                        <select class="form-control" id="supervisor_id" name="supervisor_id" required>
                                            <?php while ($row = $result_supervisor_options->fetch_assoc()): ?>
                                                <option value="<?php echo $row['supervisor_id']; ?>"><?php echo $row['supervisor_email']; ?></option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="region_id">Select Region:</label>
                                        <select class="form-control" id="region_id" name="region_id" required>
                                            <option value="">Select a region</option>
                                            <?php while ($row = $result_region_options->fetch_assoc()): ?>
                                                <option value="<?php echo $row['region_id']; ?>"><?php echo $row['region_name']; ?></option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="districts">Select Districts:</label>
                                        <div id="districts"></div>
                                    </div>
                                    <button type="submit" class="btn btn-primary" name="assign_supervisor">Assign Supervisor</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Add Supervisor</h3>
                            </div>
                            <div class="card-body">
                                <form action="admin.php" method="POST">
                                    <div class="form-group">
                                        <label for="supervisor_name">Supervisor Name:</label>
                                        <input type="text" class="form-control" id="supervisor_name" name="supervisor_name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="supervisor_email">Supervisor Email:</label>
                                        <input type="email" class="form-control" id="supervisor_email" name="supervisor_email" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="Year">Year:</label>
                                        <input type="year" class="form-control" id="year" name="year" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="Contact">Contact:</label>
                                        <input type="number" class="form-control" id="contact" name="contact" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary" name="add_supervisor">Add Supervisor</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <div class="float-right d-none d-sm-inline">
            <!-- Theme switch toggle button -->
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="themeSwitch">
                <label class="form-check-label" for="themeSwitch">Toggle Dark Mode</label>
            </div>
        </div>
    </footer>
</div>
<script>
    $(document).ready(function() {
        // Toggle dark mode
        $('#themeSwitch').change(function() {
            $('body').toggleClass('dark-mode');
        });

        // Fetch districts based on selected region
        $('#region_id').change(function() {
            var region_id = $(this).val();
            if(region_id) {
                $.ajax({
                    url: 'fetch_districts.php',
                    method: 'POST',
                    data: {region_id: region_id},
                    success: function(response) {
                        $('#districts').html(response);
                    }
                });
            } else {
                $('#districts').html('');
            }
        });
    });
</script>
</body>
</html>
