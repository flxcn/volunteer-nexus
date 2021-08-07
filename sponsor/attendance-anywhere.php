<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["sponsor_loggedin"]) || $_SESSION["sponsor_loggedin"] !== true){
  header("location: sign-in.php");
  exit;
}

// Include config file
require_once "../classes/EngagementFormPopulator.php";

// Define and intialize variables
$sponsor_id = $_SESSION["sponsor_id"];
$volunteer_id = "";
$event_id = "";
$opportunity_id = "";
$contribution_value = "";

// Define and initialize error message variables
$sponsor_id_error = "";
$volunteer_name_error = "";
$event_name_error = "";
$opportunity_name_error = "";
$contribution_value_error = '';

// Instantiate an EngagementFormPopulator object
$populatorObj = new EngagementFormPopulator($sponsor_id);

// Initialize JSON Objects
$jsonVolunteers = $populatorObj->getVolunteers();
$jsonEvents = $populatorObj->getEvents();
$jsonOpportunities = $populatorObj->getOpportunities();

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Felix Chen">
    
    <title>Attendance Anywhere</title>

    <!-- Bootstrap core CSS -->
    <link href="../assets/bootstrap-5.0.2-dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom styles for this template -->
    <link href="../assets/css/form.css" rel="stylesheet">

    <!-- Custom JS for this template -->
    <script>
        var sponsorId = <?php echo $sponsor_id;?>;
        var eventId = '';
        var opportunityId = '';
        var contributionValue = '';

        var volunteers = <?php echo $jsonVolunteers;?>;
        var events = <?php echo $jsonEvents;?>;
        var opportunities = <?php echo $jsonOpportunities;?>;
    </script>

    <script src="../assets/js/attendance-anywhere.js"></script>

</head>

<body class="bg-light" onload='loadEvents();'>

    
    <div class="container">
        <div id="initialForm">
            <div class="py-5 text-center">
                <img class="d-block mx-auto mb-3" src="../assets/images/volunteernexus-logo-1.png" alt="" width="72" height="72">
                <h2>Attendance Anywhere</h2>
                <p class="lead">Fill out this form to start scanning Volunteer IDs.</p>
            </div>

            <div class="row">
                <div class="col-md-12 d-flex justify-content-center order-md-1">
                    <form name="engagementForm">
                        <div class="mb-3">
                            <label for="eventsSelect">Event Name</label>
                            <select name='event_name' id='eventsSelect' class="form-control">
                            </select>
                            <div class="invalid-feedback">
                                Please select an event.
                            </div> 
                            <span class="help-block"><?php echo $event_name_error;?></span>
                        </div>

                        <div class="mb-3">
                            <label for="opportunitiesSelect">Opportunity Name</label>
                            <select name='opportunity_name' id='opportunitiesSelect' class="form-control">
                            </select>
                            <div class="invalid-feedback">
                                Please select an opportunity.
                            </div> 
                            <span class="help-block"><?php echo $opportunity_name_error;?></span>
                        </div>

                        <div class="mb-3">
                            <label for="school">Contribution Value</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id='contributionValue' readonly>
                            </div>
                        </div>

                        <hr class="mb-3">

                        <button type="button" class="w-100 col-12 btn btn-primary btn-lg btn-block" onclick="return validateForm()">Start scanning!</button>                    
                        <div class="row">
                            <a href="dashboard.php" class="btn btn-default">Cancel</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        <div id="scanningArea" style="display:none;">
            <div class="card my-3 border-dark">
                <div class="card-body">
                    <h3 class="card-title text-center">Attendance Anywhere <a href="dashboard.php" class="btn btn-sm btn-primary">Finish</a></h3>
                    <div class="row text-center">
                        <p class="card-text col-4"><b>Scanned ID:</b><br><span id="cam-qr-result">N/A</span></p>
                        <p class="card-text col-4"><b>Camera Available:</b><br><span id="cam-has-camera" class="text-success"></span></p>
                        <p class="card-text col-4"><b>Last Detected At:</b><br><span id="cam-qr-result-timestamp"></span></p>
                    </div>
                </div>
            </div>

            <div class="row">
                <video muted playsinline id="qr-video"></video>
            </div>

            <script type="module">
                import QrScanner from "../assets/qr-scanner/qr-scanner.min.js";
                QrScanner.WORKER_PATH = '../assets/qr-scanner/qr-scanner-worker.min.js';

                const video = document.getElementById('qr-video');
                const camHasCamera = document.getElementById('cam-has-camera');
                const camQrResult = document.getElementById('cam-qr-result');
                const camQrResultTimestamp = document.getElementById('cam-qr-result-timestamp');
                const fileSelector = document.getElementById('file-selector');
                const fileQrResult = document.getElementById('file-qr-result');

                var previousVolunteerId = '';

                function flashWithTheIntersect(result) {
                    var scannedCode = result.substring(4);

                    camQrResult.textContent = result;
                    camQrResultTimestamp.textContent = new Date().toString();
                    camQrResult.style.color = 'teal';
                    clearTimeout(camQrResult.highlightTimeout);
                    camQrResult.highlightTimeout = setTimeout(() => camQrResult.style.color = 'inherit', 100);

                    if(!isNaN(scannedCode) && previousVolunteerId != scannedCode) {
                        confirmAttendance(scannedCode);
                        previousVolunteerId = scannedCode;
                        beep();
                    }
                }

                // Webcam Scanning
                QrScanner.hasCamera().then(hasCamera => camHasCamera.textContent = hasCamera);
                const scanner = new QrScanner(video, result => flashWithTheIntersect(result));
                scanner.start();
            </script>
        </div>

        <?php include "footer.php"; ?>
    </div>

    <script src="../assets/jQuery/jquery-3.4.1.min.js"></script>
    <script src="../assets/bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
