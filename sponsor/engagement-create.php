<?php
session_start();

// Make sure user is logged in
if(!isset($_SESSION["sponsor_loggedin"]) || $_SESSION["sponsor_loggedin"] == true){
    header("location: login.php");
    exit;
}

require_once '../config.php';

// Define all variables
$email = ""; //NOTE: this value will be readonly {1}
$event_name = "";
$role_name = "";
$sponsor_id = $_SESSION["sponsor_id"]; //NOTE: this value will be readonly {1}
$contribution_value = "";
$status = "";
$contact_phone = "";
$contact_email = "";
$registration_start = "";
$registration_end = "";
$event_start = "";
$event_end = "";

//Define all error variables
$event_name_error = "";
$sponsor_error = "";
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

// Data Validation + SQL
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate event name
    $input_event_name = trim($_POST["event_name"]);
    if(empty($input_event_name)){
        $event_name_error = "Please enter an event name.";
    }else{
        $event_name = $input_event_name;
    }

    // Validate sponsor //NOTE: refer to-do list {1}
    $input_sponsor = trim($_POST["sponsor"]);
    if(empty($input_sponsor)){
        $sponsor_error = "Please enter a sponsor.";
    } else{
        $sponsor = $input_sponsor;
    }

    // Validate description // NOTE: refer to-do list {3}
    $input_description = trim($_POST["description"]);
    if(empty($input_description)){
        $description_error = "Please enter a description.";
    } else{
        $description = $input_description;
    }

    // Validate location // NOTE: refer to-do list {3}
    $input_location = trim($_POST["location"]);
    if(empty($input_location)){
        $location_error = "Please enter a location.";
    } else{
        $location = $input_location;
    }

    // Validate contribution_type
    $input_contribution = trim($_POST["contribution_type"]);
    if(empty($input_contribution)){
        $contribution_error = "Please enter a contribution type.";
    } else{
        $contribution_type = $contribution_type;
    }

    // Validate contact_name
    $input_contact_name = trim($_POST["contact_name"]);
    if(empty($input_contact_name)){
        $contact_name_error = "Please enter a contact name.";
    } else{
        $description = $input_description;
    }

    // Validate contact_phone
    $input_contact_phone = trim($_POST["contact_phone"]);
    if(empty($input_contact_phone)){
        $contact_phone_error = "Please enter a contact phone.";
    } else{
        $contact_phone = $input_contact_phone;
    }

    // Validate contact_email
    $input_contact_email = trim($_POST["contact_email"]);
    if(empty($input_contact_email)){
        $contact_email_error = "Please enter a contact email.";
    } else{
        $contact_email = $input_contact_email;
    }

    // Validate registration_start
    $input_registration_start = trim($_POST["registration_start"]);
    if(empty($input_registration_start)){
        $registration_start_error = "Please enter a registration start date.";
    } else{
        $registration_start = $input_registration_start;
    }

    // Validate registration_end
    $input_registration_end = trim($_POST["registration_end"]);
    if(empty($input_registration_end)){
        $registration_end_error = "Please enter a registration end date.";
    } else{
        $registration_end = $input_registration_end;
    }

    // Validate event_start
    $input_event_start = trim($_POST["event_start"]);
    if(empty($input_event_start)){
        $event_start_error = "Please enter an event start date.";
    } else{
        $event_start = $input_event_start;
    }

    // Validate event_end
    $input_event_end = trim($_POST["event_end"]);
    if(empty($input_event_end)){
        $event_end_error = "Please enter an event end date.";
    } else{
        $event_end = $input_event_end;
    }

    // Check to make sure all errors are clear
    if(empty($event_name_error) && empty($sponsor_error) && empty($description_error) && empty($location_error) && empty($contribution_type_error) && empty($registration_start_error) && empty($registration_end_error) && empty($event_start_error) && empty($event_end_error)){

        $sql = "INSERT INTO events (event_name, sponsor_name, description, location, contribution_type, contact_name, contact_phone, contact_email, registration_start, registration_end, event_start, event_end) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "ssssssssssss", $param_event_name, $param_sponsor, $param_description, $param_location, $param_contribution_type, $param_contact_name, $param_contact_phone, $param_contact_email, $param_registration_start, $param_registration_end, $param_event_start, $param_event_end);

            // Set parameters
            $param_event_name = $event_name;
            $param_sponsor = $sponsor;
            $param_description = $description;
            $param_location = $location;
            $param_contribution_type = $contribution_type;
            $param_contact_name = $contact_name;
            $param_contact_phone = $contact_phone;
            $param_contact_email = $contact_email;
            $param_registration_start = $registration_start;
            $param_registration_end = $registration_end;
            $param_event_start = $event_start;
            $param_event_end = $event_end;

            if(mysqli_stmt_execute($stmt)){
                //NOTE: Success!
                header("Location: index.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later. If the issue persists, send an email to westlakestuco@gmail.com detailing the problem.";
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
    <title>Create Event</title>

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
    
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Create Event</h2>
                    </div>
                    <p>Please fill this form and submit to add a new event to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                        <!--form for event name-->
                        <div class="form-group <?php echo (!empty($event_name_error)) ? 'has-error' : ''; ?>">
                            <label>Event Name</label>
                            <input type="text" name="event_name" class="form-control" value="<?php echo $event_name; ?>">
                            <span class="help-block"><?php echo $event_name_error;?></span>
                        </div>

                        <!--form for sponsor-->
                        <div class="form-group <?php echo (!empty($sponsor_error)) ? 'has-error' : ''; ?>">
                            <label>Sponsor</label>
                            <input name="sponsor" class="form-control"><?php echo $sponsor; ?>
                            <span class="help-block"><?php echo $sponsor_error;?></span>
                        </div>

                        <!--form for description-->
                        <div class="form-group <?php echo (!empty($description_error)) ? 'has-error' : ''; ?>"> <!-- NOTE:see {2} -->
                            <label>Description</label>
                            <textarea type="text" name="description" class="form-control" value="<?php echo $description; ?>"></textarea>
                            <span class="help-block"><?php echo $description_error;?></span>
                        </div>

                        <!--form for location-->
                        <div class="form-group <?php echo (!empty($location_error)) ? 'has-error' : ''; ?>">
                            <label>Location</label>
                            <input type="text" name="location" class="form-control" value="<?php echo $location; ?>">
                            <span class="help-block"><?php echo $location_error;?></span>
                        </div>

                        <!--form for contribution_type-->
                        <div class="form-group <?php echo (!empty($contribution_type_error)) ? 'has-error' : ''; ?>">
                            <label>Contribution Type</label>
                            <input type="text" name="contribution_type" class="form-control"><?php echo $contribution_type; ?>
                            <span class="help-block"><?php echo $contribution_type_error;?></span>
                        </div>

                        <!--form for contact_name-->
                        <div class="form-group <?php echo (!empty($contact_name_error)) ? 'has-error' : ''; ?>"> <!-- NOTE:see {2} -->
                            <label>Contact Name</label>
                            <input type="text" name="contact_name" class="form-control" value="<?php echo $contact_name; ?>">
                            <span class="help-block"><?php echo $contact_name_error;?></span>
                        </div>

                        <!--form for contact_phone-->
                        <div class="form-group <?php echo (!empty($contact_phone_error)) ? 'has-error' : ''; ?>">
                            <label>Contact Phone</label>
                            <input type="tel" name="contact_phone" class="form-control" value="<?php echo $contact_phone; ?>">
                            <span class="help-block"><?php echo $contact_phone_error;?></span>
                        </div>

                        <!--form for contact_email-->
                        <div class="form-group <?php echo (!empty($contact_email_error)) ? 'has-error' : ''; ?>">
                            <label>Contact Email</label>
                            <input type="email" name="contact_email" class="form-control"><?php echo $contact_email; ?>
                            <span class="help-block"><?php echo $contact_email_error;?></span>
                        </div>



                        <!--form for registration_start-->
                        <div class="form-group <?php echo (!empty($registration_start_error)) ? 'has-error' : ''; ?>"> <!-- NOTE:see {2} -->
                            <label>Registration Start</label>
                            <input type="date" name="registration_start" class="form-control"><?php echo $registration_start; ?>
                            <span class="help-block"><?php echo $registration_start_error;?></span>
                        </div>

                        <!--form for registration_end-->
                        <div class="form-group <?php echo (!empty($registration_end_error)) ? 'has-error' : ''; ?>"> <!-- NOTE:see {2} -->
                            <label>Registration End</label>
                            <input type="date" name="registration_end" class="form-control"><?php echo $registration_end; ?>
                            <span class="help-block"><?php echo $registration_end_error;?></span>
                        </div>

                        <!--form for event_start-->
                        <div class="form-group <?php echo (!empty($event_start_error)) ? 'has-error' : ''; ?>">
                            <label>Event Start Date</label>
                            <input type="date" name="event_start" class="form-control"><?php echo $event_start; ?>
                            <span class="help-block"><?php echo $event_start_error;?></span>
                        </div>

                        <!--form for event_end-->
                        <div class="form-group <?php echo (!empty($event_end_error)) ? 'has-error' : ''; ?>"> <!-- NOTE:see {2} -->
                            <label>Event End Date</label>
                            <input type="date" name="event_end" class="form-control"><?php echo $event_end; ?>
                            <span class="help-block"><?php echo $event_end_error;?></span>
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
