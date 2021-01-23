<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["volunteer_loggedin"]) || $_SESSION["volunteer_loggedin"] !== true){
    header("location: login.php");
    exit;
}


// Define and intialize variables
$volunteer_id = $_SESSION["volunteer_id"];
$sponsor_id = "";
$date = "";
$start_time = "";
$end_time = "";
$description = "";
$contribution_value = "";

// Define and initialize error message variables
$sponsor_name_error = "";
$date_error = "";
$start_time_error = "";
$end_time_error = "";
$description_error = "";
$contribution_value_error = '';

// Initialize EngagementFormPopulator object
require_once '../classes/TutorEngagementFormPopulator.php';
$tutorEngagementFormPopulatorObj = new TutorEngagementFormPopulator($volunteer_id);

// Populate volunteer array for "volunteer name" dropdown boxes, and initialize JSON object
$jsonSponsors = $tutorEngagementFormPopulatorObj->getSponsors();


// Process Form Submission
if($_SERVER["REQUEST_METHOD"] == "POST")
{
    $sponsor_id = trim($_POST["sponsor_id"]);
    require_once '../classes/Tutor.php';
    $obj = new TutorEngagementCreation($volunteer_id, $sponsor_id);

    // Check to see that a Tutoring event exists for the selected Sponsor
    if(doesTutoringEventExist($sponsor_id) == true) {
        // Get event_id of Tutoring Event
        // Create engagement
    }
    else {
        // Create event
        // Save event_id from this event

        // Create engagement based on the event
    }
    

  // Instatiate EngagementCreation object
  require_once '../classes/TutorEngagementCreation.php';
  $tutorEngagementCreationObj = new TutorEngagementCreation($sponsor_id);

  // Validate volunteer_id from "volunteer_name" selector 
  $volunteer_id = trim($_POST["volunteer_name"]);
  $volunteer_name_error = $tutorEngagementCreationObj->setVolunteerId($volunteer_id);

  // Validate event_id from "event_id" selector
  $event_id = trim($_POST["event_name"]);
  $event_name_error = $tutorEngagementCreationObj->setEventId($event_id);
 
  // Validate opportunity_id and contribution value from "opportunity_name" selector
  $opportunity_values = json_decode($_POST["opportunity_name"]);
  $opportunity_id = $opportunity_values[0];
  $opportunity_name_error = $tutorEngagementCreationObj->setOpportunityId($opportunity_id);
  $contribution_value = $opportunity_values[1];
  $opportunity_name_error = $tutorEngagementCreationObj->setContributionValue($contribution_value);
  
  // Set status of whether the engagement needs verification
  $status = trim($_POST["status"]);
  $tutorEngagementCreationObj->setStatus($status);

  // Set sponsor_id
  // $sponsor_id = $_SESSION["sponsor_id"];

  // Check input errors before inserting in database
  if(empty($sponsor_id_error) && empty($volunteer_name_error) && empty($event_name_error) && empty($opportunity_name_error) && empty($contribution_value_error) && empty($status_error)) 
  {
    if($tutorEngagementCreationObj->addEngagement()) {
      header("Location: dashboard.php");
      exit();
    }
    else {
      echo "Something went wrong. Please try again later. If the issue persists, send an email to felix@volunteernexus.com detailing the problem.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Create Tutor Engagement</title>

    <!--Load required libraries-->
    <?php $pageContent='Form'?>
    <?php include '../head.php'?>

    <!-- Bootstrap Date-Picker Plugin -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>

    <!--datepicker-->
    <script>
    $(document).ready(function(){
      var date_input=$('input[type="date"]'); //our date input has the type "date"
      var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
      var options={
        format: 'yyyy-mm-dd',
        container: container,
        todayHighlight: true,
        autoclose: true,
      };
      date_input.datepicker(options);
    })
    </script>

    <style type="text/css">
        .wrapper{ width: 350px; padding: 20px; padding-bottom: 100px; }
    </style>

    <script type='text/javascript'>
      <?php
        echo "var sponsors = $jsonSponsors; \n";
      ?>

      function loadSponsors(){
        var select = document.getElementById("sponsorsSelect");
        for(var i = 0; i < sponsors.length; i++){
          select.options[i] = new Option(sponsors[i].sponsor_name, sponsors[i].sponsor_id);
        }
      }
    </script>
</head>

<!-- onload could be revised to be less obtrusive -->
<body onload='loadSponsors();'>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Create Tutor Engagement</h2>
                    </div>
                    <p>Please fill this form and submit to create a pending engagement for tutoring.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                        <!--form for volunteer_name-->
                        <div class="form-group">
                            <label>Volunteer Name</label>
                            <p><?php echo $_SESSION["first_name"] . " " . $_SESSION["last_name"];?></p>
                        </div>

                        <!--form for sponsor_name-->
                        <div class="form-group <?php echo (!empty($sponsor_name_error)) ? 'has-error' : ''; ?>">
                            <label>Sponsor Name</label>
                            <select name='sponsor_name' id='sponsorsSelect' class="form-control">
                            </select>
                        <span class="help-block"><?php echo $sponsor_name_error;?></span>

                        <!--form for date-->
                        <div class="form-group <?php echo (!empty($date_error)) ? 'has-error' : ''; ?>"> 
                            <label>Date</label>
                            <input type="date" name="date" class="form-control" value="<?php echo $date; ?>">
                            <span class="help-block"><?php echo $date_error;?></span>
                        </div>

                        <!--form for start_time-->
                        <div class="form-group <?php echo (!empty($start_time_error)) ? 'has-error' : ''; ?>">
                            <label>Start Time</label>
                            <input type="time" name="start_time" class="form-control" value="<?php echo $start_time; ?>">
                            <span class="help-block"><?php echo $start_time_error;?></span>
                        </div>

                        <!--form for end_time-->
                        <div class="form-group <?php echo (!empty($end_time_error)) ? 'has-error' : ''; ?>"> <!-- NOTE:see {2} -->
                            <label>End Time</label>
                            <input type="time" name="end_time" class="form-control" value="<?php echo $end_time; ?>">
                            <span class="help-block"><?php echo $end_time_error;?></span>
                        </div>

                        <!--form for contribution_value-->
                        <div class="form-group <?php echo (!empty($contribution_value_error)) ? 'has-error' : ''; ?>">
                            <label>Contribution Value</label>
                            <p>How many hours did you tutor?</p>
                            <input type="number" min="0" step="any" name="contribution_value" class="form-control" value="<?php echo $contribution_value; ?>">
                            <span class="help-block"><?php echo $contribution_value_error;?></span>
                        </div>

                        <!--form for description-->
                        <div class="form-group <?php echo (!empty($description_error)) ? 'has-error' : ''; ?>"> <!-- NOTE:see {2} -->
                            <label>Description</label>
                            <p>Who did you tutor? What subject(s) and topic(s) did you cover?</p>
                            <textarea type="text" name="description" class="form-control"><?php echo $description; ?></textarea>
                            <span class="help-block"><?php echo $description_error;?></span>
                        </div>

                        <input type="hidden" name="needs_verification" value="1">

                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="dashboard.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php include '../footer.php';?>
</body>
</html>