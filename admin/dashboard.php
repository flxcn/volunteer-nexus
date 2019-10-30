<?php
session_start();

if(!isset($_SESSION["admin_loggedin"]) || $_SESSION["admin_loggedin"] !== true){
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
        <h1>G'day, <b><?php echo htmlspecialchars($_SESSION["first_name"]); ?></b>!</h1>
    </div>

    <p>
        <a href="query.php" class="btn btn-success">Query!</a>
    </p>

    <?php include '../footer.php';?>


</body>
</html>
