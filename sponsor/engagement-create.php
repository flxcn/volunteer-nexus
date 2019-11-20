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

$volunteer_name = "";
$volunteer_id = "";

$event_name = "";
$event_id = "";

$opportunity_name = "";
$opportunity_id = "";

$contribution_value = "";
$status = "";

// Define and initialize error message variables
$sponsor_id_error = "";

$volunteer_name_error = "";
$volunteer_id_error = "";

$event_name_error = "";
$event_id_error = "";

$opportunity_name_error = "";
$opportunity_id_error = "";

$contribution_value_error = '';
$status_error = "";

// Populate volunteer array for "volunteer name" dropdown boxes
$query =
"SELECT volunteers.volunteer_id AS volunteer_id, volunteers.last_name AS last_name, volunteers.first_name AS first_name
FROM volunteers
INNER JOIN affiliations ON affiliations.volunteer_id = volunteers.volunteer_id
WHERE affiliations.sponsor_id = '$sponsor_id'
ORDER BY volunteers.last_name ASC";
$result = $db->query($query);

while($row = $result->fetch_assoc()){
  $full_name = $row['last_name'] . ", " . $row['first_name'];
  $volunteers[] = array("volunteer_name" => $full_name, "volunteer_id" => $row['volunteer_id']);
}

// Populate event_name & event_id array for "event name" dropdown boxes
$query = "SELECT event_id, event_name FROM events WHERE sponsor_id = '$sponsor_id' ORDER BY start_date DESC";
$result = $db->query($query);

while($row = $result->fetch_assoc()){
  $events[] = array("event_name" => $row['event_name'], "event_id" => $row['event_id']);
}

// Populate opportunity_name, opportunity_id, and event_id array for "opportunity name" dropdown boxes
$query = "SELECT opportunity_id, contribution_value, event_id, opportunity_name FROM opportunities WHERE sponsor_id = '$sponsor_id' ORDER BY start_date DESC";
$result = $db->query($query);

while($row = $result->fetch_assoc()){
  $opportunities[$row['event_id']][] = array("opportunity_id" => $row['opportunity_id'], "opportunity_name" => $row['opportunity_name'], "contribution_value" => $row['contribution_value']);
}

// Initialize JSON Objects
$jsonVolunteers = json_encode($volunteers);
$jsonEvents = json_encode($events);
$jsonOpportunities = json_encode($opportunities);





// Process Form Submission
if($_SERVER["REQUEST_METHOD"] == "POST"){

    $volunteer_id = $input_volunteer_id;

    $event_id = $input_event_id;

    $opportunity_id = $input_opportunity_id;

    $contribution_value = '';

    $status = $input_status;

    $sponsor_id = $_SESSION["sponsor_id"];

    // Check input errors before inserting in database
    if(/*error*/){
        // Prepare an insert statement
        $sql = "INSERT INTO engagements (volunteer_id, event_id, opportunity_id, sponsor_id, contribution_value, status) VALUES (?, ?, ?, ?, ?, ?)";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "iiiiii", $param_volunteer_id, $param_event_id, $param_opportunity_id, $param_sponsor_id, $param_contribution_value, $param_status);

            // Set parameters
            $param_volunteer_id = $volunteer_id;
            $param_event_id = $event_id;
            $param_opportunity_id = $opportunity_id;
            $param_sponsor_id = $sponsor_id;
            $param_contribution_value = $contribution_value;
            $param_status = $status;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("Location: index.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later. If the issue persists, send an email to felix@volunteernexus.com detailing the problem.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Create Engagement</title>

    <!--Load required libraries-->
    <?php $pageContent='Form'?>
    <?php include '../head.php'?>

    <script type='text/javascript'>
      <?php
        echo "var volunteers = $jsonVolunteers; \n";
        echo "var events = $jsonEvents; \n";
        echo "var opportunities = $jsonOpportunities; \n";
      ?>

      function loadVolunteers(){
        var select = document.getElementById("volunteersSelect");
        // NOTE: could add a default option such as "Select Volunteer"; just make sure to adjust the array accordingly. Also validate data later.
        // select.options[0] = new Option('Select Volunteer','0',true,true);
        for(var i = 0; i < volunteers.length; i++){
          select.options[i] = new Option(volunteers[i].volunteer_name, volunteers[i].volunteer_id);
        }
      }

      function loadEvents(){
        var select = document.getElementById("eventsSelect");
        select.onchange = updateOpportunities;
        // See NOTE above.
        // select.options[0] = new Option('Select Volunteer','0',true,true);
        for(var i = 0; i < events.length; i++){
          select.options[i] = new Option(events[i].event_name, events[i].event_id);
        }
      }

      function updateOpportunities(){
        var eventSelect = this;
        var eventId = this.value;
        var opportunitySelect = document.getElementById("opportunitiesSelect");
        subcatSelect.options.length = 0; //delete all options if any present
        // See NOTE above.
        // select.options[0] = new Option('Select Opportunity','0',true,true);
        for(var i = 0; i < opportunities[eventId].length; i++){
          var opportunityValue = [opportunities[eventId][i].opportunity_id, opportunities[eventId][i].contribution_value];
          opportunitiesSelect.options[i] = new Option(opportunities[eventId][i].opportunity_name, opportunityValue);
        }
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

                        <!--form for volunteer_name-->
                        <div class="form-group <?php echo (!empty($volunteer_name_error)) ? 'has-error' : ''; ?>">
                            <label>Volunteer Name</label>
                            <select name='volunteer_name' id='volunteersSelect' class="form-control">
                            </select>
                            <span class="help-block"><?php echo $volunteer_name_error;?></span>
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
