<?php
session_start();

// Check if the sponsor is already logged in, if yes then redirect to dashboard
if(isset($_SESSION["sponsor_loggedin"]) && $_SESSION["sponsor_loggedin"] === true){
    header("location: dashboard.php");
    exit;
}

require_once "../classes/SponsorLogin.php";

$username = "";
$password = "";
$error = "";
$username_error = "";
$password_error = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Instatiate SponsorLogin object
    $obj = new SponsorLogin();

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
            $_SESSION["sponsor_loggedin"] = true;
            $_SESSION["username"] = $username;
            $_SESSION["sponsor_id"] = $obj->getSponsorId();
            $_SESSION["sponsor_name"] = $obj->getSponsorName();
            $_SESSION["contribution_type"] = $obj->getContributionType();

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
                <h1 class="h3 mb-3 fw-normal">Sponsor</h1>

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
                    Don't have an account? <a href="register.php">Sign up here.</a>
                </div>

                <p class="mt-5 mb-3 text-muted text-white">&copy; 2019–2021 Volunteer Nexus, Inc.</p>
            </form>
        </main>
    </body>
</html>
