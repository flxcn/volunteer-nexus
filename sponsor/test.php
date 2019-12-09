<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["sponsor_loggedin"]) || $_SESSION["sponsor_loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once '../classes/AttendanceAnywhere.php';

    $event_id = 9;
    $opportunity_id = 17;
    $student_id = 83436;
    $sponsor_id = 1;
    $contribution_value = 1000;

    $obj = new AttendanceAnywhere($sponsor_id, $event_id, $opportunity_id, $contribution_value);
    $status = $obj->setVolunteerId($student_id);
    if($status) {
      echo "Invalid Student ID. Please try again.";
      exit;
    }
    echo

    $status = $obj->confirmAttendance();
    if($status) {
      echo "Success! Check-in complete!";
    }
    else {
      echo "Error. Please try again.";
    }

?>
