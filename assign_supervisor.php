<?php
session_start();

// Ensure the user is logged in before accessing the page
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['assign_supervisor'])) {
    $supervisor_id = $_POST['supervisor_id'];
    $region_id = $_POST['region_id'];
    $district_ids = $_POST['district_ids'];

    try {
        // Update supervisors table with region_id
        $sql_update_supervisor = "UPDATE supervisors SET region_id = ? WHERE supervisor_id = ?";
        $stmt_update = $conn->prepare($sql_update_supervisor);
        $stmt_update->bind_param("ii", $region_id, $supervisor_id);
        $stmt_update->execute();

        // Clear existing entries in supervisor_districts for this supervisor
        $sql_delete_districts = "DELETE FROM supervisor_districts WHERE supervisor_id = ?";
        $stmt_delete = $conn->prepare($sql_delete_districts);
        $stmt_delete->bind_param("i", $supervisor_id);
        $stmt_delete->execute();

        foreach ($district_ids as $district_id) {
            // Check if the assignment already exists
            $check_sql = "SELECT * FROM supervisor_districts WHERE supervisor_id = ? AND district_id = ?";
            $check_stmt = $conn->prepare($check_sql);
            $check_stmt->bind_param("ii", $supervisor_id, $district_id);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();

            if ($check_result->num_rows == 0) {
                // Insert assignment
                $insert_sql = "INSERT INTO supervisor_districts (supervisor_id, district_id) VALUES (?, ?)";
                $insert_stmt = $conn->prepare($insert_sql);
                $insert_stmt->bind_param("ii", $supervisor_id, $district_id);

                if ($insert_stmt->execute()) {
                    echo "<script>alert('Successfully assigned');</script>";
                } else {
                    echo "Error: " . $insert_stmt->error;
                }

                $insert_stmt->close();
            } else {
                echo "<script>alert('Supervisor is already assigned to district ID: $district_id');</script>";
            }

            $check_stmt->close();
        }

        // Redirect to manage supervisor page
        header("location: manage supervisor.php");
        exit();

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
