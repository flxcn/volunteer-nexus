<?php
// Initialize the session
session_start();

// Check to see if user is logged in; if they are already in, then redirect them to the dashboard
if(isset($_SESSION["sponsor_loggedin"]) && $_SESSION["sponsor_loggedin"] === true){
    header("location: dashboard.php");
    exit;
}

require_once "../config.php";

// Define all variables
$username = "";
$password = "";
$username_error = "";
$password_error = "";

// Data Validation + SQL
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate usernaem
    if(empty(trim($_POST["username"]))){
        $username_error = "Please enter your email address.";
    } else{
        $username = trim($_POST["username"]);
    }

    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_error = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate username and password combo
    if(empty($username_error) && empty($password_error)){
        $sql = "SELECT sponsor_id, sponsor_name, contribution_type, username, password FROM sponsors WHERE username = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            $param_username = $username;

            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1){
                    mysqli_stmt_bind_result($stmt, $sponsor_id, $sponsor_name, $contribution_type, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){

                            //start new session
                            if(session_status() !== PHP_SESSION_ACTIVE) session_start();

                            // Initialize session variables // NOTE: These will be used across VolunteerNexus
                            $_SESSION["sponsor_loggedin"] = true;
                            $_SESSION["sponsor_id"] = $sponsor_id;
                            $_SESSOPM["username"] = $username;
                            $_SESSION["sponsor_name"] = $sponsor_name;
                            $_SESSION["contribution_type"] = $contribution_type;

                            // Redirect user to dashboard
                            header("location: dashboard.php");
                        } else{
                            //NOTE: ERROR! Wrong Password.
                            $password_error = "The password you entered was incorrect. Try again.";
                        }
                    }
                } else{
                    //NOTE: ERROR! Wrong Username.
                    $username_error = "No account found with that email.";
                }
            } else{
                echo "ERROR! Something went wrong...";
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
    <title>Sponsor Login</title>

        <!--Load required libraries-->
        <?php $pageContent='Form'?>
        <?php include '../head.php'?>

</head>
<body>
    <div class="wrapper">
        <h2>Sponsor Login</h2>
        <p>Please fill in your account information to login.</p>
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
            <p>Don't have an account? <a href="register.php">Sign up now!</a>.</p>
        </form>
    </div>
</body>
</html>
