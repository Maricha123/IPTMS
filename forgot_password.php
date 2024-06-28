<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';
require 'vendor/autoload.php';


// forgot_password.php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
   
    // Database connection (update with your connection details)
    $conn = new mysqli("localhost", "root", "", "project2");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if email exists
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Generate unique token
        $token = bin2hex(random_bytes(12));
        $expiry = date("Y-m-d H:i:s", strtotime('+5 hour'));
        $resetLink = "http://localhost/IPTMS/reset_passwords.php?token=" . $token;

        // Insert token into password_resets table
        $sql = "INSERT INTO password_resets (email, token, expiry) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $email, $token, $expiry);
        $stmt->execute();
        
        
        $mail = new PHPMailer(true);
        
        try {
            /// Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'swalehemmary8991@gmail.com'; // SMTP username
            $mail->Password   = 'fgxlrggfdboivktz';       // SMTP password
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;
        
            //Recipients
            $mail->setFrom('swalehemmary8991@gmail.com', 'Mailer');
            $mail->addAddress($email);                // Add a recipient
        
            // Content
            $mail->isHTML(true);                      // Set email format to HTML
            $mail->Subject = 'Password Reset';
            $mail->Body    = "Click the link to reset your password: " . $resetLink;
        
            $mail->send();
            echo "<script>
                alert('massage sent to your email!');
                window.location.href = 'index.php';
              </script>";
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
       
        
    } else {
        echo "Email does not exist.";
    }

    $stmt->close();
    $conn->close();
}
