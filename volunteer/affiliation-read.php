<?php
session_start();

if(!isset($_SESSION["volunteer_loggedin"]) || $_SESSION["volunteer_loggedin"] !== true){
    header("location: sign-in.php");
    exit;
}

require "../classes/VolunteerAffiliationReader.php";

$obj = new VolunteerAffiliationReader($_SESSION["volunteer_id"]);
$engagements = $obj->getEngagementsForAffiliatedSponsor($_GET['sponsor_id']);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Felix Chen">
    
    <title>My contributions</title>

    <!-- Bootstrap core CSS -->
    <link href="../assets/bootstrap-5.0.2-dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom styles for this template -->
    <link href="../assets/css/dashboard.css" rel="stylesheet">
</head>

<body>
    <?php include "navbar.php"; ?>

    <div class="container-fluid">
        <div class="row">
            <main class="ms-sm-auto px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">My contributions <?php echo $obj->getAffiliatedSponsorName($_GET["sponsor_id"]);?></h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()"><span data-feather="printer"></span> Print</button>
                        </div>
                    </div>
                </div>

                <?php if($engagements): ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">Event</th>
                            <th scope="col">Description</th>
                            <th scope="col">Opportunity</th>
                            <th scope="col">Contact Info</th>
                            <th scope="col">Event Duration</th>
                            <th scope="col">Value</th>
                            <th scope="col">Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($engagements as $engagement): ?>
                            <tr>
                                <th scope="row"><?php echo $engagement['event_name']; ?></th>
                                <td>
                                    <?php echo $engagement['event_description']; ?>
                                </td>
                                <td>
                                    <?php echo $engagement['opportunity_name']; ?>
                                </td>
                                <td>
                                    <?php echo $engagement['contact_name'] . "<br>" . $engagement['contact_email']; ?>
                                </td>
                                <td>
                                    <?php echo $engagement['event_start'] . " to " . $engagement['event_end']; ?>
                                </td>
                                <td>
                                    <?php echo $engagement['contribution_value']; ?>
                                </td>
                                <?php
                                if(strcmp($engagement['status'],'1') == 0): 
                                    echo "<td class='text-success'>Confirmed</td>";
                                elseif (strcmp($engagement['status'],'0') == 0): 
                                    echo "<td class='text-danger'>Denied</td>";
                                else:
                                    echo "<td class='text-muted'>Pending</td>";
                                endif;
                                ?>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <p class='lead'><em>No past engagements were found.</em></p>
                <?php endif; ?>
            </main>

            <?php include "footer.php"; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script>

    <script src="../assets/bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>

    <script>
    (function () {
    'use strict'

    feather.replace({ 'aria-hidden': 'true' })

    })()
    </script>
</body>
<html>