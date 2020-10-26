<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["sponsor_loggedin"]) || $_SESSION["sponsor_loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Include class
require_once "../classes/SponsorAccountUpdate.php";

$sponsor_id = $_SESSION["sponsor_id"];

$obj = new SponsorAccountUpdate($sponsor_id);

$sponsor_name = "";
$username = "";
$password = "";
$confirm_password = "";
$contribution_type = "";
$advisor1_name = "";
$advisor1_email = "";
$advisor1_phone = "";
$advisor2_name = "";
$advisor2_email = "";
$advisor2_phone = "";
$advisor3_name = "";
$advisor3_email = "";
$advisor3_phone = "";

// Define variables and initialize with empty values
$sponsor_name_error = "";
$username_error = "";
$password_error = "";
$confirm_password_error = "";
$contribution_type_error = "";
$advisor1_name_error = "";
$advisor1_email_error = "";
$advisor1_phone_error = "";
$advisor2_name_error = "";
$advisor2_email_error = "";
$advisor2_phone_error = "";
$advisor3_name_error = "";
$advisor3_email_error = "";
$advisor3_phone_error = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

  // Set sponsor_name
  $sponsor_name = trim($_POST["sponsor_name"]);
  $sponsor_name_error = $obj->setSponsorName($sponsor_name);

  // Set username
  $username = trim($_POST["username"]);
  $username_error = $obj->setUsername($username);

  // Set password
  $password = trim($_POST["password"]);
  $password_error = $obj->setPassword($password);

  // Set confirm_password
  $confirm_password = trim($_POST["confirm_password"]);
  $confirm_password_error = $obj->setConfirmPassword($confirm_password);

  // Set contribution_type
  $contribution_type = trim($_POST["contribution_type"]);
  $contribution_type_error = $obj->setContributionType($contribution_type);

  // Set advisor1 information
  $advisor1_name = $obj->setAdvisor1Name(($_POST["advisor1_name"]));
  $advisor1_email = $obj->setAdvisor2Email(trim($_POST["advisor1_email"]));
  $advisor1_phone = $obj->setAdvisor1Phone(trim($_POST["advisor1_phone"]));
  //$obj->addAdvisor($advisor1_name, $advisor1_email, $advisor1_phone);

  // Set advisor2 information
  $advisor2_name = $obj->setAdvisor2Name(trim($_POST["advisor2_name"]));
  $advisor2_email = $obj->setAdvisor2Email(trim($_POST["advisor2_email"]));
  $advisor2_phone = $obj->setAdvisor2Phone(trim($_POST["advisor2_phone"]));
  //$obj->addAdvisor($advisor2_name, $advisor2_email, $advisor2_phone);

  // Set advisor3 information
  $advisor3_name = $obj->setAdvisor3Name(trim($_POST["advisor3_name"]));
  $advisor3_email = $obj->setAdvisor3Email(trim($_POST["advisor3_email"]));
  $advisor3_phone = $obj->setAdvisor3Phone(trim($_POST["advisor3_phone"]));
  //$obj->addAdvisor($advisor3_name, $advisor3_email, $advisor3_phone);

  if(empty($username_error) && empty($password_error) && empty($confirm_password_error) && empty($contribution_type_error) && empty($sponsor_name_error)  && empty($advisor1_name_error) && empty($advisor1_email_error) && empty($advisor1_phone_error))
  {
    if($obj->updateSponsor()) {
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
    <title>Update Event</title>

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
                        <h2>Update Event</h2>
                    </div>
                    <p>Please edit the input values and submit to update the event.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">

                        <!--form for sponsor (organziation) name-->
                        <div class="form-group <?php echo (!empty($sponsor_name_error)) ? 'has-error' : ''; ?>">
                            <label>Sponsor (Organization) Name</label>
                            <input required type="text" name="sponsor_name" class="form-control" placeholder="Organization Name" value="<?php echo $sponsor_name; ?>">
                            <span class="help-block"><?php echo $sponsor_name_error; ?></span>
                        </div>
            
                        <!--form for username-->
                        <div class="form-group <?php echo (!empty($username_error)) ? 'has-error' : ''; ?>">
                            <label>Username (Email Address)</label>
                            <input required type="email" name="username"  size="30" class="form-control" placeholder="Email" value="<?php echo $username; ?>">
                            <span class="help-block"><?php echo $username_error; ?></span>
                        </div>
            
                        <!--form for contribution_type-->
                        <div class="form-group <?php echo (!empty($contribution_type_error)) ? 'has-error' : ''; ?>">
                            <label>Contribution Type (points, hours, etc.)</label>
                            <input required type="text" name="contribution_type" class="form-control" placeholder="Contribution Type" value="<?php echo $contribution_type; ?>">
                            <span class="help-block"><?php echo $contribution_type_error; ?></span>
                        </div>
            
                        <!--form for advisor1_name-->
                        <div class="form-group <?php echo (!empty($advisor1_name_error)) ? 'has-error' : ''; ?>">
                            <label>Teacher Advisor #1 Name</label>
                            <input required type="text" name="advisor1_name" class="form-control" placeholder="Name" value="<?php echo $advisor1_name; ?>">
                            <span class="help-block"><?php echo $advisor1_name_error; ?></span>
                        </div>
                        <!--form for advisor1_email-->
                        <div class="form-group <?php echo (!empty($advisor1_email_error)) ? 'has-error' : ''; ?>">
                            <label>Teacher Advisor #1 Email</label>
                            <input required type="email" name="advisor1_email" class="form-control" placeholder="Email" value="<?php echo $advisor1_email; ?>">
                            <span class="help-block"><?php echo $advisor1_email_error; ?></span>
                        </div>
                        <!--form for advisor1_phone-->
                        <div class="form-group <?php echo (!empty($advisor1_phone_error)) ? 'has-error' : ''; ?>">
                            <label>Teacher Advisor #1 Phone Number</label>
                            <input type="tel" name="advisor1_phone" class="form-control" placeholder="Phone Number (optional)" value="<?php echo $advisor1_phone; ?>">
                            <span class="help-block"><?php echo $advisor1_phone_error; ?></span>
                        </div>
            
                        <!-- optional information -->
                        <!--form for advisor2_name-->
                        <div class="form-group <?php echo (!empty($advisor2_name_error)) ? 'has-error' : ''; ?>">
                            <label>Teacher Advisor #2 Name</label>
                            <input type="text" name="advisor2_name" class="form-control" placeholder="Name (optional)" value="<?php echo $advisor2_name; ?>">
                            <span class="help-block"><?php echo $advisor2_name_error; ?></span>
                        </div>
                        <!--form for advisor2_email-->
                        <div class="form-group <?php echo (!empty($advisor2_email_error)) ? 'has-error' : ''; ?>">
                            <label>Teacher Advisor #2 Email</label>
                            <input type="email" name="advisor2_email" class="form-control" placeholder="Email (optional)" value="<?php echo $advisor2_email; ?>">
                            <span class="help-block"><?php echo $advisor2_email_error; ?></span>
                        </div>
                        <!--form for advisor2_phone-->
                        <div class="form-group <?php echo (!empty($advisor2_phone_error)) ? 'has-error' : ''; ?>">
                            <label>Teacher Advisor #2 Phone Number</label>
                            <input type="tel" name="advisor2_phone" class="form-control" placeholder="Phone Number (optional)" value="<?php echo $advisor2_phone; ?>">
                            <span class="help-block"><?php echo $advisor2_phone_error; ?></span>
                        </div>
          
                        <!--form for advisor3_name-->
                        <div class="form-group <?php echo (!empty($advisor3_name_error)) ? 'has-error' : ''; ?>">
                            <label>Teacher Advisor #3 Name</label>
                            <input type="text" name="advisor3_name" class="form-control" placeholder="Name (optional)" value="<?php echo $advisor3_name; ?>">
                            <span class="help-block"><?php echo $advisor3_name_error; ?></span>
                        </div>
                        <!--form for advisor3_email-->
                        <div class="form-group <?php echo (!empty($advisor3_email_error)) ? 'has-error' : ''; ?>">
                            <label>Teacher Advisor #3 Email</label>
                            <input type="email" name="advisor3_email" class="form-control" placeholder="Email (optional)" value="<?php echo $advisor3_email; ?>">
                            <span class="help-block"><?php echo $advisor3_email_error; ?></span>
                        </div>
                        <!--form for advisor3_phone-->
                        <div class="form-group <?php echo (!empty($advisor3_phone_error)) ? 'has-error' : ''; ?>">
                            <label>Teacher Advisor #3 Phone Number</label>
                            <input type="tel" name="advisor3_phone" class="form-control" placeholder="Phone Number (optional)" value="<?php echo $advisor3_phone; ?>">
                            <span class="help-block"><?php echo $advisor3_phone_error; ?></span>
                        </div>
                
                        <input type="hidden" name="event_id" value="<?php echo $event_id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="events.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include '../footer.php';?>
</body>
</html>
