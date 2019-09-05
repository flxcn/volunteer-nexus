<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["sponsor_loggedin"]) || $_SESSION["sponsor_loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Process delete operation after confirmation
if(isset($_POST["engagement_id"]) && !empty($_POST["engagement_id"]) && isset($_POST["opportunity_id"]) && !empty($_POST["opportunity_id"])){
  // Include config file
  require_once "../config.php";

  // Prepare a delete statement
  $sql = "DELETE FROM engagements WHERE engagement_id = ? AND sponsor_id = ?";

  if($stmt = mysqli_prepare($link, $sql)){
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "ii", $param_engagement_id, $param_sponsor_id);

    // Set parameters
    $param_engagement_id = trim($_POST["engagement_id"]);
    $param_sponsor_id = trim($_SESSION["sponsor_id"]);

    // Attempt to execute the prepared statement
    if(mysqli_stmt_execute($stmt)){
      // Records deleted successfully. Redirect to landing page
      header("location: opportunity-read.php?opportunity_id=". $param_opportunity_id);
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
  if(empty(trim($_GET["engagement_id"]))){
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
    <title>Delete Engagement</title>

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
                        <h1>Delete Engagement</h1>
                    </div>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="alert alert-danger fade in">
                            <input type="hidden" name="engagement_id" value="<?php echo trim($_GET["engagement_id"]); ?>"/>
                            <p>Are you sure you want to delete this engagement? This action cannot be undone.</p><br>
                            <p>
                                <input type="submit" value="Yes" class="btn btn-danger">
                                <a href="opportunity-read.php?" class="btn btn-default">No</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php include '../footer.php';?>
</body>
</html>
