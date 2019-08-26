<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Include config file
require_once '../config.php';

if($_SERVER["REQUEST_METHOD"] == "POST")
{
    // Check input errors before inserting in database
    if(isset($_POST["engagement_id"]) && isset($_POST["status"]))
    {
        // Prepare an insert statement
        $sql = "UPDATE engagements (status) VALUES (?) WHERE engagement_id=?";

        if($stmt = mysqli_prepare($link, $sql))
        {
              // Bind variables to the prepared statement as parameters
              mysqli_stmt_bind_param($stmt, "ii", $param_status, $param_engagement_id);

              // Set parameters
              $param_engagement_id = $_POST["engagement_id"];
              $param_status = $_POST["status"];

              // Attempt to execute the prepared statement
              if(mysqli_stmt_execute($stmt))
              {
                  // Records created successfully. Redirect to landing page
                  echo "Done!";
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
