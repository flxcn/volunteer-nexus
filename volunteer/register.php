<?php
// Include config file
require_once "../config.php";

// Define variables and initialize with empty values
$student_id = "";
$username = "";
$password = "";
$confirm_password = "";
$graduation_year = "";
$first_name = "";
$last_name = "";

$student_id_error = "";
$username_error = "";
$password_error = "";
$confirm_password_error = "";
$graduation_year_error = "";
$first_name_error = "";
$last_name_error = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

  // Validate username (email)
  if(empty(trim($_POST["username"]))){
      $username_error = "Please enter a username.";
  } else{
      // Prepare a select statement
      $sql = "SELECT student_id FROM volunteers WHERE username = ?";

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


    // Validate student_id
    if(empty(trim($_POST["student_id"]))){
        $student_id_error = "Please enter your five-digit student ID.";
    } else{
        // Prepare a select statement
        $sql = "SELECT student_id FROM volunteers WHERE student_id = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_student_id);

            // Set parameters
            $param_student_id = trim($_POST["student_id"]);

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) == 1){
                    $student_id_error = "This student ID is already in use.";
                } else{
                    $student_id = trim($_POST["student_id"]);
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

    // Validate graduation_year
    if(empty(trim($_POST["graduation_year"]))){
        $graduation_year_error = "Please enter a graduation year.";
    } else{
        $graduation_year = trim($_POST["graduation_year"]);
    }

    // Validate first name
    if(empty(trim($_POST["first_name"]))){
        $first_name_error = "Please enter your first name.";
    } else{
        $first_name = trim($_POST["first_name"]);
    }

    // Validate last name
    if(empty(trim($_POST["last_name"]))){
        $last_name_error = "Please enter your last name.";
    } else{
        $last_name = trim($_POST["last_name"]);
    }

    // Check input errors before inserting in database
    if(empty($student_id_error) && empty($password_error) && empty($confirm_password_error) && empty($graduation_year_error) && empty($first_name_error) && empty($last_name_error)){

        // Prepare an insert statement
        $sql = "INSERT INTO volunteers (student_id, username, password, graduation_year, first_name, last_name) VALUES (?, ?, ?, ?, ?, ?)";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ississ", $param_student_id, $param_username, $param_password, $param_graduation_year, $param_first_name, $param_last_name);

            // Set parameters
            $param_student_id = $student_id;
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_graduation_year = $graduation_year;
            $param_first_name = $first_name;
            $param_last_name = $last_name;

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
    <meta charset="UTF-8">
    <title>Volunteer Sign Up</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Volunteer Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

          <!--form for first name-->
          <div class="form-group <?php echo (!empty($first_name_error)) ? 'has-error' : ''; ?>">
              <label>First Name</label>
              <input type="text" name="first_name" class="form-control" value="<?php echo $first_name; ?>">
              <span class="help-block"><?php echo $first_name_error; ?></span>
          </div>

          <!--form for last name-->
          <div class="form-group <?php echo (!empty($last_name_error)) ? 'has-error' : ''; ?>">
              <label>Last Name</label>
              <input type="text" name="last_name" class="form-control" value="<?php echo $last_name; ?>">
              <span class="help-block"><?php echo $last_name_error; ?></span>
          </div>

          <!--form for graduation_year-->
          <div class="form-group <?php echo (!empty($graduation_year_error)) ? 'has-error' : ''; ?>">
              <label>Graduation Year</label>
              <input type="number" name="graduation_year" maxlength="4" size="4" class="form-control" value="<?php echo $graduation_year; ?>">
              <span class="help-block"><?php echo $graduation_year_error; ?></span>
          </div>

          <!--form for student ID-->
          <div class="form-group <?php echo (!empty($student_id_error)) ? 'has-error' : ''; ?>">
              <label>Student ID</label>
              <input type="number" name="student_id" maxlength="5" size="5" class="form-control" value="<?php echo $student_id; ?>">
              <span class="help-block"><?php echo $student_id_error; ?></span>
          </div>

          <!--form for username-->
          <div class="form-group <?php echo (!empty($username_error)) ? 'has-error' : ''; ?>">
              <label>Email Address</label>
              <input type="email" name="username"  size="30" class="form-control" value="<?php echo $username; ?>">
              <span class="help-block"><?php echo $username_error; ?></span>
          </div>

            <!--form for password-->
            <div class="form-group <?php echo (!empty($password_error)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_error; ?></span>
            </div>

            <!--form for confirm password-->
            <div class="form-group <?php echo (!empty($confirm_password_error)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_error; ?></span>
            </div>



            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>
</body>
</html>
