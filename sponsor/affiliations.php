<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["sponsor_loggedin"]) || $_SESSION["sponsor_loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Affiliations</title>

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
  <?php $thisPage='Affiliations'; include 'navbar.php';?>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header clearfix">
                        <h2 class="pull-left">My Members</h2>
                        <!-- <a href="affiliation-create.php" class="btn btn-success pull-right">Add New Affiliation</a> -->
                    </div>

                    <?php
                    // Include config file
                    require_once "../config.php";
                    // Attempt select query execution

                    $sql = "SELECT volunteers.last_name AS last_name, volunteers.first_name AS first_name, volunteers.username AS email_address, SUM(engagements.contribution_value) AS total_contribution_value FROM volunteers INNER JOIN affiliations ON volunteers.student_id = affiliations.student_id LEFT JOIN engagements ON affiliations.student_id = engagements.student_id WHERE engagements.sponsor_id = '{$_SESSION['sponsor_id']}' GROUP BY volunteers.last_name, volunteers.first_name, volunteers.username";

                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo "<table class='table table-bordered table-striped'>";
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
                                        echo "<td>" . $row['last_name'] . ", " . $row['first_name'] . "</td>"; //NOTE: this needs work!
                                        echo "<td>" . $row['email_address'] . "</td>"; //NOTE: this needs work!
                                        echo "<td>" . $row['total_contribution_value'] . "</td>"; //NOTE: this needs work!
                                        // echo "<td>";
                                        //     //echo "<a href='affiliation-read.php?sponsor_id=". $row['sponsor_id'] ."' title='View My Contributions' data-toggle='tooltip'><span class='glyphicon glyphicon-eye-open'></span></a>";
                                        //     //echo "<a href='affiliation-delete.php?affiliation_id=". $row['affiliation_id'] ."' title='Delete This Affiliation' data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a>";
                                        // echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            echo "<p class='lead'><em>No members were found.</em></p>";
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
