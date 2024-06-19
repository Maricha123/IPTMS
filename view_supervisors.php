<?php
session_start();
include 'db.php';

// Fetch supervisors with their assigned regions, districts, and emails
$sql = "SELECT supervisors.supervisor_id, supervisors.supervisor_name, supervisors.supervisor_email, supervisors.year, supervisors.contact, regions.region_name, 
               GROUP_CONCAT(districts.district_name SEPARATOR ', ') AS assigned_districts
        FROM supervisors 
        LEFT JOIN regions ON supervisors.region_id = regions.region_id
        LEFT JOIN supervisor_districts ON supervisors.supervisor_id = supervisor_districts.supervisor_id
        LEFT JOIN districts ON supervisor_districts.district_id = districts.district_id
        LEFT JOIN users ON supervisors.supervisor_email = users.Email
        GROUP BY supervisors.supervisor_id";

$result = $conn->query($sql);

$supervisor_regions = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $supervisor_regions[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Supervisors</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <style>
        .dark-mode {
            background-color: #333;
            color: #fff;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="admin.php" class="nav-link">Home</a>
                </li>
            </ul>
        </nav>

        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="" class="brand-link">
                <span class="brand-text font-weight-light">View Supervisors</span>
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
                            <h1 class="m-0">Supervisors and Their Assigned Regions and Districts</h1>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Supervisor</th>
                                                <th>Email</th>
                                                <th>Year</th>
                                                <th>Contact</th>
                                                <th>Region</th>
                                                <th>Districts</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($supervisor_regions as $supervisor_region): ?>
                                                <tr>
                                                    <td><?php echo $supervisor_region['supervisor_name']; ?></td>
                                                    <td><?php echo $supervisor_region['supervisor_email']; ?></td>
                                                    <td><?php echo $supervisor_region['year']; ?></td>
                                                    <td><?php echo $supervisor_region['contact']; ?></td>
                                                    <td><?php echo $supervisor_region['region_name'] ?? 'Not Assigned'; ?></td>
                                                    <td><?php echo $supervisor_region['assigned_districts'] ?? 'Not Assigned'; ?></td>
                                                    <td>
                                                        <form method="post" action="delete_supervisor.php" onsubmit="return confirm('Are you sure you want to delete this supervisor?');">
                                                            <input type="hidden" name="supervisor_id" value="<?php echo $supervisor_region['supervisor_id']; ?>">
                                                            <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Delete</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
</body>
</html>
