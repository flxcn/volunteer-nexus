<?php
// Initialize the session
session_start();

// // Check if the user is logged in, if not then redirect him to login page
// if(!isset($_SESSION["volunteer_loggedin"]) || $_SESSION["volunteer_loggedin"] !== true){
//     header("location: sign-in.php");
//     exit;
// }

// Check existence of id parameter before processing further
if(isset($_SESSION["volunteer_id"])) {
    
    $volunteer_id = $_SESSION["volunteer_id"];
    require_once "../classes/VolunteerAccountReader.php";
    $obj = new VolunteerAccountReader($volunteer_id);

    $obj->getVolunteerDetails();

} else {
    header("location: error.php");
    exit();
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Felix Chen">
    
    <title>Tutoring Organizer</title>

    <!-- Bootstrap core CSS -->
    <link href="../assets/bootstrap-5.0.2-dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom styles for this template -->
    <link href="../assets/css/main.css" rel="stylesheet">
</head>
<body>
    <?php $thisPage='Tutoring'; include '../volunteer/navbar.php';?>

    <div class="container-fluid">
        <div class="row">
            <main class="ms-sm-auto px-md-4">
                
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Tutoring</h1>
                    <div class="btn-toolbar mb-2 mb-md-0 no-print">
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()"><span data-feather="printer"></span> Print</button>
                        </div>
                    </div>
                </div>

                <a href="requests.php" class="btn btn-success">Requests</a>
                <a href="request-read.php?event_id=9" class="btn btn-success">Read Request</a>

                <a href="assignments.php" class="btn btn-primary">Assignments</a>
                <a href="assignment-read.php" class="btn btn-primary">Read Assignment</a>
                <a href="engagements.php" class="btn btn-info">Engagements</a>

                <?php include "requests.php"; ?>

                <?php // include "assignments.php"; ?>

            </main>

            <?php include "../volunteer/footer.php" ?>
        </div>
    </div>

    <script src="../assets/bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
