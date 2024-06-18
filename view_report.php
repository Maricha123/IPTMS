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

// Fetch reports for the logged-in user
$reportsQuery = "SELECT report_id, works, uploaded_at FROM reports WHERE UserID = '$userId' ORDER BY uploaded_at DESC";
$reportsResult = $conn->query($reportsQuery);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Reports</title>
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="homee.php" class="nav-link">Home</a>
                </li>
            </ul>
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
                            <h1 class="m-0" style="color:orange">View Reports</h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                  
                                    <?php if ($reportsResult->num_rows > 0): ?>
                                        <ul class="list-group">
                                            
                                            <?php while($reportRow = $reportsResult->fetch_assoc()): ?>
                                                <li class="list-group-item">
                                                    <strong><?php echo htmlspecialchars($reportRow['uploaded_at']); ?></strong>
                                                    <a href="download_report.php?report_id=<?php echo $reportRow['report_id']; ?>" 
                                                    class="btn btn-primary btn-sm float-right" download>Download</a>
                                                    <a href="see_report.php?report_id=<?php echo $reportRow['report_id']; ?>" class="btn btn-info btn-sm float-right mr-2">View</a>
                                                    <form action="delete_report.php" method="post" class="float-right mr-2">
                    <input type="hidden" name="report_id" value="<?php echo $reportRow['report_id']; ?>">
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this report?');">Delete</button>
                </form>
                                                </li>
                                            <?php endwhile; ?>
                                        </ul>
                                    <?php else: ?>
                                        <p>No reports submitted.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Existing Code -->

<!-- Add this button to generate the final report -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <a href="generate_final_report.php" class="btn btn-success mb-3">Generate Final Report</a>
                        <?php if ($reportsResult->num_rows > 0): ?>
    <ul class="list-group">
        <?php while($reportRow = $reportsResult->fetch_assoc()): ?>
            <li class="list-group-item">
                <strong><?php echo htmlspecialchars($reportRow['uploaded_at']); ?></strong>
                <a href="download_report.php?report_id=<?php echo $reportRow['report_id']; ?>" class="btn btn-primary btn-sm float-right" download>Download</a>
                <a href="see_report.php?report_id=<?php echo $reportRow['report_id']; ?>" class="btn btn-info btn-sm float-right mr-2">View</a>
               
            </li>
        <?php endwhile; ?>
    </ul>
<?php else: ?>
    <p>No reports submitted.</p>
<?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.content -->

            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Main Footer -->
        <footer class="main-footer">
            <strong>IPTMS &copy; 2024.</strong> All rights reserved.
        </footer>
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
