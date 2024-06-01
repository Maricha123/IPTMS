<?php
session_start();
include 'db.php';

if (isset($_POST['register'])) {
    $supervisor_name = $_POST['supervisor_name'];
    $supervisor_email = $_POST['supervisor_email'];

    // Check if the supervisor with the same email already exists
    $check_sql = "SELECT * FROM supervisors WHERE supervisor_email = '$supervisor_email'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows == 0) {
        // Generate random password
        $temp_password = generateRandomPassword();

        // Hash the temporary password
        $hashed_password = password_hash($temp_password, PASSWORD_DEFAULT);

        // Insert the supervisor into the database
        $sql = "INSERT INTO supervisors (supervisor_name, supervisor_email, password) VALUES ('$supervisor_name', '$supervisor_email', '$hashed_password')";
        if ($conn->query($sql) === TRUE) {
            // Send email with temporary password
            $to = $supervisor_email;
            $subject = 'Your Temporary Password';
            $message = 'Hello ' . $supervisor_name . ',<br><br>Your temporary password is: ' . $temp_password . '<br><br>Please login using this password and change it immediately.<br><br>Thank you.';
            $headers = 'From: swalehemmary8991@gmail.com' . "\r\n" .
                'Content-Type: text/html; charset=UTF-8' . "\r\n" .
                'MIME-Version: 1.0' . "\r\n" .
                'Reply-To: swalehemmary8991@gmail.com' . "\r\n" .
                'X-Mailer: PHP/' . phpversion();

            mail($to, $subject, $message, $headers);

            echo "Supervisor registered successfully. Temporary password sent to email.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Supervisor with this email already exists!";
    }
}

$conn->close();

// Function to generate random password
function generateRandomPassword($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $password;
}
?>
