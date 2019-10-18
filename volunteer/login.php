<?php
session_start();

//If the user is already logged in, redirect them to the dashboard
if(isset($_SESSION["volunteer_loggedin"]) && $_SESSION["volunteer_loggedin"] === true){
    header("location: dashboard.php");
    exit;
}

require_once "../config.php";

//Define all variables
$username = "";
$password = "";

//Define all error variables
$username_error = "";
$password_error = "";

//Data Validation + SQL
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate username
    if(empty(trim($_POST["username"]))) {
        $username_error = "Please enter your email address.";
    } else if (strpos(trim($_POST["username"]), "@eanesisd.net") !== false) {
        $username_error = "Sorry, email must be in the eanesisd.net domain";
    } else {
        $username = trim($_POST["username"]);
    }

    //Validate password
    if(empty(trim($_POST["password"]))){
        $password_error = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate login information
    if(empty($username_error) && empty($password_error)){

        $sql = "SELECT volunteer_id, first_name, last_name, graduation_year, username, password FROM volunteers WHERE username = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set params
            $param_username = $username;

            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) == 1){
                    mysqli_stmt_bind_result($stmt, $volunteer_id, $first_name, $last_name, $graduation_year, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            if(session_status() !== PHP_SESSION_ACTIVE) session_start();

                            //Initialize SESSION variables
                            $_SESSION["volunteer_loggedin"] = true;
                            $_SESSION["volunteer_id"] = $volunteer_id;
                            $_SESSOPM["username"] = $username;
                            $_SESSION["first_name"] = $first_name;
                            $_SESSION["last_name"] = $last_name;
                            $_SESSION["graduation_year"] = $graduation_year;

                            header("location: dashboard.php");
                        } else{
                            //Error!
                            $password_error = "The password you entered was incorrect.";
                        }
                    }
                } else{
                    //NOTE: Error!
                    $username_error = "No account found with that email.";
                }
            } else{
                echo "ERROR! Something went wrong...";
            }
        }
        mysqli_stmt_close($stmt);
    }

    mysqli_close($link);
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

</head>
<body>
    <div class="wrapper">
        <h2>Volunteer Login</h2>
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
