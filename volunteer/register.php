<?php
require_once "../config.php";

// Define variables
$username = "";
$password = "";
$confirm_password = "";
$graduation_year = "";
$first_name = "";
$last_name = "";

$username_error = "";
$password_error = "";
$confirm_password_error = "";
$graduation_year_error = "";
$first_name_error = "";
$last_name_error = "";

//Data Validation and SQL Insertion
if($_SERVER["REQUEST_METHOD"] == "POST"){

  // Validate username (email)
  if(empty(trim($_POST["username"]))){
      $username_error = "Please enter a username.";
  } else{
      // Prepare a select statement
      $sql = "SELECT volunteer_id FROM volunteers WHERE username = ?";

      if($stmt = mysqli_prepare($link, $sql)){
          mysqli_stmt_bind_param($stmt, "s", $param_username);

          // Set params
          $param_username = trim($_POST["username"]);

          if(mysqli_stmt_execute($stmt)){
              mysqli_stmt_store_result($stmt);

              if(mysqli_stmt_num_rows($stmt) > 0){
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
        $password_error = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_error = "Please confirm your password.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if($password != $confirm_password)){
            $confirm_password_error = "Password did not match.";
        }
    }

    // Validate last name
    if(empty(trim($_POST["last_name"]))){
        $last_name_error = "Please enter your last name.";
    } else{
        $last_name = trim($_POST["last_name"]);
    }

    // Validate graduation_year
    if(trim($_POST["graduation_year"])=="Select year"){
        $graduation_year_error = "Please enter your graduation year.";
    } else{
        $graduation_year = trim($_POST["graduation_year"]);
    }

    // Validate first name
    if(empty(trim($_POST["first_name"]))){
        $first_name_error = "Please enter your first name.";
    } else{
        $first_name = trim($_POST["first_name"]);
    }



    // Making sure there are no errors
    if(empty($password_error) && empty($confirm_password_error) && empty($graduation_year_error) && empty($first_name_error) && empty($last_name_error)){

        // Prepare an insert statement
        $sql = "INSERT INTO volunteers (username, password, graduation_year, first_name, last_name) VALUES (?, ?, ?, ?, ?)";

        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "ssiss", $param_username, $param_password, $param_graduation_year, $param_first_name, $param_last_name);

            // Set params
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT);
            $param_graduation_year = $graduation_year;
            $param_first_name = $first_name;
            $param_last_name = $last_name;

            if(mysqli_stmt_execute($stmt)){
                // Success!
                header("location: login.php");
            } else{
                echo "ERROR! There appears to be an issue...";
            }
        }

        mysqli_stmt_close($stmt);
    }

    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Volunteer Sign Up</title>

        <!--Load required libraries-->
        <?php $pageContent='Form'?>
        <?php include '../head.php'?>

</head>
<body>
    <div class="wrapper">
        <h2>Volunteer Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

          <!--form for first name-->
          <div class="form-group <?php echo (!empty($first_name_error)) ? 'has-error' : ''; ?>">
              <label>First Name</label>
              <input type="text" name="first_name" class="form-control" placeholder="First Name" value="<?php echo $first_name; ?>">
              <span class="help-block"><?php echo $first_name_error; ?></span>
          </div>

          <!--form for last name-->
          <div class="form-group <?php if(!empty($last_name_error)){echo'has-error';} ?>">
              <label for="last_name">Last Name</label>
              <input type="text" name="last_name" class="form-control" placeholder="Last Name" value="<?php echo $last_name; ?>">
              <span class="help-block"><?php echo $last_name_error; ?></span>
          </div>

          <!--form for graduation_year-->
          <div class="form-group <?php if(!empty($graduation_year_error)){echo'has-error';}  ?>">
              <label for="graduation_year">Graduation Year</label>
              <select class="form-control" name="graduation_year" placeholder="Graduation Year" value="<?php echo $graduation_year; ?>">
                <option>Select year</option>
                <option>2020</option>
                <option>2021</option>
                <option>2022</option>
                <option>2023</option>
              </select>
              <span class="help-block"><?php echo $graduation_year_error; ?></span>
          </div>

          <!--form for username-->
          <div class="form-group <?php echo (!empty($username_error)) ? 'has-error' : ''; ?>">
              <label>Email Address</label>
              <input type="email" name="username"  size="30" class="form-control" placeholder="Email Address" value="<?php echo $username; ?>">
              <span class="help-block"><?php echo $username_error; ?></span>
          </div>

            <!--form for password-->
            <div class="form-group <?php echo (!empty($password_error)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="Password" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_error; ?></span>
            </div>

            <!--form for confirm password-->
            <div class="form-group <?php echo (!empty($confirm_password_error)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" placeholder="Password" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_error; ?></span>
            </div>



            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>

    <?php include '../footer.php';?>
</body>
</html>
