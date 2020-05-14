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
                            echo "<table class='table' id='affiliations'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th onclick='sortTable(0)' style='cursor:pointer'>Member Name";
                                          echo "<a href='#' onclick='sortTable(0)'><span class='glyphicon glyphicon-sort'></span></a>";
                                          echo "<button type='button' onclick='sortTable(0)' class='btn'><span class='glyphicon glyphicon-sort'></span></button>";
                                        echo "</th>";
                                        echo "<th onclick='sortTable(1)' style='cursor:pointer'>Email Address</th>";
                                        echo "<th onclick='sortTable(2)' style='cursor:pointer'>Total Contributions</th>";
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

    <script>
    function sortTable(n) {
      var table, rows, switching, i, x, y, shouldSwitch, direction, switchcount = 0;
      table = document.getElementById("affiliations");
      switching = true;
      // Set the sorting direction to ascending:
      direction = "asc";
      /* Make a loop that will continue until
      no switching has been done: */
      while (switching) {
        // Start by saying: no switching is done:
        switching = false;
        rows = table.rows;
        /* Loop through all table rows (except the
        first, which contains table headers): */
        for (i = 1; i < (rows.length - 1); i++) {
          // Start by saying there should be no switching:
          shouldSwitch = false;
          /* Get the two elements you want to compare,
          one from current row and one from the next: */
          x = rows[i].getElementsByTagName("TD")[n];
          y = rows[i + 1].getElementsByTagName("TD")[n];
          /* Check if the two rows should switch place,
          based on the direction, asc or desc: */
          if (direction == "asc") {
            if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
              // If so, mark as a switch and break the loop:
              shouldSwitch = true;
              break;
            }
          } else if (direction == "desc") {
            if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
              // If so, mark as a switch and break the loop:
              shouldSwitch = true;
              break;
            }
          }
        }
        if (shouldSwitch) {
          /* If a switch has been marked, make the switch
          and mark that a switch has been done: */
          rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
          switching = true;
          // Each time a switch is done, increase this count by 1:
          switchcount ++;
        } else {
          /* If no switching has been done AND the direction is "asc",
          set the direction to "desc" and run the while loop again. */
          if (switchcount == 0 && direction == "asc") {
            direction = "desc";
            switching = true;
          }
        }
      }
    }
    </script>
    <?php include '../footer.php';?>
</body>
</html>
