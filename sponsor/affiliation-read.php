<?php
session_start();

if(!isset($_SESSION["sponsor_loggedin"]) || $_SESSION["sponsor_loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>This Volunteer's Contributions</title>
    <!--Load required libraries-->
    <?php include '../head.php'?>

    <style type="text/css">
        .wrapper{
            margin: 0 auto;
        }
        .page-header h2{
            margin-top: 0;
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
</head>





<body>
  <?php $thisPage='Affiliations'; include 'navbar.php';?>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header clearfix">
                        <h2 class="pull-left">Volunteer's Past Engagements for This Sponsor</h2>
                        <a href='affiliations.php' class="btn btn-primary pull-right">Back</a>
                    </div>

                    <?php
                    require_once "../config.php";

                    $sql =
                    "SELECT
                      engagements.contribution_value AS contribution_value,
                      engagements.status AS status,
                      events.event_name AS event_name,
                      events.description AS event_description,
                      events.contact_name AS contact_name,
                      events.contact_email AS contact_email,
                      events.event_start AS event_start,
                      events.event_end AS event_end,
                      opportunities.opportunity_name AS opportunity_name
                    FROM
                      engagements
                      INNER JOIN
                        events
                        ON engagements.event_id = events.event_id
                      INNER JOIN
                        opportunities
                        ON engagements.opportunity_id = opportunities.opportunity_id
                    WHERE
                      engagements.volunteer_id = '{$_GET['volunteer_id']}'
                      AND engagements.sponsor_id = '{$_SESSION['sponsor_id']}'";

                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo "<table class='table table-bordered'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>Event Name</th>";
                                        echo "<th>Event Description</th>";
                                        echo "<th>Opportunity Name</th>";
                                        echo "<th>Contact Info</th>";
                                        echo "<th>Event Duration</th>";
                                        echo "<th>Value</th>";
                                        echo "<th>Status</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['event_name'] . "</td>";
                                        echo "<td>" . $row['event_description'] . "</td>";
                                        echo "<td>" . $row['opportunity_name'] . "</td>";
                                        echo "<td>" . $row['contact_name'] . "<br>" . $row['contact_email'] . "</td>";
                                        echo "<td>" . $row['event_start'] . " to " . $row['event_end'] . "</td>";
                                        echo "<td>" . $row['contribution_value'] . "</td>";
                                        echo "<td>";
                                          if(strcmp($row['status'],'1') == 0)
                                            echo "Confirmed";
                                          elseif (strcmp($row['status'],'0') == 0) {
                                            echo "Denied";
                                          }
                                          else {
                                            echo "Pending";
                                          }
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";
                            echo "</table>";
                            mysqli_free_result($result);
                        } else{
                            echo "<p class='lead'><em>No past engagements were found.</em></p>";
                        }
                    } else{
                        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
                    }

                    mysqli_close($link);
                    ?>
                </div>
            </div>
        </div>
    </div>

    <?php include '../footer.php';?>
</body>
</html>
