<?php
session_start();

if(!isset($_SESSION["admin_loggedin"]) || $_SESSION["admin_loggedin"] !== true){
    header("location: sign-in.php");
    exit;
}

require "../classes/AdminDashboard.php";

$obj = new AdminDashboard();
$events = $obj->getLatestEvents();
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
                    <h1 class="h2">Welcome, <?php echo htmlspecialchars($_SESSION["admin_name"]); ?>!</h1>
                </div>

                <div class="row mb-4">
                    <div class="col-12 col-md-6 mb-4 mb-lg-0 col-lg-3">
                        <div class="card text-center">
                            <h5 class="card-header">Volunteers</h5>
                            <div class="card-body">
                                <h3 class="card-title"><?php echo $obj->countVolunteers(); ?></h3>
                            </div>
                            <a href="volunteers.php" class="btn btn-block btn-light card-footer">View all</a>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 mb-4 mb-lg-0 col-lg-3">
                        <div class="card text-center">
                            <h5 class="card-header">Engagements</h5>
                            <div class="card-body">
                                <h3 class="card-title"><?php echo $obj->countEngagements(); ?></h3>
                            </div>
                            <a href="engagements.php" class="btn btn-block btn-light card-footer">View all</a>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-3 mb-4 mb-lg-0">
                        <div class="card text-center">
                            <h5 class="card-header">Sponsors</h5>
                            <div class="card-body">
                                <h3 class="card-title"><?php echo $obj->countSponsors(); ?></h3>
                            </div>
                            <a href="sponsors.php" class="btn btn-block btn-light card-footer">View all</a>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-3 mb-4 mb-lg-0">
                        <div class="card text-center">
                            <h5 class="card-header">Opportunities</h5>
                            <div class="card-body">
                                <h3 class="card-title"><?php echo $obj->countOpportunities(); ?></h3>
                            </div>
                            <a href="opportunities.php" class="btn btn-block btn-light card-footer">View all</a>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                        <div class="col-12 col-xl-12 mb-4 mb-lg-0">
                            <div class="card">
                                <h5 class="card-header">Latest events</h5>
                                <div class="card-body">
                                    <?php if($events): ?>
                                    <div class="table-responsive">
                                        <table class='table table-hover'>
                                            <thead>
                                                <tr>
                                                    <th scope="col">Name</th>
                                                    <th scope="col">Sponsor</th>
                                                    <th scope="col">Description</th>
                                                    <th scope="col">Event Duration</th>
                                                    <th scope="col">Posted At</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($events as $event): ?>
                                                <tr onclick="window.location='<?php echo 'event-read.php?event_id='.$event['event_id']; ?>';">
                                                    <td><?php echo $event['event_name']; ?></td>   
                                                    <td><?php echo $event['sponsor_name']; ?></td>
                                                    <td><?php echo $obj->formatDescription($event['description']); ?></td>
                                                    <td><?php echo $obj->formatEventStartToEnd($event['event_start'],$event['event_end']); ?></td>
                                                    <td><?php echo $event['time_posted']; ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <?php else: ?>
                                    <p class='lead'>
                                        <em>There are currently no events. If you have not yet, click <a href='affiliation-create.php'>here</a> to join a Sponsor in order to view their Events.</em>
                                    </p>
                                    <?php endif; ?>
                                </div>
                                <a href="events.php" class="btn btn-block btn-light card-footer">View all</a>
                            </div>
                        </div>
                    </div>
            </main>

            <!-- Footer -->
            <?php include "footer.php"; ?>
        </div>
    </div>

    <script src="../assets/bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
