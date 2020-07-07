<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["sponsor_loggedin"]) || $_SESSION["sponsor_loggedin"] !== true){
    header("location: login.php");
    exit;
}

require '../classes/SponsorEventReader.php';
$obj = new SponsorEventReader($_SESSION['sponsor_id']);
$events = $obj->getSponsoredEvents();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Events</title>

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

    <!-- toggle Bootstrap tooltip functionality -->
    <script type="text/javascript">
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
</head>





<body>
  <?php $thisPage='Events'; include 'navbar.php';?>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header clearfix">
                        <h2 class="pull-left">My Sponsored Events</h2>
                        <a href="event-create.php" class="btn btn-success pull-right">Add New Event</a>
                    </div>

                    <!-- Search Bar -->
                    <br>
                    <!-- <p>Type something in the input field to search the table for first names, last names or emails:</p>   -->
                    <input class="form-control" id="searchInput" type="text" placeholder="Search..">
                    <br>
                    <?php if ($events): ?>
                      <table class='table table-bordered table-condensed' id='events'>
                        <thead>
                          <tr>
                            <th onclick='sortTable(0)' style='cursor:pointer'>
                              Reg. Deadline
                              <!-- <a href='#'><span class='glyphicon glyphicon-sort'></span></a> -->
                            </th>
                            <th onclick='sortTable(1)' style='cursor:pointer'>
                              Event Name
                              <!-- <a href='#'><span class='glyphicon glyphicon-sort'></span></a> -->
                            </th>
                            <th>
                              Description
                            </th>
                            <th>
                              Location
                            </th>
                            <th>
                              Event Duration
                            </th>
                            <th>
                              Action
                            </th>
                          </tr>
                        </thead>

                        <tbody id="eventTableBody">
                        <?php foreach($events as $event): ?>
                          <tr>
                            <td>
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
                            <td>
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
            </div>
        </div>
    </div>

    <!-- Sort table functionality -->
    <script>
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
    <!-- Search Feature -->
    <script>
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

    <?php include '../footer.php';?>
</body>
</html>
