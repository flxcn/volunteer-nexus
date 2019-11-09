<?php
// Include config file
require_once "../config.php";

// Define variables and initialize with empty values
//$sponsor_id = "";
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

//$sponsor_id_error = "";
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

  // Validate username (email)
  if(empty(trim($_POST["username"]))){
      $username_err = "Please enter a username.";
  } else{
      // Prepare a select statement
      $sql = "SELECT sponsor_id FROM sponsors WHERE username = ?";

      if($stmt = mysqli_prepare($link, $sql)){
          // Bind variables to the prepared statement as parameters
          mysqli_stmt_bind_param($stmt, "s", $param_username);

          // Set parameters
          $param_username = trim($_POST["username"]);

          // Attempt to execute the prepared statement
          if(mysqli_stmt_execute($stmt)){
              /* store result */
              mysqli_stmt_store_result($stmt);

              if(mysqli_stmt_num_rows($stmt) == 1){
                  $username_error = "This username is already taken.";
              } else{
                  $username = trim($_POST["username"]);
              }
          } else{
              echo "Oops! Something went wrong. Please try again later.";
          }
      }

      // Close statement
      mysqli_stmt_close($stmt);
  }

    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_error = "Please enter a password.";
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_error = "Password must have at least 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_error = "Please confirm password.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_error) && ($password != $confirm_password)){
            $confirm_password_error = "Password did not match.";
        }
    }

    // Validate contribution_type
    if(empty(trim($_POST["contribution_type"]))){
        $contribution_type_error = "Please enter a contribution type.";
    } else{
        $contribution_type = trim($_POST["contribution_type"]);
    }

    // Validate sponsoring organization name
    if(empty(trim($_POST["sponsor_name"]))){
        $sponsor_name_error = "Please enter the name of your organization.";
    } else{
        $sponsor_name = trim($_POST["sponsor_name"]);
    }

    // Validate advisor1_name
    if(empty(trim($_POST["advisor1_name"]))){
        $advisor1_name_error = "Please enter your teacher advisor's full name.";
    } else{
        $advisor1_name = trim($_POST["advisor1_name"]);
    }
    // Validate advisor1_email
    if(empty(trim($_POST["advisor1_email"]))){
        $advisor1_email_error = "Please enter your teacher advisor's email.";
    } else{
        $advisor1_email = trim($_POST["advisor1_email"]);
    }
    // Validate advisor1_phone
    $advisor1_phone = trim($_POST["advisor1_phone"]);

    // Validate advisor2 information
    $advisor2_name = trim($_POST["advisor2_name"]);
    $advisor2_email = trim($_POST["advisor2_email"]);
    $advisor2_phone = trim($_POST["advisor2_phone"]);


    // Validate advisor3 information
    $advisor3_name = trim($_POST["advisor3_name"]);
    $advisor3_email = trim($_POST["advisor3_email"]);
    $advisor3_phone = trim($_POST["advisor3_phone"]);



    // Check input errors before inserting in database
    if(empty($username_error) && empty($password_error) && empty($confirm_password_error) && empty($contribution_type_error) && empty($sponsor_name_error)  && empty($advisor1_name_error) && empty($advisor1_email_error) && empty($advisor1_phone_error)){

        // Prepare an insert statement
        $sql = "INSERT INTO sponsors (sponsor_name, username, password, contribution_type, advisor1_name, advisor1_email, advisor1_phone, advisor2_name, advisor2_email, advisor2_phone, advisor3_name, advisor3_email, advisor3_phone) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssssssssssss", $param_sponsor_name, $param_username, $param_password, $param_contribution_type, $param_advisor1_name, $param_advisor1_email, $param_advisor1_phone, $param_advisor2_name, $param_advisor2_email, $param_advisor2_phone, $param_advisor3_name, $param_advisor3_email, $param_advisor3_phone);

            // Set parameters
            $param_sponsor_name = $sponsor_name;
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_contribution_type = $contribution_type;
            $param_advisor1_name = $advisor1_name;
            $param_advisor1_email = $advisor1_email;
            $param_advisor1_phone = $advisor1_phone;
            $param_advisor2_name = $advisor2_name;
            $param_advisor2_email = $advisor2_email;
            $param_advisor2_phone = $advisor2_phone;
            $param_advisor3_name = $advisor3_name;
            $param_advisor3_email = $advisor3_email;
            $param_advisor3_phone = $advisor3_phone;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Something went wrong. Please try again later.";
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
