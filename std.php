<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="styl.css">
</head>
<body>
    <div class="caption">
        Our aim is to simplify the whole process of finding places to conduct field work as well as being supervised.</p> <br><p>This platform enables students to:
            <li>prepare report for every week.</li>
            <li>fill logbook everyday.</li>
            <li>send IPT forms.</li>
        <p>Thank you!</p>
    </div>
    <div class="dashboard">
        <!-- Add a new div for displaying current time, date, and day -->
        <div class="time-section">
            <p id="currentDateTime"></p>
        </div>

        <div class="section" id="studentFormSection">
            <h2>Student Form</h2>
            <form id="studentForm" action="submits.php" method="post">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" requiredtype="text" autocomplete="given-name" ><br>
                

                <label for="regNo">Registration Number:</label>
                <input type="text" id="regNo" name="regNo" required type="text" autocomplete="given-regno"><br>


                <label for="academicYear">Academic Year:</label>
                <input type="text" id="academicYear" name="academicYear" required type="text" autocomplete="given-year"><br>

                <label for="region">Region:</label>
                <input type="text" id="region" name="region" required type="text" autocomplete="given-region"><br>

                <label for="district">District:</label>
                <input type="text" id="district" name="district" required type="text" autocomplete="given-district"><br>
 
                <label for="organization">Organization:</label>
                <input type="text" id="organization" name="organization" required type="text" autocomplete="given-organization"><br>

                <label for="supervisorName">Onsite Supervisor Name:</label>
                <input type="text" id="supervisorName" name="supervisorName" required><br>

                <label for="supervisorNo">Onsite Supervisor Number:</label>
                <input type="text" id="supervisorNo" name="supervisorNo" required><br>
                <button type="submit">Submit</button>
            </form>
        </div>

        <div class="section" id="logbookSection">
            <h2>Logbook</h2>
            <form id="logbookForm" action="submits.php" method="post">
                <label for="date">Date:</label>
                <input type="date" id="date" name="date" required><br>

                <label for="workspace">Workspace:</label>
                <textarea id="workspace" name="workspace" rows="4" required></textarea><br>
                <button type="submit">Submit</button>
            </form>
        </div>

        <div class="section" id="reportSection">
            <h2>Report</h2>
            <form id="reportForm" action="submits.php" method="post">
                <label for="weekNo">Week Number:</label>
                <input type="text" id="weekNo" name="weekNo" required><br>

                <label for="work">Works:</label>
                <textarea id="work" name="work" rows="4" required></textarea><br>

                <label for="problems">Problems:</label>
                <textarea id="problems" name="problems" rows="4" required></textarea><br>

                <button type="submit">Submit</button>
            </form>
        </div>
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
