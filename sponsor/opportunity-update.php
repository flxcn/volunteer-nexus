<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["sponsor_loggedin"]) || $_SESSION["sponsor_loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Include config file
require_once "../config.php";

// Define variables and initialize with empty values
$opportunity_name = "";
$description = "";
$start_date = "";
$start_time = "";
$end_date = "";
$end_time = "";
$total_positions = "";
$limit_per_volunteer = "";
$contribution_value = "";
$needs_verification = "";
$needs_reminder = "";


$opportunity_name_error = "";
$description_error = "";
$start_date_error = "";
$start_time_error = "";
$end_date_error = "";
$end_time_error = "";
$total_positions_error = "";
$limit_per_volunteer_error = "";
$contribution_value_error = "";
$needs_verification_error = "";
$needs_reminder_error = "";


// Processing form data when form is submitted
if(isset($_POST["event_id"]) && !empty($_POST["event_id"]) && isset($_POST["opportunity_id"]) && !empty($_POST["opportunity_id"])){
    // Get hidden input value
    $event_id = $_POST["event_id"];
    $opportunity_id = $_POST["opportunity_id"];

    // Validate opportunity name
    $input_opportunity_name = trim($_POST["opportunity_name"]);
    if(empty($input_opportunity_name)){
        $opportunity_name_error = "Please enter an opportunity name.";
    }else{
        $opportunity_name = $input_opportunity_name;
    }

    // Validate description
    $input_description = trim($_POST["description"]);
    if(empty($input_description)){
        $description_error = "Please enter a description.";
    } else{
        $description = $input_description;
    }

    // Validate contribution_value
    $input_contribution_value = trim($_POST["contribution_value"]);
    if(empty($input_contribution_value)){
        $contribution_value_error = "Please enter a contribution value.";
    } else{
        $contribution_value = $input_contribution_value;
    }

    // Validate total_positions
    $input_total_positions = trim($_POST["total_positions"]);
    if(empty($input_total_positions)){
        $total_positions_error = "Please enter the total number of positions.";
    } else{
        $total_positions = $input_total_positions;
    }

    // Validate limit_per_volunteer
    $input_limit_per_volunteer = trim($_POST["limit_per_volunteer"]);
    if(empty($input_limit_per_volunteer)){
        $limit_per_volunteer_error = "Please enter the total number of sign-ups each volunteer is allowed.";
    } else{
        $limit_per_volunteer = $input_limit_per_volunteer;
    }

    // Validate start_date
    $input_start_date = trim($_POST["start_date"]);
    if(empty($input_start_date)){
        $start_date_error = "Please enter an opportunity start date.";
    } else{
        $start_date = $input_start_date;
    }

    // Validate start_time
    $input_start_time = trim($_POST["start_time"]);
    if(empty($input_start_time)){
        $start_time_error = "Please enter a opportunity start time.";
    } else{
        $start_time = $input_start_time;
    }

    // Validate end_date
    $input_end_date = trim($_POST["end_date"]);
    if(empty($input_end_date)){
        $end_date_error = "Please enter an opportunity end date.";
    } else{
        $end_date = $input_end_date;
    }

    // Validate end_time
    $input_end_time = trim($_POST["end_time"]);
    if(empty($input_end_time)){
        $end_time_error= "Please enter an opportunity end time.";
    } else{
        $end_time = $input_end_time;
    }

    // Validate needs_verification
    $input_needs_verification = trim($_POST["needs_verification"]);
    $needs_verification = $input_needs_verification;

    // Validate needs_reminder
    $input_needs_reminder = trim($_POST["needs_reminder"]);
    $needs_reminder = $input_needs_reminder;


    // Check input errors before inserting in database
    if(empty($opportunity_name_error) && empty($description_error) && empty($start_date_error) && empty($start_time_error) && empty($end_date_error) && empty($total_positions_error) && empty($contribution_value_error)){
        // Prepare an update statement
        $sql = "UPDATE opportunities SET opportunity_name=?, description=?, start_date=?, start_time=?, end_date=?, end_time=?, total_positions=?, limit_per_volunteer=?, contribution_value=?, needs_verification=?, needs_reminder=? WHERE opportunity_id=?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssssiidiii", 
            $param_opportunity_name,
            $param_description,
            $param_start_date,
            $param_start_time,
            $param_end_date,
            $param_end_time,

            $param_total_positions,
            $param_limit_per_volunteer,
            $param_contribution_value,
            $param_needs_verification,
            $param_needs_reminder,
            $param_opportunity_id);

            // Set parameters
            $param_opportunity_name = $opportunity_name;
            $param_description = $description;
            $param_start_date = $start_date;
            $param_start_time = $start_time;
            $param_end_date = $end_date;
            $param_end_time = $end_time;
            $param_total_positions = $total_positions;
            $param_limit_per_volunteer = $limit_per_volunteer;
            $param_contribution_value = $contribution_value;
            $param_needs_verification = $needs_verification;
            $param_needs_reminder = $needs_reminder;

            $param_opportunity_id = $opportunity_id;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("Location: event-read.php?event_id=" . $event_id);
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($link);
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["opportunity_id"]) && !empty(trim($_GET["opportunity_id"]))){
        // Get URL parameter
        $opportunity_id =  trim($_GET["opportunity_id"]);

        // Prepare a select statement
        $sql = "SELECT * FROM opportunities WHERE opportunity_id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_opportunity_id);

            // Set parameters
            $param_opportunity_id = $opportunity_id;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);

                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    // Retrieve individual field value
                    $opportunity_name = $row["opportunity_name"];
                    $description = $row["description"];
                    $start_date = $row["start_date"];
                    $start_time = $row["start_time"];
                    $end_date = $row["end_date"];
                    $end_time = $row["end_time"];
                    $total_positions = $row["total_positions"];
                    $limit_per_volunteer = $row["limit_per_volunteer"];
                    $contribution_value = $row["contribution_value"];
                    $needs_verification = $row["needs_verification"];
                    $needs_verification = $row["needs_reminder"];

                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }

            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);

        // Close connection
        mysqli_close($link);
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Opportunity</title>

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

    <!--CSS-->
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
                        <h2>Update Opportunity</h2>
                    </div>
                    <p>Please edit the input values and submit to update the opportunity.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">

                      <!--form for opportunity_name-->
                      <div class="form-group <?php echo (!empty($opportunity_name_error)) ? 'has-error' : ''; ?>">
                          <label>Opportunity Name</label>
                          <input type="text" name="opportunity_name" class="form-control" value="<?php echo $opportunity_name; ?>">
                          <span class="help-block"><?php echo $opportunity_name_error;?></span>
                      </div>

                      <!--form for description-->
                      <div class="form-group <?php echo (!empty($description_error)) ? 'has-error' : ''; ?>"> <!-- NOTE:see {2} -->
                          <label>Description</label>
                          <textarea type="text" name="description" class="form-control"><?php echo $description; ?></textarea>                          <span class="help-block"><?php echo $description_error;?></span>
                      </div>

                      <!--form for start_date-->
                      <div class="form-group <?php echo (!empty($start_date_error)) ? 'has-error' : ''; ?>"> <!-- NOTE:see {2} -->
                          <label>Start Date</label>
                          <input type="date" name="start_date" class="form-control" value="<?php echo $start_date; ?>">
                          <span class="help-block"><?php echo $start_date_error;?></span>
                      </div>

                      <!--form for start_time-->
                      <div class="form-group <?php echo (!empty($start_time_error)) ? 'has-error' : ''; ?>"> <!-- NOTE:see {2} -->
                          <label>Start Time</label>
                          <input type="time" name="start_time" class="form-control" value="<?php echo $start_time; ?>">
                          <span class="help-block"><?php echo $start_time_error;?></span>
                      </div>

                      <!--form for end_date-->
                      <div class="form-group <?php echo (!empty($end_date_error)) ? 'has-error' : ''; ?>">
                          <label>End Date</label>
                          <input type="date" name="end_date" class="form-control" value="<?php echo $end_date; ?>">
                          <span class="help-block"><?php echo $end_date_error;?></span>
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
                          <input type="radio" name="needs_verification" value="1" <?php if($needs_verification==1){echo "checked";}?>> Yes
                          <input type="radio" name="needs_verification" value="0" <?php if($needs_verification==0){echo "checked";}?>> No
                          <span class="help-block"><?php echo $needs_verification_error;?></span>
                      </div>

                      <!--form for needs_reminder-->
                      <div class="form-group <?php echo (!empty($needs_reminder_error)) ? 'has-error' : ''; ?>">
                          <label for="needs_reminder">Needs reminder?</label>
                          <p>Do volunteers need a reminder the day before?</p>
                          <input type="radio" name="needs_reminder" value="1" <?php if($needs_reminder==1){echo "checked";}?>> Yes
                          <input type="radio" name="needs_reminder" value="0" <?php if($needs_reminder==0){echo "checked";}?>> No
                          <span class="help-block"><?php echo $needs_reminder_error;?></span>
                      </div>

                        <input type="hidden" name="opportunity_id" value="<?php echo $_GET['opportunity_id']; ?>"/>
                        <input type="hidden" name="event_id" value="<?php echo $_GET['event_id']; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="event-read.php?event_id=<?php echo $_GET['event_id'];?>" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php include '../footer.php';?>
</body>
</html>
