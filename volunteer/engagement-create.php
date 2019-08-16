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
    if(isset($_POST["opportunity_id"]) && isset($_POST["student_id"]))
    {
        // Prepare an insert statement
        $sql = "INSERT INTO engagements (opportunity_id, student_id) VALUES (?, ?)";

        if($stmt = mysqli_prepare($link, $sql))
        {
              // Bind variables to the prepared statement as parameters
              mysqli_stmt_bind_param($stmt, "ii", $param_opportunity_id, $param_student_id);

              // Set parameters
              $param_opportunity_id = $_POST["opportunity_id"];
              $param_student_id = $_POST["student_id"];

              // Attempt to execute the prepared statement
              if(mysqli_stmt_execute($stmt))
              {
                  // Records created successfully. Redirect to landing page
                  header("location: welcome.php"); //NOTE: this can redirect to my upcoming events page
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
