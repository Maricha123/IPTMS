<?php
session_start();

// Ensure the user is logged in before accessing the page
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Database connection
$servername = "localhost"; // Your server name
$username = "root"; // Your database username
$password = ""; // Your database password
$database = "project2"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch message details based on message ID
$messageId = $_GET['messageId'];
$sql = "SELECT * FROM messages WHERE id = '$messageId'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output full message
    $row = $result->fetch_assoc();
    echo '<div class="dropdown-item">';
    echo '<strong>' . date('Y-m-d H:i', strtotime($row['sent_at'])) . ':</strong>';
    echo htmlspecialchars($row['message']);
    echo '</div>';
} else {
    echo 'Message not found';
}

$conn->close();
?>
