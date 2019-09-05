<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["sponsor_loggedin"]) || $_SESSION["sponsor_loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Process delete operation after confirmation
if(isset($_POST["opportunity_id"]) && !empty($_POST["opportunity_id"]) && isset($_POST["event_id"]) && !empty($_POST["event_id"])){
  // Include config file
  require_once "../config.php";

  // Prepare a delete statement
  $sql = "DELETE FROM opportunities WHERE opportunity_id = ? AND student_id = {$_SESSION['student_id']}";

  if($stmt = mysqli_prepare($link, $sql)){
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "i", $param_opportunity_id);

    // Set parameters
    $param_opportunity_id = trim($_POST["opportunity_id"]);


    // Attempt to execute the prepared statement
    if(mysqli_stmt_execute($stmt)){
      // Opportunity deleted successfully. Redirect to landing page
      //NOTE: link may be broken
      header("location: event-read.php?event_id=" . $_POST['event_id']);
      exit();
    } else{
      echo "Oops! Something went wrong. Please try again later.";
    }
  }

  // Close statement
  mysqli_stmt_close($stmt);

  // Close connection
  mysqli_close($link);
} else{
  // Check existence of id parameter
  if(empty(trim($_GET["opportunity_id"]))){
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
    <title>View Event</title>

        <!--Load required libraries-->
        <?php include '../head.php'?>

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
                        <h1>Delete Opportunity</h1>
                    </div>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="alert alert-danger fade in">
                            <input type="hidden" name="opportunity_id" value="<?php echo trim($_GET["opportunity_id"]); ?>"/>
                            <input type="hidden" name="event_id" value="<?php echo trim($_GET["event_id"]); ?>"/>
                            <p>Are you sure you want to delete this opportunity?</p><br>
                            <p>
                                <input type="submit" value="Yes" class="btn btn-danger">
                                <a href="opportunity-read.php" class="btn btn-default">No</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
