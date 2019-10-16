<?php
session_start();

//Make sure user is logged in
if(!isset($_SESSION["sponsor_loggedin"]) || $_SESSION["sponsor_loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "../config.php";

// SQL: Delete Opportunity
if(isset($_POST["opportunity_id"]) && !empty($_POST["opportunity_id"]) && isset($_POST["event_id"]) && !empty($_POST["event_id"])){


  $sql = "DELETE FROM opportunities WHERE opportunity_id = ? AND sponsor_id = {$_SESSION['sponsor_id']}";

  if($stmt = mysqli_prepare($link, $sql)){
    mysqli_stmt_bind_param($stmt, "i", $param_opportunity_id);
    $param_opportunity_id = trim($_POST["opportunity_id"]);
    if(mysqli_stmt_execute($stmt)){
      // Success!
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
  // Check that opportunity_id is set
  if(empty(trim($_GET["opportunity_id"]))){
    //NOTE: Error!
    header("Location: error.php");
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
                            <p>Delete this opportunity?</p><br>
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
