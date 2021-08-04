<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["volunteer_loggedin"]) || $_SESSION["volunteer_loggedin"] !== true){
    header("location: sign-in.php");
    exit;
}

// Check existence of id parameter before processing further
if(isset($_SESSION["volunteer_id"])) {
    
    $volunteer_id = $_SESSION["volunteer_id"];
    require_once "../classes/VolunteerAccountReader.php";
    $obj = new VolunteerAccountReader($volunteer_id);

    $obj->getVolunteerDetails();

} else {
    header("location: error.php");
    exit();
}
?>

<?php
if(session_status() !== PHP_SESSION_ACTIVE) session_start();

require '../classes/VolunteerEngagementReader.php';
$obj = new VolunteerEngagementReader($_SESSION['volunteer_id']);
$engagements = $obj->getUpcomingEngagements();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Felix Chen">
    
    <title>Tutoring</title>

    <!-- Bootstrap core CSS -->
    <link href="../assets/bootstrap-5.0.2-dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom styles for this template -->
    <link href="../assets/css/main.css" rel="stylesheet">
</head>
<body>
    <?php $thisPage='Tutoring'; include '../volunteer/navbar.php';?>

    <div class="container-fluid">
        <div class="row">
            <main class="ms-sm-auto px-md-4">
                
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Tutoring</h1>
                </div>

                <a href="tutor-create.php" class="btn btn-success">Register as a Tutor</a>
                <a href="../volunteer/affiliation-create.php" class="btn btn-primary">Sign up with a Sponsor</a>
                <a href="tutor-request.php" class="btn btn-primary">Request tutoring</a>
                <a href="tutorial-create.php" class="btn btn-primary">Log tutoring</a>

                <div class="card border-dark mt-3">
                    <div class="card-body">
                        <h5 class="card-title">Assignments</h5>
                        <p class="card-text">No current assignments</p>
                    </div>
                </div>

                <div class="row mt-4 print">
        <div class="mb-lg-0">
            <div class="card">
                <h5 class="card-header">Latest Tutorials</h5>
                <div class="card-body">
                <?php if($engagements): ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">Event</th>
                                <!-- <th scope="col">Description</th> -->
                                <th scope="col">Opportunity</th>
                                <th scope="col">Contact Info</th>
                                <th scope="col">Duration</th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php 
                            $modalCount = 0; 
                            foreach($engagements as $engagement): 
                                $modalCount++;
                            ?>
                                <tr>
                                    <th scope="row">
                                        <?php echo $engagement['event_name']; ?>
                                    </th>
                                    <!-- <td>
                                        <?php //echo $engagement['description']; ?>
                                    </td> -->
                                    <td>
                                        <?php echo $engagement['opportunity_name']; ?>
                                    </td>
                                    <td>
                                        <?php echo $engagement['contact_name'] . "<br>" . $engagement['contact_email']; ?>
                                    </td>
                                    <td>
                                        <?php echo $obj->formatOpportunityStartToEnd($engagement['start_date'],$engagement['end_date']); ?>
                                    </td>
                                    <td>
                                        <!-- <a href=<?php // echo "engagement-read.php?engagement_id=" . $engagement['engagement_id'] . "&opportunity_id=" . $engagement['opportunity_id']; ?> class="btn btn-primary">View</a> -->
                                        
                                        <!-- Button trigger modal -->
                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal<?php echo $modalCount;?>">
                                            View
                                        </button>

                                        <!-- <a href=<?php //echo "engagement-delete.php?engagement_id=" . $engagement['engagement_id']; ?> class='btn btn-danger' >Cancel</a> -->
                                        
                                        <!-- Modal -->
                                        <div class="modal fade" id="modal<?php echo $modalCount;?>" tabindex="-1" aria-labelledby="modal<?php echo $modalCount;?>Label" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modal<?php echo $modalCount;?>Label"><?php echo $engagement["opportunity_name"]; ?></h5>                           
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <h6 class="small"><?php echo $engagement["contribution_value"] . " " . $engagement['contribution_type']; ?></h6>
                                                        <p><?php echo $engagement["description"]; ?></p>
                                                        <p><b>From </b><?php echo $obj->formatDate($engagement['start_date']) . " @ " . $obj->formatTime($engagement['start_time']) . " <br><b>To </b>" . $obj->formatDate($engagement['end_date']) . " @ " . $obj->formatTime($engagement['end_time']); ?></p>
                                                        <p class="small text-muted"><?php echo "Submitted on " . $engagement["time_submitted"]; ?></p>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <a href=<?php echo "engagement-delete.php?engagement_id=" . $engagement['engagement_id']; ?> class='btn btn-danger'>Cancel sign-up</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted">You have none at the moment.</p>
                <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
            </main>

            <?php include "../volunteer/footer.php" ?>
        </div>
    </div>

    <script src="../assets/bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
