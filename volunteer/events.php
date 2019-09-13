<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["volunteer_loggedin"]) || $_SESSION["volunteer_loggedin"] !== true)
{
    header("location: login.php");
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
                    // Include config file
                    require_once "../config.php";
                    // Attempt select query execution
                    $sql = "SELECT * FROM events WHERE registration_start <= CURDATE() AND registration_end >= CURDATE() ORDER BY registration_end";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo "<table class='table table-bordered table-hover'>";
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

    <?php include '../footer.php';?>
</body>
</html>
