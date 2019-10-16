<?php
session_start();

//Make sure user is logged in
if(!isset($_SESSION["sponsor_loggedin"]) || $_SESSION["sponsor_loggedin"] == FALSE){
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Engagements</title>

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

    <!--AJAX-->
    <script>
      function showStatus(id,num) {
        if (id == "" && num == ""){
          return;
        } else
        {
          if (window.XMLHttpRequest)
          {
            xmlhttp = new XMLHttpRequest();
          }

          xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
              document.getElementById("statusOf"+id).innerHTML = this.responseText;
            }
          };
          xmlhttp.open("GET","engagement-verify.php?engagement_id="+id+"&status="+num,true);
          xmlhttp.send();
        }
      }
    </script>

</head>





<body>
    <?php $thisPage='Engagements'; include 'navbar.php';?>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header clearfix">
                        <h2 class="pull-left">Engagements</h2>
                        <!-- <a href="engagement-create.php" class="btn btn-success pull-right">Add New Engagement</a> -->
                    </div>

                    <?php
                    require_once "../config.php";

                    $sql = "SELECT opportunities.start_date AS start_date, engagements.engagement_id AS engagement_id, engagements.time_submitted, volunteers.first_name AS first_name, volunteers.last_name AS last_name, events.event_name AS event_name, opportunities.role_name AS role_name
                    FROM engagements LEFT JOIN volunteers ON volunteers.volunteer_id = engagements.volunteer_id LEFT JOIN events ON events.event_id = engagements.event_id LEFT JOIN opportunities ON opportunities.opportunity_id = engagements.opportunity_id
                    WHERE engagements.sponsor_id = '{$_SESSION['sponsor_id']}' AND engagements.status IS NULL AND opportunities.start_date >= CURDATE()
                    GROUP BY engagements.time_submitted, engagements.engagement_id, events.event_name, opportunities.role_name, volunteers.first_name, volunteers.last_name";

                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo "<table class='table table-hover'>";
                                echo "<thead class='thead-dark'>";
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
                                        echo "<td>" . $row['role_name'] . "</td>";
                                        echo "<td>" . $row['event_name'] . "</td>";
                                        echo "<td>";
                                            echo "<div id='statusOf". $row['engagement_id'] ."'>";
                                            echo "<a onclick='showStatus(" . $row['engagement_id'] .",1)' title='Confirm This Engagement' data-toggle='tooltip'><span class='glyphicon glyphicon-ok'></span></a>";
                                            echo "<a onclick='showStatus(" . $row['engagement_id'] .",0)' title='Deny This Engagement' data-toggle='tooltip'><span class='glyphicon glyphicon-remove'></span></a>";
                                            echo "</div>";
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            echo "<p class='lead'><em>No pending engagements were found.</em></p>";
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
