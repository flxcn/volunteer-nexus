<?php
session_start();

// Make sure user is logged in
if(!isset($_SESSION["volunteer_loggedin"]) || $_SESSION["volunteer_loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "../config.php";

if(isset($_POST["affiliation_id"]) && !empty($_POST["affiliation_id"])){

  $sql = "DELETE FROM affiliations WHERE affiliation_id = ? AND volunteer_id = ?";

  if($stmt = mysqli_prepare($link, $sql)){
    mysqli_stmt_bind_param($stmt, "ii", $param_affiliation_id, $param_volunteer_id);

    // Set params
    $param_engagement_id = trim($_POST["affiliation_id"]);
    $param_volunteer_id = trim($_SESSION["volunteer_id"]);

    if(mysqli_stmt_execute($stmt)){
      //NOTE: Success!
      header("Location: dashboard.php");
      exit();
    } else{
      echo "Oops! Something went wrong. Please try again later.";
    }
  }

  mysqli_stmt_close($stmt);

  mysqli_close($link);
} else{
  // Chec if affiliation_id is present
  if(empty(trim($_GET["affiliation_id"]))){
    //NOTE: Error!
    header("location: error.php");
    exit();
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Affiliation</title>

    <!--Load required libraries-->
    <?php include '../head.php'?>

</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h1>Delete Affiliation</h1>
                    </div>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="alert alert-danger fade in">
                            <input type="hidden" name="affiliation_id" value="<?php echo trim($_GET["affiliation_id"]); ?>"/>
                            <p>Are you sure you want to delete this affiliation? This action can be undone.</p><br>
                            <p>
                                <input type="submit" value="Yes" class="btn btn-danger">
                                <a href="dashboard.php" class="btn btn-default">No</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
