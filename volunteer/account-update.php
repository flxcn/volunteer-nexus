<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["volunteer_loggedin"]) || $_SESSION["volunteer_loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Include class
require_once "../classes/VolunteerAccountUpdate.php";

$volunteer_id = $_SESSION["volunteer_id"];

$obj = new VolunteerAccountUpdate($volunteer_id);

$first_name = "";
$last_name = "";
$graduation_year = "";
$username = "";
$password = "";
$confirm_password = "";

// additional future feature for people to showcase their awards
$description = "";

// Define variables and initialize with empty values
$first_name_error = "";
$last_name_error = "";
$graduation_year_error = "";
$username_error = "";
$password_error = "";
$confirm_password_error = "";

//
$description_error;

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

  // Set first_name
  $first_name = trim($_POST["first_name"]);
  $first_name_error = $obj->setFirstName($first_name);

  // Set last_name
  $last_name = trim($_POST["last_name"]);
  $last_name_error = $obj->setLastName($last_name);

  // Set graduation_year
  $graduation_year = trim($_POST["graduation_year"]);
  $graduation_year_error = $obj->setGraduationYear($graduation_year);

    //   // Set password
    //   $password = trim($_POST["password"]);
    //   $password_error = $obj->setPassword($password);

    //   // Set confirm_password
    //   $confirm_password = trim($_POST["confirm_password"]);
    //   $confirm_password_error = $obj->setConfirmPassword($confirm_password);

  
  if(empty($username_error) && empty($password_error) && empty($confirm_password_error) && empty($first_name_error) && empty($last_name_error))
  {
    if($obj->updateVolunteer()) {
      header("location: login.php");
    }
    else {
      echo "Something went wrong. Please try again later.";
    }
  }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Volunteer</title>

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
                        <h2>Update Volunteer</h2>
                    </div>
                    <p>Please edit the input values and submit to update the account.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">

                        <!--form for first name-->
                        <div class="form-group <?php echo (!empty($first_name_error)) ? 'has-error' : ''; ?>">
                            <label>First Name</label>
                            <input required type="text" name="first_name" class="form-control" placeholder="First Name" value="<?php echo $first_name; ?>">
                            <span class="help-block"><?php echo $first_name_error; ?></span>
                        </div>

                        <!--form for last name-->
                        <div class="form-group <?php echo (!empty($last_name_error)) ? 'has-error' : ''; ?>">
                            <label>Last Name</label>
                            <input required type="text" name="last_name" class="form-control" placeholder="Last Name" value="<?php echo $last_name; ?>">
                            <span class="help-block"><?php echo $last_name_error; ?></span>
                        </div>

                        <!--form for graduation_year-->
                        <div class="form-group <?php echo (!empty($graduation_year_error)) ? 'has-error' : ''; ?>">
                            <label>Graduation Year</label>
                            <input required type="text" name="graduation_year" class="form-control" placeholder="Graduation Year" value="<?php echo $graduation_year; ?>">
                            <span class="help-block"><?php echo $graduation_year_error; ?></span>
                        </div>
                
                        <input type="hidden" name="event_id" value="<?php echo $event_id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="account.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include "footer.php"; ?>
</body>
</html>
