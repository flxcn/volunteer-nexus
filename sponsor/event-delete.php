<?php
session_start();

//Make sure user is logged in
if(!isset($_SESSION["sponsor_loggedin"]) || $_SESSION["sponsor_loggedin"] == FALSE){
    header("location: login.php");
    exit;
}

require_once "../config.php";

if(isset($_POST["event_id"]) && !empty($_POST["event_id"])){

  //SQL: Delete Event
  $sql = "DELETE FROM events WHERE event_id = ?";

  if($stmt = mysqli_prepare($link, $sql)){
    mysqli_stmt_bind_param($stmt, "i", $param_event_id);

    //Set parrams
    $param_event_id = trim($_POST["event_id"]);

    if(mysqli_stmt_execute($stmt)){
      //NOTE: Success!
      header("Location: events.php");
      exit();
    } else{
      echo "ERROR! Something went wrong... ";
    }
  }

  mysqli_stmt_close($stmt);
  mysqli_close($link);

} else{

  //Make sure event_id is set
  if(empty(trim($_GET["event_id"]))){
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

</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h1>Delete Event</h1>
                    </div>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="alert alert-danger fade in">
                            <input type="hidden" name="event_id" value="<?php echo trim($_GET["event_id"]); ?>"/>
                            <p>Delete this event? This cannot be undone.</p><br>
                            <p>
                                <input type="submit" value="Yes" class="btn btn-danger">
                                <a href="events.php" class="btn btn-default">No</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
