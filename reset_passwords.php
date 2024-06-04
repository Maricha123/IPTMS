<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <style>
        body {
            background-color: #f0f0f0;
        }
        .container {
            margin-top: 50px;
            width: 400px;
            margin-left: auto;
            margin-right: auto;
        }
        .form-group input {
            border: 2px solid #0eacb8;
            border-radius: 5px;
            padding: 10px;
            width: 100%;
        }
        .btn-primary {
            background-color: #0eacb8;
            border-color: #0eacb8;
            transition: background-color 0.3s, border-color 0.3s;
            margin-top: 20px;
        }
        .btn-primary:hover {
            background-color: #0b8d94;
            border-color: #0b8d94;
        }
    </style>
</head>
<body>
    <div class="container">
        <form action="reset_password.php" method="post" class="card p-4">
            <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">
            <div class="form-group">
                <label for="password">New Password:</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
        </form>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js"></script>
</body>
</html>
