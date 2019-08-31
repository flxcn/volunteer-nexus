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

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>

  <nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">

      <!--hamburger-->
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#theNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>

      <!--logo-->
      <a class="navbar-brand" href="#">VolunteerNexus</a>
    </div>

    <div class="collapse navbar-collapse" id="theNavbar">

      <ul class="nav navbar-nav">
        <li class="active"><a href="#">Home</a></li>
        <li><a href="events.php">Find Events</a></li>
        <li><a href="engagements.php">My Upcoming Engagements</a></li>
        <li><a href="affiliations.php">My Contributions</a></li>
      </ul>

      <ul class="nav navbar-nav navbar-right">
        <li><a href="reset.php"><span class="glyphicon glyphicon-user"></span> Account</a></li>
        <li><a href="/logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
      </ul>

    </div>
  </div>
</nav>


    <div class="page-header">
        <h1>Hi, <b><?php echo htmlspecialchars($_SESSION["first_name"]); ?></b>. Welcome to <b>VolunteerNexus</b>.</h1>
    </div>
    <p>
        <a href="events.php" class="btn btn-primary">Find Events</a>
        <a href="affiliations.php" class="btn btn-warning">Check My Contributions</a>
        <a href="engagements.php" class="btn btn-primary">My Upcoming Engagements</a>
        <a href="reset.php" class="btn btn-warning">Reset Your Password</a>
        <a href="../logout.php" class="btn btn-danger">Sign Out of Your Account</a>
    </p>
</body>
</html>
