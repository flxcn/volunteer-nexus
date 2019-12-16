<!DOCTYPE html>
<html lang="en">

<head>
    <title>Upcoming Engagements</title>

    <!-- Toggle Bootstrap tooltips -->
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
                    if(session_status() !== PHP_SESSION_ACTIVE) session_start();
                    require_once "../config.php";

                    $sql =
                    "SELECT
                      engagements.engagement_id AS engagement_id,
                      opportunities.opportunity_id AS opportunity_id,
                      opportunities.start_date AS start_date,
                      opportunities.end_date AS end_date,
                      opportunities.start_time AS start_time,
                      opportunities.end_time AS end_time,
                      events.event_name AS event_name,
                      opportunities.opportunity_name AS opportunity_name
                    FROM
                      engagements
                      LEFT JOIN
                        volunteers
                        ON volunteers.volunteer_id = engagements.volunteer_id
                      LEFT JOIN
                        events
                        ON events.event_id = engagements.event_id
                      LEFT JOIN opportunities
                        ON opportunities.opportunity_id = engagements.opportunity_id
                    WHERE
                      engagements.volunteer_id = '{$_SESSION['volunteer_id']}'
                      AND opportunities.end_date >= CURDATE()
                    GROUP BY
                      opportunities.start_date,
                      opportunities.end_date,
                      opportunities.start_time,
                      opportunities.end_time,
                      engagements.engagement_id,
                      events.event_name,
                      opportunities.opportunity_name,
                      opportunities.opportunity_id";

                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo "<table class='table table-bordered'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>Start Date</th>";
                                        echo "<th>End Date</th>";
                                        echo "<th>Event Name</th>";
                                        echo "<th>Opportunity Name</th>";
                                        echo "<th>Action</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['start_date'] . " " . $row['start_time'] . "</td>";
                                        echo "<td>" . $row['end_date'] . " " . $row['end_time'] . "</td>";
                                        echo "<td>" . $row['event_name'] . "</td>";
                                        echo "<td>" . $row['opportunity_name'] . "</td>";
                                        echo "<td>";
                                            echo "<a href='engagement-read.php?engagement_id=" . $row['engagement_id'] . "&opportunity_id=" . $row['opportunity_id'] . "' title='View This Engagement' data-toggle='tooltip' class='btn btn-link' ><span class='glyphicon glyphicon-eye-open'></span> View</a>";
                                            echo "<br>";
                                            echo "<a href='engagement-delete.php?engagement_id=" . $row['engagement_id'] . "' title='Delete This Engagement' data-toggle='tooltip' style='color:red' class='btn btn-link' ><span class='glyphicon glyphicon-trash'></span> Delete</a>";
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
