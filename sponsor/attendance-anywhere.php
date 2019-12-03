<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["sponsor_loggedin"]) || $_SESSION["sponsor_loggedin"] !== true){
  header("location: login.php");
  exit;
}

// Include config file
require_once '../config.php';

// Define and intialize variables
$sponsor_id = $_SESSION["sponsor_id"];
$volunteer_id = "";
$event_id = "";
$opportunity_id = "";
$contribution_value = "";
$status = "";

// Define and initialize error message variables
$sponsor_id_error = "";
$volunteer_name_error = "";
$event_name_error = "";
$opportunity_name_error = "";
$contribution_value_error = '';
$status_error = "";






// Populate volunteer array for "volunteer name" dropdown boxes
$query =
"SELECT volunteers.volunteer_id AS volunteer_id, volunteers.last_name AS last_name, volunteers.first_name AS first_name
FROM volunteers
INNER JOIN affiliations ON affiliations.volunteer_id = volunteers.volunteer_id
WHERE affiliations.sponsor_id = '$sponsor_id'";
$result = $link->query($query);

$volunteers[] = array("volunteer_name" => 'Select Name', "volunteer_id" => '');
while($row = $result->fetch_assoc()){
  $full_name = $row['last_name'] . ", " . $row['first_name'];
  $volunteers[] = array("volunteer_name" => $full_name, "volunteer_id" => $row['volunteer_id']);
}

// Populate event_name & event_id array for "event name" dropdown boxes
$query = "SELECT event_id, event_name FROM events WHERE sponsor_id = '$sponsor_id'";
$result = $link->query($query);

$events[] = array("event_name" => 'Select Event', "event_id" => '');
while($row = $result->fetch_assoc()){
  $events[] = array("event_name" => $row['event_name'], "event_id" => $row['event_id']);
}

// Populate opportunity_name, opportunity_id, and event_id array for "opportunity name" dropdown boxes
$query = "SELECT opportunity_id, contribution_value, event_id, opportunity_name FROM opportunities WHERE sponsor_id = '$sponsor_id' ORDER BY start_date DESC";
$result = $link->query($query);

while($row = $result->fetch_assoc()){
  //$opportunities[$row['event_id']][] = array("opportunity_name" => 'Select Opportunity', "opportunity_id" => '',  "contribution_value" => '');
  $opportunities[$row['event_id']][] = array("opportunity_name" => $row['opportunity_name'], "opportunity_id" => $row['opportunity_id'], "contribution_value" => $row['contribution_value']);
}

// Initialize JSON Objects
$jsonVolunteers = json_encode($volunteers);
$jsonEvents = json_encode($events);
$jsonOpportunities = json_encode($opportunities);

?>















<!DOCTYPE html>
<html lang="en">
<head>
  <title>Attendance Anywhere</title>

  <!--Load required libraries-->
  <?php $pageContent='Form'?>
  <?php include '../head.php'?>

  <!-- <style type="text/css">
  .wrapper{ width: 350px; padding: 20px; }
  </style> -->

  <script>
    <?php
    $sponsorId = $_SESSION['sponsor_id'];
    echo "var sponsorId = $sponsorId; \n";
    ?>
    var eventId = '';
    var opportunityId = '';
    var contributionValue = '';
  </script>

  <style type="text/css">
      #initialForm{ width: 350px; padding: 20px; margin: 0 auto;}
  </style>

  <script type='text/javascript'>
    <?php
    echo "var volunteers = $jsonVolunteers; \n";
    echo "var events = $jsonEvents; \n";
    echo "var opportunities = $jsonOpportunities; \n";
    ?>

    // function loadVolunteers(){
    //   var select = document.getElementById("volunteersSelect");
    //   for(var i = 0; i < volunteers.length; i++){
    //     select.options[i] = new Option(volunteers[i].volunteer_name, volunteers[i].volunteer_id);
    //   }
    // }

    function loadEvents(){
      var select = document.getElementById("eventsSelect");
      select.onchange = updateOpportunities;
      for(var i = 0; i < events.length; i++){
        select.options[i] = new Option(events[i].event_name, events[i].event_id);
      }
    }

    function updateOpportunities(){
      var eventSelect = this;
      var eventId = this.value;
      var opportunitySelect = document.getElementById("opportunitiesSelect");
      opportunitiesSelect.options.length = 0; // clear previous options
      opportunitiesSelect.options[0] = new Option('Select Opportunity', '');
      if (typeof opportunities[eventId] != 'undefined') {
        for(var i = 0; i < opportunities[eventId].length; i++){
          var opportunityValue = [opportunities[eventId][i].opportunity_id, opportunities[eventId][i].contribution_value];
          opportunitiesSelect.options[1+i] = new Option(opportunities[eventId][i].opportunity_name, JSON.stringify(opportunityValue));
        }
        opportunitySelect.onchange = updateContributionValue;
      }

    }

    function updateContributionValue(){
      var opportunitySelect = document.getElementById('opportunitiesSelect');
      var contributionValue = document.getElementById('contributionValue');
      var opportunityValues = JSON.parse(opportunitySelect.value);
      contributionValue.innerHTML = opportunityValues[1];
      //console.log(opportunityValues[1]);
    }

    function validateForm() {
      console.log(document.forms["engagementForm"]["event_name"].value);
      console.log(document.forms["engagementForm"]["opportunity_name"].value);


      // validate submittedEventId
      var submittedEventId = document.forms["engagementForm"]["event_name"].value;
      if (submittedEventId == "") {
        alert("Please select an event.");
        return false;
      }
      eventId = submittedEventId;

      // validate submittedOpportunityValues
      var submittedOpportunityValues = document.forms["engagementForm"]["opportunity_name"].value;
      if (submittedOpportunityValues == "") {
        alert("Please select an opportunity.");
        return false;
      }

      // parse OpportunityValues array to find opportunityId and contributionValue;
      var submittedOpportunityValuesArray = JSON.parse(submittedOpportunityValues);
      var submittedOpportunityId = submittedOpportunityValuesArray[0];
      opportunityId = submittedOpportunityId;
      //console.log(submittedOpportunityId);
      var submittedContributionValue = submittedOpportunityValuesArray[1];
      contributionValue = submittedContributionValue;
      //console.log(submittedContributionValue);

      // sponsorId


      document.getElementById('eventsSelect').disabled = true;
      document.getElementById('opportunitiesSelect').disabled = true;

      var initialForm = document.getElementById("initialForm");
      console.log("Works?");
      console.log(initialForm)
      if (initialForm.style.display === "none") {
        initialForm.style.display = "block";
      } else {
        initialForm.style.display = "none";
      }

      var scannerToggler = document.getElementById("scannerToggler");
      if (scannerToggler.style.display === "none") {
        scannerToggler.style.display = "block";
      } else {
        scannerToggler.style.display = "none";
      }


      startScanner();

      var scannerContainer = document.getElementById("scanner-container");
      scannerContainer.scrollIntoView(true); // Top


      return true;
    }
  </script>

  <script>
    function confirmAttendance(volunteerId) {
      var http = new XMLHttpRequest();
      var url = 'attendance-anywhere-process.php';
      var params = 'sponsor_id=' + sponsorId + '&event_id=' + eventId + '&opportunity_id=' + opportunityId + '&volunteer_id=' + volunteerId + '&contribution_value=' + contributionValue + '&status=1';
      http.open('POST', url, true);

      //Send the proper header information along with the request
      http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

      http.onreadystatechange = function() {//Call a function when the state changes.
        if(http.readyState == 4 && http.status == 200) {
          alert("Check-in complete!");
        }
      }
      http.send(params);
    }
  </script>

  <script>
  function beep() {
    var snd = new Audio("data:audio/wav;base64,//uQRAAAAWMSLwUIYAAsYkXgoQwAEaYLWfkWgAI0wWs/ItAAAGDgYtAgAyN+QWaAAihwMWm4G8QQRDiMcCBcH3Cc+CDv/7xA4Tvh9Rz/y8QADBwMWgQAZG/ILNAARQ4GLTcDeIIIhxGOBAuD7hOfBB3/94gcJ3w+o5/5eIAIAAAVwWgQAVQ2ORaIQwEMAJiDg95G4nQL7mQVWI6GwRcfsZAcsKkJvxgxEjzFUgfHoSQ9Qq7KNwqHwuB13MA4a1q/DmBrHgPcmjiGoh//EwC5nGPEmS4RcfkVKOhJf+WOgoxJclFz3kgn//dBA+ya1GhurNn8zb//9NNutNuhz31f////9vt///z+IdAEAAAK4LQIAKobHItEIYCGAExBwe8jcToF9zIKrEdDYIuP2MgOWFSE34wYiR5iqQPj0JIeoVdlG4VD4XA67mAcNa1fhzA1jwHuTRxDUQ//iYBczjHiTJcIuPyKlHQkv/LHQUYkuSi57yQT//uggfZNajQ3Vmz+Zt//+mm3Wm3Q576v////+32///5/EOgAAADVghQAAAAA//uQZAUAB1WI0PZugAAAAAoQwAAAEk3nRd2qAAAAACiDgAAAAAAABCqEEQRLCgwpBGMlJkIz8jKhGvj4k6jzRnqasNKIeoh5gI7BJaC1A1AoNBjJgbyApVS4IDlZgDU5WUAxEKDNmmALHzZp0Fkz1FMTmGFl1FMEyodIavcCAUHDWrKAIA4aa2oCgILEBupZgHvAhEBcZ6joQBxS76AgccrFlczBvKLC0QI2cBoCFvfTDAo7eoOQInqDPBtvrDEZBNYN5xwNwxQRfw8ZQ5wQVLvO8OYU+mHvFLlDh05Mdg7BT6YrRPpCBznMB2r//xKJjyyOh+cImr2/4doscwD6neZjuZR4AgAABYAAAABy1xcdQtxYBYYZdifkUDgzzXaXn98Z0oi9ILU5mBjFANmRwlVJ3/6jYDAmxaiDG3/6xjQQCCKkRb/6kg/wW+kSJ5//rLobkLSiKmqP/0ikJuDaSaSf/6JiLYLEYnW/+kXg1WRVJL/9EmQ1YZIsv/6Qzwy5qk7/+tEU0nkls3/zIUMPKNX/6yZLf+kFgAfgGyLFAUwY//uQZAUABcd5UiNPVXAAAApAAAAAE0VZQKw9ISAAACgAAAAAVQIygIElVrFkBS+Jhi+EAuu+lKAkYUEIsmEAEoMeDmCETMvfSHTGkF5RWH7kz/ESHWPAq/kcCRhqBtMdokPdM7vil7RG98A2sc7zO6ZvTdM7pmOUAZTnJW+NXxqmd41dqJ6mLTXxrPpnV8avaIf5SvL7pndPvPpndJR9Kuu8fePvuiuhorgWjp7Mf/PRjxcFCPDkW31srioCExivv9lcwKEaHsf/7ow2Fl1T/9RkXgEhYElAoCLFtMArxwivDJJ+bR1HTKJdlEoTELCIqgEwVGSQ+hIm0NbK8WXcTEI0UPoa2NbG4y2K00JEWbZavJXkYaqo9CRHS55FcZTjKEk3NKoCYUnSQ0rWxrZbFKbKIhOKPZe1cJKzZSaQrIyULHDZmV5K4xySsDRKWOruanGtjLJXFEmwaIbDLX0hIPBUQPVFVkQkDoUNfSoDgQGKPekoxeGzA4DUvnn4bxzcZrtJyipKfPNy5w+9lnXwgqsiyHNeSVpemw4bWb9psYeq//uQZBoABQt4yMVxYAIAAAkQoAAAHvYpL5m6AAgAACXDAAAAD59jblTirQe9upFsmZbpMudy7Lz1X1DYsxOOSWpfPqNX2WqktK0DMvuGwlbNj44TleLPQ+Gsfb+GOWOKJoIrWb3cIMeeON6lz2umTqMXV8Mj30yWPpjoSa9ujK8SyeJP5y5mOW1D6hvLepeveEAEDo0mgCRClOEgANv3B9a6fikgUSu/DmAMATrGx7nng5p5iimPNZsfQLYB2sDLIkzRKZOHGAaUyDcpFBSLG9MCQALgAIgQs2YunOszLSAyQYPVC2YdGGeHD2dTdJk1pAHGAWDjnkcLKFymS3RQZTInzySoBwMG0QueC3gMsCEYxUqlrcxK6k1LQQcsmyYeQPdC2YfuGPASCBkcVMQQqpVJshui1tkXQJQV0OXGAZMXSOEEBRirXbVRQW7ugq7IM7rPWSZyDlM3IuNEkxzCOJ0ny2ThNkyRai1b6ev//3dzNGzNb//4uAvHT5sURcZCFcuKLhOFs8mLAAEAt4UWAAIABAAAAAB4qbHo0tIjVkUU//uQZAwABfSFz3ZqQAAAAAngwAAAE1HjMp2qAAAAACZDgAAAD5UkTE1UgZEUExqYynN1qZvqIOREEFmBcJQkwdxiFtw0qEOkGYfRDifBui9MQg4QAHAqWtAWHoCxu1Yf4VfWLPIM2mHDFsbQEVGwyqQoQcwnfHeIkNt9YnkiaS1oizycqJrx4KOQjahZxWbcZgztj2c49nKmkId44S71j0c8eV9yDK6uPRzx5X18eDvjvQ6yKo9ZSS6l//8elePK/Lf//IInrOF/FvDoADYAGBMGb7FtErm5MXMlmPAJQVgWta7Zx2go+8xJ0UiCb8LHHdftWyLJE0QIAIsI+UbXu67dZMjmgDGCGl1H+vpF4NSDckSIkk7Vd+sxEhBQMRU8j/12UIRhzSaUdQ+rQU5kGeFxm+hb1oh6pWWmv3uvmReDl0UnvtapVaIzo1jZbf/pD6ElLqSX+rUmOQNpJFa/r+sa4e/pBlAABoAAAAA3CUgShLdGIxsY7AUABPRrgCABdDuQ5GC7DqPQCgbbJUAoRSUj+NIEig0YfyWUho1VBBBA//uQZB4ABZx5zfMakeAAAAmwAAAAF5F3P0w9GtAAACfAAAAAwLhMDmAYWMgVEG1U0FIGCBgXBXAtfMH10000EEEEEECUBYln03TTTdNBDZopopYvrTTdNa325mImNg3TTPV9q3pmY0xoO6bv3r00y+IDGid/9aaaZTGMuj9mpu9Mpio1dXrr5HERTZSmqU36A3CumzN/9Robv/Xx4v9ijkSRSNLQhAWumap82WRSBUqXStV/YcS+XVLnSS+WLDroqArFkMEsAS+eWmrUzrO0oEmE40RlMZ5+ODIkAyKAGUwZ3mVKmcamcJnMW26MRPgUw6j+LkhyHGVGYjSUUKNpuJUQoOIAyDvEyG8S5yfK6dhZc0Tx1KI/gviKL6qvvFs1+bWtaz58uUNnryq6kt5RzOCkPWlVqVX2a/EEBUdU1KrXLf40GoiiFXK///qpoiDXrOgqDR38JB0bw7SoL+ZB9o1RCkQjQ2CBYZKd/+VJxZRRZlqSkKiws0WFxUyCwsKiMy7hUVFhIaCrNQsKkTIsLivwKKigsj8XYlwt/WKi2N4d//uQRCSAAjURNIHpMZBGYiaQPSYyAAABLAAAAAAAACWAAAAApUF/Mg+0aohSIRobBAsMlO//Kk4soosy1JSFRYWaLC4qZBYWFRGZdwqKiwkNBVmoWFSJkWFxX4FFRQWR+LsS4W/rFRb/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////VEFHAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAU291bmRib3kuZGUAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAMjAwNGh0dHA6Ly93d3cuc291bmRib3kuZGUAAAAAAAAAACU=");
    snd.play();
  }
  </script>

  <script src="../quaggaJS/quagga.min.js"></script>
  <script>
  Quagga.init({
    inputStream : {
      name : "Live",
      type : "LiveStream",
      // Or '#yourElement' (optional)
      target: document.querySelector('#yourElement')
    },
    decoder : {
      readers : ["code_39_reader"]
    }
  }, function(err) {
    if (err) {
      console.log(err);
      return;
    }
    console.log("Initialization finished. Ready to start");
    Quagga.start();
  });
  </script>
  <style>
  /* In order to place the tracking correctly */
  canvas.drawing, canvas.drawingBuffer {
    position: absolute;
    left: 0;
    top: 0;
  }
  </style>

</head>

<body onload='loadEvents();'>
  <div class="wrapper">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="page-header">
            <h2>Attendance Anywhere</h2>
          </div>
          <div id="initialForm">
            <p>Please fill this form and submit to add a new engagement for an affiliated volunteer.</p>
            <form name="engagementForm">

              <!--form for volunteer_name-->
              <!-- <div class="form-group <?php echo (!empty($volunteer_name_error)) ? 'has-error' : ''; ?>">
              <label>Volunteer Name</label>
              <select name='volunteer_name' id='volunteersSelect' class="form-control">
              </select>
              <span class="help-block"><?php echo $volunteer_name_error;?></span>
              </div> -->

              <!--form for event_name-->
              <div class="form-group <?php echo (!empty($event_name_error)) ? 'has-error' : ''; ?>">
                <label>Event Name</label>
                <select name='event_name' id='eventsSelect' class="form-control">
                </select>
                <span class="help-block"><?php echo $event_name_error;?></span>
              </div>

              <!--form for opportunity_name-->
              <div class="form-group <?php echo (!empty($opportunity_name_error)) ? 'has-error' : ''; ?>">
                <label>Opportunity Name</label>
                <select name='opportunity_name' id='opportunitiesSelect' class="form-control">
                </select>
                <span class="help-block"><?php echo $opportunity_name_error;?></span>
              </div>

              <!-- display for contribution value -->
              <div class="form-group">
                <label>Contribution Value</label>
                <p class="form-control-static" id='contributionValue'></p>
              </div>


              <input type="hidden" name="status" value="1">
              <input type="button" class="btn btn-success" value="Proceed!" onclick="return validateForm()">
              <a href="dashboard.php" class="btn btn-default">Cancel</a>
            </form>
          </div>

          <!-- Div to show the scanner -->
          <div id="scanner-container"></div>

          <input type="btn" id="scannerToggler" class="btn btn-primary" value="Finish Scanning" style="display:none; text-align:center">


          <!-- Include the image-diff library -->
          <script src="../quaggaJS/quagga.min.js"></script>

          <script>
          var _scannerIsRunning = false;

          function startScanner() {
            Quagga.init({
              inputStream: {
                name: "Live",
                type: "LiveStream",
                target: document.querySelector('#scanner-container'),
                constraints: {
                  width: window.innerWidth,
                  height: window.innerHeight,
                  facingMode: "environment"
                },
              },
              decoder: {
                readers: [
                  "code_128_reader",
                  "ean_reader",
                  "ean_8_reader",
                  "code_39_reader",
                  "code_39_vin_reader",
                  "codabar_reader",
                  "upc_reader",
                  "upc_e_reader",
                  "i2of5_reader"
                ],
                debug: {
                  showCanvas: true,
                  showPatches: true,
                  showFoundPatches: true,
                  showSkeleton: true,
                  showLabels: true,
                  showPatchLabels: true,
                  showRemainingPatchLabels: true,
                  boxFromPatches: {
                    showTransformed: true,
                    showTransformedBox: true,
                    showBB: true
                  }
                }
              },

            }, function (err) {
              if (err) {
                console.log(err);
                return
              }

              console.log("Initialization finished. Ready to start");
              Quagga.start();

              // Set flag to is running
              _scannerIsRunning = true;
            });

            Quagga.onProcessed(function (result) {
              var drawingCtx = Quagga.canvas.ctx.overlay,
              drawingCanvas = Quagga.canvas.dom.overlay;

              if (result) {
                if (result.boxes) {
                  drawingCtx.clearRect(0, 0, parseInt(drawingCanvas.getAttribute("width")), parseInt(drawingCanvas.getAttribute("height")));
                  result.boxes.filter(function (box) {
                    return box !== result.box;
                  }).forEach(function (box) {
                    Quagga.ImageDebug.drawPath(box, { x: 0, y: 1 }, drawingCtx, { color: "green", lineWidth: 2 });
                  });
                }

                if (result.box) {
                  Quagga.ImageDebug.drawPath(result.box, { x: 0, y: 1 }, drawingCtx, { color: "#00F", lineWidth: 2 });
                }

                if (result.codeResult && result.codeResult.code) {
                  Quagga.ImageDebug.drawPath(result.line, { x: 'x', y: 'y' }, drawingCtx, { color: 'red', lineWidth: 3 });
                }
              }
            });

            var previousStudentId = '';
            Quagga.onDetected(function (result) {
              var scannedCode = result.codeResult.code;
              console.log("Barcode detected and processed : [" + result.codeResult.code + "]", result);
              if(previousStudentId != scannedCode && scannedCode.length == 5){
                confirmAttendance(scannedCode);
                previousStudentId = scannedCode;
                beep();
              }

            });
          }


          // Start/stop scanner
          document.getElementById("scannerToggler").addEventListener("click", function () {
            if (_scannerIsRunning) {
              Quagga.stop();
            } else {
              startScanner();
            }
          }, false);
          </script>
        </div>
      </div>
    </div>
  </div>
  <?php include '../footer.php';?>
</body>
</html>
