<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["volunteer_loggedin"]) || $_SESSION["volunteer_loggedin"] !== true)
{
    header("location: login.php");
    exit;
}
?>

<?php
require_once "../classes/VolunteerAccountUpdate.php";

$volunteer_id = $_SESSION["volunteer_id"];
$graduation_year = NULL;

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
  $obj = new VolunteerAccountUpdate($volunteer_id);

  // Set graduation_year
  if(trim($_POST["graduation_year"]) == null) {
    $graduation_year_error = "Please select a year or check \"Does not apply\".";
  }
  else {
    $graduation_year = trim($_POST["graduation_year"]);
    $obj->setGraduationYear($graduation_year);
  }

  if(empty($graduation_year_error))
  {
    if($obj->updateGraduationYear()) {
      header("location: dashboard.php");
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
    <title>Update Account</title>
    <!--Load required libraries-->
    <?php include '../head.php'?>
    <style type="text/css">
        /* .wrapper{ width: 350px; padding: 20px; } */
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Welcome back to VolunteerNexus!</h2>
        <p>Please update your graduation year.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

            <!--form for graduation_year-->
            <div class="form-group <?php echo (!empty($graduation_year_error)) ? 'has-error' : ''; ?>">
                <label for="yearsSelect">Graduation Year</label>

                <!-- dropdown list for graduation_year -->
                <select id="dropdown" name="graduation_year">
                    <option>Select year</option>
                    <option value="2021">2021</option>
                    <option value="2020">2022</option>
                    <option value="2021">2023</option>
                    <option value="2021">2024</option>
                </select>

                <!-- checkbox for graduation_year -->
                <div class="checkbox">
                    <label><input type="checkbox" name="graduation_year" id="checkbox" value="0" onchange="toggleDropdownDisable()">This does not apply to me.</label>
                </div>

                <span class="help-block"><?php echo $sponsor_id_error;?></span>
            </div>

          <div class="form-group">
              <input type="submit" class="btn btn-primary" value="Submit">
              <input type="reset" class="btn btn-default" value="Reset">
          </div>
      </form>
    </div>

    <!-- jQuery, ensures one-time use of submit button -->
    <script>
    $("body").on("submit", "form", function() {
        $(this).submit(function() {
            return false;
        });
        return true;
    });
    </script>

    <script>
    function toggleDropdownDisable() {
        if(document.getElementById("dropdown").disabled == false) {
        document.getElementById("dropdown").disabled = true;
        }
        else {
        document.getElementById("dropdown").disabled = false;
        }
    }
    </script>
</body>
</html>