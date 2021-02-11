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
$sponsor_values = "";
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
require_once '../classes/TutorialEngagementFormPopulator.php';
$tutorialEngagementFormPopulatorObj = new TutorialEngagementFormPopulator($volunteer_id);

// Populate volunteer array for "volunteer name" dropdown boxes, and initialize JSON object
$jsonSponsors = $tutorialEngagementFormPopulatorObj->getSponsors();


// Process Form Submission
if($_SERVER["REQUEST_METHOD"] == "POST")
{
    // Obtain values from "sponsor_name" selector
    $sponsor_values = json_decode($_POST['sponsor_name']);
    $sponsor_name = $sponsor_values[0];
    $sponsor_id = $sponsor_values[1];
  

    // Instatiate TutorialEngagementCreation object
    require_once '../classes/TutorialEngagementCreation.php';
    $tutorialEngagementCreationObj = new TutorialEngagementCreation($volunteer_id, $sponsor_id);

    // Validate sponsor_name
    $sponsor_name_error = $tutorialEngagementCreationObj->setSponsorName($sponsor_name);

    // Validate sponsor_id
    $tutorialEngagementCreationObj->setSponsorId($sponsor_id);
    
    // Set opportunity_name for this Tutorial
    $last_name = $_SESSION['last_name'];
    $first_name = $_SESSION['first_name'];
    $tutorialEngagementCreationObj->setOpportunityName($last_name, $first_name);

    // Validate date of tutorial from "date"
    $date = trim($_POST["date"]);
    $date_error = $tutorialEngagementCreationObj->setStartDate($date);
    $date_error = $tutorialEngagementCreationObj->setEndDate($date);

    $start_time = trim($_POST["start_time"]);

    $end_time = trim($_POST["end_time"]);

    // Validate start time of tutorial from "start_time"
    $input_start_time = trim($_POST["start_time"]);
    if(empty($input_start_time)){
        $start_time_error = "Please enter a start time.";
    } else{
        // $date = DateTime::createFromFormat( 'H:i A', $input_start_time);
        // $temp_start_time = $date->format( 'H:i:s');
	      // $start_time = $temp_start_time;
        $start_time = date("H:i", strtotime($input_start_time));
        $start_time_error = $tutorialEngagementCreationObj->setStartTime($start_time);
    }

    // Validate end time of tutorial from "end_time"
    $input_end_time = trim($_POST["end_time"]);
    if(empty($input_end_time)){
        $end_time_error = "Please enter an end time.";
    } else{
      // $date = DateTime::createFromFormat( 'H:i A', $input_end_time);
      // $temp_end_time = $date->format( 'H:i:s');
      // $end_time = $temp_end_time;
      $end_time = date("H:i", strtotime($input_end_time));
      $end_time_error = $tutorialEngagementCreationObj->setEndTime($end_time);
    }


    

    // Validate contribution value from "contribution_value"
    $contribution_value = $_POST["contribution_value"];
    $contribution_value_error = $tutorialEngagementCreationObj->setContributionValue($contribution_value);

    // Validate description from "description"
    $description = $_POST["contribution_value"];
    $description_error = $tutorialEngagementCreationObj->setDescription($description);

    // Set status of whether the engagement needs verification (always true)
    // $needs_verification = trim($_POST["needs_verification"]);

    if(empty($sponsor_name_error) && empty($volunteer_name_error) && empty($event_name_error) && empty($contribution_value_error)) 
    {
        // Check to see that a Tutoring event exists for the selected Sponsor
        if($tutorialEngagementCreationObj->doesTutorialEventExist()) 
        {
            // Get event_id of Tutoring Event [try not to fold it into the check function]
            
            // Add tutorial engagement
            if($tutorialEngagementCreationObj->addTutorial()) 
            {
                header("Location: dashboard.php");
                exit();
            }
            else 
            {
                echo "Something went wrong. Please try again later. If the issue persists, send an email to felix@volunteernexus.com detailing the problem.";
            }
            
        }
        else 
        {
            // Create event, get event id of newly generated event             
            // Save event_id from this event
            /*
            if($tutorialEngagementCreationObj->addTutorialEvent()) 
            {
                // Add tutorial engagement
                // Create engagement based on the event
                if($tutorialEngagementCreationObj->addEngagement()) 
                {
                    header("Location: dashboard.php");
                    exit();
                }
                else 
                {
                    echo "Something went wrong. Please try again later. If the issue persists, send an email to felix@volunteernexus.com detailing the problem.";
                }
            }
            */
            echo "Sorry, this Sponsoring organization does not yet offer Tutoring.";

        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Create Tutorial Engagement</title>

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
            console.log(sponsors);
            var select = document.getElementById("sponsorsSelect");
            for(var i = 0; i < sponsors.length; i++){
                var sponsorValue = [sponsors[i].sponsor_name, sponsors[i].sponsor_id];
                select.options[i] = new Option(sponsors[i].sponsor_name, JSON.stringify(sponsorValue));
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
                        <h2>Create Tutorial Engagement</h2>
                    </div>

                    <p>Please fill this form and submit to request engagement for tutoring.</p>

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

                        <!-- <input type="hidden" name="needs_verification" value="1"> -->

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