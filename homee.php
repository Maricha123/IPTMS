
<?php
session_start();
// Database connection details
$servername = "localhost"; // or your server name
$username = "root"; // your database username
$password = ""; // your database password
$database = "project2"; // your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Assuming the user is already authenticated and their ID is stored in a session
// You should replace this with your authentication logic
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

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="homee.css">
</head>
<body>
    <div class="main">
        <div class="caption">
       <b><p> Hello <?php echo $username; ?>, Our aim is to make sure that you are able to:</p></b> 
        
            <h4>* Fill arrival form. <br> * Fill daily logbook & report. </h4>

    </div>

    <div class="inst"> 
        <marquee behavior="alternate" direction="left"><b><i style="color:red">*INSTRUCTIONS!*</i></u</b></marquee> 
            <h4>1. You have to fill the arrival form only once. 
            <br>2. Fill the arrival form when you are in the area of ipt.
            <br>3. You have to fill logbook averyday you attend in ipt area.
            <br>4. Submit the documents in logbook form or report form whenever neccessary.
        </h4>

    </div>  
    

    <!-- <div style="width: 10px;"></div> -->
    <div class="dashboard">
        <!-- Add a new div for displaying current time, date, and day -->
        <div class="time-section" >
           <b><p id="currentDateTime"></p></b> 
        </div>

    <ul>
        <li><a href="forms.php">Student Arrival Form</a></li>
        <li><a href="logbook.html">Logbook</a></li>
        <li><a href="report.html">Report</a></li>
    </ul>

    </div>
    
    <!-- Include JavaScript for updating current time, date, and day -->
    <script>
        function updateDateTime() {
            const currentDateTimeElement = document.getElementById('currentDateTime');
            const now = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric', second: 'numeric', timeZone: 'Africa/Nairobi' };

            // Display the time, date, and day
            const currentDateTime = new Date().toLocaleString('en-US', options);
            currentDateTimeElement.textContent = `${currentDateTime}`;
        }

        // Update current time, date, and day every second
        setInterval(updateDateTime, 1000);

        // Initial update
        updateDateTime();
    </script>
</body>
</html>
