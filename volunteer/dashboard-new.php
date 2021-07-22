<?php
session_start();

if(!isset($_SESSION["volunteer_loggedin"]) || $_SESSION["volunteer_loggedin"] !== true){
    header("location: login.php");
    exit;
}

require "../classes/AdminDashboard.php";

$obj = new AdminDashboard();
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

        <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css"> -->
        
        <!-- Custom styles for this template -->
        <link href="../assets/css/dashboard.css" rel="stylesheet">
    </head>

    <body>
        <?php include "navbar-new.php"; ?>
    

        <div class="container-fluid">
            <div class="row">
                
  

                <main class="ms-sm-auto px-md-4">
                    
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2">Welcome, <?php echo htmlspecialchars($_SESSION["first_name"]); ?>!</h1>
                        <!-- <h1 class="h2">Dashboard</h1> -->
                        <div class="btn-toolbar mb-2 mb-md-0">
                            <div class="btn-group me-2">
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()"><span data-feather="printer"></span> Print</button>
                            </div>
                        </div>
                    </div>

                    
                    <!-- <div class="btn-group btn-group-lg mb-4"> --> 
                        <a href="events.php" class="btn btn-success">Discover Events!</a>
                    
                        <a href="tutoring.php" class="btn btn-primary">Tutoring</a>
                    
                        <a href="attendance-anywhere.php" class="btn btn-primary">Volunteer ID</a>

                        <a href="affiliation-create.php" class="btn btn-primary">Join a Sponsor</a>

                    <!-- </div> -->

                    <div class="row mt-4">
                        <div class=" mb-lg-0">
                            <div class="card">
                                <h5 class="card-header">Upcoming Engagements</h5>
                                <div class="card-body">
                                    <p class="text-muted">You have none at the moment.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php include "affiliations-new.php"; ?>
                </main>

                <?php include "../footer.php" ?>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script>
        <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script> -->
        <script src="../assets/bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>

        <script>
        (function () {
        'use strict'

        feather.replace({ 'aria-hidden': 'true' })

        })()
        </script>

    </body>
</html>
