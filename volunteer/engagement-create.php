<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["volunteer_loggedin"]) || $_SESSION["volunteer_loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Include config file
require_once '../config.php';

if($_SERVER["REQUEST_METHOD"] == "POST")
{
    // Check input errors before inserting in database
    if(isset($_POST["event_id"]) && isset($_POST["opportunity_id"]) && isset($_POST["volunteer_id"]) && isset($_POST["sponsor_id"]) && isset($_POST["contribution_value"]) && isset($_POST["needs_verification"]))
    {
        // Prepare an insert statement
        $sql = "INSERT INTO engagements (event_id, opportunity_id, volunteer_id, sponsor_id, contribution_value, status) VALUES (?, ?, ?, ?, ?, ?)";

        if($stmt = mysqli_prepare($link, $sql))
        {
              // Bind variables to the prepared statement as parameters
              mysqli_stmt_bind_param($stmt, "iiiii", $param_event_id, $param_opportunity_id, $param_volunteer_id, $param_sponsor_id, $param_contribution_value, $param_status);

              // Set parameters
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

              // Attempt to execute the prepared statement
              if(mysqli_stmt_execute($stmt))
              {
                  // Records created successfully. Redirect to landing page
                  header("location: dashboard.php");
                  exit();
              }
              else
              {
                echo "Something went wrong. Please try again later. If the issue persists, send an email to westlakestuco@gmail.com detailing the problem.";
              }

          }

          // Close statement
          mysqli_stmt_close($stmt);
    }

  mysqli_close($link);
}

?>
