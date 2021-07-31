<?php

// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["sponsor_loggedin"]) || $_SESSION["sponsor_loggedin"] !== true){
    header("location: sign-in.php");
    exit;
}

require_once "../classes/SponsorAccountUpdate.php";
$obj = new SponsorAccountUpdate($_SESSION["sponsor_id"]);

// Define variables and initialize with empty values
$password = "";
$confirm_password = "";

$error = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Set new password
    $password = trim($_POST["password"]);
    $error .= $obj->setPassword($password);

    // Set new confirm password
    $confirm_password = trim($_POST["confirm_password"]);
    $error .= $obj->setConfirmPassword($confirm_password);

    if(empty($error))
    {
        if($obj->updatePassword()) {
            session_destroy();
            header("location: sign-in.php");
        }
        else {
            echo "Something went wrong. Please try again later.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <link rel="icon" type="image/png" href="assets/images/volunteernexus-logo-1.png">

    <title>Reset Password</title>

    <!-- Bootstrap core CSS -->
    <link href="../assets/bootstrap-5.0.2-dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../assets/css/form.css" rel="stylesheet">
</head>

<body class="bg-light">
    
    <div class="container">
        <div class="py-5 text-center">
            <img class="d-block mx-auto mb-4" src="../assets/images/volunteernexus-logo-1.png" alt="" width="72" height="72">
            <h2>Reset Password</h2>
        </div>

        <div class="text-danger text-center"><?php echo $error; ?></div>

        <div class="row">
            <div class="col-md-12 d-flex justify-content-center order-md-1">
                <form class="needs-validation" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" novalidate="" oninput='confirm_password.setCustomValidity(confirm_password.value != password.value ? "Passwords do not match." : "")'>
                
                    <div class="form-floating mb-3">
                        <input type="password" name="password" class="form-control" placeholder="New Password" value="<?php echo $password; ?>" id="password" name="password">
                        <label for="password">New Password</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password" >
                        <label for="confirm_password">Confirm Password</label>

                    </div>

                    <hr class="mb-3">

                    <button class="w-100 btn btn-primary btn-lg btn-block" type="submit">Update!</button>
                    <a class="w-100 btn btn-link btn-block" href="profile.php">Go back</a>

                </form>
            </div>
        </div>

        <?php include "footer.php"; ?>
    </div>

    <!-- Custom js for this page -->
    <script src="../assets/js/form.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script>
        $("body").on("submit", "form", function() {
            $(this).submit(function() {
                return false;
            });
            return true;
        });
    </script>
</body>
</html>