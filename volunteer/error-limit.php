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
    
    <title>Sign-up Limit Reached</title>

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
                        <div class="card border-dark">
                            <h3 class="card-header">Opportunity Sign-up Limit Reached</h3>
                            <div class="card-body">
                                <p>It looks like you've already signed up for this event—you have reached the individual sign-up limit for this opportunity. Please reach out to your Sponsor if you think the limit is different.</p>
                                <p>Remember, if the contribution value isn't being added to your total, it may be due to the fact that this engagement is still pending and requires approval from the Sponsor.</p>
                                <a href="dashboard.php" class="btn btn-success">Click here to go back to the dashboard.</a>
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

