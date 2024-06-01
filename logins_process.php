<?php
session_start(); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    include 'db.php';

    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $stmt = $conn->prepare("SELECT UserID, Email, Password, Role, PasswordChanged FROM Users WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['Password'];
        $password_changed = $row['PasswordChanged'];

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $row['UserID'];
            $_SESSION['user_email'] = $row['Email'];

            if ($row['Role'] == 'student') {
                header("Location: homee.php");
                exit();
            } elseif ($row['Role'] == 'supervisor') {
                if ($password_changed == 0) {
                    // Redirect to password change page
                    $_SESSION['change_password'] = true;
                    header("Location: change_password.php");
                    exit();
                } else {
                    // Normal dashboard
                    header("Location: supdash.php");
                    exit();
                }
            } elseif ($row['Role'] == 'admin') {
                header("Location: admin.php");
                exit();
            } else {
                echo "Wrong Credentials";
            }
        } else {
            // Set your message
               $message = 'Wrong Credential!';

               // Redirect to another page with the message
               header("Location: index.php?message=$message");
               exit();

        }
    } else {
        $message = 'Wrong Credential!';

        // Redirect to another page with the message
        header("Location: index.php?message=$message");
        exit();
    }

    // Close prepared statement
    $stmt->close();
    $conn->close();
}
?>
