<?php

// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to dashboard
if(isset($_SESSION["admin_loggedin"]) && $_SESSION["admin_loggedin"] === true){
    header("location: dashboard.php");
    exit;
}

// Include config file
require_once "../classes/AdminLogin.php";

// Define variables and initialize with empty values
$username = "";
$password = "";
$username_error = "";
$password_error = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST") {

    // Instatiate AdminLogin object
    $obj = new AdminLogin();
    
    // Set username
    $username = trim($_POST["username"]);
    $username_error = $obj->setUsername($username);

    // Set password
    $password = trim($_POST["password"]);
    $password_error = $obj->setPassword($password);

    if(empty($username_error) && empty($password_error)){
        $status = $obj->login();
        if(!$status) {
          $password_error = "The password you entered was not valid.";
        }
        else {
          // Start a new session
          if(session_status() !== PHP_SESSION_ACTIVE) session_start();

          // Set session variables
          $_SESSION["admin_loggedin"] = true;
          $_SESSION["username"] = $username;
          $_SESSION["admin_id"] = $obj->getAdminId();
          $_SESSION["admin_name"] = $obj->getAdminName();

          // Redirect user to dashboard
          header("location: dashboard.php");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>

        <!--Load required libraries-->
        <?php $pageContent='Form'?>
        <?php include '../head.php'?>

    <style type="text/css">
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Admin Login</h2>
        <p>Please fill in your credentials to login.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_error)) ? 'has-error' : ''; ?>">
                <label>Email Address</label>
                <input type="email" name="username" class="form-control" placeholder="Email" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_error; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($password_error)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" placeholder="Password" class="form-control">
                <span class="help-block"><?php echo $password_error; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Don't have an account? <a href="#">Too bad.</a>.</p>
        </form>
    </div>
</body>
</html>
