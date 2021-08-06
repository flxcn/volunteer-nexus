<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["sponsor_loggedin"]) || $_SESSION["sponsor_loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Include config file
require_once '../classes/SponsorOpportunityCreation.php';
$sponsor_id = trim($_SESSION["sponsor_id"]);
$obj = new SponsorOpportunityCreation($sponsor_id);

// Define variable
if(isset($_GET["event_id"])) {
  $event_id = trim($_GET["event_id"]);
} else {
  $event_id = "";
}

$opportunity_name = "";
$description = "";
$start_date = "";
$end_date = "";
$start_time = "";
$end_time = "";
$total_positions = "";
$limit_per_volunteer = 1;
$contribution_value = "";
$needs_verification = "";
$needs_reminder = "";

//define and initialize error message variables
$event_id_error = "";
$sponsor_id_error = "";
$opportunity_name_error = "";
$description_error = "";
$start_date_error = "";
$end_date_error = "";
$start_time_error = "";
$end_time_error = "";
$total_positions_error = "";
$limit_per_volunteer_error = "";
$contribution_value_error = "";
$needs_verification_error = "";
$needs_reminder_error = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    $event_id = trim($_POST["event_id"]);
    $obj->setEventId($event_id);

    // Validate opportunity_name
    $opportunity_name = trim($_POST["opportunity_name"]);
    $opportunity_name_error = $obj->setOpportunityName($opportunity_name);

    // Validate description
    $description = trim($_POST["description"]);
    $description_error = $obj->setDescription($description);

    // Validate start_date
    $start_date = trim($_POST["start_date"]);
    $start_date_error = $obj->setStartDate($start_date);

    // Validate end_date
    $end_date = trim($_POST["end_date"]);
    $end_date_error = $obj->setEndDate($end_date);

    // Validate start_time
    $start_time = trim($_POST["start_time"]);
    $start_time_error = $obj->setStartTime($start_time);

    // Validate end_time
    $end_time = trim($_POST["end_time"]);
    $end_time_error = $obj->setEndTime($end_time);

    // Validate total_positions
    $total_positions = trim($_POST["total_positions"]);
    $total_positions_error = $obj->setTotalPositions($total_positions);

    // Validate limit_per_volunteer
    $limit_per_volunteer = trim($_POST["limit_per_volunteer"]);
    $limit_per_volunteer_error = $obj->setLimitPerVolunteer($limit_per_volunteer);

    // Validate contribution_value
    $contribution_value = trim($_POST["contribution_value"]);
    $contribution_value_error = $obj->setContributionValue($contribution_value);

    // Validate needs_verification
    $needs_verification = trim($_POST["needs_verification"]);
    $obj->setNeedsVerification($needs_verification);

    // Validate needs_reminder
    $needs_reminder = 0;
    $obj->setNeedsReminder($needs_reminder);

    // Check input errors before inserting in database
    if( empty($event_id_error) 
        && empty($sponsor_id_error) 
        && empty($opportunity_name_error) 
        && empty($description_error) 
        && empty($start_date_error) 
        && empty($end_date_error) 
        && empty($start_time_error) 
        && empty($end_time_error) 
        && empty($total_positions_error) 
        && empty($limit_per_volunteer_error) 
        && empty($contribution_value_error) 
        && empty($needs_verification_error) 
        && empty($needs_reminder_error)
    ) 
    {
        if($obj->addOpportunity()) 
        {
            header("Location: event-read.php?event_id=" . $event_id);
            exit();
        } else {
            echo "Something went wrong. Please try again later. If the issue persists, send an email to felix@volunteernexus.com detailing the problem.";
        }
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Opportunity</title>

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
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Create Opportunity</h2>
                    </div>
                    <p>Please fill this form and submit to add a new opportunity to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <!--form for opportunity name-->
                        <div class="form-group <?php echo (!empty($opportunity_name_error)) ? 'has-error' : ''; ?>">
                            <label>Opportunity Name</label>
                            <input type="text" name="opportunity_name" class="form-control" value="<?php echo $opportunity_name; ?>">
                            <span class="help-block"><?php echo $opportunity_name_error;?></span>
                        </div>

                        <!--form for description-->
                        <div class="form-group <?php echo (!empty($description_error)) ? 'has-error' : ''; ?>"> <!-- NOTE:see {2} -->
                            <label>Description</label>
                            <textarea type="text" name="description" class="form-control"><?php echo $description; ?></textarea>
                            <span class="help-block"><?php echo $description_error;?></span>
                        </div>

                        <!--form for start_date-->
                        <div class="form-group <?php echo (!empty($start_date_error)) ? 'has-error' : ''; ?>"> <!-- NOTE:see {2} -->
                            <label>Start Date</label>
                            <input type="date" name="start_date" class="form-control" value="<?php echo $start_date; ?>">
                            <span class="help-block"><?php echo $start_date_error;?></span>
                        </div>

                        <!--form for end_date-->
                        <div class="form-group <?php echo (!empty($end_date_error)) ? 'has-error' : ''; ?>"> <!-- NOTE:see {2} -->
                            <label>End Date</label>
                            <input type="date" name="end_date" class="form-control" value="<?php echo $end_date; ?>">
                            <span class="help-block"><?php echo $end_date_error;?></span>
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

                        <!--form for total_positions-->
                        <div class="form-group <?php echo (!empty($total_positions_error)) ? 'has-error' : ''; ?>">
                            <label>Total Positions</label>
                            <input type="number" name="total_positions" class="form-control" value="<?php echo $total_positions; ?>">
                            <span class="help-block"><?php echo $total_positions_error;?></span>
                        </div>

                        <!--form for limit_per_volunteer-->
                        <div class="form-group <?php echo (!empty($limit_per_volunteer_error)) ? 'has-error' : ''; ?>">
                            <label>Sign-up Limit per Volunteer</label>
                            <p>How many times is a volunteer allowed to sign up for this event?</p>
                            <input type="number" min="1" step="1" name="limit_per_volunteer" class="form-control" value="<?php echo $limit_per_volunteer; ?>">
                            <span class="help-block"><?php echo $limit_per_volunteer_error;?></span>
                        </div>

                        <!--form for contribution_value-->
                        <div class="form-group <?php echo (!empty($contribution_value_error)) ? 'has-error' : ''; ?>">
                            <label>Contribution Value</label>
                            <input type="number" min="0" step="any" name="contribution_value" class="form-control" value="<?php echo $contribution_value; ?>">
                            <span class="help-block"><?php echo $contribution_value_error;?></span>
                        </div>

                        <!--form for needs_verification-->
                        <div class="form-group <?php echo (!empty($needs_verification_error)) ? 'has-error' : ''; ?>">
                            <label for="needs_verification">Needs verification?</label>
                            <p>Do volunteers need their contribution verified in this opportunity?</p>
                            <input type="radio" name="needs_verification" value="1"> Yes
                            <input type="radio" name="needs_verification" value="0" checked> No
                            <span class="help-block"><?php echo $needs_verification_error;?></span>
                        </div>

                        <!--form for needs_reminder-->
                        <!-- <div class="form-group <?php // echo (!empty($needs_reminder_error)) ? 'has-error' : ''; ?>">
                            <label for="needs_reminder">Needs reminder?</label>
                            <p>Do volunteers need a reminder the day before?</p>
                            <input type="radio" name="needs_reminder" value="1"> Yes
                            <input type="radio" name="needs_reminder" value="0" checked> No
                            <span class="help-block"><?php // echo $needs_reminder_error;?></span>
                        </div> -->

                        <input type="hidden" name="event_id" value="<?php echo $event_id;?>">
                        <input type="hidden" name="sponsor_id" value="<?php echo $sponsor_id;?>">

                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="event-read.php?event_id=<?php echo $event_id; ?>" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include '../footer.php';?>
</body>
</html>
