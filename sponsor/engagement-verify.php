<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["sponsor_loggedin"]) || $_SESSION["sponsor_loggedin"] !== true){
    header("location: login.php");
    exit;
}

require '../classes/EngagementVerification.php';

$sponsor_id = $_SESSION["sponsor_id"];
$obj = new EngagementVerification($sponsor_id);

if($_SERVER["REQUEST_METHOD"] == "GET")
{
  // Check input errors before inserting in database
  if(isset($_GET["engagement_id"]) && isset($_GET["status"]))
  {
    // Set engagement_id
    $engagement_id = $_GET["engagement_id"];
    $obj->setEngagementId($engagement_id);

    // Set engagement_status
    $engagement_status = $_GET["status"];
    $obj->setEngagementStatus($engagement_status);

    if($obj->updateEngagement()) {
      if($engagement_status == -1) {
        echo "<a onclick='showStatus(" . $engagement_id .",1)' class='btn btn-success btn-sm'><span class='glyphicon glyphicon-ok'></span>Confirm</a> ";
        echo "<a onclick='showStatus(" . $engagement_id .",0)' class='btn btn-danger btn-sm' ><span class='glyphicon glyphicon-remove'></span>Deny</a>";
      }
      elseif($engagement_status == 0) {
        echo "<b>Denied!</b> <a onclick='showStatus(" . $engagement_id .", -1)' class='btn btn-outline-secondary btn-sm'>Undo</a>";
      }
      elseif($engagement_status == 1) {
        echo "<b>Confirmed!</b> <a onclick='showStatus(" . $engagement_id .", -1)' class='btn btn-outline-secondary btn-sm'>Undo</a>";
      }
      else {
        echo "Error!";
      }
    }
  }
}
?>
