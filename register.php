<?php
session_start();

// Include the database connection
include 'db.php';

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $email = $_POST['email'];
    $password = $_POST['password'];
    $name = $_POST['name'];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if the email is already registered
    $stmt_check_email = $conn->prepare("SELECT * FROM Users WHERE Email = ?");
    $stmt_check_email->bind_param("s", $email);
    $stmt_check_email->execute();
    $result_check_email = $stmt_check_email->get_result();

    if ($result_check_email->num_rows > 0) {
        echo "<script>
        alert('User Already Exist!!');
        window.location.href = 'reg.php';
      </script>";
        exit();
    } else {
        // Determine the role based on the entered email
        $role = ($email === 'swalehemmary8991@gmail.com') ? 'admin' : 'student';

        // Insert user data into the Users table
        $stmt_register = $conn->prepare("INSERT INTO Users (Name, Email, Password, Role) VALUES (?, ?, ?, ?)");
        $stmt_register->bind_param("ssss", $name, $email, $hashed_password, $role);

        if ($stmt_register->execute()) {
            echo "<script>
            alert('Successfully Registered!!');
            window.location.href = 'index.php';
          </script>";
            exit();
        } else {
            echo "Error: " . $stmt_register->error;
        }

        // Close the statement
        $stmt_register->close();
    }

    // Close the email check statement and the database connection
    $stmt_check_email->close();
    $conn->close();
}
?>
