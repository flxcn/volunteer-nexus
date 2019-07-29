<?php
// Include config file
require_once 'config.php';
// Check existence of id parameter before processing further
if(isset($_GET["event_id"]) && !empty(trim($_GET["event_id"]))){
    // Include config file
    require_once "config.php";

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
                $sponsor = $row["sponsor"];
                $description = $row["description"];
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
    <title>View Record</title>
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
                        <label>Sponsor</label>
                        <p class="form-control-static"><?php echo $row["sponsor"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <p class="form-control-static"><?php echo $row["description"]; ?></p>
                    </div>
                    <p><a href="index.php" class="btn btn-primary">Back</a></p>
                </div>
            </div>
        </div>
    </div>

    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header clearfix">
                        <h2 class="pull-left">Opportunities Available</h2>
                        <a href="create.php" class="btn btn-success pull-right">Add New Opportunity</a>
                    </div>

                    <?php
                    // Attempt select query execution
                    $sql = "SELECT * FROM events";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo "<table class='table table-bordered table-striped'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>#</th>";
                                        echo "<th>Event Name</th>";
                                        echo "<th>Sponsor</th>";
                                        echo "<th>Description</th>";
                                        echo "<th>Location</th>";
                                        echo "<th>Contact Name</th>";
                                        echo "<th>Contact Phone</th>";
                                        echo "<th>Contact Email</th>";
                                        echo "<th>Registration End</th>";
                                        echo "<th>Event Start</th>";
                                        echo "<th>Event End</th>";

                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['event_id'] . "</td>";
                                        echo "<td>" . $row['event_name'] . "</td>";
                                        echo "<td>" . $row['sponsor'] . "</td>";
                                        echo "<td>" . $row['description'] . "</td>";
                                        echo "<td>" . $row['location'] . "</td>";
                                        echo "<td>" . $row['contact_name'] . "</td>";
                                        echo "<td>" . $row['contact_phone'] . "</td>";
                                        echo "<td>" . $row['contact_email'] . "</td>";
                                        echo "<td>" . $row['registration_end'] . "</td>";
                                        echo "<td>" . $row['event_start'] . "</td>";
                                        echo "<td>" . $row['event_end'] . "</td>";
                                        echo "<td>";
                                            echo "<a href='read.php?event_id=". $row['event_id'] ."' title='View Event' data-toggle='tooltip'><span class='glyphicon glyphicon-eye-open'></span></a>";
                                            echo "<a href='update.php?event_id=". $row['event_id'] ."' title='Update Event' data-toggle='tooltip'><span class='glyphicon glyphicon-pencil'></span></a>";
                                            echo "<a href='delete.php?event_id=". $row['event_id'] ."' title='Delete Event' data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a>";
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            echo "<p class='lead'><em>No events were found.</em></p>";
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
