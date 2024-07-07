<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REGISTER - FIELD PLACEMENT AND SUPERVISION SYSTEM (FPAS)</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <div class="heading">
        <h1>INDUSTRIAL PRACTICAL TRAINING MANAGEMENT SYSTEM</h1>
        <h2>(IPTMS)</h2>
    </div>

    <div class="main-container">
        <div class="details">
            <h3 style="text-align: center;"> Welcome to <b>IPTMS</b>.</h3>
            <p>Our aim is to simplify the whole process of supervision</p>
            <p>This platform enables students to:</p>
            <ul>
                <li>Fill the arrival form.</li>
                <li>Connect students and their respective supervisors.</li>
                <li>Fill a daily logbook &report for reference.</li>
                <li>Allow sharing of files (documents and images) between a student and supervisor.</li>
            </ul>
            <p style="text-align: center;">Thank you!</p>
        </div>

        <div class="login-container">
            <form action="register.php" method="post" class="container" onsubmit="return validateForm()">
                <h3>INSTITUTE OF ACCOUNTANCE ARUSHA</h3>
                <div style="width: 100%; display: flex; justify-content: center;">
                    <img src="iptms.png" alt="" style="width:120px;height:120px"><br>
                </div>
                <div style="width: 100%; padding: 10px;">
                    <p style="color:red;">
                        <?php if (!empty($_GET["message"])) { echo $_GET["message"]; } ?>
                    </p>
                </div>
                <div class="input-container">
                    <input type="email" id="email" name="email" placeholder="Enter Email" required style="border-radius: 10px;">
                    <i class="fa fa-envelope"></i>
                </div>
                <div class="input-container">
                    <input type="text" id="name" name="name" class="input" placeholder="Full Name" autocomplete="name" required style="border-radius: 10px;">
                    <i class="fa fa-user"></i>
                </div>
                <div class="input-container">
                    <input type="password" id="password" name="password" class="input" placeholder="Enter password" required style="border-radius: 10px;">
                    <i class="fa fa-lock"></i>
                </div>
                <div class="input-container">
                    <input type="password" id="confirm_password" name="confirm_password" class="input" placeholder="Confirm password" required style="border-radius: 10px;">
                    <i class="fa fa-lock"></i>
                </div>
                <button type="submit" class="buttons">Register</button>
                <a href="index.php" class="buttons" style="margin-top:10px;">Back to Login</a>
            </form>
        </div>
    </div>

    <div id="foot">
        <h3><b><p><i>IPTMS &copy; 2024</i></p></b></h3>
    </div>

    <script>
        function validateForm() {
            var password = document.getElementById("password").value;
            var confirmPassword = document.getElementById("confirm_password").value;
            if (password != confirmPassword) {
                alert("Passwords do not match.");
                return false;
            }
            return true;
        }
    </script>
</body>

</html>
