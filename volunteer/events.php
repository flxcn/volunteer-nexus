<?php
session_start();

//Make sure user is logged in
if(!isset($_SESSION["volunteer_loggedin"]) || $_SESSION["volunteer_loggedin"] == FALSE)
{
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Find Events</title>

    <!--Load required libraries-->
    <?php include '../head.php'?>

    <script type="text/javascript">
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
</head>





<body>

  <!--Navigation Bar-->
  <?php $thisPage='Events'; include 'navbar.php';?>

    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header clearfix">
                        <h2 class="pull-left">All Events</h2>
                        <!-- <a href="affiliation-create.php" class="btn btn-success pull-right">Add New Affiliation</a> -->
                    </div>

                    <?php
                    require_once "../config.php";

                    $sql = "SELECT * FROM events WHERE registration_start <= CURDATE() AND registration_end >= CURDATE() ORDER BY registration_end";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo "<table class='table table-bordered table-hover table-responsive'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>Reg. Deadline</th>";
                                        echo "<th>Event Name</th>";
                                        echo "<th>Sponsor Name</th>";
                                        echo "<th>Description</th>";
                                        echo "<th>Location</th>";
                                        echo "<th>Event Duration</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['registration_end'] . "</td>";
                                        echo "<td>" . $row['event_name'] . "</td>";
                                        echo "<td>" . $row['sponsor_name'] . "</td>";
                                        echo "<td>" . $row['description'] . "</td>";
                                        echo "<td>" . $row['location'] . "</td>";
                                        echo "<td>" . $row['event_start'] . " to " . $row['event_end'] . "</td>";
                                        echo "<td>";
                                            echo "<a href='event-read.php?event_id=". $row['event_id'] ."' title='View Event' data-toggle='tooltip'><span class='glyphicon glyphicon-eye-open'></span></a>";
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";
                            echo "</table>";
                            mysqli_free_result($result);
                        } else{
                            echo "<p class='lead'><em>No events were found.</em></p>";
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
