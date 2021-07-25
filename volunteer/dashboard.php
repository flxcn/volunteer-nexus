<?php
session_start();

if(!isset($_SESSION["volunteer_loggedin"]) || $_SESSION["volunteer_loggedin"] !== true){
    header("location: sign-in.php");
    exit;
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Felix Chen">
    
    <title>Dashboard</title>

    <!-- Bootstrap core CSS -->
    <link href="../assets/bootstrap-5.0.2-dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom styles for this template -->
    <link href="../assets/css/dashboard.css" rel="stylesheet">
    <link rel="stylesheet" media="print" href="../assets/css/print.css" />
</head>
<body>
    <?php $thisPage='Dashboard'; include 'navbar.php';?>

    <div class="container-fluid">
        <div class="row">
            <main class="ms-sm-auto px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom print">
                    <h1 class="h2">Welcome, <?php echo htmlspecialchars($_SESSION["first_name"]); ?>!</h1>
                    <div class="btn-toolbar mb-2 mb-md-0 no-print">
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()"><span data-feather="printer"></span> Print</button>
                        </div>
                    </div>
                </div>

                <?php if($_SESSION["engagement_creation_success"] && $_SESSION["engagement_creation_success"] === true): ?>
                <div class="row my-4">
                    <div class="col-12 col-xl-12 mb-4 mb-lg-0">
                        <div class="card border-success">
                            <h5 class="card-header bg-success text-light">Sign Up Successful</h5>
                            <div class="card-body">
                                <p>You've successfully signed up for the <?php // echo $_POST["opportunity_name"];?> opportunity.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php 
                $_SESSION["engagement_creation_success"] = false;
                endif; 
                ?>

                <!-- <div class="btn-group btn-group-lg mb-4"> --> 
                    <a href="events.php" class="btn btn-success">Discover Events!</a>
                    <a href="../tutor/dashboard.php" class="btn btn-primary">Tutoring</a>
                    <a href="affiliation-create.php" class="btn btn-primary">Join a Sponsor</a>
                    <a href="profile.php#volunteer_id" class="btn btn-primary">Volunteer ID</a>
                <!-- </div> -->

                <?php include "engagements.php"; ?>

                <?php include "affiliations.php"; ?>
            </main>

            <?php include "footer.php"; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script>
    <script src="../assets/bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>

    <script>
    (function () {
    'use strict'

    feather.replace({ 'aria-hidden': 'true' })

    })()
    </script>
</body>
</html>
