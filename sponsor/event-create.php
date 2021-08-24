<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["sponsor_loggedin"]) || $_SESSION["sponsor_loggedin"] !== true){
    header("location: sign-in.php");
    exit;
}

// Include config file
require_once "../classes/SponsorEvent.php";
$obj = new SponsorEvent($_SESSION['sponsor_id']);

// Define variables and initialize with empty values
$event_name = "";
$sponsor_name = "";
$description = "";
$location = "";
$contribution_type = "";
$contact_name = "";
$contact_phone = "";
$contact_email = "";
$registration_start = "";
$registration_end = "";
$event_start = "";
$event_end = "";
$is_public = "";

//define and initialize error message variables
$event_name_error = "";
$sponsor_name_error = "";
$description_error = "";
$location_error = "";
$contribution_type_error = "";
$contact_name_error = "";
$contact_phone_error = "";
$contact_email_error = "";
$registration_start_error = "";
$registration_end_error = "";
$event_start_error = "";
$event_end_error = "";
$is_public_error = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Get hidden input value
    $contribution_type = $_SESSION["contribution_type"];
    $contribution_type_error = $obj->setContributionType($contribution_type);

    // Validate event name
    $event_name = trim($_POST["event_name"]);
    $event_name_error = $obj->setEventName($event_name);

    // Validate sponsor
    $sponsor_name = $_SESSION["sponsor_name"];
    $sponsor_name_error = $obj->setSponsorName($sponsor_name);

    // Validate description
    $description = trim($_POST["description"]);
    $description_error = $obj->setDescription($description);

    // Validate location
    $location = trim($_POST["location"]);
    $location_error = $obj->setLocation($location);

    // Validate contact_name
    $contact_name = trim($_POST["contact_name"]);
    $contact_name_error = $obj->setContactName($contact_name);

    // Validate contact_phone
    $contact_phone = trim($_POST["contact_phone"]);
    $contact_phone_error = $obj->setContactPhone($contact_phone);

    // Validate contact_email
    $contact_email = trim($_POST["contact_email"]);
    $contact_email_error = $obj->setContactEmail($contact_email);

    // Validate registration_start
    $registration_start = trim($_POST["registration_start"]);
    $registration_start_error = $obj->setRegistrationStart($registration_start);

    // Validate registration_end
    $registration_end = trim($_POST["registration_end"]);
    $registration_end_error = $obj->setRegistrationEnd($registration_end);

    // Validate event_start
    $event_start = trim($_POST["event_start"]);
    $event_start_error = $obj->setEventStart($event_start);

    // Validate event_end
    $event_end = trim($_POST["event_end"]);
    $event_end_error = $obj->setEventEnd($event_end);

    // Check is_public
    $is_public = trim($_POST["is_public"]);
    $is_public_error = $obj->setIsPublic($is_public);


    // Check input errors before inserting in database
    if( empty($event_name_error) 
        && empty($description_error) 
        && empty($location_error) 
        && empty($contribution_type_error) 
        && empty($registration_start_error) 
        && empty($registration_end_error) 
        && empty($event_start_error) 
        && empty($event_end_error) ) {

        if($obj->addEvent()) {

            header("location: event-read.php?event_id=" . $obj->getEventIdFromLastInsert() );
            exit();
        
        } else {
            echo "Something went wrong. Please try again later.";
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

    <title>Create Event</title>

    <!-- Bootstrap core CSS -->
    <link href="../assets/bootstrap-5.0.2-dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../assets/css/form.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container">
        <div class="py-5 text-center">
            <img class="d-block mx-auto mb-4" src="../assets/images/volunteernexus-logo-1.png" alt="" width="72" height="72">
            <h2>Create Event</h2>
        </div>
        
        <div class="row">
            <div class="col-md-12 d-flex justify-content-center order-md-1">
                <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post" class="row g-2">

                    <!--form for sponsor-->
                    <div class="row"> 
                        <div class="mb-3">
                            <label for="event_name">Event Name</label>
                            <div class="input-group">
                                <input type="text" name="event_name" id="event_name" class="form-control" value="<?php echo $event_name; ?>">
                            </div>
                            <span class="help-block text-danger"><?php echo $event_name_error;?></span>
                        </div>
                    </div>

                    <!--form for is_public-->
                    <div class="row"> 
                        <div class="mb-3">
                            <p>Who can see this event?</p>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="is_public" id="is_public_1" value="0" <?php if($is_public==0){echo "checked";}?>> 
                                <label class="form-check-label" for="is_public_1">Affiliated Volunteers Only</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="is_public" id="is_public_2" value="1" <?php if($is_public==1){echo "checked";}?>> 
                                <label class="form-check-label" for="is_public_2">Everyone</label>
                            </div>
                            <span class="help-block text-danger"><?php echo $is_public_error;?></span>
                        </div>
                    </div>

                    <!--form for location-->
                    <div class="row">
                        <div class="mb-3">
                            <label for="location">Location</label>
                            <div class="input-group">
                                <input type="text" name="location" class="form-control" value="<?php echo $location; ?>">
                            </div>
                            <span class="help-block text-danger"><?php echo $location_error;?></span>
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
                        <!--form for contact_name-->
                        <div class="mb-3 col-md-4">
                            <label for="contact_name">Contact Name</label>
                            <div class="input-group">
                                <input type="text" name="contact_name" id="contact_name" class="form-control" value="<?php echo $contact_name; ?>" >
                            </div>
                            <span class="help-block text-danger"><?php echo $contact_name_error;?></span>
                        </div>

                        <!--form for contact_phone-->
                        <div class="mb-3 col-md-4">
                            <label for="contact_phone">Contact Phone</label>
                            <div class="input-group">
                                <input type="tel" name="contact_phone" id="contact_phone" class="form-control" value="<?php echo $contact_phone; ?>">
                            </div>
                            <span class="help-block text-danger"><?php echo $contact_phone_error;?></span>
                        </div>

                        <!--form for contact_email-->
                        <div class="mb-3 col-md-4">
                            <label for="contact_email">Contact Email</label>
                            <div class="input-group">
                                <input type="email" name="contact_email" class="form-control" value="<?php echo $contact_email; ?>">
                            </div>
                            <span class="help-block text-danger"><?php echo $contact_email_error;?></span>
                        </div>
                    </div>

                    <hr class="mb-3">

                    <div class="row">
                        <!--form for registration dates-->
                        <div class="col-12 mb-3">
                            <p>Registration Period</p>
                            <div class="input-group">
                                <span class="input-group-text">Start Date</span>
                                <input type="date" name="registration_start" class="form-control" value="<?php echo $registration_start; ?>">
                                <span class="input-group-text">End Date</span>
                                <input type="date" name="registration_end" id="registration_end"class="form-control" value="<?php echo $registration_end; ?>">
                                
                            </div>
                            <span class="help-block text-danger"><?php echo $registration_start_error;?></span>
                            <span class="help-block text-danger"><?php echo $registration_end_error;?></span>
                        </div>
                        
                        <!--form for event_end-->
                        <div class="col-12 mb-3">
                            <p>Event Period</p>
                            <div class="input-group">
                                <span class="input-group-text">Start Date</span>
                                <input type="date" name="event_start" id="event_start" class="form-control" value="<?php echo $event_start; ?>">
                                <span class="input-group-text">End Date</span>
                                <input type="date" name="event_end" id="event_end" class="form-control" value="<?php echo $event_end; ?>">
                            </div>
                            <span class="help-block text-danger"><?php echo $event_start_error;?></span>
                            <span class="help-block text-danger"><?php echo $event_end_error;?></span>
                        </div>
                    </div>

                    <hr class="mb-3">

                    <button class="w-100 btn btn-primary btn-lg btn-block" type="submit">Create event</button>

                    <a href="events.php" class="btn btn-default">Cancel</a>
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
