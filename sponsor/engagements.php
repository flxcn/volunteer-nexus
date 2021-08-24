<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["sponsor_loggedin"]) || $_SESSION["sponsor_loggedin"] !== true){
    header("location: sign-in.php");
    exit;
}

require '../classes/SponsorEngagementReader.php';
$obj = new SponsorEngagementReader($_SESSION['sponsor_id']);
$engagements = $obj->getPendingEngagements();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Felix Chen">
    
    <title>Pending Engagements</title>

    <!-- Bootstrap core CSS -->
    <link href="../assets/bootstrap-5.0.2-dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom styles for this template -->
    <link href="../assets/css/main.css" rel="stylesheet">
    <link rel="stylesheet" media="print" href="../assets/css/print.css" />
</head>

<body>
    <?php $thisPage='Engagements'; include 'navbar.php';?>

    <div class="container-fluid">
        <div class="row">
            <main class="ms-sm-auto px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom print">
                    <h1 class="h2">Pending Engagements</h1>
                    <div class="btn-toolbar mb-2 mb-md-0 no-print">
                        <div class="btn-group me-2">
                            <a class="btn btn-sm btn-outline-success" href="engagement-create.php">Create Engagement</a>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()"><span data-feather="printer"></span> Print</button>
                        </div>
                    </div>
                </div>

                <!-- Search Bar -->
                <input class="form-control my-3" id="searchInput" type="text" placeholder="Search">

                <!-- Events Table -->
                <div id="engagementsContent">
                    <?php if ($engagements): ?>
                        <table class='table table-hover' id="engagements">
                            <thead class='thead-dark'>
                                <tr>
                                    <th onclick='sortTable(0)' style='cursor:pointer'>
                                        Name
                                    </th>
                                    <th onclick='sortTable(1)' style='cursor:pointer'>
                                        Opportunity
                                    </th>
                                    <th onclick='sortTable(2)' style='cursor:pointer'>
                                        Event
                                    </th>
                                    <th>
                                    </th>
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
                                            <a onclick=<?php echo "showStatus(" . $engagement['engagement_id'] .",1)"; ?>  class='btn btn-success btn-sm'><span class='glyphicon glyphicon-ok'></span>Confirm</a>
                                            <a onclick=<?php echo "showStatus(" . $engagement['engagement_id'] .",0)"; ?>  class='btn btn-danger btn-sm'><span class='glyphicon glyphicon-remove'></span>Deny</a>
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
                } 
                else if (direction == "desc") {
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
            } 
            else {
                /* If no switching has been done AND the direction is "asc",
                set the direction to "desc" and run the while loop again. */
                if (switchcount == 0 && direction == "asc") {
                    direction = "desc";
                    switching = true;
                }
            }
        }
    }

    // AJAX function for engagement verification
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

</body>
</html>
