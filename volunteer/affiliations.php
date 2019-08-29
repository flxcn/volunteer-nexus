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
    <title>Affiliations</title>
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
                        <h2 class="pull-left">My Affiliations</h2>
                        <a href="affiliation-create.php" class="btn btn-success pull-right">Add New Affiliation</a>
                    </div>

                    <?php
                    // Include config file
                    require_once "../config.php";
                    // Attempt select query execution

                    $sql = "SELECT sponsors.sponsor_name AS sponsor_name, sponsors.sponsor_id AS sponsor_id, SUM(engagements.contribution_value) AS total_contribution_value FROM sponsors LEFT JOIN engagements ON engagements.sponsor_id = sponsors.sponsor_id WHERE student_id = '{$_SESSION['student_id']}' AND status = 1 GROUP BY sponsors.sponsor_name";

                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo "<table class='table table-bordered table-striped'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>Affiliated Sponsor</th>";
                                        echo "<th>My Total Contributions</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['sponsor_name'] . "</td>"; //NOTE: this needs work!
                                        echo "<td>" . $row['total_contribution_value'] . "</td>"; //NOTE: this needs work!
                                        echo "<td>";
                                            echo "<a href='affiliation-read.php?sponsor_id=". $row['sponsor_id'] ."' title='View My Contributions' data-toggle='tooltip'><span class='glyphicon glyphicon-eye-open'></span></a>";
                                            //echo "<a href='affiliation-delete.php?affiliation_id=". $row['affiliation_id'] ."' title='Delete This Affiliation' data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a>";
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
