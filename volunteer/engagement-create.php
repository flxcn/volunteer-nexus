<?php
session_start();

// Make sure user is logged in
if(!isset($_SESSION["volunteer_loggedin"]) || $_SESSION["volunteer_loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once '../config.php';

if($_SERVER["REQUEST_METHOD"] == "POST")
{
    //Check to make sure all error variables are clear
    if(isset($_POST["event_id"]) && isset($_POST["opportunity_id"]) && isset($_POST["volunteer_id"]) && isset($_POST["sponsor_id"]) && isset($_POST["contribution_value"]) && isset($_POST["needs_verification"]))
    {
        $sql = "INSERT INTO engagements (event_id, opportunity_id, volunteer_id, sponsor_id, contribution_value, status) VALUES (?, ?, ?, ?, ?, ?)";

        if($stmt = mysqli_prepare($link, $sql))
        {
              mysqli_stmt_bind_param($stmt, "iiiiii", $param_event_id, $param_opportunity_id, $param_volunteer_id, $param_sponsor_id, $param_contribution_value, $param_status);

              // Set params
              $param_event_id = $_POST["event_id"];
              $param_opportunity_id = $_POST["opportunity_id"];
              $param_volunteer_id = $_POST["volunteer_id"];
              $param_sponsor_id = $_POST["sponsor_id"];
              $param_contribution_value = $_POST["contribution_value"];

              $param_status = NULL;

              if(trim($_POST["needs_verification"]) == 0)
              {
                $param_status = 1;
              }

              if(mysqli_stmt_execute($stmt))
              {
                  //NOTE: Success!
                  header("Location: dashboard.php");
                  exit();
              }
              else
              {
                echo "Something went wrong... If the issue persists, send an email to felix@volunteernexus.com detailing the problem.";
              }

          }

          mysqli_stmt_close($stmt);
    }

  mysqli_close($link);
}

?>
