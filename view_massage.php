<?php
session_start();

// Database connection details
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$database = "project2"; 

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Assuming the user is already authenticated and their ID is stored in a session
$userId = $_SESSION['user_id'];

// Fetch the username from the database based on the user ID
$sql = "SELECT Name FROM users WHERE UserID = '$userId'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        $username = $row["Name"];
    }
} else {
    $username = "Guest"; // Default to Guest if user not found
}

// Mark all unread messages as read for the logged-in user
$updateQuery = "UPDATE messages SET is_read = 1 WHERE UserID = '$userId' AND is_read = 0";
$conn->query($updateQuery);

// Fetch messages for the logged-in user
$messageQuery = "SELECT message, sent_at FROM messages WHERE UserID = '$userId' ORDER BY sent_at DESC";
$messagesResult = $conn->query($messageQuery);

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body class="hold-transition sidebar-mini">
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
            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <h5><a class="nav-link" href="view_studentsprofile.php" role="button" style="color:#0eacb8"><?php echo $username; ?></a></h5>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="homee.php" class="brand-link">
                <i class="fas fa-user-graduate"></i>
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
                            <a href="logbook.php" class="nav-link>
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
                            <a href="view_form.php" class="nav-link">
                                <i class="nav-icon fas fa-book"></i>
                                <p>View Arrival Form</p>
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
                          
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <h2 class="mt-4">All Messages</h2>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="list-group">
                                        <?php if ($messagesResult->num_rows > 0): ?>
                                            <?php while($messageRow = $messagesResult->fetch_assoc()): ?>
                                                <div class="list-group-item">
                                                    <h5 class="mb-1"><?php echo date('Y-m-d H:i', strtotime($messageRow['sent_at'])); ?></h5>
                                                    <p class="mb-1"><?php echo htmlspecialchars($messageRow['message']); ?></p>
                                                </div>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <p>No messages found.</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- AdminLTE App -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js"></script>
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

