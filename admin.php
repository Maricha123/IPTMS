<?php
session_start();
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
    // Output data of each row
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

// Function to generate random password
function generateRandomPassword($length = 3) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $password;
}

$conn->close();
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="supdash.css"> 
    <style>
        ._head{
            display: flex;
            flex-direction: row;
            width: 100%;
            justify-content: space-between;
        }
        .total-students {
            display: inline-block;
            border-radius: 20px;
            background-color: rgb(77, 155, 207); 
            color: white;
            height: fit-content;
            text-align: center;
            padding: 10px;
        }
        .total-student {
            display: inline-block;
            border-radius: 20px;
            background-color: rgb(77, 155, 207); 
            color: white;
            height: fit-content;
            text-align: center;
            padding: 10px;
            margin-left: 10%;
        }
    </style>
    
</head>
<body>
    <div class="container">
        <div class="caption"></div>
        <div class="all">
            <h2 style="text-align:center">Welcome <?php echo $username; ?></h2>
            <div class="_head">
                <h2><u>Add Region</u></h2>
                <div class="total-students">Total regions: <?php echo $total_regions; ?></div>
            </div>
            <form action="" method="post">
                <input type="text" name="region_name" placeholder="Enter region name" required>
                <button type="submit" name="add_region" style="background-color: rgb(77, 155, 207)">Add Region</button>
            </form>
          
            <div class="caption"></div>
          
            <div class="_head">
                <h2><u>Add Supervisor</u></h2>
                <div class="total-students">Total supervisors: <?php echo $total_supervisors; ?></div>
            </div>
            <!-- Form to add supervisor -->
            <form action="" method="post">
                <input type="text" name="supervisor_name" placeholder="Enter supervisor name" required>
                <input type="email" name="supervisor_email" placeholder="Enter supervisor email" required>
                <button type="submit" name="add_supervisor" style="background-color: rgb(77, 155, 207)">Add Supervisor</button>
            </form>
          
            <div class="caption"></div>
          
            <div class="all">
                <h2><u>Assign Supervisor to Region</u></h2>
                <form action="" method="post">
                    <select name="supervisor_id" required>
                        <option value="" disabled selected>Select Supervisor</option>
                        <?php while($row = $result_supervisor_options->fetch_assoc()): ?>
                            <option value="<?php echo $row['supervisor_id']; ?>"><?php echo $row['supervisor_name']; ?></option>
                        <?php endwhile; ?>
                    </select>
                    <select name="region_id" required>
                        <option value="" disabled selected>Select Region</option>
                        <?php while($row = $result_region_options->fetch_assoc()): ?>
                            <option value="<?php echo $row['region_id']; ?>"><?php echo $row['region_name']; ?></option>
                        <?php endwhile; ?>
                    </select>
                    <button type="submit" name="assign_supervisor" style="background-color: rgb(77, 155, 207)">Assign Supervisor</button>
                    <div class="total-student"><a href="view_supervisors.php" style="color:aliceblue"> Supervisors & Assigned Regions</a></div>
                </form>
            </div>
          
            <div class="caption"></div>
        </div>
    </div>
</body>
</html>

<?php
include 'db.php';

// Check if the form for adding a region is submitted
if(isset($_POST['add_region'])) {
    $region_name = $_POST['region_name'];
    // Check if the region already exists
    $check_sql = "SELECT * FROM regions WHERE region_name = '$region_name'";
    $check_result = $conn->query($check_sql);
    if($check_result->num_rows == 0) {
        // Insert the region into the database
        $sql = "INSERT INTO regions (region_name) VALUES ('$region_name')";
        if ($conn->query($sql) === TRUE) {
            header("location: admin.php");
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "<script>alert('Region already exists!');</script>";
    }
}

// Check if the form for adding a supervisor is submitted
if(isset($_POST['add_supervisor'])) {
    $supervisor_name = $_POST['supervisor_name'];
    $supervisor_email = $_POST['supervisor_email'];
    if($check_result->num_rows == 0) {
        // Generate random password
        $temp_password = generateRandomPassword();

        // Hash the temporary password
        $hashed_password = password_hash($temp_password, PASSWORD_DEFAULT);
        
        // Insert the supervisor into the users table with role as supervisor
        $sql = "INSERT INTO users (Name, Email, Password, Role) VALUES ('$supervisor_name', '$supervisor_email', '$hashed_password', 'supervisor')";
        if ($conn->query($sql) === TRUE) {
            // Also insert the supervisor into the supervisors table
            $sql_supervisor = "INSERT INTO supervisors (supervisor_name, supervisor_email) VALUES ('$supervisor_name', '$supervisor_email')";
            if ($conn->query($sql_supervisor) === TRUE) {
                // Send email with temporary password
                $mail = new PHPMailer(true);
                try {
                    // Server settings
                    $mail->isSMTP();
                    $mail->Host       = 'smtp.gmail.com';
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'swalehemmary8991@gmail.com'; // SMTP username
                    $mail->Password   = 'fgxlrggfdboivktz';       // SMTP password
                    $mail->SMTPSecure = 'tls';
                    $mail->Port       = 587;

                    // Recipient
                    $mail->setFrom('swalehemmary8991@gmail.com', 'Admin');
                    $mail->addAddress($supervisor_email, $supervisor_name);

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = 'Your Temporary Password';
                    $mail->Body    = 'Hello ' . $supervisor_name . ',<br><br>Your temporary password is: ' . $temp_password . '<br><br>Please login using this password and change it immediately.<br><br>Thank you.';

                    $mail->send();
                    echo 'Supervisor registered successfully. Temporary password sent to email.';
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
                header("location: admin.php");
            } else {
                echo "Error: " . $sql_supervisor . "<br>" . $conn->error;
            }
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "<script>alert('Supervisor with this email already exists!');</script>";
    }
}

// Check if the form for assigning a supervisor to a region is submitted
if(isset($_POST['assign_supervisor'])) {
    $supervisor_id = $_POST['supervisor_id'];
    $region_id = $_POST['region_id'];
    // Check if the supervisor is already assigned to the selected region
    $check_sql = "SELECT * FROM supervisors WHERE supervisor_id = '$supervisor_id' AND region_id = '$region_id'";
    $check_result = $conn->query($check_sql);
    if($check_result->num_rows == 0) {
        // Update the supervisor's region in the database
        $sql = "UPDATE supervisors SET region_id = '$region_id' WHERE supervisor_id = '$supervisor_id'";
        if ($conn->query($sql) === TRUE) {
       
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
