<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["sponsor_loggedin"]) || $_SESSION["sponsor_loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>

    <?php $thisPage='Home'; include 'navbar.php';?>
    <div class="page-header">
        <h1>Hello, <b><?php echo htmlspecialchars($_SESSION["sponsor_name"]); ?></b>. Welcome to <b>VolunteerNexus</b>.</h1>
    </div>
    <p>
        <a href="events.php" class="btn btn-primary">My Sponsored Events</a>
        <a href="affiliations.php" class="btn btn-primary">My Members</a>
        <a href="engagements.php" class="btn btn-primary">Pending Engagements</a>
        <a href="reset.php" class="btn btn-warning">Reset Your Password</a>
        <a href="../logout.php" class="btn btn-danger">Sign Out of Your Account</a>
    </p>
</body>
</html>
