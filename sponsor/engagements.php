<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["sponsor_loggedin"]) || $_SESSION["sponsor_loggedin"] !== true){
    header("location: login.php");
    exit;
}

require '../classes/SponsorEngagementReader.php';
$obj = new SponsorEngagementReader($_SESSION['sponsor_id']);
$engagements = $obj->getPendingEngagements();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Pending Engagements</title>

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
    <script type="text/javascript">
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>

    <script>
      function showStatus(id,num) {
        if (id == "" && num=="")
        {
          return;
        }
        else
        {
          if (window.XMLHttpRequest)
          {
            xmlhttp = new XMLHttpRequest();
          }

          xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
              document.getElementById("statusOf"+id).innerHTML = this.responseText;
            }
          };
          xmlhttp.open("GET","engagement-verify.php?engagement_id="+id+"&status="+num,true);
          xmlhttp.send();
        }
      }
    </script>
</head>

<body>
    <?php $thisPage='Engagements'; include 'navbar.php';?>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header clearfix">
                        <h2 class="pull-left">Engagements</h2>
                        <a href="engagement-create.php" class="btn btn-success pull-right"><span class='glyphicon glyphicon-plus'></span> Add Engagement</a>
                    </div>

                    <!-- Search Bar -->
                    <br>
                    <input class="form-control" id="searchInput" type="text" placeholder="Search">
                    <br>

                    <div id="engagementsContent">
                    <?php if ($engagements): ?>
                        <table class='table table-hover' id="engagements">
                            <thead class='thead-dark'>
                                <tr>
                                    <th onclick='sortTable(0)' style='cursor:pointer'>
                                        Name
                                        <a href='#'><span class='glyphicon glyphicon-sort'></span></a>
                                    </th>
                                    <th onclick='sortTable(1)' style='cursor:pointer'>
                                        Opportunity
                                    </th>
                                    <th onclick='sortTable(2)' style='cursor:pointer'>
                                        Event
                                    </th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody id="engagementTableBody">
                            <?php foreach($engagements as $engagement): ?>
                                <tr>
                                    <td>
                                        <?php echo $engagement['first_name'] . " " . $engagement['last_name']; ?>
                                    </td>
                                    <td>
                                        <?php echo $engagement['opportunity_name']; ?>
                                    </td>
                                    <td>
                                        <?php echo $engagement['event_name']; ?>
                                    </td>
                                    <td>
                                        <div id=<?php echo "statusOf" . $engagement['engagement_id']; ?> >
                                            <a onclick=<?php echo "showStatus(" . $engagement['engagement_id'] .",1)"; ?> title='Confirm This Engagement' data-toggle='tooltip' class='btn btn-link' style='color:green'><span class='glyphicon glyphicon-ok'></span></a>
                                            <a onclick=<?php echo "showStatus(" . $engagement['engagement_id'] .",0)"; ?> title='Deny This Engagement' data-toggle='tooltip' class='btn btn-link' style='color:red'><span class='glyphicon glyphicon-remove'></span></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                      <p class='lead'><em>No pending engagements were found.</em></p>
                    <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    // search feature
    $(document).ready(function(){
    $("#searchInput").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#engagementTableBody tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
    });

    // sort table feature
    function sortTable(n) {
      var table, rows, switching, i, x, y, shouldSwitch, direction, switchcount = 0;
      table = document.getElementById("engagements");
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
