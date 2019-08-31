<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Upcoming Engagements</title>
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
                        <h2 class="pull-left">Upcoming Engagements</h2>
                    </div>

                    <?php
                    // Attempt select query execution

                    $sql2 =
                    "SELECT engagements.engagement_id AS engagement_id, opportunities.opportunity_id AS opportunity_id, opportunities.start_date AS start_date, opportunities.end_date AS end_date, opportunities.start_time AS start_time, opportunities.end_time AS end_time, events.event_name AS event_name, opportunities.role_name AS role_name
                    FROM engagements LEFT JOIN volunteers ON volunteers.student_id = engagements.student_id LEFT JOIN events ON events.event_id = engagements.event_id LEFT JOIN opportunities ON opportunities.opportunity_id = engagements.opportunity_id
                    WHERE engagements.student_id = '{$_SESSION['student_id']}' AND opportunities.end_date >= CURDATE()
                    GROUP BY opportunities.start_date, opportunities.end_date, opportunities.start_time, opportunities.end_time, engagements.engagement_id, events.event_name, opportunities.role_name, opportunities.opportunity_id";

                    if($result = mysqli_query($link, $sql2)){
                        if(mysqli_num_rows($result) > 0){
                            echo "<table class='table table-bordered table-striped'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>Start Date/Time</th>";
                                        echo "<th>End Date/Time</th>";
                                        echo "<th>Event Name</th>";
                                        echo "<th>Opportunity Name</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['start_date'] . " " . $row['start_time'] . "</td>";
                                        echo "<td>" . $row['end_date'] . " " . $row['end_time'] . "</td>";
                                        echo "<td>" . $row['event_name'] . "</td>";
                                        echo "<td>" . $row['role_name'] . "</td>";
                                        echo "<td>";
                                            echo "<a href='engagement-read.php?engagement_id=" . $row['engagement_id'] . "&opportunity_id=" . $row['opportunity_id'] . "' title='View This Engagement' data-toggle='tooltip'><span class='glyphicon glyphicon-eye-open'></span></a>";
                                            echo "<a href='engagement-delete.php?engagement_id=" . $row['engagement_id'] . "' title='Delete This Engagement' data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a>";
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            echo "<p class='lead'><em>No upcoming engagements were found.</em></p>";
                        }
                    } else{
                        echo "ERROR: Could not able to execute $sql2. " . mysqli_error($link);
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
