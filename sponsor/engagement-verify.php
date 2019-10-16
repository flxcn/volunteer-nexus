<!DOCTYPE HTML>
<html>
<head>
</head>
<body>
<?php
session_start();

//Make sure user is loged in
if(!isset($_SESSION["sponsor_loggedin"]) || $_SESSION["sponsor_loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Include config file
require_once '../config.php';

if($_SERVER["REQUEST_METHOD"] == "GET")
{
    // Check that engagement_id and status are set
    if(isset($_GET["engagement_id"]) && isset($_GET["status"]))
    {
        $sql = "UPDATE engagements SET status=? WHERE engagement_id=?";

        if($stmt = mysqli_prepare($link, $sql))
        {
              mysqli_stmt_bind_param($stmt, "ii", $param_status, $param_engagement_id);

              // Set params
              $param_engagement_id = $_GET["engagement_id"];
              $param_status = $_GET["status"];

              if(mysqli_stmt_execute($stmt))
              {
                  //NOTE: ERROR!
                  if($_GET["status"] == 1)
                    echo "<b>Confirmed!</b>";
                  if($_GET["status"] == 0)
                    echo "<b>Denied!</b><a onclick='showStatus(" . $row['engagement_id'] .", NULL)' class='btn btn-outline-secondary'><>Undo</a>";
              }
              else
              {
                echo "Something went wrong. Please try again later. If the issue persists, send an email to westlakestuco@gmail.com detailing the problem.";
              }

          }

          mysqli_stmt_close($stmt);
    }

  mysqli_close($link);
}
?>
</body>
</html>
