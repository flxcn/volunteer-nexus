<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: sponsor-login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Events</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.js"></script>
    <style type="text/css">
        .wrapper{
            width: 650px;
            margin: 0 auto;
        }
        .page-header h2{
            margin-top: 0;
        }
        table tr td:last-child a{
            margin-right: 15px;
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
</head>





<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header clearfix">
                        <h2 class="pull-left">My Sponsored Events</h2>
                        <a href="create-event.php" class="btn btn-success pull-right">Add New Event</a>
                    </div>

                    <?php
                    // Include config file
                    require_once "config.php";
                    // Attempt select query execution

                    //NOTE: may need to sanitize the data in $_SESSION["sponsor_name"];
                    $sql = "SELECT * FROM events WHERE sponsor_name = '$_SESSION["sponsor_name"]'";

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
                                            echo "<a href='event-read.php?event_id=". $row['event_id'] ."' title='View Event' data-toggle='tooltip'><span class='glyphicon glyphicon-eye-open'></span></a>";
                                            echo "<a href='event-update.php?event_id=". $row['event_id'] ."' title='Update Event' data-toggle='tooltip'><span class='glyphicon glyphicon-pencil'></span></a>";
                                            echo "<a href='event-update.php?event_id=". $row['event_id'] ."' title='Delete Event' data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a>";
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
