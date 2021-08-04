<?php
session_start();

if(!isset($_SESSION["sponsor_loggedin"]) || $_SESSION["sponsor_loggedin"] !== true){
    header("location: sign-in.php");
    exit;
}

require '../classes/SponsorAffiliationReader.php';
$obj = new SponsorAffiliationReader($_SESSION['sponsor_id']);
$volunteers = $obj->getAffiliatedVolunteers();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Felix Chen">
    
    <title>My Volunteers</title>

    <!-- Bootstrap core CSS -->
    <link href="../assets/bootstrap-5.0.2-dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom styles for this template -->
    <link href="../assets/css/main.css" rel="stylesheet">
    <link rel="stylesheet" media="print" href="../assets/css/print.css" />
</head>

<body>
    <?php $thisPage='Affiliations'; include 'navbar.php';?>

    <div class="container-fluid">
        <div class="row">
            <main class="ms-sm-auto px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom print">
                    <h1 class="h2">My Volunteers</h1>
                    <div class="btn-toolbar mb-2 mb-md-0 no-print">
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()"><span data-feather="printer"></span> Print</button>
                        </div>
                    </div>
                </div>

                <!-- Search Bar -->
                <input class="form-control my-3" id="searchInput" type="text" placeholder="Search">

                <!-- Volunteers Table -->
                <?php if ($volunteers): ?>
                    <div class="table-responsive">
                        <table class='table table-condensed print' id='affiliations'>
                            <thead>
                                <tr>
                                    <th onclick='sortTable(0)' style='cursor:pointer'>
                                    Volunteer Name
                                    </th>
                                    <th onclick='sortTable(1)' style='cursor:pointer'>
                                    Email Address
                                    </th>
                                    <th onclick='sortTableNumerically(2)' style='cursor:pointer'>
                                    Graduation Year
                                    <a href='#'><span class='glyphicon glyphicon-sort'></span></a>
                                    </th>
                                    <th onclick='sortTableNumerically(3)' style='cursor:pointer'>
                                    This Semester's Contributions
                                    <a href='#'><span class='glyphicon glyphicon-sort'></span></a>
                                    </th>
                                    <th onclick='sortTableNumerically(4)' style='cursor:pointer'>
                                    This School Year's Contributions
                                    <a href='#'><span class='glyphicon glyphicon-sort'></span></a>
                                    </th>
                                    <th onclick='sortTableNumerically(5)' style='cursor:pointer'>
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
                                        <?php echo $volunteer['graduation_year']; ?>
                                    </td>
                                    <td>
                                        <?php echo $obj->getSemesterContributionTotal($volunteer['volunteer_id']); //echo $volunteer['total_contribution_value']; ?>
                                    </td>
                                    <td>
                                        <?php echo $obj->getSchoolYearContributionTotal($volunteer['volunteer_id']); //echo $volunteer['total_contribution_value']; ?>
                                    </td>
                                    <td>
                                        <?php echo $volunteer['total_contribution_value']; ?>
                                    </td>
                                    <td>
                                        <a href=<?php echo "affiliation-read.php?volunteer_id=".$volunteer['volunteer_id']; ?> title="View Volunteer's Contributions" data-toggle='tooltip' class='btn btn-link btn-sm'><span class='glyphicon glyphicon-eye-open'></span> View</a>
                                        <a href=<?php echo "affiliation-delete.php?affiliation_id=".$volunteer['affiliation_id']; ?> title='Delete This Affiliation' data-toggle='tooltip' class='btn btn-link btn-sm' style='color:red'><span class='glyphicon glyphicon-trash' style='color:red'></span> Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class='lead'><em>No affiliated volunteers were found.</em></p>
                <?php endif; ?>
            </main>

            <?php include "footer.php"; ?>
        </div>
    </div>

    <script src="../assets/jQuery/jquery-3.4.1.min.js"></script>
    <script src="../assets/bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script>

    <script>
    // Activate feather icon 
    (function () {
    'use strict'

    feather.replace({ 'aria-hidden': 'true' })

    })()

    // search feature
    $(document).ready(function(){
    $("#searchInput").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#engagementTableBody tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
    });

    // sort table alphabetically
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

    // sort table numerically
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

</body>
</html>








