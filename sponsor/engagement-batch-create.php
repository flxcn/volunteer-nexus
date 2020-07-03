<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["sponsor_loggedin"]) || $_SESSION["sponsor_loggedin"] !== true){
    header("location: login.php");
    exit;
}


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

// Instantiate EngagementFormPopulator object
require_once '../classes/EngagementFormPopulator.php';
$engagementFormPopulatorObj = new EngagementFormPopulator($sponsor_id);

// Populate volunteer array for "volunteer name" dropdown boxes, and initialize JSON object
$jsonVolunteers = $engagementFormPopulatorObj->getVolunteers();

// Populate event_name & event_id array for "event name" dropdown boxes, and initialize JSON object
$jsonEvents = $engagementFormPopulatorObj->getEvents();

// Populate opportunity_name, opportunity_id, and event_id array for "opportunity name" dropdown boxes, and initialize JSON object
$jsonOpportunities = $engagementFormPopulatorObj->getOpportunities();


// Process Form Submission
if($_SERVER["REQUEST_METHOD"] == "POST")
{

  $output = "Processing of Student IDs\n";
  $output .= "****************************************\n";

  $failed_student_ids = array();
  // Instantiate EngagementCreation object
  require_once '../classes/EngagementCreation.php';
  $engagementCreationObj = new EngagementCreation($sponsor_id);

  // Validate volunteer_id from "volunteer_name" selector 
  $volunteer_id = trim($_POST["volunteer_name"]);
  $volunteer_name_error = $engagementCreationObj->setVolunteerId($volunteer_id);

  // Validate event_id from "event_id" selector
  $event_id = trim($_POST["event_name"]);
  $event_name_error = $engagementCreationObj->setEventId($event_id);
 
  // Validate opportunity_id and contribution value from "opportunity_name" selector
  $opportunity_values = json_decode($_POST["opportunity_name"]);
  $opportunity_id = $opportunity_values[0];
  $opportunity_name_error = $engagementCreationObj->setOpportunityId($opportunity_id);
  $contribution_value = $opportunity_values[1];
  $opportunity_name_error = $engagementCreationObj->setContributionValue($contribution_value);
  
  // Set status of whether the engagement needs verification
  $status = trim($_POST["status"]);
  $engagementCreationObj->setStatus($status);

  // Set sponsor_id
  // $sponsor_id = $_SESSION["sponsor_id"];

  // Check input errors before inserting in database
  if(empty($sponsor_id_error) && empty($volunteer_name_error) && empty($event_name_error) && empty($opportunity_name_error) && empty($contribution_value_error) && empty($status_error)) 
  {

    // Convert string input of student_ids, delimited by newline character, to string array of student_ids
    //$student_ids = explode("\n", trim($_POST["student_ids"]));
    $student_ids = preg_split('/\s+/', trim($_POST["student_ids"]));

    // Loop through input of student_ids to find corresponding volunteer_id to each student_id
    foreach($student_ids as $student_id)
    {
      $output .= "Student ID = " . $student_id . "\n";

      if($engagementCreationObj->setVolunteerIdByStudentId($student_id))
      {
        $output .= "\tSUCCESS: Volunteer ID Retrieval\n";

        if($engagementCreationObj->addEngagement())
        {
            $output .= "\tSUCCESS: Engagement Addition\n";

        }
        else
        {
            $output .= "\tFAILURE: Engagement Addition\n";
            array_push($failed_student_ids, $student_id);
        }
      }
      else
      {
        $output .= "\tFAILURE: Volunteer ID Retrieval\n";
        array_push($failed_student_ids, $student_id);
      }
    }

    $output .= "****************************************\n";
    $output .= "List of Failed Student IDs:\n";
    foreach($failed_student_ids as $failed_student_id) {
        $output .= $failed_student_id . "\n";
    }

    echo "<textarea readonly name='output' rows='10' cols='40'>";
    echo $output;
    echo "</textarea>";

  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Create Engagement</title>

    <!--Load required libraries-->
    <?php $pageContent='Form'?>
    <?php include '../head.php'?>

    <style type="text/css">
        .wrapper{ width: 350px; padding: 20px; }
    </style>

    <script type='text/javascript'>
      <?php
        echo "var volunteers = $jsonVolunteers; \n";
        echo "var events = $jsonEvents; \n";
        echo "var opportunities = $jsonOpportunities; \n";
      ?>

      function loadVolunteers(){
        var select = document.getElementById("volunteersSelect");
        for(var i = 0; i < volunteers.length; i++){
          select.options[i] = new Option(volunteers[i].volunteer_name, volunteers[i].volunteer_id);
        }
      }

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
        opportunitiesSelect.options[0] = new Option('Select Opportunity', "{'opportunity_id':'','contribution_value':''}");
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
        console.log(opportunityValues[1]);
      }
    </script>
</head>

<!-- onload could be revised to be less obtrusive -->
<body onload='loadVolunteers(); loadEvents();'>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Create Engagement</h2>
                    </div>
                    <p>Please fill this form and submit to add a new engagement for an affiliated volunteer.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                        <!--form for student_ids-->
                        <div class="form-group <?php echo (!empty($student_ids_error)) ? 'has-error' : ''; ?>">
                            <label>Student IDs</label>
                            <textarea type="text" name="student_ids" class="form-control"><?php
                                if(!($student_ids == "")) {
                                  foreach($failed_student_ids as $failed_student_id) {
                                    echo $failed_student_id . "\n";
                                  }
                                }
                              ?></textarea>
                            <span class="help-block"><?php echo $student_ids_error;?></span>
                        </div>

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
                        <!-- NOTE: May want to allow this value to be edited for more flexibility on behalf of the sponsors -->
                        <div class="form-group">
                            <label>Contribution Value</label>
                            <p class="form-control-static" id='contributionValue'></p>
                        </div>

                        <!-- form for contribution_value
                        <div class="form-group <?php //echo (!empty($contribution_value_error)) ? 'has-error' : ''; ?>">
                            <label>Contribution Value</label>
                            <input type="number" name="contribution_value" class="form-control" value="<?php //echo $contribution_value; ?>" id='contributionValue'>
                            <span class="help-block"><?php // echo $contribution_value_error;?></span>
                        </div> -->

                        <!--form for status-->
                        <div class="form-group <?php echo (!empty($status_error)) ? 'has-error' : ''; ?>">
                            <label for="status">Verified?</label>
                            <p>Is this engagement already verified?</p>
                            <input type="radio" name="status" value="1" checked> Yes
                            <input type="radio" name="status" value="0"> No
                            <span class="help-block"><?php echo $status_error;?></span>
                        </div>



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
