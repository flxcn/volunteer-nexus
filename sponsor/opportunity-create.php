<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["sponsor_loggedin"]) || $_SESSION["sponsor_loggedin"] !== true){
    header("location: sign-in.php");
    exit;
}

if(!isset($_GET["event_id"]) && empty(trim($_GET["event_id"]))) {
    header("location: error.php");
    exit();
}

require_once '../classes/SponsorOpportunity.php';
$sponsor_id = trim($_SESSION["sponsor_id"]);
$obj = new SponsorOpportunity($sponsor_id);

// Define variable
$event_id = trim($_GET["event_id"]);

$opportunity_name = "";
$description = "";
$start_date = "";
$end_date = "";
$start_time = "";
$end_time = "";
$total_positions = "";
$limit_per_volunteer = 1;
$contribution_value = 0.0;
$contribution_type = "";
$contribution_type_display = "";
$needs_verification = 0;
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

require_once "../classes/SponsorEvent.php";
$sponsorEventObj = new SponsorEvent($_SESSION["sponsor_id"]);
$event_id =  trim($_GET["event_id"]);

if($sponsorEventObj->getEvent($event_id)) {
    $event_name = $sponsorEventObj->getEventName(); 
    $contribution_type = $sponsorEventObj->getContributionType();
    $contribution_type_display = $sponsorEventObj->getFormattedContributionType($contribution_type);
    $start_date = $sponsorEventObj->getEventStart();
    $end_date = $sponsorEventObj->getEventEnd();
}
else{
    echo "Existing event details unavailable.";
} 

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
else {

    if(isset($_GET["event_id"]) && !empty(trim($_GET["event_id"]))) {

        require_once "../classes/SponsorEvent.php";
        $sponsorEventObj = new SponsorEvent($_SESSION["sponsor_id"]);
        $event_id =  trim($_GET["event_id"]);

        if($sponsorEventObj->getEvent($event_id)) {
            $event_name = $sponsorEventObj->getEventName(); 
            $contribution_type = $sponsorEventObj->getContributionType();
            $contribution_type_display = $sponsorEventObj->getFormattedContributionType($contribution_type);
            $start_date = $sponsorEventObj->getEventStart();
            $end_date = $sponsorEventObj->getEventEnd();
        }
        else{
            echo "Existing event details unavailable.";
            exit();
        } 
    }
    else {
        header("location: error.php");
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <link rel="icon" type="image/png" href="assets/images/volunteernexus-logo-1.png">

    <title>Create Opportunity</title>

    <!-- Bootstrap core CSS -->
    <link href="../assets/bootstrap-5.0.2-dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../assets/css/form.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container">
        <div class="py-5 text-center">
            <img class="d-block mx-auto mb-4" src="../assets/images/volunteernexus-logo-1.png" alt="" width="72" height="72">
            <h2>Create Opportunity</h2>
        </div>
        
        <div class="row">
            <div class="col-md-12 d-flex justify-content-center order-md-1">
                <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post" class="row g-2">

                    <!--form for sponsor-->
                    <div class="row"> 
                        <div class="mb-3">
                            <label for="opportunity_name">Opportunity Name</label>
                            <div class="input-group">
                                <input type="text" name="opportunity_name" id="opportunity_name" class="form-control" value="<?php echo $opportunity_name; ?>">
                            </div>
                            <span class="help-block text-danger"><?php echo $opportunity_name_error;?></span>
                        </div>
                    </div>

                    <!--form for description-->
                    <div class="row">
                        <div class="mb-3">
                            <label for="description">Description</label>
                            <div class="input-group">
                                <textarea type="text" name="description" id="description" class="form-control"><?php echo $description; ?></textarea>
                            </div>      
                            <span class="help-block text-danger"><?php echo $description_error;?></span>
                        </div>
                    </div>

                    <hr class="mb-3">

                    <div class="row">
                        <!--form for opportunity dates-->
                        <div class="col-12 col-md-6 mb-3">
                            <p>Start Date & Time</p>
                            <div class="input-group">
                                <input required type="date" name="start_date" class="form-control" value="<?php echo $start_date; ?>">
                                <span class="input-group-text">@</span>
                                <input required type="time" name="start_time" class="form-control" value="<?php echo $start_time; ?>">
                            </div>
                            <div class="row">
                                <span class="help-block text-danger col-6"><?php echo $start_date_error;?></span>
                                <span class="help-block text-danger col-6"><?php echo $start_time_error;?></span>
                            </div>
                        </div>
                        
                        <!--form for event_end-->
                        <div class="col-12 col-md-6 mb-3">
                            <p>End Date & Time</p>
                            <div class="input-group">
                                <input required type="date" name="end_date" class="form-control" value="<?php echo $end_date; ?>">
                                <span class="input-group-text">@</span>
                                <input required type="time" name="end_time" class="form-control" value="<?php echo $end_time; ?>">
                            </div>
                            <div class="row">
                                <span class="help-block text-danger col-6"><?php echo $end_date_error;?></span>
                                <span class="help-block text-danger col-6"><?php echo $end_time_error;?></span>
                            </div>
                        </div>
                    </div>

                    <hr class="mb-3">

                    <!--form for needs_verification-->
                    <div class="row"> 
                        <div class="mb-3">
                            <div class="form-check">
                                <input type='hidden' value='1' name='needs_verification'>
                                <input class="form-check-input" type="checkbox" value="0" name="needs_verification" id="needs_verification" <?php if($needs_verification===0){echo "checked";}?>>
                                <label class="form-check-label" for="needs_verification">
                                    Immediately approve all volunteer sign-ups
                                </label>
                            </div>
                            <span class="help-block text-danger"><?php echo $needs_verification_error;?></span>
                        </div>
                    </div>

                    <div class="row">
                        <!--form for contribution_value-->
                        <div class="col-12 col-md-4 mb-3">
                            <label for="contribution_value">Contribution value</label>
                            <div class="input-group">
                                <input required type="number" min="0" step="any" name="contribution_value" id="contribution_value" class="form-control" value="<?php echo $contribution_value; ?>">
                                <?php echo $contribution_type_display; ?>
                            </div>      
                            <span class="help-block text-danger"><?php echo $contribution_value_error;?></span>
                        </div>

                        <!--form for limit_per_volunteer-->
                        <div class="col-12 col-md-4 mb-3">
                            <label for="limit_per_volunteer">Sign-up limit per volunteer</label>
                            <div class="input-group">
                                <input required type="number" min="1" step="1" name="limit_per_volunteer" id="limit_per_volunteer" class="form-control" value="<?php echo $limit_per_volunteer; ?>">
                            </div>      
                            <span class="help-block text-danger"><?php echo $limit_per_volunteer_error;?></span>
                        </div>

                        <!--form for total_positions-->
                        <div class="col-12 col-md-4 mb-3">
                            <label for="total_positions">Total positions (optional)</label>
                            <div class="input-group">
                                <input type="number" name="total_positions" id="total_positions" class="form-control" value="<?php echo $total_positions; ?>">
                            </div>      
                            <span class="help-block text-danger"><?php echo $total_positions_error;?></span>
                        </div>
                    </div>

                    <hr class="mb-3">

                    <input type="hidden" name="event_id" value="<?php echo $event_id;?>">
                    <input type="hidden" name="sponsor_id" value="<?php echo $sponsor_id;?>">

                    <button class="w-100 btn btn-primary btn-lg btn-block" type="submit">Create opportunity</button>

                    <a href="event-read.php?event_id=<?php echo $event_id; ?>" class="btn btn-default">Cancel</a>
                </form>
            </div>
        </div>

        <?php include 'footer.php';?>

    </div>

    <!-- Custom js for this page -->
    <script src="../assets/js/form.js"></script>
    <script src="../assets/jQuery/jquery-3.4.1.min.js"></script>

</body>
</html>