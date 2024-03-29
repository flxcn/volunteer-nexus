<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["sponsor_loggedin"]) || $_SESSION["sponsor_loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once '../classes/AttendanceAnywhere.php';

if($_SERVER["REQUEST_METHOD"] == "POST") {
  if($_POST["event_id"]
    && $_POST["opportunity_id"]
    && $_POST["volunteer_id"]
    && $_POST["sponsor_id"]
    && $_POST["contribution_value"]) {
    $event_id = $_POST["event_id"];
    $opportunity_id = $_POST["opportunity_id"];
    $volunteer_id = $_POST["volunteer_id"];
    $sponsor_id = $_POST["sponsor_id"];
    $contribution_value = $_POST["contribution_value"];

    $obj = new AttendanceAnywhere($sponsor_id, $event_id, $opportunity_id, $contribution_value);
    $status = $obj->setVolunteerId($volunteer_id);
    if(!$status) {
      echo "Invalid Volunteer ID. Please try again.";
      exit;
    }

    $status = $obj->confirmAttendance();
    if($status) {
      echo "Success! Check-in complete!";
    }
    else {
      echo "Error. Please try again.";
    }
  }
}

?>
