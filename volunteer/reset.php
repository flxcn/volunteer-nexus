<?php
session_start();

// Make sure user is logged in
if(!isset($_SESSION["volunteer_loggedin"]) || $_SESSION["volunteer_loggedin"] == FALSE){
    header("location: login.php");
    exit;
}

require_once "../config.php";

// Define all variables
$new_password = "";
$confirm_password = "";

// Define all error variabless
$new_password_error = "";
$confirm_password_error = "";

// Data Validation + SQL
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate new_password
    if(empty(trim($_POST["new_password"]))){
        $new_password_error = "Please enter the new password.";
    } else{
        $new_password = trim($_POST["new_password"]);
    }

    // Validate confirm_password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_error = "Please confirm the password.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($new_password_error) && ($new_password != $confirm_password)){
            $confirm_password_error = "New password did not match current password.";
        }
    }

    if(empty($new_password_error) && empty($confirm_password_error)){
        $sql = "UPDATE volunteers SET password = ? WHERE volunteer_id = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "si", $param_password, $param_volunteer_id);

            // Set params
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_volunteer_id = $_SESSION["volunteer_id"];

            if(mysqli_stmt_execute($stmt)){
                session_destroy();
                header("location: login.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>

    <!--Load required libraries-->
    <?php $pageContent='Form'?>
    <?php include '../head.php'?>
    
</head>
<body>

    <!--Navigation Bar-->
    <?php $thisPage='Reset'; include 'navbar.php';?>

    <div class="wrapper">
        <h2>Reset Password</h2>
        <p>Please fill out this form to reset your password.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($new_password_error)) ? 'has-error' : ''; ?>">
                <label>New Password</label>
                <input type="password" name="new_password" class="form-control" placeholder="New Password" value="<?php echo $new_password; ?>">
                <span class="help-block"><?php echo $new_password_error; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_error)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password">
                <span class="help-block"><?php echo $confirm_password_error; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <a class="btn btn-link" href="dashboard.php">Cancel</a>
            </div>
        </form>
    </div>

    <?php include '../footer.php';?>
</body>
</html>
