<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["volunteer_loggedin"]) || $_SESSION["volunteer_loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>

    <!--Load required libraries-->
    <?php include '../head.php'?>

    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>

    <?php $thisPage='Home'; include 'navbar.php';?>


    <div class="page-header">
        <h1>Hi, <b><?php echo htmlspecialchars($_SESSION["first_name"]); ?></b>. Welcome to <b>VolunteerNexus</b>.</h1>
    </div>

    <?php include 'affiliations.php';?>
    <?php include 'engagements.php';?>
    <?php include '../footer.php';?>

</body>
</html>
