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
    <p>
        <a href="engagement-create.php" class="btn btn-primary">Add Engagement</a>
    </p>
    <p>
        <a href="engagement-create.php" class="btn btn-primary">Add Multiple Engagements</a>
    </p>

    <!-- <p>
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            Engagements
            <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                <li><a href="engagements.php">Pending/Active</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="engagement-create.php">Create</a></li>
                <li><a href="engagement-batch-create.php">Create Multiple</a></li>

            </ul>
        </div>
    </p> -->
        
    <!-- <div class="dropdown">
        <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Dropdown link
        </a>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <a class="dropdown-item" href="#">Pending Engagements</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">Add Engagement</a>
            <a class="dropdown-item" href="#">Add Multiple Engagements</a>
        </div>
    </div> -->

    <!-- <p>
        <a href="engagements.php" class="btn btn-primary">Pending Engagements</a>
    </p>
    <p>
        <a href="engagement-create.php" class="btn btn-primary">Add Engagement</a>
    </p> -->
    <p>
        <a href="attendance-anywhere.php" class="btn btn-success"><span class='glyphicon glyphicon-qrcode'></span> Attendance Anywhere</a>
    </p>

    <?php include '../footer.php';?>
</body>
</html>
