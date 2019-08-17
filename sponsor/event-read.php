<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h1>View Event</h1>
                    </div>

                    <div class="form-group">
                        <label>Event Name</label>
                        <p class="form-control-static"><?php echo $row["event_name"]; ?></p>
                    </div>

                    <div class="form-group">
                        <label>Sponsor Name</label>
                        <p class="form-control-static"><?php echo $row["sponsor_name"]; ?></p>
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <p class="form-control-static"><?php echo $row["description"]; ?></p>
                    </div>

                    <div class="form-group">
                        <label>Location</label>
                        <p class="form-control-static"><?php echo $row["location"]; ?></p>
                    </div>

                    <div class="form-group">
                        <label>Contribution Type</label>
                        <p class="form-control-static"><?php echo $row["contribution_type"]; ?></p>
                    </div>

                    <div class="form-group">
                        <label>Contact Name(s)</label>
                        <p class="form-control-static"><?php echo $row["contact_name"]; ?></p>
                    </div>

                    <div class="form-group">
                        <label>Contact Phone(s)</label>
                        <p class="form-control-static"><?php echo $row["contact_phone"]; ?></p>
                    </div>

                    <div class="form-group">
                        <label>Contact Email(s)</label>
                        <p class="form-control-static"><?php echo $row["contact_email"]; ?></p>
                    </div>

                    <div class="form-group">
                        <label>Registration Start Date</label>
                        <p class="form-control-static"><?php echo $row["registration_start"]; ?></p>
                    </div>

                    <div class="form-group">
                        <label>Registration End Date</label>
                        <p class="form-control-static"><?php echo $row["registration_end"]; ?></p>
                    </div>

                    <div class="form-group">
                        <label>Event Start Date</label>
                        <p class="form-control-static"><?php echo $row["event_start"]; ?></p>
                    </div>

                    <div class="form-group">
                        <label>Event End Date</label>
                        <p class="form-control-static"><?php echo $row["event_end"]; ?></p>
                    </div>

                    <p><a href="events.php" class="btn btn-primary">Back</a></p>
                </div>
            </div>
        </div>
    </div>

    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header clearfix">
                        <h2 class="pull-left">Relevant Opportunities</h2>
                        <a href="opportunity-create.php?event_id=<?php echo $_GET["event_id"]?>" class="btn btn-success pull-right">Add New Opportunity</a>
                    </div>

                    <?php
                    // Attempt select query execution
                    $sql = "SELECT * FROM opportunities WHERE event_id = '{$_GET["event_id"]}'";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo "<table class='table table-bordered table-striped'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>#</th>";
                                        echo "<th>Role Name</th>";
                                        echo "<th>Description</th>";
                                        echo "<th>Start Date</th>";
                                        echo "<th>End Date</th>";
                                        echo "<th>Start Time</th>";
                                        echo "<th>End Time</th>";
                                        echo "<th>Total Positions Available</th>";
                                        echo "<th>Positions Currently Filled</th>";

                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['opportunity_id'] . "</td>";
                                        echo "<td>" . $row['role_name'] . "</td>";
                                        echo "<td>" . $row['description'] . "</td>";
                                        echo "<td>" . $row['start_date'] . "</td>";
                                        echo "<td>" . $row['start_time'] . "</td>";
                                        echo "<td>" . $row['end_date'] . "</td>";
                                        echo "<td>" . $row['end_time'] . "</td>";
                                        echo "<td>" . $row['total_positions'] . "</td>";
                                        echo "<td>" . $row['total_positions'] . "</td>"; //NOTE: fix it
                                        echo "<td>";
                                            echo "<a href='read-opportunity.php?event_id=". $param_event_id ."&opportunity_id=". $row['opportunity_id'] ."' title='View Opportunity' data-toggle='tooltip'><span class='glyphicon glyphicon-eye-open'></span></a>";
                                            echo "<a href='update-opportunity.php?event_id=". $param_event_id ."&opportunity_id=". $row['opportunity_id'] ."' title='Update Opportunity' data-toggle='tooltip'><span class='glyphicon glyphicon-pencil'></span></a>";
                                            echo "<a href='delete-opportunity.php?event_id=". $param_event_id ."&opportunity_id=". $row['opportunity_id'] ."' title='Delete Opportunity' data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a>";
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

</body>
</html>
