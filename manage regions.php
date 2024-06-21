<?php
session_start();

// Ensure the user is logged in before accessing the page
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

include 'db.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userId = $_SESSION['user_id'];

// Fetch the username from the database based on the user ID
$sql = "SELECT Name FROM users WHERE UserID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();
$username = $result->num_rows > 0 ? $result->fetch_assoc()['Name'] : "Guest";

// Get total number of regions
$sql_regions = "SELECT COUNT(*) AS total_regions FROM regions";
$total_regions = $conn->query($sql_regions)->fetch_assoc()['total_regions'];

// Get total number of supervisors
$sql_supervisors = "SELECT COUNT(*) AS total_supervisors FROM supervisors";
$total_supervisors = $conn->query($sql_supervisors)->fetch_assoc()['total_supervisors'];

// Get total number of students
$sql_total_students = "SELECT COUNT(*) AS total_students FROM users WHERE Role = 'student'";
$total_students = $conn->query($sql_total_students)->fetch_assoc()['total_students'];

// Fetch regions for selection
$sql_region_options = "SELECT * FROM regions";
$result_region_options = $conn->query($sql_region_options);

// Fetch supervisors for selection
$sql_supervisor_options = "SELECT * FROM supervisors";
$result_supervisor_options = $conn->query($sql_supervisor_options);

// Function to generate random password
function generateRandomPassword($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $password = '';
    for ($i = 0; $length > $i; $i++) {
        $password .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $password;
}

function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Handle adding a new district
if (isset($_POST['add_district'])) {
    $region_id = sanitize($_POST['region_id']);
    $district_name = sanitize($_POST['district_name']);

    // Check if the district already exists
    $check_sql = "SELECT * FROM districts WHERE district_name = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param('s', $district_name);
    $stmt->execute();
    $check_result = $stmt->get_result();

    if($check_result->num_rows == 0) {
        // Insert the new district into the database
        $sql = "INSERT INTO districts (district_name, region_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', $district_name, $region_id);
        if ($stmt->execute()) {
            echo "<script>alert('Successfully added district');</script>";
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "<script>alert('District already exists!');</script>";
    }
}

// Handle adding a new region
if (isset($_POST['add_region'])) {
    $region_name = sanitize($_POST['region_name']);

    // Check if the region already exists
    $check_sql = "SELECT * FROM regions WHERE region_name = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param('s', $region_name);
    $stmt->execute();
    $check_result = $stmt->get_result();

    if($check_result->num_rows == 0) {
        // Insert the region into the database
        $sql = "INSERT INTO regions (region_name) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $region_name);
        if ($stmt->execute()) {
            echo "<script>alert('Successfully added region');</script>";
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "<script>alert('Region already exists!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js"></script>
    <style>
        body.dark-mode {
            background-color: #121212;
            color: #ffffff;
        }
        .dark-mode .main-header.navbar {
            background-color: #1f1f1f;
            border-color: #1f1f1f;
        }
        .dark-mode .main-sidebar {
            background-color: #1f1f1f;
        }
        .dark-mode .content-wrapper {
            background-color: #121212;
        }
        .dark-mode .card {
            background-color: #1e1e1e;
            color: #ffffff;
        }
        .dark-mode .card-header {
            border-bottom: 1px solid #333;
        }
        .dark-mode .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .dark-mode .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        .dark-mode .small-box {
            background-color: #333;
            color: #ffffff;
        }
        .dark-mode .small-box .icon {
            color: rgba(255, 255, 255, 0.5);
        }
        .dark-mode .custom-switch .custom-control-label::before {
            background-color: #6c757d;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
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

    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="admin.php" class="brand-link">
            <span class="brand-text font-weight-light">Admin Dashboard</span>
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
                </ul>
            </nav>
        </div>
    </aside>

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Manage and View Regions</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="admin.php"></a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-12">
                        <div class="small-box bg-info">
                            <div class="inner" style="background-color:green">
                                <h3><?php echo $total_regions; ?></h3>
                                <p>Total Regions</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Add Region</h3>
                            </div>
                            <div class="card-body">
                                <form action="manage regions.php" method="POST">
                                    <div class="form-group">
                                        <label for="region_name">Region Name:</label>
                                        <input type="text" class="form-control" id="region_name" name="region_name" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary" name="add_region">Add Region</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Manage Districts</h3>
                            </div>
                            <div class="card-body">
                                <form action="manage regions.php" method="POST">
                                    <div class="form-group">
                                        <label for="region">Select Region:</label>
                                        <select class="form-control" id="region" name="region_id" required>
                                            <option value="">Select Region</option>
                                            <?php
                                            while ($row = $result_region_options->fetch_assoc()) {
                                                echo '<option value="'.$row['region_id'].'">'.$row['region_name'].'</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="district_name">District Name:</label>
                                        <input type="text" class="form-control" id="district_name" name="district_name" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary" name="add_district">Add District</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="dark-mode-toggle">
    <label class="custom-switch">
        <input type="checkbox" id="darkModeSwitch" class="custom-control-input">
        <span class="custom-control-label">Dark Mode</span>
    </label>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var darkModeSwitch = document.getElementById('darkModeSwitch');
        darkModeSwitch.addEventListener('change', function () {
            document.body.classList.toggle('dark-mode', darkModeSwitch.checked);
        });
    });

    $(document).ready(function() {
        $('#region').change(function() {
            var region_id = $(this).val();
            if (region_id != '') {
                $.ajax({
                    url: "fetch_districts.php",
                    method: "POST",
                    data: {region_id: region_id},
                    dataType: "json",
                    success: function(data) {
                        $('#district').html('<option value="">Select District</option>');
                        $.each(data, function(key, value) {
                            $('#district').append('<option value="'+value.district_id+'">'+value.district_name+'</option>');
                        });
                    }
                });
            } else {
                $('#district').html('<option value="">Select District</option>');
                $('#ward').html('<option value="">Select Ward</option>');
            }
        });
    });
</script>
</body>
</html>
