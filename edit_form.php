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

// Check if form ID is provided
if (!isset($_GET['student_form_id'])) {
    die("Form ID is required.");
}

$student_form_id = $_GET['student_form_id'];

// Fetch form data based on form ID
$sql = "SELECT * FROM student_form WHERE student_form_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_form_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $form = $result->fetch_assoc();
} else {
    die("Form not found.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $registration_number = $_POST['registration_number'];
    $academic_year = $_POST['academic_year'];
    $region = $_POST['region'];
    $district = $_POST['district'];
    $organization = $_POST['organization'];
    $supervisor_name = $_POST['supervisor_name'];
    $supervisor_number = $_POST['supervisor_number'];

    // Update the form data
    $update_sql = "UPDATE student_form SET name = ?, registration_number = ?, academic_year = ?, region = ?, district = ?, organization = ?, supervisor_name = ?, supervisor_number = ? WHERE student_form_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssssssssi", $name, $registration_number, $academic_year, $region, $district, $organization, $supervisor_name, $supervisor_number, $student_form_id);

    if ($update_stmt->execute()) {
        echo "<script>alert('Form updated successfully.'); window.location.href='view_form.php';</script>";
    } else {
        echo "Error updating form: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Form</title>
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
                            <h1 class="m-0" style="color:orange">Edit Form</h1>
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
                                    <form method="POST" action="">
                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($form['name']); ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="registration_number">Registration Number</label>
                                            <input type="text" class="form-control" id="registration_number" name="registration_number" value="<?php echo htmlspecialchars($form['registration_number']); ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="academic_year">Academic Year</label>
                                            <input type="text" class="form-control" id="academic_year" name="academic_year" value="<?php echo htmlspecialchars($form['academic_year']); ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="region">Region</label>
                                            <input type="text" class="form-control" id="region" name="region" value="<?php echo htmlspecialchars($form['region']); ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="district">District</label>
                                            <input type="text" class="form-control" id="district" name="district" value="<?php echo htmlspecialchars($form['district']); ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="organization">Organization</label>
                                            <input type="text" class="form-control" id="organization" name="organization" value="<?php echo htmlspecialchars($form['organization']); ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="supervisor_name">Supervisor Name</label>
                                            <input type="text" class="form-control" id="supervisor_name" name="supervisor_name" value="<?php echo htmlspecialchars($form['supervisor_name']); ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="supervisor_number">Supervisor Number</label>
                                            <input type="text" class="form-control" id="supervisor_number" name="supervisor_number" value="<?php echo htmlspecialchars($form['supervisor_number']); ?>" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Update Form</button>
                                    </form>
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

</body>
</html>
