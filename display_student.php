<?php
include 'db.php';

if (isset($_GET['student_id'])) {
    $UserID = $_GET['student_id'];

    // Retrieve and display the student's form
    $formQuery = "SELECT * FROM student_form WHERE UserID = ?";
    $formStmt = $conn->prepare($formQuery);
    $formStmt->bind_param("i", $UserID);
    $formStmt->execute();
    $formResult = $formStmt->get_result();
    $formData = $formResult->fetch_assoc();

    // Retrieve and display the student's logbook
    $logbookQuery = "SELECT * FROM Logbooks WHERE UserID = ?";
    $logbookStmt = $conn->prepare($logbookQuery);
    $logbookStmt->bind_param("i", $UserID);
    $logbookStmt->execute();
    $logbookResult = $logbookStmt->get_result();

    // Retrieve and display the student's report
    $reportQuery = "SELECT * FROM Reports WHERE UserID = ?";
    $reportStmt = $conn->prepare($reportQuery);
    $reportStmt->bind_param("i", $UserID);
    $reportStmt->execute();
    $reportResult = $reportStmt->get_result();

    // Retrieve and display the student's files
    $fileQuery = "SELECT * FROM file_uploads WHERE UserID = ?";
    $fileStmt = $conn->prepare($fileQuery);
    $fileStmt->bind_param("i", $UserID);
    $fileStmt->execute();
    $fileResult = $fileStmt->get_result();

    // Handle message submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
        $message = $_POST['message'];
        $messageQuery = "INSERT INTO messages (UserID, message) VALUES (?, ?)";
        $messageStmt = $conn->prepare($messageQuery);
        $messageStmt->bind_param("is", $UserID, $message);
        if ($messageStmt->execute()) {
            echo "<p>Message sent successfully.</p>";
        } else {
            echo "<p>Failed to send message.</p>";
        }
    }

    // Close the database connection
    $conn->close();
} else {
    echo "<p>Invalid request. Please select a student.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <style>
        .data-table {
            margin: 20px 0;
        }
        .data-table table {
            width: 100%;
            border-collapse: collapse;
        }
        .data-table th, .data-table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .data-table th {
            background-color: #f2f2f2;
        }
        .center-message {
            text-align: center;
            margin-top: 20px;
        }
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
            <a href="#" class="brand-link">
                <span class="brand-text font-weight-light">Supervisor Dashboard</span>
            </a>
            <div class="sidebar">
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon"></i>
                                <p> </p>
                            </a>
                        </li>
                        <!-- Add other menu items here -->
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
                            <h1 class="m-0">Student Details</h1>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- Student Form -->
                    <?php if ($formResult->num_rows > 0): ?>
                        <div class="data-table">
                            <table class="table table-bordered">
                                <thead>
                                    <tr><th colspan="2">Student Form</th></tr>
                                </thead>
                                <tbody>
                                <tr>
                                   <td>Name</td>
                                   <td><?php echo $formData['name']; ?></td>
                                </tr>
                                <tr>
                                   <td>Registration Number</td>
                                   <td><?php echo $formData['registration_number']; ?></td>
                                </tr>
                                <tr>
                                   <td>Academic Year</td>
                                   <td><?php echo $formData['academic_year']; ?></td>
                                </tr>
                                <tr>
                                    <td>Region</td>
                                    <td><?php echo $formData['region']; ?></td>
                                </tr>
                                <tr>
                                    <td>District</td>
                                    <td><?php echo $formData['district']; ?></td>
                                </tr>
                                <tr>
                                    <td>Organization</td>
                                    <td><?php echo $formData['organization']; ?></td>
                                </tr>
                                <tr>
                                    <td>Supervisor Name</td>
                                    <td><?php echo $formData['supervisor_name']; ?></td>
                                </tr>
                                <tr>
                                    <td>Supervisor Number</td>
                                    <td><?php echo $formData['supervisor_number']; ?></td>
                                </tr>
                                <tr>
                                    <td>Location</td>
                                    <td><a href="view_location.php?lat=<?php echo $formData['latitude']; ?>&lng=<?php echo $formData['longitude']; ?>">View Location</a></td>
                                </tr>
                                <tr>
                                    <td>Submitted Time</td>
                                    <td><?php echo $formData['uploaded_at']; ?></td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="center-message">
                            <p>No form data found for the student.</p>
                        </div>
                    <?php endif; ?>

                    <!-- Student Logbook -->
                    <?php if ($logbookResult->num_rows > 0): ?>
                        <div class="data-table">
                            <table class="table table-bordered">
                                <thead>
                                    <tr><th colspan="3">Student Logbook</th></tr>
                                    <tr><th>Date</th><th>Workspace</th><th>Submitted Time</th></tr>
                                </thead>
                                <tbody>
                                    <?php while ($logbookRow = $logbookResult->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo $logbookRow['date']; ?></td>
                                            <td><?php echo $logbookRow['workspace']; ?></td>
                                            <td><?php echo $logbookRow['uploaded_at']; ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="center-message">
                            <p>No logbook data found for the student.</p>
                        </div>
                    <?php endif; ?>

                    <!-- Student Report -->
                    <?php if ($reportResult->num_rows > 0): ?>
                        <div class="data-table">
                            <table class="table table-bordered">
                                <thead>
                                    <tr><th colspan="4">Student Report</th></tr>
                                    <tr><th>Week Number</th><th>Works</th><th>Problems</th><th>Submitted Time</th></tr>
                                </thead>
                                <tbody>
                                    <?php while ($reportRow = $reportResult->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo $reportRow['week_number']; ?></td>
                                            <td><?php echo $reportRow['works']; ?></td>
                                            <td><?php echo $reportRow['problems']; ?></td>
                                            <td><?php echo $reportRow['uploaded_at']; ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="center-message">
                            <p>No report data found for the student.</p>
                        </div>
                    <?php endif; ?>

                    <!-- Student Files -->
                    <?php if ($fileResult->num_rows > 0): ?>
                        <div class="data-table">
                            <table class="table table-bordered">
                                <thead>
                                    <tr><th colspan="3">Student Files</th></tr>
                                    <tr><th>File Name</th><th>Submitted Time</th><th>Action</th></tr>
                                </thead>
                                <tbody>
                                    <?php while ($fileRow = $fileResult->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo $fileRow['file_name']; ?></td>
                                            <td><?php echo $fileRow['time_submitted']; ?></td>
                                            <td><a href="view_file.php?id=<?php echo $fileRow['id']; ?>">Download</a></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="center-message">
                            <p>No files found for the student.</p>
                        </div>
                    <?php endif; ?>

                    <!-- Message Form -->
                    <div class="message-form">
                        <h2>Comments</h2>
                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="message">Message:</label>
                                <textarea name="message" id="message" class="form-control" rows="5" required></textarea>
                            </div>
                            <button type="submit" name="sendMessage" class="btn btn-primary">Send Message</button>
                        </form>
                    </div>

                </div>
            </section>
        </div>
        <!-- /.content-wrapper -->

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/plugins/jquery/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js"></script>
</body>
</html>
