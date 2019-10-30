<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["sponsor_loggedin"]) || $_SESSION["sponsor_loggedin"] !== true){
  header("location: login.php");
  exit;
}

// Check existence of id parameter before processing further
if(isset($_GET["opportunity_id"])){
  // Include config file
  require_once "../config.php";

  // Prepare a select statement
  $sql = "SELECT * FROM opportunities WHERE opportunity_id = ?";

  if($stmt = mysqli_prepare($link, $sql)){
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "i", $param_opportunity_id);

    // Set parameters
    $param_opportunity_id = trim($_GET["opportunity_id"]);

    // Attempt to execute the prepared statement
    if(mysqli_stmt_execute($stmt)){
      $result = mysqli_stmt_get_result($stmt);

      if(mysqli_num_rows($result) == 1){
        /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

        // Retrieve individual field value
        $role_name = $row["role_name"];
        $description = $row["description"];
        $start_date = $row["start_date"];
        $start_time = $row["start_time"];
        $end_date = $row["end_date"];
        $end_time = $row["end_time"];
        $total_positions = $row["total_positions"];
        //{7} $positions_available = $[];

      } else{
        // URL doesn't contain valid id parameter. Redirect to error page
        header("location: error.php");
        exit();
      }

    } else{
      echo "Oops! Something went wrong. Please try again later.";
    }
  }

  // Close statement
  mysqli_stmt_close($stmt);

  // Close connection
  //mysqli_close($link);
} else{
  // URL doesn't contain id parameter. Redirect to error page
  header("location: error.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Opportunity</title>
  <!--Load required libraries-->
  <?php include '../head.php'?>
  <style type="text/css">
  body{
    font: 12px sans-serif;
  }
  .wrapper{
    margin: 0 auto;
  }
  .page-header h2{
    margin-top: 0;
  }
  .table-details{
    table-layout: fixed;
    border: none;
  }
  </style>
</head>
<body>

  <?php $thisPage='Events'; include 'navbar.php';?>

  <div class="wrapper">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="page-header clearfix">
            <h2 class="pull-left">View Opportunity</h2>
            <p><a href='event-read.php?event_id=<?php echo $_GET["event_id"]; ?>' class="btn btn-primary pull-right">Back</a></p>
          </div>

          <!-- General Information -->
          <table class='table table-details'>
            <tr>
              <th>Role Name</th>
              <td><?php echo $row["role_name"]; ?></td>
            </tr>
            <tr>
              <th>Description</th>
              <td><?php echo $row["description"]; ?></td>
            </tr>
          </table>

          <!-- Date & Time Information -->
          <table class="table table-details">
            <tr>
              <th>On what date(s)?</th>
              <td>From <?php echo $row["start_date"]; ?> to <?php echo $row["end_date"]; ?></td>
            </tr>
            <tr>
              <th>At what time(s)?</th>
              <td>From <?php echo $row["start_time"]; ?> to <?php echo $row["end_time"]; ?></td>
            </tr>
          </table>

          <!-- Contribution System Information -->
          <table class="table table-details">
            <tr>
              <th>Total Positions</th>
              <td><?php echo $row["total_positions"]; ?></td>
            </tr>
            <tr>
              <th>Contribution Value</th>
              <td><?php echo $row["contribution_value"]; ?></td>
            </tr>
          </table>

        </div>
      </div>
    </div>
  </div>

  <div class="wrapper">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="page-header clearfix">
            <h2 class="pull-left">Who's Signed Up?</h2>
          </div>

          <?php
          // Attempt select query execution
          $sql = "SELECT engagements.time_submitted AS time_submitted, engagements.opportunity_id AS opportunity_id, volunteers.first_name AS first_name, volunteers.last_name AS last_name, volunteers.username AS email_address, engagements.engagement_id AS engagement_id
          FROM engagements LEFT JOIN volunteers ON volunteers.volunteer_id = engagements.volunteer_id
          WHERE engagements.opportunity_id = '{$_GET['opportunity_id']}'
          GROUP BY engagements.time_submitted, volunteers.first_name, volunteers.last_name, volunteers.username, engagements.engagement_id";

          if($result = mysqli_query($link, $sql)){
            if(mysqli_num_rows($result) > 0){
              echo "<table class='table table-bordered table-striped'>";
              echo "<thead>";
              echo "<tr>";
              echo "<th>Time Submitted</th>";
              echo "<th>Name</th>";
              echo "<th>Email</th>";
              echo "</tr>";
              echo "</thead>";
              echo "<tbody>";
              while($row = mysqli_fetch_array($result)){
                echo "<tr>";
                echo "<td>" . $row['time_submitted'] . "</td>";
                echo "<td>" . $row['last_name'] . ", " . $row['first_name'] . "</td>";
                echo "<td>" . $row['email_address'] . "</td>";
                echo "<td>";
                echo "<a href='engagement-delete.php?opportunity_id=". $row['opportunity_id'] ."&engagement_id=". $row['engagement_id'] ."' title='Delete Engagement' data-toggle='tooltip' style='color:red' class='btn btn-link' ><span class='glyphicon glyphicon-trash'></span> Delete</a>";
                echo "</td>";
                echo "</tr>";
              }
              echo "</tbody>";
              echo "</table>";
              // Free result set
              mysqli_free_result($result);
            } else{
              echo "<p class='lead'><em>No opportunities were found.</em></p>";
            }
          } else{
            echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
          }

          // Close connection
          mysqli_close($link);
          ?>
        </div>
      </div>
    </div>
  </div>
  <?php include '../footer.php';?>
</body>
</html>
