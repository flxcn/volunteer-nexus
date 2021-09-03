<?php
session_start();

if(!isset($_SESSION["admin_loggedin"]) || $_SESSION["admin_loggedin"] !== true)
{
    header("location: sign-in.php");
    exit;
}

require '../classes/AdminReader.php';
$admin_id = $_SESSION['admin_id'];
$obj = new AdminReader($admin_id);
$engagements = $obj->getLatestEngagements();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Felix Chen">
    
    <title>Engagements</title>

    <!-- Bootstrap core CSS -->
    <link href="../assets/bootstrap-5.0.2-dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../assets/css/events.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <?php $thisPage='Engagements'; include 'navbar.php';?>

    <div class="container-fluid">
        <div class="row">
            <main class="ms-sm-auto px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Latest Engagements</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print(); return false;"><span data-feather="printer"></span> Print</button>
                        </div>
                    </div>
                </div>

                <?php if($engagements): ?>
                    <div class="table-responsive">
                        <table class='table table-hover'>
                            <thead>
                                <tr>
                                    <th>Engagement ID</th>
                                    <th>Volunteer ID</th>
                                    <th>Sponsor ID</th>
                                    <th>Event ID</th>
                                    <th>Value</th>
                                    <th>Status</th>
                                    <th>Submitted at</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($engagements as $engagement): ?>
                                <tr>
                                    <td><?php echo $engagement['engagement_id']; ?></td>
                                    <td><?php echo $engagement['volunteer_id']; ?></td>
                                    <td><?php echo $engagement['sponsor_id']; ?></td>
                                    <td><?php echo $engagement['event_id']; ?></td>
                                    <td><?php echo $engagement['contribution_value']; ?></td>
                                    <td><?php echo $engagement['status']; ?></td>
                                    <td><?php echo $engagement['time_submitted']; ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class='lead'>
                        <em>There are currently no engagements.</em>
                    </p>
                <?php endif; ?>
            </main>
            
            <!-- Footer -->
            <?php include "footer.php"; ?>
        </div>
    </div>

    <script src="../assets/bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
