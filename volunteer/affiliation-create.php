<?php
session_start();

// Make sure user is logged in
if(!isset($_SESSION["volunteer_loggedin"]) || $_SESSION["volunteer_loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Include config file
require_once "../config.php";

// Define all variables
$volunteer_id = $_SESSION["volunteer_id"];
$sponsor_name = "";
$sponsor_id = "";

$volunteer_id_error = "";
$sponsor_name_error = "";
$sponsor_id_error = "";

//Data Validation + SQL
if($_SERVER["REQUEST_METHOD"] == "POST"){

  // Validate username (email)
  if(empty(trim($_POST["sponsor_name"]))){
      $sponsor_name_error = "Please enter a sponsor name.";
  } else{
      $sql = "SELECT sponsor_id FROM sponsors WHERE sponsor_name = ?";

      if($stmt = mysqli_prepare($link, $sql)){
          mysqli_stmt_bind_param($stmt, "s", $param_sponsor_name);

          // Set params
          $param_sponsor_name = trim($_POST["sponsor_name"]);

          if(mysqli_stmt_execute($stmt)){
              mysqli_stmt_store_result($stmt);

              if(mysqli_stmt_num_rows($stmt) != 1){
                  $sponsor_name_error = "This sponsor name is not valid.";
              } else{

									mysqli_stmt_bind_result($stmt, $temp_sponsor_id);
									if(mysqli_stmt_fetch($stmt))
									{
										$sponsor_id = $temp_sponsor_id;
									}

              }
          } else{
              echo "Oops! Something went wrong. Please try again later.";
          }
      }
      mysqli_stmt_close($stmt);
  }

    if(empty($sponsor_name_error) && empty($sponsor_id_error)){
        $sql = "INSERT INTO affiliations (volunteer_id, sponsor_id) VALUES (?, ?)";

        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "ii", $param_volunteer_id, $param_sponsor_id);

            // Set params
            $param_volunteer_id = $volunteer_id;
            $param_sponsor_id = $sponsor_id;

            if(mysqli_stmt_execute($stmt)){
                //NOTE: Success!
                header("Location: dashboard.php");
            } else{
                echo "Something went wrong. Please try again later.";
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
    <title>Affiliation Sign Up</title>

    <!--Load required libraries-->
    <?php $pageContent='Form'?>
    <?php include '../head.php'?>

</head>
<body>
    <div class="wrapper">
        <h2>Affiliation Sign Up</h2>
        <p>Please fill out this form to join an affiliation.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

          <!--form for sponsor_name, student ID-->
          <div class="form-group <?php echo (!empty($sponsor_name_error)) ? 'has-error' : ''; ?>">
              <label>Sponsor Name</label>
              <select class="form-control" name="sponsor_name" class="form-control" placeholder="Sponsor Name" value="<?php echo $sponsor_name; ?>">
                <option>Select sponsor</option>
                <option>Student Council</option>
                <option>Model United Nations</option>
              </select>
              <span class="help-block"><?php echo $sponsor_name_error; ?></span>
              <input type="hidden" name="volunteer_id" value="<?php echo $volunteer_id; ?>">
          </div>

          <div class="form-group">
              <input type="submit" class="btn btn-primary" value="Submit">
              <a href="dashboard.php" class="btn btn-default">Back</a>
          </div>

        </form>
    </div>
    <?php include '../footer.php';?>
</body>
</html>
