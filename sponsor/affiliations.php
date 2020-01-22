<?php
session_start();

if(!isset($_SESSION["sponsor_loggedin"]) || $_SESSION["sponsor_loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "../classes/SponsorAffiliationReader.php";

$sponsor_id = $_SESSION["sponsor_id"];
$obj = new SponsorAffiliationReader($sponsor_id);

$volunteers = $obj->getAffiliatedVolunteers();

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
                    if($volunteers) {
                      echo "<table class='table'>";
                        echo "<thead>";
                          echo "<tr>";
                            echo "<th>Member Name</th>";
                            echo "<th>Email Address</th>";
                            echo "<th>Total Contributions</th>";
                          echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        foreach ($volunteers as $row)
                        {
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
                    } else {
                      echo "<p class='lead'><em>No affiliated volunteers were found.</em></p>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php include '../footer.php';?>
</body>
</html>
