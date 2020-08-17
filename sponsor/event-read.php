<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["sponsor_loggedin"]) || $_SESSION["sponsor_loggedin"] !== true){
    header("location: login.php");
    exit;
}

function formatLinks($text) {
    return preg_replace('@(http)?(s)?(://)?(([a-zA-Z])([-\w]+\.)+([^\s\.]+[^\s]*)+[^,.\s])@', '<a target="ref" href="http$2://$4">$1$2$3$4</a>', $text);
}

// Check existence of id parameter before processing further
if(isset($_GET["event_id"]) && !empty(trim($_GET["event_id"]))){
    // Include config file
    require_once "../config.php";

    // Prepare a select statement
    $sql = "SELECT * FROM events WHERE event_id = ?";

    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_event_id);

        // Set parameters
        $param_event_id = trim($_GET["event_id"]);

        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);

            if(mysqli_num_rows($result) == 1){
                /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                // Retrieve individual field value
                $event_id = $row["event_id"];
                $event_name = $row["event_name"];
                $sponsor_name = $row["sponsor_name"];
                $description = $row["description"];
                $location = $row["location"];
                $contribution_type = $row["contribution_type"];
                $contact_name = $row["contact_name"];
                $contact_phone = $row["contact_phone"];
                $contact_email = $row["contact_email"];
                $registration_start = $row["registration_start"];
                $registration_end = $row["registration_end"];
                $event_start = $row["event_start"];
                $event_end = $row["event_end"];
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
    <title>View Event</title>
    <!--Load required libraries-->
    <?php include '../head.php'?>
    <style type="text/css">
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
                        <h2 class="pull-left">View Event</h2>
                        <p>
                          <a href="event-update.php?event_id=<?php echo $_GET["event_id"];?>" class="btn btn-primary pull-right"><span class='glyphicon glyphicon-pencil'></span> Edit</a>
                          <a href="events.php" class="btn btn-default pull-right">Back</a>
                        </p>
                    </div>

                    <table class='table table-details'>
                        <tr>
                          <th>Event Name</th>
                          <td><?php echo $row["event_name"]; ?></td>
                        </tr>
                        <tr>
                          <th>Sponsor Name</th>
                          <td><?php echo $row["sponsor_name"]; ?></td>
                        </tr>
                        <tr>
                          <th>Description</th>
                          <td><?php echo formatLinks($row["description"]); ?></td>
                        </tr>
                        <tr>
                          <th>Location</th>
                          <td><?php echo $row["location"]; ?></td>
                        </tr>
                        <tr>
                          <th>Contribution Type</th>
                          <td><?php echo $row["contribution_type"]; ?></td>
                        </tr>
                    </table>

                    <table class="table table-details">
                        <tr>
                          <th>Contact Name(s)</th>
                          <td><?php echo $row["contact_name"]; ?></td>
                        </tr>
                        <tr>
                          <th>Contact Phone(s)</th>
                          <td><?php echo $row["contact_phone"]; ?></td>
                        </tr>
                        <tr>
                          <th>Contact Email(s)</th>
                          <td><?php echo $row["contact_email"]; ?></td>
                        </tr>
                    </table>

                    <table class="table table-details">
                        <tr>
                          <th style="color:red">Registration Deadline</th>
                          <td><?php echo $row["registration_end"]; ?></td>
                        </tr>
                        <tr>
                          <th>Event Duration</th>
                          <td><?php echo $row["event_start"]; ?> to <?php echo $row["event_end"]; ?></td>
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
                        <h2 class="pull-left">Opportunities</h2>
                        <a href="opportunity-create.php?event_id=<?php echo $_GET["event_id"]?>" class="btn btn-success pull-right">Add New Opportunity</a>
                    </div>

                    <?php
                    // Attempt select query execution
                    $sql = "SELECT opportunities.opportunity_id AS opportunity_id, opportunities.event_id AS event_id, opportunity_name, description, start_date, start_time, end_date, end_time, total_positions, COUNT(engagement_id) AS positions_filled
                    FROM opportunities LEFT JOIN engagements ON opportunities.opportunity_id = engagements.opportunity_id
                    WHERE opportunities.event_id = '{$_GET["event_id"]}'
                    GROUP BY opportunity_name, description, start_date, start_time, end_date, end_time, total_positions, opportunities.opportunity_id";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo "<table class='table table-bordered table-striped'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>Role Name</th>";
                                        echo "<th>Description</th>";
                                        echo "<th>Start Date</th>";
                                        echo "<th>End Date</th>";
                                        echo "<th>Positions Filled</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['opportunity_name'] . "</td>";
                                        echo "<td>" . $row['description'] . "</td>";
                                        echo "<td>" . $row['start_date'] . " " . $row['start_time'] ."</td>";
                                        echo "<td>" . $row['end_date'] . " " . $row['end_time'] . "</td>";
                                        echo "<td>" . $row['positions_filled'] . "/" . $row['total_positions'] . "</td>";
                                        echo "<td>";
                                            echo "<a href='opportunity-read.php?event_id=" . $_GET["event_id"] . "&opportunity_id=". $row['opportunity_id'] ."' title='View Opportunity' data-toggle='tooltip' class='btn btn-link'><span class='glyphicon glyphicon-eye-open'></span> View</a>";
                                            echo "<br>";
                                            echo "<a href='opportunity-update.php?event_id=" . $_GET["event_id"] . "&opportunity_id=". $row['opportunity_id'] ."' title='Update Opportunity' data-toggle='tooltip' class='btn btn-link' style='color:black'><span class='glyphicon glyphicon-pencil'></span> Edit</a>";
                                            echo "<br>";
                                            echo "<a href='opportunity-delete.php?event_id=" . $_GET["event_id"] . "&opportunity_id=". $row['opportunity_id'] ."' title='Delete Opportunity' data-toggle='tooltip' class='btn btn-link' style='color:red'><span class='glyphicon glyphicon-trash'></span> Delete</a>";
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
