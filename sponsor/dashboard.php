<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["sponsor_loggedin"]) || $_SESSION["sponsor_loggedin"] !== true){
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
    <link href="../assets/css/main.css" rel="stylesheet">
    <link rel="stylesheet" media="print" href="../assets/css/print.css" />
</head>
<body>
    <?php $thisPage='Dashboard'; include 'navbar.php';?>

    <div class="container-fluid">
        <div class="row">
            <main class="ms-sm-auto px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom print">
                    <h1 class="h2">Welcome, <?php echo htmlspecialchars($_SESSION["sponsor_name"]); ?>!</h1>
                    <div class="btn-toolbar mb-2 mb-md-0 no-print">
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()"><span data-feather="printer"></span> Print</button>
                        </div>
                    </div>
                </div>

                <a href="events.php" class="btn btn-success mb-1">My Events</a>

                <a href="affiliations.php" class="btn btn-primary mb-1">My Volunteers</a>

                <div class="btn-group mb-1">
                    <button class="btn btn-primary dropdown-toggle dropdown-menu-end"" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                        Engagements
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        <li><a class="dropdown-item" href="engagements.php">Pending</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="engagement-create.php">Add New</a></li>
                        <li><a class="dropdown-item" href="engagement-batch-create.php">Multi-Add</a></li>
                    </ul>
                </div>

                <a href="../organizer/dashboard.php" class="btn btn-primary mb-1">Tutoring</a>

                <a href="attendance-anywhere.php" class="btn btn-primary mb-1">ID Scanner</a>

            </main>

            <?php include "footer.php"; ?>
        </div>
    </div>

    <script src="../assets/bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script>

    <script>
    (function () {
    'use strict'

    feather.replace({ 'aria-hidden': 'true' })

    })()
    </script>
</body>
</html>