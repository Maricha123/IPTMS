<?php
session_start();

include 'db.php';

if (isset($_GET['student_id'])) {
    $UserID = $_GET['student_id'];

    // Queries to retrieve data from the database
    // Using prepared statements to prevent SQL injection
    $formQuery = "
        SELECT sf.*, r.region_name, d.district_name 
        FROM student_form sf
        LEFT JOIN regions r ON sf.region = r.region_id
        LEFT JOIN districts d ON sf.district = d.district_id
        WHERE sf.UserID = ?";
    $formStmt = $conn->prepare($formQuery);
    $formStmt->bind_param("i", $UserID);
    $formStmt->execute();
    $formResult = $formStmt->get_result();
    $formData = $formResult->fetch_assoc();

    $logbookQuery = "SELECT * FROM Logbooks WHERE UserID = ?";
    $logbookStmt = $conn->prepare($logbookQuery);
    $logbookStmt->bind_param("i", $UserID);
    $logbookStmt->execute();
    $logbookResult = $logbookStmt->get_result();

    $reportQuery = "SELECT * FROM Reports WHERE UserID = ?";
    $reportStmt = $conn->prepare($reportQuery);
    $reportStmt->bind_param("i", $UserID);
    $reportStmt->execute();
    $reportResult = $reportStmt->get_result();

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
            echo "<script>
            alert('submitted successfuly!');
          </script>";

        } else {
            echo "<p>Failed to send message.</p>";
        }
    }

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
            <a href="supdash.php" class="brand-link">
                <span class="brand-text font-weight-light">Home</span>
            </a>
            <div class="sidebar">
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-item">
                            <a href="view_locations.php" class="nav-link">
                                <i class="nav-icon"></i>
                                <p>Students Locations</p>
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
                                   <td><?php echo htmlspecialchars($formData['name']); ?></td>
                                </tr>
                                <tr>
                                   <td>Registration Number</td>
                                   <td><?php echo htmlspecialchars($formData['registration_number']); ?></td>
                                </tr>
                                <tr>
                                   <td>Academic Year</td>
                                   <td><?php echo htmlspecialchars($formData['academic_year']); ?></td>
                                </tr>
                                <tr>
                                    <td>Region</td>
                                    <td><?php echo htmlspecialchars($formData['region_name']); ?></td>
                                </tr>
                                <tr>
                                    <td>District</td>
                                    <td><?php echo htmlspecialchars($formData['district_name']); ?></td>
                                </tr>
                                <tr>
                                    <td>Organization</td>
                                    <td><?php echo htmlspecialchars($formData['organization']); ?></td>
                                </tr>
                                <tr>
                                    <td>Supervisor Name</td>
                                    <td><?php echo htmlspecialchars($formData['supervisor_name']); ?></td>
                                </tr>
                                <tr>
                                    <td>Supervisor Number</td>
                                    <td><?php echo htmlspecialchars($formData['supervisor_number']); ?></td>
                                </tr>
                                
    <td>Location</td>
    <td><a href="view_location.php?lat=<?php echo $formData['latitude']; ?>&lng=<?php echo $formData['longitude']; ?>&name=<?php echo urlencode($formData['name']); ?>">View Location</a></td>
</tr>

                                </tr>
                                <tr>
                                    <td>Submitted Time</td>
                                    <td><?php echo htmlspecialchars($formData['uploaded_at']); ?></td>
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
                <tr><th>Date</th><th>Submitted Time</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php while ($logbookRow = $logbookResult->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($logbookRow['date']); ?></td>
                        <td><?php echo htmlspecialchars($logbookRow['uploaded_at']); ?></td>
                        <td>
                        <a href="see_studentlogbook.php?logbook_id=<?php echo $logbookRow['logbook_id']; ?>" class="btn btn-info btn-sm">View</a>
                            <a href="download_logbook.php?logbook_id=<?php echo $logbookRow['logbook_id']; ?>" class="btn btn-primary btn-sm">Download</a>
                        </td>
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
                <tr><th colspan="3">Student Report</th></tr>
                <tr><th>Week Number</th><th>Submitted Time</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php while ($reportRow = $reportResult->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($reportRow['week_number']); ?></td>
                        <td><?php echo htmlspecialchars($reportRow['uploaded_at']); ?></td>
                        <td>
                        <a href="see_studentreport.php?report_id=<?php echo $reportRow['report_id']; ?>" class="btn btn-info btn-sm">View</a>
                            <a href="download_report.php?report_id=<?php echo $reportRow['report_id']; ?>" class="btn btn-primary btn-sm">Download</a>
                        </td>
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

<script>
    function viewLogbookDetails(student_id) {
        // Example AJAX call to fetch and display logbook details
        alert("Implement AJAX call to fetch and display logbook details for student ID: " + student_id);
    }

    function viewReportDetails(student_id) {
        // Example AJAX call to fetch and display report details
        alert("Implement AJAX call to fetch and display report details for student ID: " + student_id);
    }
</script>



                    <!-- Student Files -->
                    <!-- Student Files -->
<?php if ($fileResult->num_rows > 0): ?>
    <div class="data-table">
        <table class="table table-bordered">
            <thead>
                <tr><th colspan="3">Student Files</th></tr>
                <tr><th>File Name</th><th>Submitted Time</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php while ($fileRow = $fileResult->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($fileRow['file_name']); ?></td>
                        <td><?php echo htmlspecialchars($fileRow['time_submitted']); ?></td>
                        <td>
                            <a href="see_studentdoc.php?id=<?php echo $fileRow['id']; ?>" class="btn btn-info btn-sm">View</a>
                            <a href="view_file.php?id=<?php echo $fileRow['id']; ?>" class="btn btn-primary btn-sm">Download</a>
                        </td>
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

<!-- Add this script section in your HTML -->
<script>
    function viewLogbookDetails(student_id) {
        fetch('fetch_logbook_details.php?student_id=' + student_id)
            .then(response => response.json())
            .then(data => {
                // Example: Display logbook details in a modal or another section
                console.log(data); // Output the data to console for testing
                // Replace alert with actual code to display data
                alert('Logbook details retrieved. Check console for details.');
            })
            .catch(error => {
                console.error('Error fetching logbook details:', error);
                alert('Failed to fetch logbook details. Check console for error.');
            });
    }

    function viewReportDetails(student_id) {
        fetch('fetch_report_details.php?student_id=' + student_id)
            .then(response => response.json())
            .then(data => {
                // Example: Display report details in a modal or another section
                console.log(data); // Output the data to console for testing
                // Replace alert with actual code to display data
                alert('Report details retrieved. Check console for details.');
            })
            .catch(error => {
                console.error('Error fetching report details:', error);
                alert('Failed to fetch report details. Check console for error.');
            });
    }
</script>
