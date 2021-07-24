<?php
if(session_status() !== PHP_SESSION_ACTIVE) session_start();

require '../classes/VolunteerEngagementReader.php';
$obj = new VolunteerEngagementReader($_SESSION['volunteer_id']);
$engagements = $obj->getUpcomingEngagements();
?>
<!DOCTYPE html>
<html>
<body>
    <div class="row mt-4 print">
        <div class="mb-lg-0">
            <div class="card">
                <h5 class="card-header">Upcoming Engagements</h5>
                <div class="card-body">
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
                                <th scope="col"></th>
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
                                        <a href=<?php echo "engagement-read.php?engagement_id=" . $engagement['engagement_id'] . "&opportunity_id=" . $engagement['opportunity_id']; ?> class="btn btn-primary">View</a>";
                                        <a href=<?php echo "engagement-delete.php?engagement_id=" . $engagement['engagement_id']; ?> class='btn btn-danger' >Delete</a>";
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
</body>
</html>