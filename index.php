<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN - INDUSTRIAL PRACTICAL TRAINING MANAGEMENT SYSTEM(IPTMS)</title>
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
            <h3 style="text-align: center;">Welcome to <b>IPTMS</b></h3>
            <p>Our aim is to simplify the whole process of supervision.</p>

            <p>This platform will enable to:</p>
            <ul>
                <li>Fill the arrival.</li>
                <li>Connect students and their respective supervisors.</li>
                <li>Fill a daily logbook & report for reference.</li>
                <li>Allow sharing of files (documents and images) between a student and supervisor.</li>
                <li>Supervisors to see all students details.</li>
                <li>Admin should be able to assign supervisor the area to supervisor.</li>
            </ul>

            <p style="text-align: center;">Thank you!</p>
        </div>

        <div class="login-container">
           
            <form action="logins_process.php" method="post" class="container">
                <H3>INSTITUTE OF ACCOUNTANCE ARUSHA</H3>
                <div class="" style="width: 100%;display: flex;justify-content: center;">
                    <img src="iptms.png" alt="" style="width:100px;height:100px"> <br>
                </div>
                <div style="padding: 10px;width:100%;">
                    <p style="color: red;">
                    <?PHP
                    if (!empty( $_GET["message"])) {
                        
                        echo $_GET["message"]; 
                    }
                    ?>
                    </p>
                </div>
                <!-- <h3>LOGIN</h3> -->
                <div class="input-container">
                    <input type="email" id="email" name="email" placeholder="Enter Email" required style="border-radius: 10px;">
                    <i class="fa fa-envelope"></i>
                </div>
                <div class="input-container">
                    <input type="password" id="password" name="password" class="input" placeholder="Enter password" required style="border-radius: 10px;">
                    <i class="fa fa-lock"></i>
                </div>

                <div class="remember-me">
                    <input type="checkbox" style="width: auto; margin:7px;" id="rememberMe" name="rememberMe">
                    <label for="rememberMe">Remember Me</label>
                </div>

                <button type="submit" class="buttons" style="border-radius: 10px;">Login</button>
                <!-- Inside your existing form in login.html -->
                <a href="forgotPassword.php" class="buttons" style="margin-bottom: 10px; margin-top: 10px;">Forgot your password</a>
               <p style="margin:0">Dont have an account? 
                <a href="reg.php"  >Register</a>
            </p>

                <!-- <p class="buttons">Are you a supervisor/Admin?<br>
                    <a href="suplogin.html" class="buttons">Click here to log in</a>
                </p> -->
            </form>
        </div>
    </div>

    <div id="foot">
        <h3><b><p><i>IPTMS &copy; 2024</i></p></b></h3>
    </div>
    
</body>
</html>

