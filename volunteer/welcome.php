<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
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
    <div class="page-header">
        <h1>Hi, <b><?php echo htmlspecialchars($_SESSION["first_name"]); ?></b>. Welcome to <b>VolunteerNexus</b>.</h1>
    </div>
    <div class="">
        <h1>Each affiliation and the total, with glyphicons so that I can look closer at each thing.</h1>
    </div>
    <div class="">
        <h1>Upcoming events that I am signed up for, with glyphicon to read, and one to cancel.</h1>
    </div>
    <p>
        <a href="events.php" class="btn btn-primary">Find Events</a>
        <a href="affiliations.php" class="btn btn-warning">Check My Contributions</a>
        <a href="engagements.php" class="btn btn-danger">My Upcoming Engagements</a>
        <a href="reset.php" class="btn btn-warning">Reset Your Password</a>
        <a href="../logout.php" class="btn btn-danger">Sign Out of Your Account</a>
    </p>
</body>
</html>
