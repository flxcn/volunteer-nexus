<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

if(!isset($_GET['event_id']) || !is_int($_GET['event_id'])){
  header('Location: events.php');
  exit;
}

// Include config file
require_once '../config.php';

// Define variables and initialize with empty values
$event_id = $_GET['event_id'];
$sponsor_id = $_SESSION["sponsor_id"];
$role_name = "";
$description = "";
$start_date = "";
$end_date = "";
$start_time = "";
$end_time = "";
$total_positions = "";
$contribution_value = "";

//define and initialize error message variables
$role_name_error = "";
$description_error = "";
$start_date_error = "";
$end_date_error = "";
$start_time_error = "";
$end_time_error = "";
$total_positions_error = "";
$contribution_value_error = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate opportunity name
    $input_event_name = trim($_POST["role_name"]);
    if(empty($input_role_name)){
        $event_role_error = "Please enter a role name.";
    }else{
        $role_name = $input_role_name;
    }

    // Validate description //NOTE: refer to-do list {1} // NOTE: add string length validator
    $input_description = trim($_POST["description"]);
    if(empty($input_description)){
        $description_error = "Please enter a description.";
    } else{
        $description = $input_description;
    }

    // Validate start_date // NOTE: refer to-do list {3}
    $input_start_date = trim($_POST["start_date"]);
    if(empty($input_start_date)){
        $start_date_error = "Please enter a start date.";
    } else{
        $start_date = $input_start_date;
    }

    // Validate start_time // NOTE: refer to-do list {3}
    $input_start_time = trim($_POST["start_time"]);
    if(empty($input_start_time)){
        $start_time_error = "Please enter a start time.";
    } else{
        $start_time = $input_start_time;
    }

    // Validate end_date // NOTE: refer to-do list {3}
    $input_end_date = trim($_POST["end_date"]);
    if(empty($input_end_date)){
        $end_date_error = "Please enter a end date.";
    } else{
        $end_date = $input_end_date;
    }

    // Validate end_time // NOTE: refer to-do list {3}
    $input_end_time = trim($_POST["end_time"]);
    if(empty($input_end_time)){
        $end_time_error = "Please enter an end time.";
    } else{
        $date = DateTime::createFromFormat( 'H:i A', $input_end_time);
        $end_time = $date->format( 'H:i:s');
        $end_time = $input_end_time;
    }

    // Validate total_positions // NOTE: refer to-do list {3}
    $input_total_positions = trim($_POST["total_positions"]);
    if(empty($input_total_positions)){
        $total_positions_error = "Please enter the total number of positions. Leave blank if there is no limit.";
    } else{
        $total_positions = $input_total_positions;
    }

    // Validate contribution_value // NOTE: refer to-do list {3}
    $input_contribution_value = trim($_POST["contribution_value"]);
    if(empty($input_contribution_value)){
        $contribution_value_error = "Please enter a contribution value.";
    } else{
        $contribution_value = $input_contribution_value;
    }

    // Check input errors before inserting in database
    if(empty($role_name_error) && empty($description_error) && empty($start_date_error) && empty($end_date_error) && empty($start_time_error) && empty($end_time_error) && empty($total_positions_error) && empty($contribution_value_error)){
        // Prepare an insert statement
        $sql = "INSERT INTO opportunities (event_id, sponsor_id, role_name, description, start_date, end_date, start_time, end_time, total_positions, contribution_value) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "iissssssssssii", $param_event_id, $param_sponsor_id, $param_role_name, $param_description, $param_start_date, $param_end_date, $param_start_time, $param_end_time, $param_total_positions, $param_contribution_value);

            // Set parameters
            $param_event_id = $event_id;
            $param_sponsor_id = $sponsor_id;
            $param_role_name = $role_name;
            $param_description = $description;
            $param_start_date = $start_date;
            $param_end_date = $end_date;
            $param_start_time = $start_time;
            $param_end_time = $end_time;
            $param_total_positions= $total_positions;
            $param_contribution_value = $contribution_value;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: event-read.php?event_id='. $event_id .'");
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Opportunity</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <!--  jQuery -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>



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

                        <!--form for opportunity_name-->
                        <div class="form-group <?php echo (!empty($role_name_error)) ? 'has-error' : ''; ?>">
                            <label>Opportunity Name</label>
                            <input type="text" name="role_name" class="form-control" value="<?php echo $role_name; ?>">
                            <span class="help-block"><?php echo $role_name_error;?></span>
                        </div>

                        <!--form for description-->
                        <div class="form-group <?php echo (!empty($description_error)) ? 'has-error' : ''; ?>">
                            <label>Description</label>
                            <input name="description" class="form-control"><?php echo $description; ?>
                            <span class="help-block"><?php echo $description_error;?></span>
                        </div>

                        <!--form for total_positions-->
                        <div class="form-group <?php echo (!empty($total_positions_error)) ? 'has-error' : ''; ?>">
                            <label>Total Positions</label>
                            <input type="number" name="total_positions" class="form-control" value="<?php echo $total_positions; ?>">
                            <span class="help-block"><?php echo $total_positions_error;?></span>
                        </div>

                        <!--form for contribution_value-->
                        <div class="form-group <?php echo (!empty($contribution_value_error)) ? 'has-error' : ''; ?>">
                            <label>Contribution Value</label>
                            <input type="number" name="contribution_value" class="form-control"><?php echo $contribution_value; ?>
                            <span class="help-block"><?php echo $contribution_value_error;?></span>
                        </div>

                        <!--form for start_date-->
                        <div class="form-group <?php echo (!empty($start_date_error)) ? 'has-error' : ''; ?>"> <!-- NOTE:see {2} -->
                            <label>Start Date</label>
                            <input type="date" name="start_date" class="form-control"><?php echo $start_date; ?>
                            <span class="help-block"><?php echo $start_date_error;?></span>
                        </div>

                        <!--form for start_time-->
                        <div class="form-group <?php echo (!empty($start_time_error)) ? 'has-error' : ''; ?>"> <!-- NOTE:see {2} -->
                            <label>Start Time</label>
                            <input type="time" name="start_time" class="form-control"><?php echo $start_time; ?>
                            <span class="help-block"><?php echo $start_time_error;?></span>
                        </div>

                        <!--form for end_date-->
                        <div class="form-group <?php echo (!empty($end_date_error)) ? 'has-error' : ''; ?>">
                            <label>End Date</label>
                            <input type="date" name="end_date" class="form-control"><?php echo $end_date; ?>
                            <span class="help-block"><?php echo $end_date_error;?></span>
                        </div>

                        <!--form for end_time-->
                        <div class="form-group <?php echo (!empty($end_time_error)) ? 'has-error' : ''; ?>"> <!-- NOTE:see {2} -->
                            <label>End Time</label>
                            <input type="time" name="end_time" class="form-control"><?php echo $end_time; ?>
                            <span class="help-block"><?php echo $end_time_error;?></span>
                        </div>

                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="event-read.php?event_id=<?php echo $event_id; ?>" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
