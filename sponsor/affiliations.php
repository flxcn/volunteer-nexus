<?php
session_start();

if(!isset($_SESSION["sponsor_loggedin"]) || $_SESSION["sponsor_loggedin"] !== true){
    header("location: login.php");
    exit;
}

require '../classes/SponsorAffiliationReader.php';
$obj = new SponsorAffiliationReader($_SESSION['sponsor_id']);
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
                    <div class="page-header clearfix">
                        <h2 class="pull-left">Affiliated Volunteers</h2>
                    </div>

                    <!-- Search Bar -->
                    <br>
                    <!-- <p>Type something in the input field to search the table for first names, last names or emails:</p>   -->
                    <input class="form-control" id="searchInput" type="text" placeholder="Search">
                    <br>

                    <?php if ($volunteers): ?>
                      <table class='table table-bordered table-striped' id='affiliations'>
                        <thead>
                          <tr>
                            <th onclick='sortTable(0)' style='cursor:pointer'>
                              Member Name
                              <a href='#'><span class='glyphicon glyphicon-sort'></span></a>
                              <!-- <button type='button' class='btn btn-link'><span class='glyphicon glyphicon-sort'></span></button> -->
                            </th>
                            <th onclick='sortTable(1)' style='cursor:pointer'>
                              Email Address
                            </th>
                            <th onclick='sortTableNumerically(2)' style='cursor:pointer'>
                              Total Contributions
                              <a href='#'><span class='glyphicon glyphicon-sort'></span></a>
                            </th>
                            <th>
                              Action
                            </th>
                          </tr>
                        </thead>

                        <tbody id="affiliationTableBody">
                        <?php foreach($volunteers as $volunteer): ?>
                          <tr>
                            <td>
                              <?php echo $volunteer['last_name'] . ", " . $volunteer['first_name']; ?>
                            </td>
                            <td>
                              <?php echo $volunteer['email_address']; ?>
                            </td>
                            <td>
                              <?php echo $volunteer['total_contribution_value']; ?>
                            </td>
                            <td>
                              <a href=<?php echo "affiliation-read.php?volunteer_id=".$volunteer['volunteer_id']; ?> title="View Volunteer's Contributions" data-toggle='tooltip' class='btn btn-link'><span class='glyphicon glyphicon-eye-open'></span> View</a>
                              <!-- <a href=<?php //echo "affiliation-delete.php?affiliation_id=".$volunteer['volunteer_id']; ?> title="Delete This Affiliation" data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span> Delete</a> -->
                            </td>
                          </tr>
                        <?php endforeach; ?>
                        </tbody>

                      </table>
                    <?php else: ?>
                      <p class='lead'><em>No affiliated volunteers were found.</em></p>
                    <?php endif; ?>
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



    function sortTableNumerically(n) {
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
            if (Number(x.innerHTML.toLowerCase()) > Number(y.innerHTML.toLowerCase())) {
              // If so, mark as a switch and break the loop:
              shouldSwitch = true;
              break;
            }
          } else if (direction == "desc") {
            if (Number(x.innerHTML.toLowerCase()) < Number(y.innerHTML.toLowerCase())) {
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

    <script>
    // search feature
    $(document).ready(function(){
    $("#searchInput").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#affiliationTableBody tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
    });
    </script>
    
    <?php include '../footer.php';?>
</body>
</html>
