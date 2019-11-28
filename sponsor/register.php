<?php
// Include config file
require_once "../config.php";

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
  $obj = new SponsorRegistration();
  $sponsor_name_error = $obj->setUsername(trim($_POST["username"]));
  $username_error = $obj->setUsername(trim($_POST["username"]));
  $password_error = $obj->setPassword(trim($_POST["password"]));
  $confirm_password_error = $obj->setConfirmPassword(trim($_POST["confirm_password"]));
  $contribution_type_error = $obj->setContributionType(trim($_POST["contribution_type"]));
  $obj->addAdvisor(trim($_POST["advisor1_name"]), trim($_POST["advisor1_email"]), trim($_POST["advisor1_phone"]));
  $obj->addAdvisor(trim($_POST["advisor2_name"]), trim($_POST["advisor2_email"]), trim($_POST["advisor2_phone"]));
  $obj->addAdvisor(trim($_POST["advisor3_name"]), trim($_POST["advisor3_email"]), trim($_POST["advisor3_phone"]));

  if(empty($username_error) && empty($password_error) && empty($confirm_password_error) && empty($contribution_type_error) && empty($sponsor_name_error)  && empty($advisor1_name_error) && empty($advisor1_email_error) && empty($advisor1_phone_error))
  {
    if($obj->addSponsor()) {
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
    <title>Sponsor Sign Up</title>
    <!--Load required libraries-->
    <?php include '../head.php'?>
    <script>
        function toggleOptionalFields() {
            var optionalFields = document.getElementById("optionalFields");
            var toggleButton = document.getElementById("toggleButton");
            if (optionalFields.style.display === "none") {
              optionalFields.style.display = "block";
              toggleButton.style.display = "none";

            } else {
              optionalFields.style.display = "none";
            }
        }
    </script>
    <style type="text/css">
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Sponsor Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

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

          <!--form for password-->
          <div class="form-group <?php echo (!empty($password_error)) ? 'has-error' : ''; ?>">
              <label>Password</label>
              <input required type="password" name="password" class="form-control" placeholder="Password" value="<?php echo $password; ?>">
              <span class="help-block"><?php echo $password_error; ?></span>
          </div>

          <!--form for confirm password-->
          <div class="form-group <?php echo (!empty($confirm_password_error)) ? 'has-error' : ''; ?>">
              <label>Confirm Password</label>
              <input required type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" value="<?php echo $confirm_password; ?>">
              <span class="help-block"><?php echo $confirm_password_error; ?></span>
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
              <input required type="tel" name="advisor1_phone" class="form-control" placeholder="Phone Number (optional)" value="<?php echo $advisor1_phone; ?>">
              <span class="help-block"><?php echo $advisor1_phone_error; ?></span>
          </div>

          <p>
            <button class="btn btn-link" onclick="toggleOptionalFields()" type="button" id="toggleButton">Add more advisors</button>
          </p>

          <!-- optional information -->
          <div id="optionalFields" style = "display:none;">
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
          </div>

          <div class="form-group">
              <input type="submit" class="btn btn-primary" value="Submit">
              <input type="reset" class="btn btn-default" value="Reset">
          </div>
          <p>Already have an account? <a href="login.php">Login here</a>.</p>
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

</body>
</html>
