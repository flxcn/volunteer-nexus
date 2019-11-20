<?php
session_start();

if(!isset($_SESSION["volunteer_loggedin"]) || $_SESSION["volunteer_loggedin"] !== true)
{
    header("location: login.php");
    exit;
}

// Check existence of id parameter before processing further
if(isset($_GET["event_id"]) && isset($_GET["opportunity_id"])){
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
                $sponsor_id = $row["sponsor_id"];
                $opportunity_name = $row["opportunity_name"];
                $description = $row["description"];
                $start_date = $row["start_date"];
                $start_time = $row["start_time"];
                $end_date = $row["end_date"];
                $end_time = $row["end_time"];
                $total_positions = $row["total_positions"];
                $contribution_value = $row["contribution_value"];
                $needs_verification = $row["needs_verification"];
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
    mysqli_close($link);

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

  <!--Navigation Bar-->
  <?php $thisPage='Events'; include 'navbar.php';?>

    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <!-- <div class="page-header">
                        <h1>View Opportunity</h1>
                    </div> -->
                    <div class="page-header clearfix">
                        <h2 class="pull-left">View Opportunity</h2>
                        <p><a href='event-read.php?event_id=<?php echo $_GET["event_id"]; ?>' class="btn btn-primary pull-right">Back</a></p>
                    </div>

                    <table class='table table-details'>
                        <tr>
                          <th>Opportunity Name</th>
                          <td><?php echo $row["opportunity_name"]; ?></td>
                        </tr>
                        <tr>
                          <th>Description</th>
                          <td><?php echo $row["description"]; ?></td>
                        </tr>
                    </table>

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

                    <div class="form-group">
                      <form action="engagement-create.php" method="post">
                        <input type="hidden" name="event_id" value="<?php echo trim($_GET["event_id"]); ?>">
                        <input type="hidden" name="opportunity_id" value="<?php echo trim($_GET["opportunity_id"]); ?>">
                        <input type="hidden" name="sponsor_id" value="<?php echo $row["sponsor_id"];?>">
                        <input type="hidden" name="volunteer_id" value="<?php echo trim($_SESSION["volunteer_id"]); ?>">
                        <input type="hidden" name="contribution_value" value="<?php echo $row["contribution_value"];?>">
                        <input type="hidden" name="needs_verification" value="<?php echo $row["needs_verification"];?>">
                        <input type="submit" class="btn btn-success" value="Sign up!">
                      </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery, ensures one-time use of submit button -->
    <script>
    $("body").on("submit", "form", function() {
        $(this).submit(function() {
            return false;
        });
        return true;
    });
    </script>

    <?php include '../footer.php';?>
</body>
</html>
