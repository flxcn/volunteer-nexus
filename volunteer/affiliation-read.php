<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["volunteer_loggedin"]) || $_SESSION["volunteer_loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Contributions</title>
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
                        <h2 class="pull-left">My Past Engagements For This Affiliation</h2>
                    </div>

                    <?php
                    // Include config file
                    require_once "../config.php";
                    // Attempt select query execution

                    //NOTE: may need to sanitize the data in $_SESSION["sponsor_id"];
                    $sql = "SELECT *
                    FROM engagements INNER JOIN events ON engagements.event_id = events.event_id
                    WHERE engagements.student_id = '{$_SESSION['student_id']}' AND engagements.sponsor_id = '{$_GET['sponsor_id']}'";

                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo "<table class='table table-bordered table-striped'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>Time Submitted</th>";
                                        echo "<th>Event Name</th>";
                                        echo "<th>Description</th>";
                                        echo "<th>Location</th>";
                                        echo "<th>Contact Name</th>";
                                        echo "<th>Contact Email</th>";
                                        echo "<th>Event Duration</th>";
                                        echo "<th>Contribution Value</th>";

                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['time_submitted'] . "</td>";
                                        echo "<td>" . $row['event_name'] . "</td>";
                                        echo "<td>" . $row['description'] . "</td>";
                                        echo "<td>" . $row['location'] . "</td>";
                                        echo "<td>" . $row['contact_name'] . "</td>";
                                        echo "<td>" . $row['contact_email'] . "</td>";
                                        echo "<td>" . $row['event_start'] . " to " . $row['event_end'] . "</td>";
                                        echo "<td>" . $row['contribution_value'] . "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            echo "<p class='lead'><em>No past engagements were found.</em></p>";
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
