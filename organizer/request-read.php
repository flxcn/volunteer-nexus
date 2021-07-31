<?php
session_start();

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
    <?php $thisPage='Tutoring'; include 'navbar.php';?>

    <div class="container-fluid print">
        <div class="row">
            <main class="ms-sm-auto px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">View Tutoring Request</h1>
                    <div class="btn-toolbar mb-2 mb-md-0 no-print">
                        <div class="btn-group me-2">
                            <a class="btn btn-sm btn-outline-success" href="events.php">Mark as done</a>
                            <!-- <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print(); return false;"><span data-feather="printer"></span> Print</button> -->
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <!-- Learner Details -->
                        <div class="card border-dark mb-3">
                            <div class="card-body">
                                <h4 class="card-title">Johnny Apple<?php //echo $eventObj->getEventName(); ?></h4>                                
                                <hr>
                                <p class="card-text"><b>Student ID: </b> 83436<?php //echo $eventObj->getLocation(); ?></p>
                                <p class="card-text"><b>Graduation Year: </b> 2025<?php //echo $eventObj->getLocation(); ?></p>
                                <p class="card-text"><b>Previous Tutor(s): </b> N/A<?php //echo $eventObj->getLocation(); ?></p>
                                <p class="card-text"><b>Subject(s): </b> Calculus, American History, Physics 1<?php //echo $eventObj->getLocation(); ?></p>
                                <p class="card-text"><b>Additional Info: </b> Would like some help on understanding calculus. Prefers to work with Jack<?php //echo $eventObj->getLocation(); ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Info -->
                    <div class="col">
                    <div class="card border-dark mb-3">
                            <div class="card-body">
                                <h5 class="card-title mb-3">Contact Info</h5>
                                <p class="card-text"><b>Learner's Name:</b> <?php echo $eventObj->getContactName(); ?></p>
                                <p class="card-text"><b>Learner's Email Address:</b> <?php echo $eventObj->getContactEmail(); ?></p>
                                <p class="card-text"><b>Learner's Phone Number:</b> <?php echo $eventObj->getContactPhone(); ?></p>
                                <hr>
                                <p class="card-text"><b>Parent's Name:</b> <?php echo $eventObj->getContactName(); ?></p>
                                <p class="card-text"><b>Parent's Email Address:</b> <?php echo $eventObj->getContactEmail(); ?></p>
                                <p class="card-text"><b>Parent's Phone Number:</b> <?php echo $eventObj->getContactPhone(); ?></p>
                            </div>
                    </div>
                    </div>

                    <div class="col">
                        <div class="card border-dark mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Availability</h5>
                                <h6 class="card-subtitle mb-3 text-muted">During a Typical Week<?php //echo $eventObj->getSponsorName(); ?></h6>
                                <table class="table table-bordered table-sm border-dark table-hover align-middle">
                                    <thead class="table-dark">
                                        <td class="col-3" scope="col">Day</td>
                                        <td class="col-3 text-center" scope="col">Morning</td>
                                        <td class="col-3 text-center" scope="col">After School</td>
                                        <td class="col-3 text-center" scope="col">Evening</td>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td scope="row">Mon.</td>
                                            <td class="table-success border-dark"></td>
                                            <!-- <td style="background-color: green;"></td> -->
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td scope="row">Tue.</td>
                                            <td class="table-success border-dark"></td>
                                            <td class="table-success border-dark"></td>
                                            <td class="table-success border-dark"></td>
                                        </tr>
                                        <tr>
                                            <td scope="row">Wed.</td>
                                            <td></td>
                                            <td class="table-success border-dark"></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td scope="row">Thu.</td>
                                            <td class="table-success border-dark"></td>
                                            <td class="table-success border-dark"></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td scope="row">Fri.</td>
                                            <td></td>
                                            <td class="table-success border-dark"></td>
                                            <td class="table-success border-dark"></td>       
                                        </tr>
                                    </tbody>
                                </table>                              
                            </div>
                        </div>
                    </div>
                </div>
                    <!-- <div class="col-sm-12 col-lg-8 mb-4"> -->
                <div class="row">
                    <div class="col-12 mb-4">
                        <div class="card border-primary">
                            <div class="card-body">
                                <h5 class="card-title text-primary">Recommended Tutors</h5>
                                <?php if($opportunities): ?>
                                    <div class="table-responsive">
                                        <table class='table table-hover'>
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Graduation Year</th>
                                                    <th>Knowledgable Subject(s)</th>
                                                    <th>Existing Assignments</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Adam Newman</td>
                                                    <td>am93948@eanesisd.net</td>
                                                    <td>2022</td>
                                                    <td>Calculus, American History</td>
                                                    <td>0</td>
                                                    <td>
                                                        <a href="#" class="btn btn-primary btn-sm">View</a>
                                                        <a href="#" class="btn btn-success btn-sm">Assign</a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Barry Oldman</td>
                                                    <td>am93948@eanesisd.net</td>
                                                    <td>2022</td>
                                                    <td>Calculus, American History</td>
                                                    <td>0</td>
                                                    <td>
                                                        <a href="#" class="btn btn-primary btn-sm">View</a>
                                                        <a href="#" class="btn btn-success btn-sm">Assign</a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Adam Newman</td>
                                                    <td>am93948@eanesisd.net</td>
                                                    <td>2023</td>
                                                    <td>American History</td>
                                                    <td>1</td>
                                                    <td>
                                                        <a href="#" class="btn btn-primary btn-sm">View</a>
                                                        <a href="#" class="btn btn-success btn-sm">Assign</a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Adam Newman</td>
                                                    <td>am93948@eanesisd.net</td>
                                                    <td>2025</td>
                                                    <td>Calculus</td>
                                                    <td>1</td>
                                                    <td>
                                                        <a href="#" class="btn btn-primary btn-sm">View</a>
                                                        <a href="#" class="btn btn-success btn-sm">Assign</a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <p class='lead'>
                                        <em>There are currently no opportunities.</em>
                                    </p>
                                <?php endif; ?>
                            </div>

                            <button onclick="toggleManualAssignment()" class="btn btn-block btn-light card-footer" id="toggleButton">Assign someone else</button>
                        </div>
                    </div>
                </div>

                <div class="row" id="manualAssignment" style="display: none;">
                    <div class="col-12 mb-4">
                        <div class="card border-primary">
                            <div class="card-body">
                                <h5 class="card-title mb-3">Manually Assign Tutor</h5>
                                <form>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Find tutor" id="searchField" aria-describedby="button-addon2">
                                        <button class="btn btn-outline-success" type="submit" id="button-addon2">Assign</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            
            <!-- Footer -->
            <?php // include "footer.php"; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script>
    <script src="../assets/bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function toggleManualAssignment() {
            var contactInfo = document.getElementById("manualAssignment");
            var toggleButton = document.getElementById("toggleButton");
            if (contactInfo.style.display === "none") {
                contactInfo.style.display = "block";
                document.getElementById("searchField").focus({preventScroll:false});
            } else {
                contactInfo.style.display = "none";
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