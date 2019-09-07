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
    <title>Dashboard</title>

    <!--Load required libraries-->
    <?php include '../head.php'?>
</head>
<body>

    <?php $thisPage='Dashboard'; include 'navbar.php';?>
    <div class="page-header">
        <h1>Hello, <b><?php echo htmlspecialchars($_SESSION["sponsor_name"]); ?></b>. Welcome to <b>VolunteerNexus</b>.</h1>
    </div>
    <p>
        <a href="events.php" class="btn btn-primary">My Sponsored Events</a>
        <a href="affiliations.php" class="btn btn-primary">My Affiliated Volunteers</a>
        <a href="engagements.php" class="btn btn-primary">Pending Engagements</a>
    </p>

    <?php include '../footer.php';?>
</body>
</html>
