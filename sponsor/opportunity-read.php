<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["sponsor_loggedin"]) || $_SESSION["sponsor_loggedin"] !== true){
    header("location: sign-in.php");
    exit;
}

$event_id = trim($_GET["event_id"]);
$opportunity_id = trim($_GET["opportunity_id"]);
$sponsor_id = trim($_GET["sponsor_id"]);

require '../classes/SponsorOpportunityReader.php';
$obj = new SponsorOpportunityReader($sponsor_id, $event_id);
$engagements = $obj->getEngagements($opportunity_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Felix Chen">
    
    <title>View Opportunity</title>

    <!-- Bootstrap core CSS -->
    <link href="../assets/bootstrap-5.0.2-dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom styles for this template -->
    <link href="../assets/css/main.css" rel="stylesheet">
    <link rel="stylesheet" media="print" href="../assets/css/print.css" />
</head>

<body>
    <?php $thisPage=''; include 'navbar.php';?>

    <div class="container-fluid">
        <div class="row">
            <main class="ms-sm-auto px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom print">
                    <h1 class="h2">View Opportunity Signups</h1>
                    <div class="btn-toolbar mb-2 mb-md-0 no-print">
                        <div class="btn-group me-2">
                            <a class="btn btn-sm btn-outline-primary" href="event-read.php?event_id=<?php echo $event_id; ?>">View Event</a>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()"><span data-feather="printer"></span> Print</button>
                        </div>
                    </div>
                </div>

                <!-- Search Bar -->
                <input class="form-control my-3" id="searchInput" type="text" placeholder="Search">

                <!-- Events Table -->
                <div id="engagementsContent">
                    <?php if ($engagements): ?>
                        <table class='table table-condensed print' id='engagements'>
                            <thead>
                                <tr>
                                    <th onclick='sortTable(0)' style='cursor:pointer'>Time Submitted</th>
                                    <th onclick='sortTable(1)' style='cursor:pointer'>Volunteer Name</th>
                                    <th>Email Address</th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody id="engagementTableBody">
                                <?php foreach($engagements as $engagement): ?>
                                <tr>
                                    <td>
                                        <?php echo $engagement['time_submitted']; ?>
                                    </td>
                                    <td>
                                        <?php echo $engagement['last_name'] . ", " . $engagement['first_name']; ?>
                                    </td>
                                    <td>
                                        <?php echo $engagement['email_address']; ?>
                                    </td>
                                    <td>
                                        <div id=<?php echo "statusOf" . $engagement['engagement_id']; ?> >
                                            <button onclick="deleteEngagement(<?php echo $engagement['engagement_id']; ?>)" class='btn btn-link btn-sm' >Delete</button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class='lead'><em>No engagements so far.</em></p>
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

        // search feature
        $(document).ready(function(){
            $("#searchInput").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#engagementTableBody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });

        function deleteEngagement(id) {
            if (id == "")
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
                xmlhttp.open("GET","engagement-delete.php?engagement_id="+id,true);
                xmlhttp.send();
            }
        }
    </script>
</body>
</html>