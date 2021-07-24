<?php
session_start();

if(!isset($_SESSION["volunteer_loggedin"]) || $_SESSION["volunteer_loggedin"] !== true)
{
    header("location: sign-in.php");
    exit;
}

require '../classes/VolunteerEventReader.php';
$volunteer_id = $_SESSION['volunteer_id'];
$obj = new VolunteerEventReader($volunteer_id);
$events = $obj->getEvents();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Felix Chen">
    
    <title>Events</title>

    <!-- Bootstrap core CSS -->
    <link href="../assets/bootstrap-5.0.2-dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../assets/css/events.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <?php $thisPage='Events'; include 'navbar.php';?>

    <div class="container-fluid">
        <div class="row">
            <main class="ms-sm-auto px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Discover Events!</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print(); return false;"><span data-feather="printer"></span> Print</button>
                        </div>
                    </div>
                </div>

                <?php if($events): ?>
                    <div class="table-responsive">
                        <table class='table table-hover'>
                            <thead>
                                <tr>
                                    <th>Reg. Deadline</th>
                                    <th>Event</th>
                                    <th>Sponsor</th>
                                    <th>Description</th>
                                    <th>Event Duration</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($events as $event): ?>
                                <tr onclick="window.location='<?php echo 'event-read.php?event_id='.$event['event_id']; ?>';">
                                    <td><?php echo $obj->formatDate($event['registration_end']); ?></td>
                                    <td><?php echo $event['event_name']; ?></td>
                                    <td><?php echo $event['sponsor_name']; ?></td>
                                    <td><?php echo $obj->formatDescription($event['description']); ?></td>
                                    <td><?php echo $obj->formatEventStartToEnd($event['event_start'],$event['event_end']); ?></td>
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
            </main>
            
            <!-- Footer -->
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
