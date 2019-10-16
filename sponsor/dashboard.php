<?php
session_start();

// Make sure user is logged in
if(!isset($_SESSION["sponsor_loggedin"]) || $_SESSION["sponsor_loggedin"] == false){
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
        <h1>Howdy, <b><?php echo htmlspecialchars($_SESSION["sponsor_name"]); ?></b>!</h1>
        <p> Welcome to <b>VolunteerNexus</b></p>
    </div>
    <p>
        <a href="events.php" class="btn btn-primary">Sponsored Events</a>
    </p>
    <p>
        <a href="affiliations.php" class="btn btn-primary">Affiliated Volunteers</a>
    </p>
    <p>
        <a href="engagements.php" class="btn btn-primary">Pending Engagements</a>
    </p>

    <?php include '../footer.php';?>
</body>
</html>
