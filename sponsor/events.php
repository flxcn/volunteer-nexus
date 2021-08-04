<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["sponsor_loggedin"]) || $_SESSION["sponsor_loggedin"] !== true){
    header("location: sign-in.php");
    exit;
}

require '../classes/SponsorEventReader.php';
$obj = new SponsorEventReader($_SESSION['sponsor_id']);
$events = $obj->getUpcomingSponsoredEvents();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Felix Chen">
    
    <title>Events</title>

    <!-- Bootstrap core CSS -->
    <link href="../assets/bootstrap-5.0.2-dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom styles for this template -->
    <link href="../assets/css/main.css" rel="stylesheet">
    <link rel="stylesheet" media="print" href="../assets/css/print.css" />
</head>

<body>
    <?php $thisPage='Events'; include 'navbar.php';?>

    <div class="container-fluid">
        <div class="row">
            <main class="ms-sm-auto px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom print">
                    <h1 class="h2">My Events</h1>
                    <div class="btn-toolbar mb-2 mb-md-0 no-print">
                        <div class="btn-group me-2">
                            <a class="btn btn-sm btn-outline-success" href="event-create.php">Create Event</a>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()"><span data-feather="printer"></span> Print</button>
                        </div>
                    </div>
                </div>

                <!-- Tabs -->
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" onclick="showTable('upcoming')" id="upcoming-tab" data-bs-toggle="tab" data-bs-target="#upcoming" type="button" role="tab" aria-controls="home" aria-selected="true">Upcoming <span class="badge bg-secondary rounded-pill"><?php echo $obj->countUpcomingSponsoredEvents(); ?></span></button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" onclick="showTable('ongoing')" id="ongoing-tab" data-bs-toggle="tab" data-bs-target="#ongoing" type="button" role="tab" aria-controls="ongoing" aria-selected="false">Ongoing <span class="badge bg-secondary rounded-pill"><?php echo $obj->countOngoingSponsoredEvents(); ?></span></button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" onclick="showTable('completed')" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed" type="button" role="tab" aria-controls="completed" aria-selected="false">Completed <span class="badge bg-secondary rounded-pill"><?php echo $obj->countCompletedSponsoredEvents(); ?></span></button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" onclick="showTable('all')" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab" aria-controls="all" aria-selected="false">All <span class="badge bg-secondary rounded-pill"><?php echo $obj->countSponsoredEvents(); ?></span></button>
                    </li>
                </ul>

                <!-- Search Bar -->
                <input class="form-control my-3" id="searchInput" type="text" placeholder="Search">

                <!-- Events Table -->
                <div id="eventsContent">
                    <?php if ($events): ?>
                        <table class='table table-condensed print' id='events'>
                            <thead>
                                <tr>
                                    <th style='cursor:pointer'>Reg. Deadline</th>
                                    <th onclick='sortTable(1)' style='cursor:pointer'>Event Name</th>
                                    <th>Description</th>
                                    <th>Location</th>
                                    <th>Duration</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody id="eventTableBody">
                                <?php foreach($events as $event): ?>
                                <tr>
                                    <td class="text-nowrap">
                                        <?php echo $obj->formatDate($event['registration_end']); ?>
                                    </td>
                                    <td>
                                        <?php echo $event['event_name']; ?>
                                    </td>
                                    <td>
                                        <?php echo $obj->formatDescription($event['description']); ?>
                                    </td>
                                    <td>
                                        <?php echo $event['location']; ?>
                                    </td>
                                    <td class="text-nowrap">
                                        <?php echo $obj->formatEventStartToEnd($event['event_start'],$event['event_end']); ?>
                                    </td>
                                    <td>
                                        <a href=<?php echo "event-read.php?event_id=".$event['event_id']; ?> title='View Event' data-toggle='tooltip' class='btn btn-link'><span class='glyphicon glyphicon-eye-open'></span> View</a>
                                        <br>
                                        <a href=<?php echo "event-update.php?event_id=".$event['event_id']; ?> title='Update Event' data-toggle='tooltip' class='btn btn-link' style='color:black'><span class='glyphicon glyphicon-pencil'></span> Update</a>
                                        <br>
                                        <a href=<?php echo "event-delete.php?event_id=".$event['event_id']; ?> title='Delete Event' data-toggle='tooltip' class='btn btn-link' style='color:red'><span class='glyphicon glyphicon-trash'></span> Delete</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class='lead'><em>No events were found.</em></p>
                    <?php endif; ?>
                </div>
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

    // Sort table functionality 
    function sortTable(n) {
      var table, rows, switching, i, x, y, shouldSwitch, direction, switchcount = 0;
      table = document.getElementById("events");
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

    // AJAX script to fetch table
    function showTable(interval) {
        if (interval == "") {
            return;
        }
        else {
            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            }

            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("eventsContent").innerHTML = this.responseText;
                }
            };

            xmlhttp.open("GET","events-get-table.php?interval="+interval,true);
            xmlhttp.send();
        }
    }

    // search feature
    $(document).ready(function(){
        $("#searchInput").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#eventTableBody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
    </script>

</body>
</html>
