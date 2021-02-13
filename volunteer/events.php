<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["volunteer_loggedin"]) || $_SESSION["volunteer_loggedin"] !== true)
{
    header("location: login.php");
    exit;
}

require '../classes/VolunteerEventReader.php';
$volunteer_id = $_SESSION['volunteer_id'];
$obj = new VolunteerEventReader($volunteer_id);
$events = $obj->getEvents();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Find Events</title>
    <!--Load required libraries-->
    <?php include '../head.php'?>
    <!-- CSS -->
    <style type="text/css">
        .wrapper{
            margin: 0 auto;
        }
        .page-header h2{
            margin-top: 0;
        }
    </style>
    <!-- Toggle Bootstrap tooltips -->
    <script type="text/javascript">
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
</head>
<body>
  <!-- Navbar -->
  <?php $thisPage='Events'; include 'navbar.php';?>

  <div class="wrapper">
      <div class="container-fluid">
          <div class="row">
              <div class="col-md-12">
                    <div class="page-header clearfix">
                        <h2 class="pull-left">All Events</h2>
                        <a href="affiliation-create.php" class="btn btn-primary pull-right">Add New Affiliation</a>
                    </div>

                    <?php if($events): ?>
                    <div>
                        <table class='table table-bordered table-responsive'>
                            <thead>
                                <tr>
                                    <th>Reg. Deadline</th>
                                    <th>Event Name</th>
                                    <th>Sponsor Name</th>
                                    <th>Description</th>
                                    <th>Location</th>
                                    <th>Event Duration</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($events as $event): ?>
                                <tr>
                                    <td><?php echo $obj->formatDate($event['registration_end']); ?></td>
                                    <td><?php echo $event['event_name']; ?></td>
                                    <td><?php echo $event['sponsor_name']; ?></td>
                                    <td><?php echo $obj->formatDescription($event['description']); ?></td>
                                    <td><?php echo $event['location']; ?></td>
                                    <td><?php echo $obj->formatEventStartToEnd($event['event_start'],$event['event_end']); ?></td>
                                    <td>
                                        <a href='event-read.php?event_id=<?php echo $event['event_id']; ?>' title='View Event' data-toggle='tooltip' class='btn btn-link' ><span class='glyphicon glyphicon-eye-open'></span> View</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <p class='lead'>
                        <em>No events were found. If you have not yet, click <a href='affiliation-create.php'>here</a> to join a Sponsor in order to view their Events.</em>
                    </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include '../footer.php';?>
</body>
</html>
