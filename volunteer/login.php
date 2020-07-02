<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to dashboard
if(isset($_SESSION["volunteer_loggedin"]) && $_SESSION["volunteer_loggedin"] === true){
    header("location: dashboard.php");
    exit;
}

// Include config file
require_once "../config.php";
require_once "../classes/VolunteerLogin.php";

// Google Sign-in API


// Define variables and initialize with empty values
$username = "";
$password = "";
$username_error = "";
$password_error = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Instatiate SponsorLogin object
    $obj = new VolunteerLogin();

    // Set username
    $username = trim($_POST["username"]);
    $username_error = $obj->setUsername($username);

    // Set password
    $password = trim($_POST["password"]);
    $password_error = $obj->setPassword($password);

    if(empty($username_error) && empty($password_error)) {
        $status = $obj->login();
        if(!$status) {
          $password_error = "The password you entered was not valid.";
        }
        else {
          // Start a new session
          if(session_status() !== PHP_SESSION_ACTIVE) session_start();

          // Store data in session variables
          $_SESSION["volunteer_loggedin"] = true;
          $_SESSION["volunteer_id"] = $obj->getVolunteerId();
          $_SESSION["username"] = $username;
          $_SESSION["first_name"] = $obj->getFirstName();
          $_SESSION["last_name"] = $obj->getLastName();
          $_SESSION["graduation_year"] = $obj->getGraduationYear();

          // Redirect user to dashboard
          if(!headers_sent()) header("location: dashboard.php");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Volunteer Login</title>

        <!--Load required libraries-->
        <?php $pageContent='Form'?>
        <?php include '../head.php'?>

    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Volunteer Login</h2>

        <?php require 'google-oauth.php';?>

        <p>Please fill in your credentials to login.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_error)) ? 'has-error' : ''; ?>">
                <label>Email Address</label>
                <input type="email" name="username" class="form-control" value="<?php echo $username; ?>" placeholder="Email">
                <span class="help-block"><?php echo $username_error; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($password_error)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="Password">
                <span class="help-block"><?php echo $password_error; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
        </form>
    </div>
    <?php include '../footer.php';?>
</body>
</html>
