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
    
    <title>Error</title>

    <!-- Bootstrap core CSS -->
    <link href="../assets/bootstrap-5.0.2-dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../assets/css/main.css" rel="stylesheet">
</head>
<body>
    <?php $thisPage=''; include 'navbar.php';?>

    <div class="container-fluid">
        <div class="row">
            <main class="ms-sm-auto px-md-4">
                <div class="row my-4">
                    <div class="col-12 col-md-8 mx-auto mb-4 mb-lg-0">
                        <div class="card border-danger">
                            <h3 class="card-header bg-danger text-light">Something's wrong.</h3>
                            <div class="card-body">
                                <p>Look's like there's been an error. <a href="dashboard.php" class="btn-link">Click here</a> to go back to the dashboard. If the issue persists, please send an email to <a href="mailto:felix@volunteernexus.com?Subject=VolunteerNexus%20Issue%20Report" target="_blank">felix@volunteernexus.com</a> detailing what happened. </p>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <?php include "footer.php"; ?>
        </div>
    </div>
</body>
</html>
