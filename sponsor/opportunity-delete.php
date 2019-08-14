<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: sponsor-login.php");
    exit;
}

// Process delete operation after confirmation
if(isset($_POST["event_id"]) && !empty($_POST["opportunity_id"])){
  // Include config file
  require_once "config.php";

  // Prepare a delete statement
  $sql = "DELETE FROM opportunities WHERE opportunity_id = ?";

  if($stmt = mysqli_prepare($link, $sql)){
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "i", $param_opportunity_id);

    // Set parameters
    $param_event_id = trim($_POST["event_id"]);
    $param_opportunity_id = trim($_POST["opportunity_id"]);


    // Attempt to execute the prepared statement
    if(mysqli_stmt_execute($stmt)){
      // Opportunity deleted successfully. Redirect to landing page
      //NOTE: link may be broken
      header('location: read.php?event_id=". $param_event_id ."');
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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
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
                            <input type="hidden" name="event_id" value="<?php echo trim($_GET["opportunity_id"]); ?>"/>
                            <p>Are you sure you want to delete this opportunity?</p><br>
                            <p>
                                <input type="submit" value="Yes" class="btn btn-danger">
                                <a href="read.php" class="btn btn-default">No</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>