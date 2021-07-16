<?php
session_start();

// Check if the volunteer is already logged in, if yes then redirect to dashboard
if(isset($_SESSION["volunteer_loggedin"]) && $_SESSION["volunteer_loggedin"] === true){
    header("location: dashboard.php");
    exit;
}

require_once "../classes/VolunteerLogin.php";
include "google-oauth.php";

$username = "";
$password = "";
$error = "";
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
            $password_error = "<p>The password you entered was not valid. <br></p>";
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

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <title>Sign In</title>

        <!-- Bootstrap core CSS -->
        <link href="../assets/bootstrap-5.0.2-dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom styles for this template -->
        <link href="../assets/css/signin.css" rel="stylesheet">
    </head>
    <!-- " -->
    <body class="text-center" >
        <main class="form-signin">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <img class="mb-4" src="../assets/images/volunteernexus-logo-1.png" alt="" width="72" height="72">
                <h1 class="h3 mb-3 fw-normal">Volunteer</h1>

                <a class="mt-1 mb-2 w-100 btn btn-lg btn-light text-center" style="background-color: black; border-color: white; color: white;" href="<?php echo $auth_url; ?>">
                    <img width='20px' style="margin-bottom: 3px;" class="mx-1" alt='Google sign-in' src='../assets/images/google-g-logo.png' />  Sign in with Google
                </a>

                <div class="form-floating">
                    <input type="email" name="username" class="form-control" id="floatingInput" placeholder="name@email.com">
                    <label for="floatingInput">Email address</label>
                </div>
                <div class="form-floating">
                    <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password">
                    <label for="floatingPassword">Password</label>
                </div>

                <div class="text-danger"><?php echo $username_error; ?><?php echo $password_error; ?></div>

                <button class="w-100 mt-0 btn btn-lg btn-primary" type="submit">Sign in</button>

                <div class="mt-3">
                    Don't have an account? <a href="sign-up.php">Sign up here.</a>
                </div>

                <p class="mt-5 mb-3 text-muted text-white">&copy; 2019–2021 Volunteer Nexus, Inc.</p>
            </form>
        </main>
    </body>
</html>
