<?php
// Initialize the session
session_start();

// Make sure user is logged in
if(!isset($_SESSION["sponsor_loggedin"]) || $_SESSION["sponsor_loggedin"] == FALSE){
    header("Location: login.php");
    exit;
}

if(isset($_POST["engagement_id"]) && !empty($_POST["engagement_id"]) && isset($_POST["opportunity_id"]) && !empty($_POST["opportunity_id"])){
  require_once "../config.php";

  //SQL: Delete Engagement
  $sql = "DELETE FROM engagements WHERE engagement_id = ? AND sponsor_id = ?";

  if($stmt = mysqli_prepare($link, $sql)){
    mysqli_stmt_bind_param($stmt, "ii", $param_engagement_id, $param_sponsor_id);

    // Set params
    $param_engagement_id = trim($_POST["engagement_id"]);
    $param_sponsor_id = trim($_SESSION["sponsor_id"]);

    if(mysqli_stmt_execute($stmt)){
      //SUCCESS!
      $opportunity_id = $_POST['opportunity_id'];
      header("Location: opportunity-read.php?opportunity_id=$opportunity_id");
      exit();
    } else{
      echo "Oops! Something went wrong. Please try again later.";
    }
  }

  mysqli_stmt_close($stmt);
  mysqli_close($link);
} else{

  // Check that engagement_id is present
  if(empty(trim($_GET["engagement_id"]))){
    //NOTE: ERROR!
    header("location: error.php");
    exit();
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Delete Engagement</title>

    <!--Load required libraries-->
    <?php include '../head.php'?>
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
                            <input type="hidden" name="opportunity_id" value="<?php echo trim($_GET["opportunity_id"]); ?>"/>

                            <p>Are you sure you want to delete this engagement? This action cannot be undone.</p><br>
                            <p>
                                <input type="submit" value="Yes" class="btn btn-danger">
                                <a href="opportunity-read.php?opportunity_id=<?php echo $_GET['opportunity_id'];?>" class="btn btn-default">No</a> <!--BUG-->
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
