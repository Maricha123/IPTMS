<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Form Dashboard</title>
    <link rel="stylesheet" href="form_style.css">
</head>
<body>
    
    <div class="dashboard">
        <!-- Add a new div for displaying current time, date, and day -->
        <div class="time-section">
            <p id="currentDateTime"></p>
        </div>

        <marquee behavior="alternate" direction="left" color="Green"><h1 style="color: rgb(40, 6, 83)">Student Arrival Form</h1></marquee>
        
        <form id="studentForm" action="submits.php" method="post" enctype="multipart/form-data">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required><br>

            <label for="regNo">Registration Number:</label>
            <input type="text" id="regNo" name="regNo" required><br>

            <label for="academicYear">Academic Year:</label>
            <input type="text" id="academicYear" name="academicYear" required><br>

            <label for="region">Region:</label>
            <select id="region" name="region" required>
                <option value="" disabled selected>Select Region</option>
                <?php
                    include 'db.php';
                    $sql = "SELECT * FROM regions";
                    $result = $conn->query($sql);
                    while($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['region_name'] . "'>" . $row['region_name'] . "</option>";
                    }
                    $conn->close();
                ?>
            </select><br>

            <label for="district">District:</label>
            <input type="text" id="district" name="district" required><br>

            <label for="organization">Organization:</label>
            <input type="text" id="organization" name="organization" required><br>

            <label for="supervisorName">Onsite Supervisor Name:</label>
            <input type="text" id="supervisorName" name="supervisorName" required><br>

            <label for="supervisorNo">Onsite Supervisor Number:</label>
            <input type="text" id="supervisorNo" name="supervisorNo" required><br>


            <button type="submit">Submit</button>
            <button type="back-button" onclick="goBack()">Back
            </buttontype>

                <script>
                  function goBack() {
                    window.history.back();
                    }
                </script>

        </form>
        
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
    </div>
</body>
</html>
