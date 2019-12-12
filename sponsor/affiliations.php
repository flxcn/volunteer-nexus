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
    <title>Affiliations</title>
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

    <!--Toggle Bootstrap tooltip-->
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
                    <div class="page-header">
                        <h2 class="pull-left">Affiliated Volunteers</h2>
                    </div>

                    <?php
                    // Include config file
                    require_once "../config.php";

                    // Run SQL Query
                    $sql =
                    "SELECT
                      affiliations.volunteer_id AS volunteer_id,
                      volunteers.last_name AS last_name,
                      volunteers.first_name AS first_name,
                      volunteers.username AS email_address,
                      SUM(engagements.contribution_value) AS total_contribution_value
                    FROM
                      affiliations
                      INNER JOIN
                        volunteers
                        ON affiliations.volunteer_id = volunteers.volunteer_id
                      LEFT JOIN
                        engagements
                        ON affiliations.volunteer_id = engagements.volunteer_id
                    WHERE
                      affiliations.sponsor_id = '{$_SESSION['sponsor_id']}'
                      AND engagements.status = '1'
                    GROUP BY
                      volunteers.last_name,
                      volunteers.first_name,
                      volunteers.username";

                    if($result = mysqli_query($link, $sql)) {
                        if(mysqli_num_rows($result) > 0) {
                            echo "<table class='table'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>Member Name</th>";
                                        echo "<th>Email Address</th>";
                                        echo "<th>Total Contributions</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['last_name'] . ", " . $row['first_name'] . "</td>";
                                        echo "<td>" . $row['email_address'] . "</td>";
                                        echo "<td>" . $row['total_contribution_value'] . "</td>";
                                        echo "<td>";
                                            echo "<a href='affiliation-read.php?volunteer_id=". $row['volunteer_id'] ."' title='View Volunteer's Contributions' data-toggle='tooltip' class='btn btn-link'><span class='glyphicon glyphicon-eye-open'></span> View</a>";
                                            //echo "<a href='affiliation-delete.php?affiliation_id=". $row['affiliation_id'] ."' title='Delete This Affiliation' data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a>";
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result);
                        } else
                        {
                            echo "<p class='lead'><em>No affiliated volunteers were found.</em></p>";
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
