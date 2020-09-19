<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["sponsor_loggedin"]) || $_SESSION["sponsor_loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "../classes/SponsorAccountUpdate.php";
$sponsor_id = $_SESSION["sponsor_id"];
$obj = new SponsorAccountUpdate($sponsor_id);

// Define variables and initialize with empty values
$new_password = "";
$confirm_password = "";

$new_password_error = "";
$confirm_password_error = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Set password
    $new_password = trim($_POST["new_password"]);
    $new_password_error = $obj->setPassword($new_password);

    // Set confirm_password
    $confirm_password = trim($_POST["confirm_password"]);
    $confirm_password_error = $obj->setConfirmPassword($confirm_password);

    // Check input errors before updating the database
    if(empty($new_password_error) && empty($confirm_password_error)){

        if($obj->updatePassword()) {
            session_destroy();
            header("location: login.php");
            exit();
        }
        else {
            echo "Oops! Something went wrong. Please try again later.";
        }

    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>

        <!--Load required libraries-->
        <?php include '../head.php'?>

    <style type="text/css">
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <?php $thisPage='Account'; include 'navbar.php';?>
    <div class="wrapper">
        <h2>Reset Password</h2>
        <p>Please fill out this form to reset your password.</p>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <!-- New Password -->
            <div class="form-group <?php echo (!empty($new_password_error)) ? 'has-error' : ''; ?>">
                <label>New Password</label>
                <input type="password" name="new_password" class="form-control" placeholder="New Password" value="<?php echo $new_password; ?>">
                <span class="help-block"><?php echo $new_password_error; ?></span>
            </div>

            <!-- Confirm Password -->
            <div class="form-group <?php echo (!empty($confirm_password_error)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password">
                <span class="help-block"><?php echo $confirm_password_error; ?></span>
            </div>
            
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <a class="btn btn-link" href="account.php">Cancel</a>
            </div>
        </form>
    </div>
    <?php include '../footer.php';?>
</body>
</html>
