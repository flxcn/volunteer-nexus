<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Engagements</title>
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
                        <h2 class="pull-left">Pending Engagements</h2>
                        <a href="engagement-create.php" class="btn btn-success pull-right">Add New Engagement</a>
                    </div>

                    <?php
                    // Include config file
                    require_once "../config.php";
                    // Attempt select query execution

                    $sql = "SELECT engagements.engagement_id AS engagement_id, engagements.time_submitted, volunteers.first_name AS first_name, volunteers.last_name AS last_name, events.event_name AS event_name, opportunities.role_name AS role_name FROM engagements LEFT JOIN volunteers ON volunteers.student_id = engagements.student_id LEFT JOIN events ON events.event_id = engagements.event_id LEFT JOIN opportunities ON opportunities.opportunity_id = engagements.opportunity_id WHERE engagements.sponsor_id = '{$_SESSION['sponsor_id']}' AND engagements.status IS NULL GROUP BY engagements.time_submitted, engagements.engagement_id, events.event_name, opportunities.role_name, volunteers.first_name, volunteers.last_name";

                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo "<table class='table table-bordered table-striped'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>Name</th>";
                                        echo "<th>Opportunity</th>";
                                        echo "<th>Event</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['first_name'] . " " . $row['last_name'] . "</td>"; //NOTE: this needs work!
                                        echo "<td>" . $row['role_name'] . "</td>"; //NOTE: this needs work!
                                        echo "<td>" . $row['event_name'] . "</td>"; //NOTE: this needs work!
                                        echo "<td>";
                                            echo "<a href='engagement-verify.php?engagement_id=". $row['engagement_id'] ."?status=1' title='Confirm This Engagement' data-toggle='tooltip'><span class='glyphicon glyphicon-ok'></span></a>";
                                            echo "<a href='engagement-verify.php?engagement_id=". $row['engagement_id'] ."?status=0' title='Deny This Engagement' data-toggle='tooltip'><span class='glyphicon glyphicon-remove'></span></a>";
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            echo "<p class='lead'><em>No affiliations were found.</em></p>";
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
