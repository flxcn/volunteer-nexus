<?php
session_start();

// Set no caching
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
header("Cache-Control: no-store, no-cache, must-revalidate"); 
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");


if(!isset($_SESSION["volunteer_loggedin"]) || $_SESSION["volunteer_loggedin"] !== true){
    header("location: sign-in.php");
    exit;
}

// Check existence of id parameter before processing further
if(isset($_GET["event_id"]) && !empty(trim($_GET["event_id"]))){
    
    $volunteer_id = $_SESSION["volunteer_id"];
    $event_id = $_GET["event_id"];

    require_once "../classes/VolunteerEventReader.php";
    $eventObj = new VolunteerEventReader($volunteer_id);
    $eventObj->setEventId($event_id);
    
    if($eventObj->getEventDetails()) {
        require_once "../classes/VolunteerOpportunityReader.php";
        $opportunityObj = new VolunteerOpportunityReader($volunteer_id, $event_id);
        $opportunities = $opportunityObj->getOpportunities();
    } else {
        header("location: error.php");
    }

} else {
    header("location: error.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Felix Chen">
    
    <title>View Event</title>

    <!-- Bootstrap core CSS -->
    <link href="../assets/bootstrap-5.0.2-dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../assets/css/events.css" rel="stylesheet">
    <link rel="stylesheet" media="print" href="../assets/css/print.css" />
</head>
<body>
    <!-- Navbar -->
    <?php $thisPage='Events'; include 'navbar.php';?>

    <div class="container-fluid print">
        <div class="row">
            <main class="ms-sm-auto px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">View Event</h1>
                    <div class="btn-toolbar mb-2 mb-md-0 no-print">
                        <div class="btn-group me-2">
                            <a class="btn btn-sm btn-outline-secondary" href="events.php">Go back</a>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print(); return false;"><span data-feather="printer"></span> Print</button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12 col-lg-4 mb-4">
                        <!-- Event Details -->
                        <div class="card border-dark mb-3">
                            <div class="card-body">
                                <h4 class="card-title"><?php echo $eventObj->getEventName(); ?></h4>
                                <h6 class="card-subtitle mb-2 text-muted"><?php echo $eventObj->getSponsorName(); ?></h6>
                                <hr>
                                <p class="card-text"><?php echo $eventObj->formatLinks($eventObj->getDescription()); ?></p>
                                <hr>
                                <p class="card-text"><b>When: </b><?php echo $eventObj->formatEventStartToEnd($eventObj->getEventStart(), $eventObj->getEventEnd()) ?> <i>(Register by <b><?php echo $eventObj->formatDate($eventObj->getRegistrationEnd()); ?></b>)</i></p>
                                <p class="card-text"><b>Where: </b><?php echo $eventObj->getLocation(); ?></p>
                            </div>
                            <button onclick="toggleContactInfo();" class="btn btn-block btn-light card-footer" id="toggleButton"><small id="toggleButtonText">See contact info</small></button>
                        </div>

                        <!-- Contact Info -->
                        <div class="card border-dark" style="display:none;" id="contactInfo">
                            <div class="card-body">
                                <h5 class="card-title mb-3">Contact Info</h4>
                                <p class="card-text"><b>Name:</b> <?php echo $eventObj->getContactName(); ?></p>
                                <p class="card-text"><b>Email Address:</b> <?php echo $eventObj->getContactEmail(); ?></p>
                                <p class="card-text"><b>Phone Number:</b> <?php echo $eventObj->getContactPhone(); ?></p>
                                <div class="btn-group text-center mb-2 mb-md-0">
                                    <a href="mailto:<?php echo $eventObj->getContactEmail(); ?>" class="btn btn-sm btn-outline-primary">Send Email</a>  
                                </div>
                            </div>
                        </div>
                    </div>
    
                    <!-- Opportunities -->
                    <div class="col-sm-12 col-lg-8 mb-4">
                        <div class="card border-primary">
                            <div class="card-body">
                                <h5 class="card-title text-primary">Opportunities</h5>
                                <?php if($opportunities): ?>
                                    <div class="table-responsive">
                                        <table class='table table-hover'>
                                            <thead>
                                                <tr>
                                                    <th>Role</th>
                                                    <th>Description</th>
                                                    <th>Duration</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($opportunities as $opportunity): ?>
                                                <tr>
                                                    <td><?php echo $opportunity['opportunity_name']; ?></td>
                                                    <td><?php echo $eventObj->formatDescription($opportunity['description']); ?></td>
                                                    <td><?php echo $opportunityObj->formatTime($opportunity['start_time']) . " to<br >" . $opportunityObj->formatTime($opportunity['end_time']); ?></td>
                                                    <td>
                                                        <!-- Button trigger modal -->
                                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal<?php echo $opportunity["opportunity_id"];?>">
                                                        View
                                                        </button>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Modal -->
                                    <?php foreach($opportunities as $opportunity): ?>
                                    <div class="modal fade" id="modal<?php echo $opportunity["opportunity_id"];?>" tabindex="-1" aria-labelledby="modal<?php echo $opportunity["opportunity_id"];?>Label" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modal<?php echo $opportunity["opportunity_id"];?>Label"><?php echo $opportunity["opportunity_name"]; ?></h5>                           
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>

                                                <div class="modal-body">
                                                    <h6 class="small text-muted"><?php echo $opportunity["contribution_value"] . " " . $eventObj->getContributionType(); ?></h6>
                                                    <p><?php echo $opportunity["description"]; ?></p>
                                                    <hr>
                                                    <p><b>From </b><?php echo $opportunityObj->formatDate($opportunity['start_date']) . " @ " . $opportunityObj->formatTime($opportunity['start_time']) . " <br><b>To</b>   " . $opportunityObj->formatDate($opportunity['end_date']) . " @ " . $opportunityObj->formatTime($opportunity['end_time']); ?></p>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <form action="engagement-create.php" method="post">
                                                        <input type="hidden" name="event_id" value="<?php echo trim($event_id); ?>">
                                                        <input type="hidden" name="opportunity_id" value="<?php echo trim($opportunity["opportunity_id"]); ?>">
                                                        <input type="hidden" name="sponsor_id" value="<?php echo $eventObj->getSponsorId(); ?>">
                                                        <input type="hidden" name="volunteer_id" value="<?php echo trim($_SESSION["volunteer_id"]); ?>">
                                                        <input type="hidden" name="contribution_value" value="<?php echo $opportunity["contribution_value"];?>">
                                                        <input type="hidden" name="needs_verification" value="<?php echo $opportunity["needs_verification"];?>">
                                                        <input type="submit" class="btn btn-success" value="Sign up!">                                                                    
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class='lead'>
                                        <em>There are currently no opportunities.</em>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            
            <!-- Footer -->
            <?php include "footer.php"; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script>
    <script src="../assets/bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function toggleContactInfo() {
            var contactInfo = document.getElementById("contactInfo");
            var toggleButton = document.getElementById("toggleButton");
            var toggleButtonText = document.getElementById("toggleButtonText");
            if (contactInfo.style.display === "none") {
                contactInfo.style.display = "block";
                toggleButtonText.textContent = "Hide contact info";
            } else {
                contactInfo.style.display = "none";
                toggleButtonText.textContent = "See contact info";
            }
        }
    </script>

    <script>
    (function () {
    'use strict'

    feather.replace({ 'aria-hidden': 'true' })

    })()
    </script>
</body>
</html>