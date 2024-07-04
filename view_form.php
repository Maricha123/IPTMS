<?php
session_start();

// Ensure the user is logged in before accessing the page
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}


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

// Fetch arrival form for the logged-in user
$formsQuery = "
    SELECT 
        sf.student_form_id, 
        sf.name, 
        sf.registration_number, 
        sf.academic_year, 
        r.region_name as region_name, 
        d.district_name as district_name, 
        sf.organization, 
        sf.supervisor_name, 
        sf.supervisor_number, 
        sf.uploaded_at
    FROM 
        student_form sf
    JOIN 
        regions r ON sf.region = r.region_id
    JOIN 
        districts d ON sf.district = d.district_id
    WHERE 
        sf.UserID = '$userId'
    ORDER BY 
        sf.uploaded_at DESC
";
$formsResult = $conn->query($formsQuery);

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Your Arrival Form</title>
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
                <span class="brand-text font-weight-light">Home</span>
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

                        <li class="nav-item">
                               <a href="logout.php" class="nav-link">
                                  <i class="nav-icon fas fa-sign-out-alt"></i>
                                  <p>Logout</p>
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
                            <h1 class="m-0" style="color:orange">View Your Arrival Form</h1>
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
                                    <?php if ($formsResult->num_rows > 0): ?>
                                        <ul class="list-group">
                                            <?php while($formsRow = $formsResult->fetch_assoc()): ?>
                                                <li class="list-group-item">
                                                   <p><strong>Time:</strong> <?php echo htmlspecialchars($formsRow['uploaded_at']); ?></p>
                                                   <p><strong>Name:</strong> <?php echo htmlspecialchars($formsRow['name']); ?></p>
                                                   <p><strong>RegNo:</strong> <?php echo htmlspecialchars($formsRow['registration_number']); ?></p>
                                                   <p><strong>Academic year:</strong> <?php echo htmlspecialchars($formsRow['academic_year']); ?></p>
                                                   <p><strong>Region:</strong> <?php echo htmlspecialchars($formsRow['region_name']); ?></p>
                                                   <p><strong>District:</strong> <?php echo htmlspecialchars($formsRow['district_name']); ?></p>
                                                   <p><strong>Organization:</strong> <?php echo htmlspecialchars($formsRow['organization']); ?></p>
                                                   <p><strong>Supervisor Name:</strong> <?php echo htmlspecialchars($formsRow['supervisor_name']); ?></p>
                                                   
                                                    <a href="edit_form.php?student_form_id=<?php echo $formsRow['student_form_id']; ?>" class="btn btn-info btn-sm float-right mr-2">Edit</a>
                                                    
                                                    <form action="delete_form.php" method="post" class="float-right mr-2">
                                                        <input type="hidden" name="student_form_id" value="<?php echo $formsRow['student_form_id']; ?>">
                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this form?');">Delete</button>
                                                    </form>
                                                </li>
                                            <?php endwhile; ?>
                                        </ul>
                                    <?php else: ?>
                                        <p>No Arrival form submitted.</p>
                                    <?php endif; ?>
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
</body>
</html>
