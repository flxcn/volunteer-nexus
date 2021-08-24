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
                                        <!-- Button trigger modal -->
                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal<?php echo $modalCount;?>">
                                            View
                                        </button>
                                        
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
</body>
</html>