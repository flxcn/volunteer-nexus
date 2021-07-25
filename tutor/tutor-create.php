<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["volunteer_loggedin"]) || $_SESSION["volunteer_loggedin"] !== true){
    header("location: ../volunteer/sign-in.php");
    exit;
}

if($_SESSION["is_tutor"] && $_SESSION["is_tutor"] == true) {
    echo "you are already registered as a tutor";
    header("location: tutor-update.php");
}

// echo     disk_total_space("../");

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
$error = "";
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
    $description = $_POST["description"];
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
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <link rel="icon" type="image/png" href="assets/images/volunteernexus-logo-1.png">

    <title>Tutor Sign-up</title>

    <!-- Bootstrap core CSS -->
    <link href="../assets/bootstrap-5.0.2-dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../assets/css/form.css" rel="stylesheet">
</head>

<body class="bg-light" onload='loadSponsors();'>
    
    <div class="container">
        <div class="py-3 text-center">
            <img class="d-block mx-auto mb-4" src="../assets/images/volunteernexus-logo-1.png" alt="" width="72" height="72">
            <h2>Tutor Sign-up</h2>
            <!-- <p class="lead">Please fill this form and submit to request engagement for tutoring.</p> -->
        </div>

        <div class="text-danger text-center"><?php echo $error; ?></div>

        <div class="row">
            <div class="col-md-12 d-flex justify-content-center order-md-1">
                <form class="needs-validation" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" novalidate="" oninput='confirm_password.setCustomValidity(confirm_password.value != password.value ? "Passwords do not match." : "")'>
                
                    <h4 class="mb-3">Tutorial details</h4>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="first_name">First name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" placeholder="" value="<?php echo $_SESSION['first_name'];?>" readonly>
                        
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="last_name">Last name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" placeholder="" value="<?php echo $_SESSION['last_name'];?>" readonly>
                        </div>
                    </div>


                    <div class="mb-3">
                        <label for="phone_number">Phone Number <small>(Format: 123-456-7890)</small></label>
                        <input type="tel" class="form-control" id="contribution_value" name="phone_number" placeholder="###-###-####" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" required>
                        <div class="invalid-feedback">
                            Please enter a valid email address for your username.
                        </div>
                    </div>

                    

                    <!-- Make this better, perhaps build it off of affiliations, to reduce redundancy. -->
                    <div class="mb-3">
                        <label for="graduation_year">Sponsor</label>
                        <select class="form-select d-block w-100" name='sponsor_name' id='sponsorsSelect' class="form-control" required>
                        </select>
                    </div>

                    <h4 class="mb-3">Areas of Expertise</h4>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="geometry">
                            <label class="form-check-label" for="geometry">
                                Geometry
                            </label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="alg1">
                            <label class="form-check-label" for="alg1">
                                Algebra I
                            </label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="alg2">
                            <label class="form-check-label" for="alg2">
                                Algebra II
                            </label>
                        </div>

                        <!-- <div class="invalid-feedback">
                            Please enter a valid email address for your username.
                        </div> -->
                    </div>

                    <h4 class="mb-3">Target Audience</h4>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="elem">
                            <label class="form-check-label" for="elem">
                                Elementary schoolers
                            </label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="mid">
                            <label class="form-check-label" for="mid">
                                Middle schoolers
                            </label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="high">
                            <label class="form-check-label" for="high">
                                High schoolers
                            </label>
                        </div>

                        <!-- <div class="invalid-feedback">
                            Please enter a valid email address for your username.
                        </div> -->
                    </div>

                    <h4 class="mb-3">Availability</h4>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_time">Start Time</label>
                            <input type="time" class="form-control" id="start_time" name="start_time" min="00:00" max="23:59" required>
                            <div class="invalid-feedback">
                                Valid start time is required.
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="end_time">End Time</label>
                            <input type="time" class="form-control" id="end_time" name="end_time" min="00:00" max="23:59" required>
                            <div class="invalid-feedback">
                                Valid end time is required.
                            </div>
                        </div>
                    </div>

                    <hr class="mb-4">

                    <button class="w-100 btn btn-primary btn-lg btn-block" type="submit">Record your tutoring!</button>
                </form>
            </div>
        </div>

        <footer class="my-5 pt-5 text-muted text-center text-small">
            <p class="mb-1">&copy; 2021 Felix Chen</p>
        </footer>
    </div>

    <!-- Custom js for this page -->
    <!-- <script src="../assets/js/form.js"></script> -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    
    <script>
        $("body").on("submit", "form", function() {
            $(this).submit(function() {
                return false;
            });
            return true;
        });
    </script>

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
</body>
</html>