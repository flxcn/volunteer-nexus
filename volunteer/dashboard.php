<?php
session_start();

if(!isset($_SESSION["volunteer_loggedin"]) || $_SESSION["volunteer_loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <!--Load required libraries-->
    <?php include '../head.php'?>
</head>
<body>
    <?php $thisPage='Dashboard'; include 'navbar.php';?>

    <div class="page-header">
        <h1>Howdy, <b><?php echo htmlspecialchars($_SESSION["first_name"]); ?></b>!</h1>
    </div>

    <p>
        <a href="events.php" class="btn btn-success">Find Events!</a>
    </p>

    <?php include 'affiliations.php';?>
    <?php include 'engagements.php';?>
    <?php include '../footer.php';?>
</body>
</html>
