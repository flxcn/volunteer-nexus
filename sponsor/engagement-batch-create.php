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
$student_ids = "";
$event_id = "";
$opportunity_id = "";
$contribution_value = "";
$status = "";
$output = "";

// Define and initialize error message variables
$sponsor_id_error = "";
$student_ids_error = "";
$event_name_error = "";
$opportunity_name_error = "";
$contribution_value_error = '';
$status_error = "";

// Instantiate EngagementFormPopulator object
require_once '../classes/EngagementFormPopulator.php';
$engagementFormPopulatorObj = new EngagementFormPopulator($sponsor_id);

// Populate event_name & event_id array for "event name" dropdown boxes, and initialize JSON object
$jsonEvents = $engagementFormPopulatorObj->getEvents();

// Populate opportunity_name, opportunity_id, and event_id array for "opportunity name" dropdown boxes, and initialize JSON object
$jsonOpportunities = $engagementFormPopulatorObj->getOpportunities();

// Process Form Submission
if($_SERVER["REQUEST_METHOD"] == "POST")
{

    $output .= "<div class='row'>";
    $output .=    "<div class='col-12 mb-3'>";
    $output .=       "<pre class='bg-dark text-light p-3 mt-1 rounded' id='output'>";

    $output .=          "<b>Processing of Student IDs</b>\n";
    $output .=          "----------------------------\n";

    $failed_student_ids = array();
    // Instantiate EngagementCreation object
    require_once '../classes/EngagementCreation.php';
    $engagementCreationObj = new EngagementCreation($sponsor_id);

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

    // Check input errors before inserting in database
    if(empty($sponsor_id_error) && empty($event_name_error) && empty($opportunity_name_error) && empty($contribution_value_error) && empty($status_error))
    {
        // Convert string input of student_ids, delimited by newline character, to string array of student_ids
        $student_ids = preg_split('/\s+/', trim($_POST["student_ids"]));

        // Loop through input of student_ids to find corresponding volunteer_id to each student_id
        foreach($student_ids as $student_id)
        {
        $output .= "Student ID = " . $student_id . "\n";

        if($engagementCreationObj->setVolunteerIdByStudentId($student_id))
        {
            $output .= "\t<span class='text-success'>SUCCESS: Volunteer ID Retrieval</span>\n";

            if($engagementCreationObj->addEngagement())
            {
                $output .= "\t<span class='text-success'>SUCCESS: Engagement Addition</span>\n";

            }
            else
            {
                $output .= "\t<span class='text-danger'>FAILURE: Engagement Addition</span>\n";
                array_push($failed_student_ids, $student_id);
            }
        }
        else
        {
            $output .= "\t<span class='text-danger'>FAILURE: Volunteer ID Retrieval</span>\n";
            array_push($failed_student_ids, $student_id);
        }
        }

        $output .= "----------------------------\n";
        $output .= "<b>List of Failed Student IDs:</b>\n";
        $output .= "----------------------------\n";

        foreach($failed_student_ids as $failed_student_id) {
            $output .= "<span class='text-danger'>".$failed_student_id . "</span>\n";
        }

        $output .= "----------------------------\n";

        $output .=        "<span class='text-info'><b>NOTE:</b> Make sure your volunteers have added their Student IDs to their profiles.<br>Direct them to \"Profile\" -> \"Update Profile\"<br>(https://volunteernexus.com/volunteer/profile-update.php)</span>\n";      

        $output .=        "</pre>";      
        $output .=    "</div>";
        $output .= "</div>"; 
        $output .= "<hr class='mb-3'>";
   
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
    <style>
        #output {
            max-height: 300px;
            overflow: scroll;
        }
    </style>

    <!-- Custom JS for this page -->
    <script type='text/javascript'>
      <?php
        echo "var events = $jsonEvents; \n";
        echo "var opportunities = $jsonOpportunities; \n";
      ?>
    </script>
    <script src="../assets/js/engagement-create.js"></script>
</head>
<body class="bg-light" onload='loadEvents();'>
    <div class="container">
        <div class="py-5 text-center">
            <img class="d-block mx-auto mb-4" src="../assets/images/volunteernexus-logo-1.png" alt="" width="72" height="72">
            <h2>Record Engagement</h2>
            <p>Use this form to add multiple engagements using Student IDs.</p>
            <p><b><i>NOTE:</i></b> Use with caution. Do not refresh this page.</p>
        </div>
        
        <div class="row">
            <div class="col-md-12 d-flex justify-content-center order-md-1">
                <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post" class="row g-2" id="engagementForm">

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

                    <!--form for student_ids-->
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="student_ids">Student IDs</label>
                            <div class="input-group">
                                <textarea type="text" name="student_ids" class="form-control"><?php
                                    if(!($student_ids == "")) {
                                        foreach($failed_student_ids as $failed_student_id) {
                                            echo $failed_student_id . "\n";
                                        }
                                    }
                                ?></textarea>
                            </div>      
                            <span class="help-block text-danger"><?php echo $student_ids_error;?></span>
                        </div>
                    </div>

                    <hr class="mb-3">

                    <?php echo $output; ?>

                    <div class="row">

                    <button class="w-100 btn btn-primary btn-lg btn-block" type="submit">Record engagements</button>

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