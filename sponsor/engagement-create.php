<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["sponsor_loggedin"]) || $_SESSION["sponsor_loggedin"] !== true){
    header("location: sign-in.php");
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

// Initialize EngagementFormPopulator object
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

    // Instatiate EngagementCreation object
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
    $contribution_value = $_POST["contribution_value"];
    $opportunity_name_error = $engagementCreationObj->setContributionValue($contribution_value);
    
    // Set status of whether the engagement needs verification
    $status = trim($_POST["status"]);
    $engagementCreationObj->setStatus($status);

    // Check input errors before inserting in database
    if(empty($sponsor_id_error) && empty($volunteer_name_error) && empty($event_name_error) && empty($opportunity_name_error) && empty($contribution_value_error) && empty($status_error)) 
    {
        if($engagementCreationObj->addEngagement()) {
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
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <link rel="icon" type="image/png" href="assets/images/volunteernexus-logo-1.png">

    <title>Record Engagement</title>

    <!-- Bootstrap core CSS -->
    <link href="../assets/bootstrap-5.0.2-dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../assets/css/form.css" rel="stylesheet">

    <!-- Custom JS for this page -->
    <script type='text/javascript'>
        <?php
        echo "var volunteers = $jsonVolunteers; \n";
        echo "var events = $jsonEvents; \n";
        echo "var opportunities = $jsonOpportunities; \n";
        ?>  
    </script>
    <script src="../assets/js/engagement-create.js"></script>
</head>
<body class="bg-light" onload='loadVolunteers(); loadEvents();'>
    <div class="container">
        <div class="py-5 text-center">
            <img class="d-block mx-auto mb-4" src="../assets/images/volunteernexus-logo-1.png" alt="" width="72" height="72">
            <h2>Record Engagement</h2>
        </div>
        
        <div class="row">
            <div class="col-md-12 d-flex justify-content-center order-md-1">
                <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post" class="row g-2" id="engagementForm">

                    <!--form for volunteer-->
                    <div class="row"> 
                        <div class="mb-3">
                            <label for="volunteersSelect">Volunteer Name</label>
                            <div class="input-group">
                                <select required name='volunteer_name' id='volunteersSelect' class="form-select">
                                </select>                            
                            </div>
                            <span class="help-block text-danger"><?php echo $volunteer_name_error;?></span>
                        </div>
                    </div>

                    <!--form for event-->
                    <div class="row"> 
                        <div class="mb-3">
                            <label for="eventsSelect">Event Name</label>
                            <div class="input-group">
                                <select required name='event_name' id='eventsSelect' class="form-select">
                                </select>                           
                            </div>
                            <span class="help-block text-danger"><?php echo $event_name_error;?></span>
                        </div>
                    </div>

                    <!--form for opportunity-->
                    <div class="row"> 
                        <div class="mb-3">
                            <label for="opportunitiesSelect">Opportunity Name</label>
                            <div class="input-group">
                                <select required name='opportunity_name' id='opportunitiesSelect' class="form-select">
                                </select>                           
                            </div>
                            <span class="help-block text-danger"><?php echo $opportunity_name_error;?></span>
                        </div>
                    </div>

                    <!-- display and form for contribution value -->
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="contribution_value">Contribution value</label>
                            <div class="input-group">
                                <input type="number" min="0" step="any" id='contributionValue' name="contribution_value" class="form-control" value="<?php echo $contribution_value; ?>">
                            </div>      
                            <span class="help-block text-danger"><?php echo $contribution_value_error;?></span>
                        </div>
                    </div>

                    <!--form for status-->
                    <div class="row">
                        <div class="col-12 mb-3">
                            <div class="form-check">
                                <input type='hidden' value='1' name='status'>
                                <input class="form-check-input" type="checkbox" value="0" name="status" id="status" checked>
                                <label class="form-check-label" for="status">
                                    This engagement is already verified.
                                </label>
                            </div>
                            <span class="help-block text-danger"><?php echo $status_error;?></span>
                        </div>
                    </div>

                    <hr class="mb-3">

                    <div class="row">

                    <button class="w-100 btn btn-primary btn-lg btn-block" type="submit" onclick="return validateForm()">Record engagement</button>

                    </div>

                    <a href="engagements.php" class="btn btn-default">Cancel</a>
                </form>
            </div>
        </div>

        <?php include 'footer.php';?>

    </div>

    <!-- Custom js for this page -->
    <script src="../assets/js/form.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>

</body>
</html>