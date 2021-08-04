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
                <img class="d-block mx-auto mb-4" src="../assets/images/volunteernexus-logo-1.png" alt="" width="72" height="72">
                <h2>Attendance Anywhere</h2>
                <p class="lead">Fill out this form to start scanning Volunteer IDs.</p>
            </div>

            <div class="row">
                <div class="col-md-12 d-flex justify-content-center order-md-1">
                    <form name="engagementForm">
                    
                        <h4 class="mb-3">Volunteer details</h4>

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

                        <input type="button" class="w-100 btn btn-primary btn-lg btn-block" value="Start scanning!" onclick="return validateForm()">
                        <a href="dashboard.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>
        </div>

        <div class="row" id="scanningArea" style="display:none;">
            <div class="card my-3 border-dark">
                <div class="card-body">
                    <h5 class="card-title text-center">Scanner <a href="dashboard.php" class="btn btn-primary">Finish</a></h5>
                    <div class="row text-center">
                        <p class="card-text col-4">Camera Access:<br><span id="cam-has-camera" class="text-success"></span></p>
                        <p class="card-text col-4">Scanned ID:<br><span id="cam-qr-result">N/A</span></p>
                        <p class="card-text col-4">Last Detected At:<br><span id="cam-qr-result-timestamp"></span></p>
                    </div>
                </div>
            </div>

            <video muted playsinline id="qr-video"></video>

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

                // NOTE: Doesn't work on Safari, possibly check out audio sprites?
                function beep() {
                    var snd = new Audio("data:audio/wav;base64,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qJrx4KOQjahZxWbcZgztj2c49nKmkId44S71j0c8eV9yDK6uPRzx5X18eDvjvQ6yKo9ZSS6l//8elePK/Lf//IInrOF/FvDoADYAGBMGb7FtErm5MXMlmPAJQVgWta7Zx2go+8xJ0UiCb8LHHdftWyLJE0QIAIsI+UbXu67dZMjmgDGCGl1H+vpF4NSDckSIkk7Vd+sxEhBQMRU8j/12UIRhzSaUdQ+rQU5kGeFxm+hb1oh6pWWmv3uvmReDl0UnvtapVaIzo1jZbf/pD6ElLqSX+rUmOQNpJFa/r+sa4e/pBlAABoAAAAA3CUgShLdGIxsY7AUABPRrgCABdDuQ5GC7DqPQCgbbJUAoRSUj+NIEig0YfyWUho1VBBBA//uQZB4ABZx5zfMakeAAAAmwAAAAF5F3P0w9GtAAACfAAAAAwLhMDmAYWMgVEG1U0FIGCBgXBXAtfMH10000EEEEEECUBYln03TTTdNBDZopopYvrTTdNa325mImNg3TTPV9q3pmY0xoO6bv3r00y+IDGid/9aaaZTGMuj9mpu9Mpio1dXrr5HERTZSmqU36A3CumzN/9Robv/Xx4v9ijkSRSNLQhAWumap82WRSBUqXStV/YcS+XVLnSS+WLDroqArFkMEsAS+eWmrUzrO0oEmE40RlMZ5+ODIkAyKAGUwZ3mVKmcamcJnMW26MRPgUw6j+LkhyHGVGYjSUUKNpuJUQoOIAyDvEyG8S5yfK6dhZc0Tx1KI/gviKL6qvvFs1+bWtaz58uUNnryq6kt5RzOCkPWlVqVX2a/EEBUdU1KrXLf40GoiiFXK///qpoiDXrOgqDR38JB0bw7SoL+ZB9o1RCkQjQ2CBYZKd/+VJxZRRZlqSkKiws0WFxUyCwsKiMy7hUVFhIaCrNQsKkTIsLivwKKigsj8XYlwt/WKi2N4d//uQRCSAAjURNIHpMZBGYiaQPSYyAAABLAAAAAAAACWAAAAApUF/Mg+0aohSIRobBAsMlO//Kk4soosy1JSFRYWaLC4qZBYWFRGZdwqKiwkNBVmoWFSJkWFxX4FFRQWR+LsS4W/rFRb/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////VEFHAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAU291bmRib3kuZGUAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAMjAwNGh0dHA6Ly93d3cuc291bmRib3kuZGUAAAAAAAAAACU=");
                    snd.play();
                }

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

                // ####### Web Cam Scanning #######

                QrScanner.hasCamera().then(hasCamera => camHasCamera.textContent = hasCamera);

                const scanner = new QrScanner(video, result => flashWithTheIntersect(result));
                scanner.start();

            </script>
        </div>

        <footer class="my-5 pt-5 text-muted text-center text-small">
            <p class="mb-1">&copy; 2021 Felix Chen</p>
        </footer>
    </div>

    <!-- Custom js for this page -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
</body>
</html>
